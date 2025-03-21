<template>
  <NavBar />
  <div class="cinema-home">
    <!-- Sección de sesiones -->
    <section class="sessions-section">
      <h1 class="sessions-title">Sessions d'avui {{ todayFormatted }}</h1>
      
      <div v-if="loading" class="loading">
        <p>Carregant sessions...</p>
      </div>
      <div v-else-if="error" class="error-message">
        <p>{{ error }}</p>
      </div>
      <div v-else-if="todaySessions.length === 0" class="no-sessions">
        <p>No hi ha sessions programades per avui</p>
      </div>
      <div v-else class="sessions-grid">
        <div v-for="movieGroup in groupedSessions" :key="movieGroup.movieId" class="session-card">
          <img :src="movieGroup.image" :alt="movieGroup.title" class="movie-image">
          <div class="session-details">
            <h2 class="movie-title">{{ movieGroup.title }}</h2>
            <p class="session-info">
              <span class="year">{{ movieGroup.releaseYear }}</span>
              <span class="duration">{{ formatDuration(movieGroup.duration) }}</span>
              <span v-if="movieGroup.isSpecialDay" class="special-day">Dia Espectador</span>
            </p>
            
            <!-- Horarios disponibles -->
            <div class="session-times">
              <h3>Horaris disponibles:</h3>
              <div class="times-grid">
                <button 
                  v-for="time in movieGroup.times" 
                  :key="time.sessionId" 
                  class="time-button"
                  :class="{'special-time': time.isSpecialDay}"
                  @click="selectSession(time.sessionId)"
                >
                  {{ time.formattedTime }}
                </button>
              </div>
            </div>
            
            <p class="description">{{ movieGroup.description }}</p>
            <button class="view-button" @click="viewMovieDetails(movieGroup.movieId)">Veure detalls</button>
          </div>
        </div>
      </div>
    </section>

    <!-- Aside con información de newsletter -->
    <aside class="newsletter-aside">
      <div class="newsletter-content">
        <h2>Subscriu-te al nostre butlletí</h2>
        <p>Rep tota la informació sobre les estrenes, esdeveniments especials i programació cultural del Cinema Pedralbes directament al teu correu.</p>
        
        <form class="newsletter-form" @submit.prevent="subscribeToNewsletter">
          <div class="form-group">
            <label for="email">Correu electrònic</label>
            <input 
              type="email" 
              id="email" 
              v-model="newsletterEmail" 
              placeholder="El teu correu electrònic" 
              required
            />
          </div>
          
          <div class="form-group checkbox-group">
            <input type="checkbox" id="cultural-info" v-model="culturalInfo">
            <label for="cultural-info">Vull rebre informació d'activitats culturals</label>
          </div>
          
          <button type="submit" class="subscribe-button">Subscriu-me</button>
        </form>
        
        <div class="contact-info">
          <p>Per a més informació:</p>
          <a href="mailto:info@cinemapedralbes.cat" class="contact-email">info@cinemapedralbes.cat</a>
        </div>
      </div>
    </aside>
  </div>
  <Footer />
</template>
 
<script setup>
import { ref, computed, onMounted } from 'vue';
import { useSessionsStore } from '~/stores/sessions';
import { useRouter } from 'vue-router';

// Store
const sessionsStore = useSessionsStore();
const router = useRouter();

// Refs
const loading = ref(true);
const error = ref(null);
const newsletterEmail = ref('');
const culturalInfo = ref(false);

// Computed para obtener solo las sesiones de hoy
const todaySessions = computed(() => {
  const today = new Date().toISOString().split('T')[0]; // Formato YYYY-MM-DD
  return sessionsStore.formattedUpcomingSessions.filter(session => 
    session.screening_date === today
  );
});

// Obtiene la fecha de hoy formateada
const todayFormatted = computed(() => {
  const options = { weekday: 'long', day: 'numeric', month: 'long' };
  return new Date().toLocaleDateString('ca-ES', options);
});

// Agrupa las sesiones por película
const groupedSessions = computed(() => {
  const groupedMap = {};
  
  todaySessions.value.forEach(session => {
    const movieId = session.movie.id;
    
    if (!groupedMap[movieId]) {
      groupedMap[movieId] = {
        movieId: movieId,
        title: session.movie.title,
        description: session.movie.description,
        releaseYear: session.movie.release_year,
        duration: session.movie.duration,
        image: session.movie.image,
        isSpecialDay: false, // Lo actualizaremos si alguna sesión es un día especial
        times: []
      };
    }
    
    // Añadir el tiempo a la lista de horarios
    groupedMap[movieId].times.push({
      sessionId: session.id,
      formattedTime: session.formattedTime,
      isSpecialDay: session.isSpecialDay
    });
    
    // Si alguna sesión es un día especial, marcamos la película
    if (session.isSpecialDay) {
      groupedMap[movieId].isSpecialDay = true;
    }
  });
  
  // Convertir el objeto en un array y ordenar por título
  return Object.values(groupedMap).sort((a, b) => a.title.localeCompare(b.title));
});

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

const selectSession = (sessionId) => {
  console.log('Sesión seleccionada:', sessionId);
  // Aquí puedes redirigir al usuario a la página de compra de entradas
  router.push(`/sessions/${sessionId}`);
};

const viewMovieDetails = (movieId) => {
  console.log('Ver detalles de película:', movieId);
  // Redirigir a la página de detalles de la película
  router.push(`/pelicules/movie-details/${movieId}`);
};

const subscribeToNewsletter = () => {
  // Aquí iría la lógica para enviar la suscripción
  console.log('Suscripción al boletín:', newsletterEmail.value, 'Info cultural:', culturalInfo.value);
  // Implementación de la llamada a la API
  
  // Resetear el formulario después del envío
  newsletterEmail.value = '';
  culturalInfo.value = false;
  
  // Mostrar confirmación al usuario
  alert('Gràcies per subscriure`t al nostre butlletí!');
};

// Lifecycle
onMounted(() => {
  fetchSessions();
});
</script>
<style scoped>
/* Estilos base */
.cinema-home {
  font-family: 'Montserrat', sans-serif;
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 30px;
}

/* Sección de sesiones */
.sessions-section {
  width: 100%;
}

.sessions-title {
  font-size: 1.8rem;
  color: #800040;
  margin-bottom: 20px;
  text-transform: capitalize;
}

.sessions-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
}

.session-card {
  background-color: #ffffff;
  border-radius: 8px;

  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
}

.movie-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.session-details {
  padding: 20px;
}

.movie-title {
  font-size: 1.5rem;
  color: #800040; /* Burdeos */
  margin-bottom: 10px;
}

.session-info {
  display: flex;
  gap: 10px;
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
  color: #800040; /* Burdeos */
  padding: 3px 8px;
  border-radius: 4px;
  font-size: 0.9rem;
  font-weight: bold;
}

/* Estilos para los horarios */
.session-times {
  margin: 15px 0;
}

.session-times h3 {
  font-size: 1rem;
  color: #555;
  margin-bottom: 8px;
}

.times-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.time-button {
  background-color: #f0f0f0;
  border: none;
  padding: 8px 12px;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.time-button:hover {
  background-color: #800040;
  color: white;
}

.special-time {
  background-color: #ffe5e5;
  color: #800040;
  border: 1px dashed #800040;
}

.special-time:hover {
  background-color: #800040;
  color: white;
  border: 1px solid #800040;
}

.description {
  color: #666;
  line-height: 1.5;
  margin: 15px 0;
  max-height: 4.5em;
  
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
}

.view-button {
  background-color: #800040; /* Burdeos */
  color: #ffffff; /* Blanco */
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s;
}

.view-button:hover {
  background-color: #6a0036; /* Burdeos oscuro */
}

/* Estados de carga y error */
.loading, .error-message, .no-sessions {
  text-align: center;
  padding: 30px;
  background-color: #f9f9f9;
  border-radius: 8px;
  margin-bottom: 20px;
}

.error-message {
  color: #d32f2f;
}

/* Aside de newsletter */
.newsletter-aside {
  background-color: #ffffff;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  width: 100%;
}

.newsletter-aside h2 {
  color: #800040; /* Burdeos */
  font-size: 1.4rem;
  margin-bottom: 15px;
}

.newsletter-aside p {
  color: #555;
  margin-bottom: 20px;
  line-height: 1.5;
}

.newsletter-form {
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  color: #333;
  font-weight: 500;
  display: block;
  margin-bottom: 5px;
}

.form-group input[type="email"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.checkbox-group {
  display: flex;
  align-items: center;
  gap: 8px;
}

.checkbox-group label {
  font-size: 0.9rem;
  color: #555;
}

.subscribe-button {
  background-color: #800040; /* Burdeos */
  color: #ffffff; /* Blanco */
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  font-weight: bold;
  width: 100%;
  cursor: pointer;
  transition: background-color 0.3s;
}

.subscribe-button:hover {
  background-color: #6a0036; /* Burdeos oscuro */
}

.contact-info {
  border-top: 1px solid #eee;
  padding-top: 15px;
  margin-top: 15px;
}

.contact-info p {
  color: #555;
  font-size: 0.9rem;
}

.contact-email {
  color: #800040; /* Burdeos */
  text-decoration: none;
  font-weight: 500;
}

.contact-email:hover {
  text-decoration: underline;
}

/* Responsive para pantallas grandes */
@media (min-width: 768px) {
  .cinema-home {
    flex-direction: row;
  }

  .sessions-section {
    flex: 2;
  }

  .newsletter-aside {
    flex: 1;
    position: sticky;
    top: 20px;
    height: fit-content;
  }
  
  .session-card {
    flex-direction: row;
  }
  
  .movie-image {
    width: 200px;
    height: 300px;
  }
}

@media (min-width: 992px) {
  .sessions-grid {
    grid-template-columns: repeat(auto-fill, minmax(700px, 1fr));
  }
}
</style>