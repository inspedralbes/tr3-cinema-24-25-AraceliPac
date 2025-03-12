<template>
  <NavBar />
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
 
 // Store
 const sessionsStore = useSessionsStore();
 
 // Refs
 const loading = ref(true);
 const error = ref(null);
 const newsletterEmail = ref('');
 const culturalInfo = ref(false);
 
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
 
 .sessions-grid {
   display: grid;
   grid-template-columns: 1fr;
   gap: 20px;
 }
 
 .session-card {
   background-color: #ffffff;
   border-radius: 8px;
   overflow: hidden;
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
 
 .session-date, .session-time {
   color: #555;
   margin-bottom: 5px;
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
 
 .description {
   color: #666;
   line-height: 1.5;
   margin: 15px 0;
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
 }
 </style>