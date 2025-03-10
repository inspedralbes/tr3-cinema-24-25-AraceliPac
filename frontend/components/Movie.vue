<template>
    <div 
      class="movie-card bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300"
      @click="goToMovieDetail"
    >
      <!-- Contenedor de la imagen -->
      <div class="poster-container relative">
        <!-- Imagen de la película -->
        <img 
          :src="movie.image" 
          :alt="movie.title" 
          class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
        />
        
        <!-- Etiqueta de género en la esquina superior -->
        <div class="absolute top-2 right-2 z-20">
          <span class="genre-tag">{{ genreName }}</span>
        </div>
        
        <!-- Overlay para las estrellas -->
        <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/70 to-transparent py-1 px-2 z-10">
          <!-- Rating con estrellas visuales -->
          <div class="flex items-center justify-center">
            <svg v-for="i in 5" :key="i" 
              class="w-3 h-3 mx-px" 
              :class="i <= Math.floor(movie.rating) ? 'text-yellow-400' : 'text-gray-300'"
              xmlns="http://www.w3.org/2000/svg" 
              viewBox="0 0 24 24" 
              fill="currentColor"
              stroke="#000"
              stroke-width="0.5"
            >
              <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
          </div>
        </div>
      </div>
      
      <!-- Información de la película -->
      <div class="p-3">
        <h3 class="movie-title">{{ movie.title }}</h3>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, computed } from 'vue';
  import { useRouter } from 'vue-router';
  
  const props = defineProps({
    movie: {
      type: Object,
      required: true
    },
    movieId: {
      type: [Number, String],
      default: null
    }
  });
  
  const router = useRouter();
  
  // Computed para obtener el nombre del género de manera segura
  const genreName = computed(() => {
    if (props.movie.genre && props.movie.genre.name) {
      return props.movie.genre.name;
    } else if (props.movie.genre_id) {
      return 'Gènere ' + props.movie.genre_id;
    } else {
      return 'Pel·lícula'; // Valor por defecto
    }
  });
  
  const goToMovieDetail = () => {
    router.push(`/pelicules/movie-details/${props.movie.id}`);
  };
  </script>
  
  <style scoped>
  .movie-card {
    width: 100%;
    max-width: 150px;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    border-radius: 8px;
  }
  
  @media (min-width: 640px) {
    .movie-card {
      max-width: 165px;
    }
  }
  
  @media (min-width: 768px) {
    .movie-card {
      max-width: 380px;
    }
  }
  
  .movie-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  /* Poster container con proporción 2:3 */
  .poster-container {
    position: relative;
    overflow: hidden;
    line-height: 0; /* Elimina espacio extra bajo la imagen */
    aspect-ratio: 2/3;
    width: 100%;
  }
  
  /* Aseguramos que la imagen cubra todo el contenedor manteniendo proporción */
  .poster-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
  }
  
  .movie-title {
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    font-size: 0.8rem;
    color: #800040;
    text-align: center;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 0.25rem;
    width: 100%;
  }
  
  /* Estilo para la etiqueta de género */
  .genre-tag {
    display: inline-block;
    background-color: #800040;
    color: white;
    font-size: 0.65rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    text-transform: uppercase;
    white-space: nowrap;
    line-height: 1;
  }
  </style>