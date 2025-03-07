<!-- pages/index.vue -->
<template>
   <Nav />
    <div class="cinema-home">
    
      <!-- Sección de sesiones -->
      <section class="sessions-section">
        <div v-if="loading" class="loading">
          <p>Carregant sessions...</p>
        </div>
        <div v-else-if="error" class="error-message">
          <p>{{ error }}</p>
        </div>
        <div v-else-if="upcomingSessions.length === 0" class="no-sessions">
          <p>No hi ha sessions programades</p>
        </div>
        <div v-else class="sessions-grid">
          <div v-for="session in upcomingSessions" :key="session.id" class="session-card">
            <img :src="session.movie.image" :alt="session.movie.title" class="movie-image">
            <div class="session-details">
              <h2 class="movie-title">{{ session.movie.title }}</h2>
              <p class="session-date">{{ session.formattedDate }}</p>
              <p class="session-time">Hora: {{ session.formattedTime }}</p>
              <p class="session-info">
                <span class="year">{{ session.movie.release_year }}</span>
                <span class="duration">{{ formatDuration(session.movie.duration) }}</span>
                <span v-if="session.isSpecialDay" class="special-day">Dia Espectador</span>
              </p>
              <p class="description">{{ session.movie.description }}</p>
              <button class="view-button">Veure detalls</button>
            </div>
          </div>
        </div>
      </section>
  
    
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import { useSessionsStore } from '~/stores/sessions';
  
  // Store
  const sessionsStore = useSessionsStore();
  
  // Refs
  const loading = ref(true);
  const error = ref(null);
  
  // Computed
  const upcomingSessions = computed(() => sessionsStore.formattedUpcomingSessions);
  
  // Methods
  const formatDuration = (minutes) => {
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return `${hours}h ${mins}min`;
  };
  
  const fetchSessions = async () => {
    try {
      loading.value = true;
      error.value = null;
      await sessionsStore.fetchUpcomingSessions();
      loading.value = false;
    } catch (err) {
      loading.value = false;
      error.value = 'Error al carregar les sessions. Si us plau, torna-ho a provar més tard.';
      console.error('Error fetching sessions:', err);
    }
  };
  
  // Lifecycle
  onMounted(() => {
    fetchSessions();
  });
  </script>
  
  <style scoped>
  .cinema-home {
    font-family: 'Montserrat', sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }
  
  .page-header {
    text-align: center;
    margin-bottom: 40px;
  }
  
  .page-header h1 {
    font-size: 2.5rem;
    color: #e50914;
    margin-bottom: 10px;
  }
  
  .subtitle {
    font-size: 1.5rem;
    color: #333;
  }
  
  .sessions-section {
    margin-bottom: 40px;
  }
  
  .loading, .error-message, .no-sessions {
    text-align: center;
    padding: 40px;
    font-size: 1.2rem;
    background-color: #f5f5f5;
    border-radius: 8px;
  }
  
  .error-message {
    color: #e50914;
  }
  
  .sessions-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
  }
  
  .session-card {
    display: flex;
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }
  
  .movie-image {
    width: 200px;
    height: 300px;
    object-fit: cover;
  }
  
  .session-details {
    padding: 20px;
    flex: 1;
  }
  
  .movie-title {
    font-size: 1.5rem;
    margin-bottom: 10px;
    color: #333;
  }
  
  .session-date, .session-time {
    margin-bottom: 5px;
    color: #555;
  }
  
  .session-info {
    display: flex;
    gap: 15px;
    margin: 10px 0;
  }
  
  .year, .duration {
    background-color: #f0f0f0;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.9rem;
  }
  
  .special-day {
    background-color: #ffe5e5;
    color: #e50914;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.9rem;
    font-weight: bold;
  }
  
  .description {
    margin: 15px 0;
    color: #666;
    line-height: 1.5;
  }
  
  .view-button {
    background-color: #e50914;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .view-button:hover {
    background-color: #c4070f;
  }
  
  .debug-info {
    margin-top: 50px;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
  }
  
  .debug-info h3 {
    margin-bottom: 15px;
  }
  
  .debug-button {
    background-color: #333;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    margin-bottom: 15px;
    cursor: pointer;
  }
  
  .debug-info pre {
    background-color: #f0f0f0;
    padding: 15px;
    border-radius: 4px;
    overflow-x: auto;
    font-size: 0.8rem;
  }
  
  /* Responsive */
  @media (max-width: 700px) {
    .session-card {
      flex-direction: column;
    }
    
    .movie-image {
      width: 100%;
      height: 200px;
    }
  }
  </style>