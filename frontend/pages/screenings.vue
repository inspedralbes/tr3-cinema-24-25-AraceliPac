<!-- pages/sessions.vue -->
<template>
    <div>
      <NavBar />
      
      <div class="sessions-container">
        <header class="sessions-header">
          <h1>Sessions disponibles</h1>
          <p class="subtitle">Descobreix les pel·lícules en cartellera i escull el teu horari preferit</p>
        </header>
        
        <!-- Estado de carga -->
        <div v-if="loading" class="loading-container">
          <div class="spinner"></div>
          <p>Carregant sessions...</p>
        </div>
        
        <!-- Error -->
        <div v-else-if="error" class="error-container">
          <p class="error-message">{{ error }}</p>
          <button @click="fetchSessions" class="retry-button">Tornar-ho a provar</button>
        </div>
        
        <!-- Sin sesiones -->
        <div v-else-if="movies.length === 0" class="no-sessions">
          <p>No hi ha sessions programades actualment.</p>
        </div>
        
        <!-- Listado de películas agrupadas por fecha -->
        <div v-else>
          <div v-for="(dateGroup, date) in moviesByDate" :key="date" class="date-group">
            <h2 class="date-title">{{ formatDate(date) }}</h2>
            
            <div class="movies-grid">
              <div v-for="movie in dateGroup" :key="`${date}-${movie.id}`" class="movie-card">
                <div class="movie-image">
                  <img :src="movie.image" :alt="movie.title">
                  <div v-if="hasSpecialDay(movie.sessions)" class="special-day-badge">Dia de l'espectador</div>
                </div>
                
                <div class="movie-details">
                  <h3 class="movie-title">{{ movie.title }}</h3>
                  
                  <div class="movie-metadata">
                    <span class="year">{{ movie.release_year }}</span>
                    <span class="duration">{{ formatDuration(movie.duration) }}</span>
                    <span class="rating">
                      <span class="star">★</span> {{ movie.rating }}/5
                    </span>
                  </div>
                  
                  <p class="movie-description">{{ movie.description }}</p>
                  
                  <div class="sessions-times">
                    <h4>Horaris:</h4>
                    <div class="times-container">
                      <div 
                        v-for="session in movie.sessions" 
                        :key="session.id" 
                        class="time-chip"
                        :class="{ 'special-day': session.isSpecialDay }"
                        @click="selectSession(session)"
                      >
                        {{ session.formattedTime }}
                      </div>
                    </div>
                  </div>
                  
                  <div class="action-buttons">
                    <button @click="showMovieDetails(movie)" class="details-button">
                      Més informació
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Modal selección de sesión -->
      <div v-if="selectedSession" class="modal" @click="closeModal">
        <div class="modal-content session-select" @click.stop>
          <button class="close-button" @click="closeModal">×</button>
          
          <h3 class="modal-title">{{ selectedSession.movie.title }}</h3>
          <p class="session-details">
            {{ selectedSession.formattedDate }} - {{ selectedSession.formattedTime }}
            <span v-if="selectedSession.isSpecialDay" class="modal-special-day">
              (Dia de l'espectador)
            </span>
          </p>
          
          <div class="session-actions">
            <NuxtLink :to="`/sessions/${selectedSession.id}`" class="book-button">
              Reservar entrades
            </NuxtLink>
          </div>
        </div>
      </div>
      
      <!-- Modal de detalles de película -->
      <div v-if="selectedMovie" class="modal" @click="closeModal">
        <div class="modal-content movie-details-modal" @click.stop>
          <button class="close-button" @click="closeModal">×</button>
          
          <div class="modal-header">
            <img :src="selectedMovie.image" :alt="selectedMovie.title" class="modal-image">
            
            <div class="modal-title-section">
              <h3 class="modal-title">{{ selectedMovie.title }}</h3>
              
              <div class="modal-metadata">
                <div class="metadata-item">
                  <span class="metadata-label">Any:</span>
                  <span class="metadata-value">{{ selectedMovie.release_year }}</span>
                </div>
                
                <div class="metadata-item">
                  <span class="metadata-label">Duració:</span>
                  <span class="metadata-value">{{ formatDuration(selectedMovie.duration) }}</span>
                </div>
                
                <div class="metadata-item">
                  <span class="metadata-label">Valoració:</span>
                  <span class="metadata-value">
                    <span class="star">★</span> {{ selectedMovie.rating }}/5
                  </span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="modal-body">
            <div class="movie-description-section">
              <h4>Sinopsi</h4>
              <p>{{ selectedMovie.description }}</p>
            </div>
            
            <div v-if="selectedMovie.trailer" class="trailer-section">
              <h4>Tràiler</h4>
              <div class="trailer-container">
                <iframe 
                  :src="getEmbedUrl(selectedMovie.trailer)" 
                  frameborder="0" 
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                  allowfullscreen
                ></iframe>
              </div>
            </div>
            
            <div class="available-sessions">
              <h4>Sessions disponibles:</h4>
              <div class="modal-sessions-list">
                <div 
                  v-for="session in getMovieSessions(selectedMovie.id)" 
                  :key="session.id" 
                  class="modal-session"
                  :class="{ 'special-day': session.isSpecialDay }"
                >
                  <div class="session-time">{{ session.formattedTime }}</div>
                  <div v-if="session.isSpecialDay" class="special-day-label">Dia Espectador</div>
                  <NuxtLink :to="`/sessions/${session.id}`" class="session-book-button">
                    Reservar
                  </NuxtLink>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <Footer />
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import { useSessionsStore } from '~/stores/sessions';
  
  // Store
  const sessionsStore = useSessionsStore();
  
  // Referencias
  const loading = ref(true);
  const error = ref(null);
  const selectedSession = ref(null);
  const selectedMovie = ref(null);
  
  // Computed properties
  const upcomingSessions = computed(() => sessionsStore.formattedUpcomingSessions);
  
  // Agrupar películas por fecha, evitando repetir películas
  const moviesByDate = computed(() => {
    const result = {};
    
    // Primero agrupamos por fecha
    upcomingSessions.value.forEach(session => {
      if (!result[session.screening_date]) {
        result[session.screening_date] = {};
      }
      
      // Usamos el ID de la película como clave
      if (!result[session.screening_date][session.movie.id]) {
        // Copiamos la película y añadimos un array para las sesiones
        result[session.screening_date][session.movie.id] = {
          ...session.movie,
          sessions: []
        };
      }
      
      // Añadimos esta sesión a la película
      result[session.screening_date][session.movie.id].sessions.push(session);
    });
    
    // Convertimos el objeto de películas en un array
    const finalResult = {};
    Object.keys(result).forEach(date => {
      finalResult[date] = Object.values(result[date]);
    });
    
    // Ordenamos por fecha
    return Object.fromEntries(
      Object.entries(finalResult).sort(([dateA], [dateB]) => 
        new Date(dateA) - new Date(dateB)
      )
    );
  });
  
  // Lista única de películas
  const movies = computed(() => {
    const uniqueMovies = {};
    
    upcomingSessions.value.forEach(session => {
      if (!uniqueMovies[session.movie.id]) {
        uniqueMovies[session.movie.id] = session.movie;
      }
    });
    
    return Object.values(uniqueMovies);
  });
  
  // Métodos
  const fetchSessions = async () => {
    try {
      loading.value = true;
      error.value = null;
      await sessionsStore.fetchUpcomingSessions();
    } catch (err) {
      error.value = 'Error al carregar les sessions. Si us plau, torna-ho a provar més tard.';
      console.error('Error fetching sessions:', err);
    } finally {
      loading.value = false;
    }
  };
  
  const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    
    return date.toLocaleDateString('ca-ES', {
      weekday: 'long',
      day: 'numeric',
      month: 'long'
    });
  };
  
  const formatDuration = (minutes) => {
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return `${hours}h ${mins}min`;
  };
  
  const hasSpecialDay = (sessions) => {
    return sessions.some(session => session.isSpecialDay);
  };
  
  const selectSession = (session) => {
    selectedSession.value = session;
    // document.body.style.overflow = 'hidden';
  };
  
  const showMovieDetails = (movie) => {
    selectedMovie.value = movie;
    document.body.style.overflow = 'hidden';
  };
  
  const closeModal = () => {
    selectedSession.value = null;
    selectedMovie.value = null;
    document.body.style.overflow = '';
  };
  
  const getMovieSessions = (movieId) => {
    return upcomingSessions.value.filter(session => 
      session.movie_id === movieId
    );
  };
  
  const getEmbedUrl = (trailerUrl) => {
    // Convertir URL de YouTube al formato embed
    if (trailerUrl.includes('youtube.com') || trailerUrl.includes('youtu.be')) {
      let videoId = '';
      
      if (trailerUrl.includes('v=')) {
        videoId = trailerUrl.split('v=')[1].split('&')[0];
      } else if (trailerUrl.includes('youtu.be/')) {
        videoId = trailerUrl.split('youtu.be/')[1];
      }
      
      if (videoId) {
        return `https://www.youtube.com/embed/${videoId}`;
      }
    }
    
    return trailerUrl;
  };
  
  // Ciclo de vida
  onMounted(() => {
    fetchSessions();
  });
  </script>
  
  <style scoped>
  .sessions-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Montserrat', sans-serif;
  }
  
  .sessions-header {
    text-align: center;
    margin-bottom: 40px;
  }
  
  .sessions-header h1 {
    font-size: 2.4rem;
    color: #800040; /* Color burdeos */
    margin-bottom: 10px;
  }
  
  .subtitle {
    font-size: 1.2rem;
    color: #555;
  }
  
  /* Estados de carga y error */
  .loading-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 50px 0;
  }
  
  .spinner {
    width: 40px;
    height: 40px;
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-left-color: #800040; /* Color burdeos */
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 20px;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  .error-container {
    text-align: center;
    padding: 30px;
    background-color: #fff1f1;
    border-radius: 8px;
    margin-bottom: 30px;
  }
  
  .error-message {
    color: #800040; /* Color burdeos */
    margin-bottom: 15px;
    font-size: 1.1rem;
  }
  
  .retry-button {
    background-color: #800040; /* Color burdeos */
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
  }
  
  .no-sessions {
    text-align: center;
    padding: 50px 0;
    background-color: #f8f8f8;
    border-radius: 8px;
    font-size: 1.1rem;
    color: #555;
  }
  
  /* Grupos de fecha */
  .date-group {
    margin-bottom: 50px;
  }
  
  .date-title {
    font-size: 1.6rem;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #800040; /* Color burdeos */
    text-transform: capitalize;
  }
  
  /* Grid de películas */
  .movies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
  }
  
  /* Tarjeta de película */
  .movie-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
  }
  
  .movie-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
  }
  
  .movie-image {
    position: relative;
    height: 180px;
  }
  
  .movie-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .special-day-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: #800040; /* Color burdeos */
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
  }
  
  .movie-details {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
  }
  
  .movie-title {
    font-size: 1.3rem;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.3;
  }
  
  .movie-metadata {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
  }
  
  .year, .duration {
    background-color: #f5f5f5;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
  }
  
  .rating {
    background-color: #f5f5f5;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
  }
  
  .star {
    color: #FFD700;
    margin-right: 2px;
  }
  
  .movie-description {
    color: #666;
    margin-bottom: 15px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  
  /* Horarios agrupados */
  .sessions-times {
    margin-bottom: 20px;
    flex-grow: 1;
  }
  
  .sessions-times h4 {
    font-size: 1rem;
    margin-bottom: 8px;
    color: #555;
  }
  
  .times-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }
  
  .time-chip {
    background-color: #f0f0f0;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background-color 0.2s ease, color 0.2s ease;
  }
  
  .time-chip:hover {
    background-color: #800040; /* Color burdeos */
    color: white;
  }
  
  .time-chip.special-day {
    background-color: #f2e6eb; /* Burdeos claro */
    border: 1px dashed #800040;
  }
  
  .time-chip.special-day:hover {
    background-color: #800040;
    color: white;
  }
  
  .action-buttons {
    display: flex;
    justify-content: center;
  }
  
  .details-button {
    background-color: transparent;
    border: 1px solid #800040; /* Color burdeos */
    color: #800040; /* Color burdeos */
    padding: 10px 15px;
    border-radius: 4px;
    font-weight: 500;
    width: 100%;
    text-align: center;
    transition: background-color 0.2s ease, color 0.2s ease;
    cursor: pointer;
  }
  
  .details-button:hover {
    background-color: #800040; /* Color burdeos */
    color: white;
  }
  
  /* Modal */
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.75);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    z-index: 1000;
  }
  
  .modal-content {
    background-color: white;
    border-radius: 8px;
    max-width: 800px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
  }
  
  .session-select {
    max-width: 450px;
    padding: 25px;
    text-align: center;
  }
  
  .close-button {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    font-size: 1.8rem;
    color: #333;
    cursor: pointer;
    z-index: 10;
  }
  
  .session-details {
    margin-bottom: 20px;
    color: #555;
  }
  
  .modal-special-day {
    color: #800040; /* Color burdeos */
    font-weight: 500;
  }
  
  .session-actions {
    display: flex;
    justify-content: center;
  }
  
  .book-button {
    background-color: #800040; /* Color burdeos */
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 4px;
    font-weight: 500;
    text-align: center;
    text-decoration: none;
    transition: background-color 0.2s ease;
    cursor: pointer;
    font-size: 1rem;
    width: 100%;
    max-width: 300px;
  }
  
  .book-button:hover {
    background-color: #600030; /* Burdeos más oscuro */
  }
  
  /* Modal de detalles de película */
  .movie-details-modal {
    padding: 0;
  }
  
  .modal-header {
    display: flex;
    padding: 20px;
    gap: 20px;
  }
  
  .modal-image {
    width: 180px;
    height: 270px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
  }
  
  .modal-title-section {
    flex-grow: 1;
  }
  
  .modal-title {
    font-size: 1.8rem;
    margin-bottom: 15px;
    color: #333;
  }
  
  .modal-metadata {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
  }
  
  .metadata-item {
    margin-bottom: 8px;
  }
  
  .metadata-label {
    font-weight: 500;
    color: #555;
    margin-right: 5px;
  }
  
  .modal-body {
    padding: 0 20px 20px;
  }
  
  .movie-description-section, .trailer-section, .available-sessions {
    margin-bottom: 25px;
  }
  
  .movie-description-section h4, .trailer-section h4, .available-sessions h4 {
    color: #800040; /* Color burdeos */
    margin-bottom: 10px;
    font-size: 1.2rem;
  }
  
  .trailer-container {
    position: relative;
    padding-bottom: 56.25%; /* Ratio 16:9 */
    height: 0;
    overflow: hidden;
    border-radius: 8px;
  }
  
  .trailer-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }
  
  .modal-sessions-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 15px;
    margin-top: 10px;
  }
  
  .modal-session {
    background-color: #f8f8f8;
    padding: 12px;
    border-radius: 6px;
    text-align: center;
    transition: transform 0.2s ease;
  }
  
  .modal-session:hover {
    transform: scale(1.05);
  }
  
  .modal-session.special-day {
    background-color: #f2e6eb; /* Burdeos claro */
    border: 1px dashed #800040;
  }
  
  .session-time {
    font-weight: 500;
    font-size: 1.1rem;
    margin-bottom: 5px;
  }
  
  .special-day-label {
    font-size: 0.8rem;
    color: #800040; /* Color burdeos */
    margin-bottom: 10px;
  }
  
  .session-book-button {
    display: block;
    background-color: #800040; /* Color burdeos */
    color: white;
    text-decoration: none;
    padding: 5px 0;
    border-radius: 4px;
    font-size: 0.9rem;
    transition: background-color 0.2s ease;
  }
  
  .session-book-button:hover {
    background-color: #600030; /* Burdeos más oscuro */
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .modal-header {
      flex-direction: column;
      align-items: center;
      text-align: center;
    }
    
    .modal-image {
      margin-bottom: 15px;
    }
    
    .modal-metadata {
      grid-template-columns: 1fr;
    }
  }
  
  @media (max-width: 600px) {
    .movies-grid {
      grid-template-columns: 1fr;
    }
    
    .sessions-header h1 {
      font-size: 2rem;
    }
    
    .date-title {
      font-size: 1.4rem;
    }
    
    .modal-sessions-list {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  </style>