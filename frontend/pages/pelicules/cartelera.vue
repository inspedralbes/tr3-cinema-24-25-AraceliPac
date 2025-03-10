<template>
  <div class="app-container">
    <!-- NavBar incluida directamente -->
    <NavBar />
    
    <!-- Contenido principal -->
    <div class="content-container">
      <div class="container mx-auto px-4 py-8">
        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-4">
          <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0 mt-4">
            <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">
              <label for="genre" class="text-[#800040] font-medium">Gènere:</label>
              <select
                id="genre"
                v-model="selectedGenre"
                @change="applyFilters"
                class="p-2 border border-gray-300 rounded-md"
              >
                <option :value="null">Tots els gèneres</option>
                <option v-for="genre in genres" :key="genre.id" :value="genre.id">
                  {{ genre.name }}
                </option>
              </select>
            </div>
           
            <div class="relative">
              <input
                v-model="searchText"
                @input="applyFilters"
                placeholder="Cercar pel·lícules..."
                class="w-full md:w-64 p-2 pl-10 border border-gray-300 rounded-md"
              />
            </div>
          </div>
        </div>

        
        <!-- Loader -->
        <div v-if="isLoading" class="flex justify-center items-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#800040]"></div>
        </div>
        
        <!-- Mensaje de error -->
        <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-8">
          {{ error }}
        </div>
        
        <!-- Lista de películas con el componente Movie -->
        <div v-if="!isLoading && !error" class="movies-container">
          <Movie 
            v-for="movie in filteredMovies"
            :key="movie.id"
            :movie="movie"
          />
        </div>
        
        <!-- Mensaje si no hay películas -->
        <div v-if="!isLoading && !error && filteredMovies.length === 0" class="text-center py-8 bg-white rounded-lg shadow-md">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
          </svg>
          <p class="text-gray-600 text-lg">No se encontraron películas con los criterios seleccionados</p>
          <button 
            @click="clearFilters" 
            class="mt-4 px-4 py-2 bg-[#800040] hover:bg-[#6a0036] text-white rounded-md transition-colors duration-200"
          >
            Limpiar filtros
          </button>
        </div>
      </div>
    </div>
    
    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useMoviesStore } from '@/stores/movies';


const router = useRouter();
const moviesStore = useMoviesStore();

// Estados reactivos
const selectedGenre = ref(null);
const searchText = ref('');

// Computed properties
const isLoading = computed(() => moviesStore.isLoading);
const error = computed(() => moviesStore.error);
const genres = computed(() => moviesStore.allGenres);
const filteredMovies = computed(() => {
  return moviesStore.filteredMovies;
});

// Métodos
const applyFilters = () => {
  moviesStore.setGenreFilter(selectedGenre.value);
  moviesStore.setSearchFilter(searchText.value);
};

const clearSearch = () => {
  searchText.value = '';
  applyFilters();
};

const clearFilters = () => {
  selectedGenre.value = null;
  searchText.value = '';
  moviesStore.clearFilters();
};

// Cargar datos al montar el componente
onMounted(async () => {
  await moviesStore.fetchMovies();
});
</script>

<style>
/* Estilos globales */
html, body {
  height: 100%;
  margin: 0;
  padding: 0;
  overflow-x: hidden;
  background-color: #f3f4f6; /* bg-gray-100 equivalent */
}

#app {
  height: 100%;
}
</style>

<style scoped>
/* Layout principal */
.app-container {
  min-height: 100vh;
  display: grid;
  grid-template-rows: auto 1fr auto;
}

.content-container {
  background-color: #f3f4f6;
  width: 100%;
  padding-bottom: 20px;
}

/* Grid de películas */
.movies-container {
  min-height: 60vh;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 20px;
  place-content: center;
  padding: 20px 0;
}

@media (min-width: 640px) {
  .movies-container {
    grid-template-columns: repeat(auto-fill, minmax(165px, 1fr));
  }
}

@media (min-width: 768px) {
  .movies-container {
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
  }
}

/* En caso de pocas películas, esto asegura que estén centradas */
@media (max-width: 639px) {
  .movies-container {
    justify-items: center;
  }
}
</style>