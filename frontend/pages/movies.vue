<!-- pages/movies.vue -->
<template>
  <Nav />
    <div class="movies-page">
      <header class="page-header">
        <h1>Pel·lícules</h1>
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
            <div class="movie-details">
              <span class="year">{{ movie.release_year }}</span>
              <span class="duration">{{ formatDuration(movie.duration) }}</span>
            </div>
            <span v-if="movie.genre" class="genre-badge">{{ movie.genre.name }}</span>
            <p class="movie-description">{{ truncateDescription(movie.description) }}</p>
            <div class="movie-director" v-if="movie.director">
              Director: {{ movie.director.name }} {{ movie.director.lastname }}
            </div>
          </div>
        </div>
      </div>
      
      <!-- Debug info (solo en desarrollo) -->
      <div v-if="isDevelopment" class="debug-section">
        <h3>Debug Info</h3>
        <p>Total películas: {{ allMovies.length }}</p>
        <p>Películas filtradas: {{ filteredMovies.length }}</p>
        <p>Filtro de género: {{ selectedGenre || 'Ninguno' }}</p>
        <p>Término de búsqueda: "{{ searchTerm || 'Ninguno' }}"</p>
        <button @click="reloadMovies" class="debug-button">Recargar películas</button>
      </div>
    </div>
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
  
  const truncateDescription = (text, maxLength = 120) => {
    if (text && text.length > maxLength) {
      return text.substring(0, maxLength) + '...';
    }
    return text;
  };
  
  const handleImageError = (event) => {
    event.target.src = '/images/movie-placeholder.jpg'; // Imagen de respaldo
  };
  
  const navigateToMovie = (movieId) => {
    navigateTo(`/movies/${movieId}`);
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
    padding: 20px;
    font-family: 'Montserrat', sans-serif;
  }
  
  .page-header {
    margin-bottom: 30px;
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
  }
  
  .movie-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
  }
  
  .movie-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  }
  
  .poster-container {
    position: relative;
    height: 380px;
  }
  
  .movie-poster {
    width: 100%;
    height: 100%;
    object-fit: cover;
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
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
  }
  
  .movie-details {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
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
  
  .movie-description {
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 15px;
    height: 3.6em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
  }
  
  .movie-director {
    font-size: 0.85rem;
    color: #777;
  }
  
  /* Debug section */
  .debug-section {
    margin-top: 50px;
    padding: 20px;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 8px;
  }
  
  .debug-section h3 {
    margin-bottom: 15px;
  }
  
  .debug-button {
    margin-top: 10px;
    padding: 8px 16px;
    background-color: #333;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  /* Responsive adjustments */
  @media (max-width: 768px) {
    .filters {
      flex-direction: column;
    }
    
    .search-box, .genre-filter {
      width: 100%;
    }
  }
  </style>