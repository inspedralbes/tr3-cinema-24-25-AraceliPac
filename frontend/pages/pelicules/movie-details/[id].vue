<template>
    <div class="app-container">
      <!-- NavBar -->
      <NavBar />
  
      <div class="content-container">
        <div class="container mx-auto px-4 py-8">
          <!-- Loading state -->
          <div v-if="isLoading" class="flex justify-center items-center py-16">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#800040]"></div>
          </div>
  
          <!-- Error state -->
          <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-8">
            {{ error }}
            <button 
              @click="goBack" 
              class="ml-4 px-3 py-1 bg-[#800040] hover:bg-[#6a0036] text-white rounded-md transition-colors duration-200"
            >
              Tornar a la cartelera
            </button>
          </div>
  
          <!-- Movie details -->
          <div v-else-if="movie" class="grid-layout">
            <!-- PRIMERA FILA: Dos columnas - Imagen e Información -->
            <div class="row-info bg-white rounded-lg shadow-lg p-4 md:p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna 1: Póster de la película -->
                <div class="poster-column">
                  <div class="poster-container rounded-lg overflow-hidden shadow-md mx-auto md:mx-0">
                    <img :src="movie.image" :alt="movie.title" class="w-full h-full object-cover">
                  </div>
                </div>
                
                <!-- Columna 2: Información de la película -->
                <div class="info-column">
                  <!-- Título y metadatos -->
                  <h1 class="text-2xl md:text-3xl font-bold mb-4 text-[#800040]">
                    {{ movie.title }}
                  </h1>
                  
                  <!-- Información básica -->
                  <div class="flex flex-wrap items-center gap-3 mb-6">
                    <!-- Rating con estrellas -->
                    <div class="flex items-center mr-2">
                      <div class="flex mr-1">
                        <svg v-for="i in 5" :key="i" 
                          class="w-5 h-5" 
                          :class="i <= Math.floor(movie.rating) ? 'text-yellow-400' : 'text-gray-300'"
                          xmlns="http://www.w3.org/2000/svg" 
                          viewBox="0 0 24 24" 
                          fill="currentColor"
                        >
                          <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                      </div>
                      <span>{{ movie.rating }}/5</span>
                    </div>
                    
                    <!-- Género -->
                    <span class="px-3 py-1 bg-[#800040] text-white text-xs font-bold rounded-full">
                      {{ movie.genre?.name || 'Gènere' }}
                    </span>
                    
                    <!-- Año -->
                    <span class="px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded-full">
                      {{ movie.release_year }}
                    </span>
                    
                    <!-- Duración -->
                    <span class="px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded-full">
                      {{ formatDuration(movie.duration) }}
                    </span>
                  </div>
                  
                  <!-- Sinopsis -->
                  <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2 text-[#800040]">Sinopsi</h2>
                    <p class="text-gray-700">{{ movie.description }}</p>
                  </div>
                  
                  <!-- Director -->
                  <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2 text-[#800040]">Director</h2>
                    <div class="flex items-center">
                      <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium">{{ movie.director?.name }} {{ movie.director?.lastname }}</p>
                        <p class="text-sm text-gray-500">{{ movie.director?.nationality }}</p>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Actors -->
                  <div v-if="movie.actors && movie.actors.length > 0">
                    <h2 class="text-lg font-semibold mb-2 text-[#800040]">Actors</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                      <div v-for="actor in movie.actors" :key="actor.id" class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                          </svg>
                        </div>
                        <span class="text-sm">{{ actor.name }} {{ actor.lastname }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- SEGUNDA FILA: Una columna para el trailer -->
            <div v-if="movie.trailer" class="row-trailer bg-white rounded-lg shadow-lg p-4 md:p-6">
              <h2 class="text-xl font-semibold mb-3 text-[#800040]">Trailer</h2>
              <div class="trailer-container">
                <iframe 
                  class="trailer-iframe" 
                  :src="getEmbedUrl(movie.trailer)" 
                  frameborder="0" 
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                  allowfullscreen
                ></iframe>
              </div>
            </div>
            
            <!-- Botón para volver -->
            <div class="row-button flex justify-center mt-4">
              <button 
                @click="goBack" 
                class="px-6 py-3 bg-[#800040] hover:bg-[#6a0036] text-white rounded-md transition-colors duration-200 flex items-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Tornar a la cartelera
              </button>
            </div>
          </div>
          
          <!-- No movie found state -->
          <div v-else class="text-center py-16 bg-white rounded-lg shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
            </svg>
            <p class="text-gray-600 text-lg mb-4">No s'ha trobat la pel·lícula</p>
            <button 
              @click="goBack" 
              class="px-6 py-3 bg-[#800040] hover:bg-[#6a0036] text-white rounded-md transition-colors duration-200"
            >
              Tornar a la cartelera
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
  import { useRoute, useRouter } from 'vue-router';
  import { useMoviesStore } from '@/stores/movies';
  import NavBar from '@/components/NavBar.vue';
  import Footer from '@/components/Footer.vue';
  
  const route = useRoute();
  const router = useRouter();
  const moviesStore = useMoviesStore();
  
  // Reactive state
  const movie = computed(() => moviesStore.currentMovie);
  const isLoading = computed(() => moviesStore.isLoading);
  const error = computed(() => moviesStore.error);
  
  // Methods
  const goBack = () => {
    router.push(`/pelicules/cartelera`);
  };
  
  const formatDuration = (minutes) => {
    if (!minutes) return '';
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return `${hours}h ${mins}min`;
  };
  
  const getEmbedUrl = (url) => {
    if (!url) return '';
    
    // Convert YouTube URL to embed URL
    if (url.includes('youtube.com') || url.includes('youtu.be')) {
      let videoId = '';
      
      if (url.includes('youtube.com/watch')) {
        const urlParams = new URLSearchParams(new URL(url).search);
        videoId = urlParams.get('v');
      } else if (url.includes('youtu.be/')) {
        videoId = url.split('youtu.be/')[1];
        
        // Remove any additional parameters
        if (videoId.includes('?')) {
          videoId = videoId.split('?')[0];
        }
      }
      
      if (videoId) {
        return `https://www.youtube.com/embed/${videoId}`;
      }
    }
    
    // Return original URL if not YouTube or can't parse
    return url;
  };
  
  // Fetch movie data when component is mounted
  onMounted(async () => {
    const movieId = route.params.id;
    if (movieId) {
      await moviesStore.fetchMovieById(movieId);
      
      // Fetch actors if not already included in the movie data
      if (movie.value && (!movie.value.actors || movie.value.actors.length === 0)) {
        await moviesStore.fetchMovieActors(movieId);
      }
    }
  });
  </script>
  
  <style>
  /* App container and content container */
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
  
  /* Grid layout para las filas de la película */
  .grid-layout {
    display: grid;
    grid-template-rows: auto auto auto;
    gap: 1.5rem;
  }
  
  /* Estilos para primera fila */
  .row-info {
    width: 100%;
  }
  
  /* Ajustes para el póster */
  .poster-column {
    display: flex;
    justify-content: center;
    align-items: flex-start;
  }
  
  .poster-container {
    width: 100%;
    max-width: 300px;
    height: auto;
    aspect-ratio: 2/3;
  }
  
  /* Ajustes responsivos para el grid */
  @media (max-width: 767px) {
    .poster-container {
      max-width: 200px;
    }
  }
  
  /* Trailer container con proporción adecuada */
  .trailer-container {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    overflow: hidden;
    border-radius: 0.5rem;
  }
  
  .trailer-iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
  }
  </style>