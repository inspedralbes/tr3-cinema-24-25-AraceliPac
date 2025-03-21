<!-- pages/movies.vue -->
<template>
  <NavBar />
    <div class="movies-page">
      <header class="page-header">
        <div class="filters">
          <div class="search-box">
            <input 
              type="text" 
              v-model="searchTerm" 
              placeholder="Cerca pel·lícules..." 
              @input="updateSearch"
            >
            <button class="search-button">
              <i class="fas fa-search"></i>
            </button>
          </div>
          
          <div class="genre-filter">
            <select v-model="selectedGenre" @change="filterByGenre">
              <option :value="null">Tots els gèneres</option>
              <option 
                v-for="genre in genres" 
                :key="genre.id" 
                :value="genre.id"
              >
                {{ genre.name }}
              </option>
            </select>
          </div>
          
          <button class="clear-button" @click="clearFilters" :disabled="!isFiltered">
            Netejar filtres
          </button>
        </div>
      </header>
  
      <!-- Estado de carga -->
      <div v-if="loading" class="loading-container">
        <p>Carregant pel·lícules...</p>
      </div>
      
      <!-- Mensaje de error -->
      <div v-else-if="error" class="error-container">
        <p>{{ error }}</p>
        <button @click="fetchMovies" class="retry-button">Tornar-ho a provar</button>
      </div>
      
      <!-- Sin resultados -->
      <div v-else-if="filteredMovies.length === 0" class="no-results">
        <p>No s'han trobat pel·lícules que coincideixin amb els criteris de cerca.</p>
      </div>
      
      <!-- Catálogo de películas -->
      <div v-else class="movies-grid">
        <div 
          v-for="movie in filteredMovies" 
          :key="movie.id" 
          class="movie-card"
          @click="navigateToMovie(movie.id)"
        >
          <div class="poster-container">
            <img 
              :src="movie.image" 
              :alt="movie.title" 
              class="movie-poster"
              @error="handleImageError"
            >
            <div class="rating">{{ movie.rating }}</div>
          </div>
          <div class="movie-info">
            <h3 class="movie-title">{{ movie.title }}</h3>
            <span v-if="movie.genre" class="genre-badge">{{ movie.genre.name }}</span>
          </div>
        </div>
      </div>
    </div>
    <Footer />
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import { useMoviesStore } from '~/stores/movies';
  
  // Store
  const moviesStore = useMoviesStore();
  
  // Refs para filtros
  const searchTerm = ref('');
  const selectedGenre = ref(null);
  const isDevelopment = ref(process.env.NODE_ENV === 'development');
  
  // Computed properties
  const loading = computed(() => moviesStore.isLoading);
  const error = computed(() => moviesStore.error);
  const allMovies = computed(() => moviesStore.allMovies);
  const filteredMovies = computed(() => moviesStore.filteredMovies);
  const genres = computed(() => moviesStore.allGenres);
  const isFiltered = computed(() => searchTerm.value || selectedGenre.value);
  
  // Métodos
  const fetchMovies = async () => {
    await moviesStore.fetchMovies();
  };
  
  const formatDuration = (minutes) => {
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return `${hours}h ${mins}min`;
  };
  

  
  const handleImageError = (event) => {
    event.target.src = '/images/movie-placeholder.jpg'; // Imagen de respaldo
  };
  
  const navigateToMovie = (movieId) => {
    navigateTo(`/pelicules/movie-details/${movieId}`);
  };
  
  const updateSearch = () => {
    moviesStore.setSearchFilter(searchTerm.value);
  };
  
  const filterByGenre = () => {
    moviesStore.setGenreFilter(selectedGenre.value);
  };
  
  const clearFilters = () => {
    searchTerm.value = '';
    selectedGenre.value = null;
    moviesStore.clearFilters();
  };
  
  const reloadMovies = async () => {
    clearFilters();
    await fetchMovies();
  };
  
  // Lifecycle Hooks
  onMounted(async () => {
    await fetchMovies();
  });
  </script>
  
  <style scoped>
.movies-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px 10px; /* Reducido padding horizontal en móviles */
  font-family: 'Montserrat', sans-serif;
}

.page-header {
  margin-bottom: 20px; /* Reducido en móviles */
}

.page-header h1 {
  font-size: 2rem;
  margin-bottom: 20px;
  color: #e50914;
}

.filters {
  display: flex;
  gap: 15px;
  margin-bottom: 25px;
  flex-wrap: wrap;
}

.search-box {
  position: relative;
  flex: 1;
  min-width: 250px;
}

.search-box input {
  width: 100%;
  padding: 10px;
  padding-right: 40px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.search-button {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #777;
  cursor: pointer;
}

.genre-filter select {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  min-width: 180px;
}

.clear-button {
  padding: 10px 15px;
  background-color: #f5f5f5;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
}

.clear-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.loading-container, .error-container, .no-results {
  text-align: center;
  padding: 50px 20px;
  background-color: #f8f9fa;
  border-radius: 8px;
  margin-bottom: 30px;
}

.retry-button {
  margin-top: 15px;
  padding: 8px 16px;
  background-color: #e50914;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.movies-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 30px;
  justify-items: center;
  padding: 0 15px;
}

.movie-card {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  cursor: pointer;
  width: 100%;
  max-width: 280px;
  margin: 0 auto;
}

.movie-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.poster-container {
  position: relative;
  height: 380px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  background-color: #f0f0f0;
}

.movie-poster {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

.rating {
  position: absolute;
  top: 10px;
  right: 10px;
  background-color: rgba(0, 0, 0, 0.7);
  color: white;
  padding: 5px 10px;
  border-radius: 4px;
  font-weight: bold;
  font-size: 0.8rem;
}

.movie-info {
  padding: 20px;
}

.movie-title {
  font-size: 1.2rem;
  margin: 0 0 10px;
  color: #333;
  height: 2.8em;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.year, .duration {
  font-size: 0.9rem;
  color: #777;
}

.genre-badge {
  display: inline-block;
  background-color: #f8f9fa;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.85rem;
  color: #555;
  margin-bottom: 10px;
}

/* Ajustes responsivos para tablets */
@media (max-width: 768px) {
  .filters {
    flex-direction: column;
  }
  
  .search-box, .genre-filter {
    width: 100%;
  }
  
  .movies-grid {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    padding: 0 10px;
  }
  
  .movie-card {
    max-width: 220px;
  }
  
  .poster-container {
    height: 320px;
  }
  
  .movie-info {
    padding: 15px;
  }
}

/* Ajustes específicos para teléfonos móviles */
@media (max-width: 480px) {
  .movies-page {
    padding: 15px 5px;
  }
  
  .movies-grid {
    grid-template-columns: repeat(2, 1fr); /* Forzar 2 columnas en móviles */
    gap: 12px;
    padding: 0 5px;
  }
  
  .movie-card {
    max-width: 100%; /* Ocupar todo el ancho disponible */
  }
  
  .poster-container {
    height: 220px; /* Altura reducida para móviles */
  }
  
  .movie-info {
    padding: 10px;
  }
  
  .movie-title {
    font-size: 0.95rem;
    margin-bottom: 5px;
    height: 2.4em;
  }
  
  .genre-badge {
    font-size: 0.75rem;
    padding: 2px 6px;
    margin-bottom: 5px;
  }
  
  .rating {
    font-size: 0.75rem;
    padding: 3px 6px;
  }
}

/* Ajustes para teléfonos muy pequeños */
@media (max-width: 360px) {
  .movies-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
  }
  
  .poster-container {
    height: 180px;
  }
  
  .movie-title {
    font-size: 0.85rem;
  }
}
</style>