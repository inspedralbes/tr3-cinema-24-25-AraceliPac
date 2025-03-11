<template>
  <div>
    <NavBar />
    
    <div class="session-detail-page">
      <div v-if="loading" class="loading">
        <div class="spinner"></div>
        <p>Carregant sessió...</p>
      </div>
      
      <div v-else-if="error" class="error-message">
        <p>{{ error }}</p>
        <button @click="loadSessionData" class="retry-button">Tornar-ho a provar</button>
      </div>
      
      <div v-else-if="session" class="session-details">
        <!-- Información de la película y la sesión -->
        <div class="movie-header">
          <img :src="session.movie.image" :alt="session.movie.title" class="movie-image">
          
          <div class="session-info">
            <h1>{{ session.movie.title }}</h1>
            
            <div class="session-metadata">
              <div class="metadata-item">
                <span class="metadata-label">Data:</span>
                <span>{{ session.formattedDate }}</span>
              </div>
              <div class="metadata-item">
                <span class="metadata-label">Hora:</span>
                <span>{{ session.formattedTime }}</span>
              </div>
              <div class="metadata-item" v-if="session.isSpecialDay">
                <span class="special-day-badge">Dia de l'espectador</span>
              </div>
            </div>
            
            <!-- Estado de carga para asientos -->
            <div v-if="loadingSeats" class="seats-loading">
              <p>Carregant disponibilitat de butaques...</p>
            </div>
            
            <div v-else class="seat-availability">
              <h3>Disponibilitat de butaques</h3>
              <div class="availability-stats">
                <div class="availability-item">
                  <span class="availability-value">{{ seatsMap.normalAvailable || 0 }}</span>
                  <span class="availability-label">Butaques Normals</span>
                </div>
                <div class="availability-item">
                  <span class="availability-value">{{ seatsMap.vipAvailable || 0 }}</span>
                  <span class="availability-label">Butaques VIP</span>
                </div>
                <div class="availability-item">
                  <span class="availability-value">{{ seatsMap.totalAvailable || 0 }}</span>
                  <span class="availability-label">Total Disponibles</span>
                </div>
              </div>
            </div>
            
            <button 
              @click="goToSeatSelection" 
              class="select-seats-button"
              :disabled="!seatsMap.totalAvailable"
            >
              Seleccionar butaques
            </button>
          </div>
        </div>
        
        <!-- Patio de butacas -->
        <div v-if="!loadingSeats && hasSeats" class="seating-chart">
          <h3>Patio de Butaques</h3>
          
          <div class="screen">Pantalla</div>
          
          <div class="seating-grid">
            <div 
              v-for="rowLabel in rowLabels" 
              :key="rowLabel" 
              class="seat-row"
            >
              <div class="row-label">{{ rowLabel }}</div>
              
              <div class="seats-container">
                <div 
                  v-for="seat in (seatsMap.rows[rowLabel] || [])" 
                  :key="`${seat.row}-${seat.number}`" 
                  :class="['seat', { 
                    'seat-vip': seat.is_vip == 1, 
                    'seat-occupied': seat.is_occupied == 1 
                  }]"
                >
                  {{ seat.number }}
                </div>
              </div>
              
              <div class="row-label">{{ rowLabel }}</div>
            </div>
          </div>
          
          <div class="seat-legend">
            <div class="legend-item">
              <div class="legend-seat"></div>
              <span>Disponible</span>
            </div>
            <div class="legend-item">
              <div class="legend-seat seat-vip"></div>
              <span>VIP</span>
            </div>
            <div class="legend-item">
              <div class="legend-seat seat-occupied"></div>
              <span>Ocupada</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useSessionsStore } from '~/stores/sessions'; 

const route = useRoute();
const router = useRouter();
const sessionsStore = useSessionsStore();

const loading = ref(true);
const error = ref(null);
const loadingSeats = ref(false);
const session = ref(null);
const seatsLoaded = ref(false);

// Session ID de la ruta
const sessionId = computed(() => route.params.id);

// Obtener datos de la sesión y asientos
const loadSessionData = async () => {
  loading.value = true;
  error.value = null;
  seatsLoaded.value = false;
  
  try {
    // Cargar datos de la sesión
    await sessionsStore.fetchSessionById(sessionId.value);
    
    // Verificar si se cargó la sesión
    if (!sessionsStore.currentSession) {
      throw new Error('No se pudo cargar la información de la sesión');
    }
    
    // Formatear la sesión
    const sessionDate = new Date(sessionsStore.currentSession.screening_date);
    session.value = {
      ...sessionsStore.currentSession,
      formattedDate: sessionDate.toLocaleDateString('ca-ES', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
      }),
      formattedTime: sessionsStore.currentSession.screening_time.substring(0, 5),
      isSpecialDay: sessionsStore.currentSession.is_special_day === 1
    };
    
    // Cargar datos de los asientos
    loadingSeats.value = true;
    await sessionsStore.fetchSessionSeats(sessionId.value);
    seatsLoaded.value = true;
    console.log("Seats loaded:", sessionsStore.sessionSeats);
  } catch (err) {
    error.value = 'Error al carregar les dades de la sessió';
    console.error('Error loading session data:', err);
  } finally {
    loading.value = false;
    loadingSeats.value = false;
  }
};
// Mapa de asientos disponibles
// En el computed property de seatsMap
const seatsMap = computed(() => {
  const map = sessionsStore.availableSeatsMap;
  console.log("Computed seatsMap:", map);
  // Comprobar si tenemos datos válidos
  if (!map || typeof map !== 'object') {
    console.warn('seatsMap no es un objeto válido:', map);
    return {
      rows: {},
      totalAvailable: 0,
      totalOccupied: 0,
      vipAvailable: 0,
      normalAvailable: 0
    };
  }
  return map;
});

// Verificar si hay asientos cargados
const hasSeats = computed(() => {
  return Object.keys(seatsMap.value.rows).length > 0;
});

// Obtener las etiquetas de filas ordenadas alfabéticamente (A-L)
const rowLabels = computed(() => {
  const rows = Object.keys(seatsMap.value.rows || {});
  return rows.sort();
});

// Ir a la selección de asientos
const goToSeatSelection = () => {
  router.push(`/sessions/${sessionId.value}/seats`);
};

// Cargar datos al montar el componente
onMounted(() => {
  loadSessionData();
});

// Recargar cuando cambie el ID de la sesión
watch(() => sessionId.value, () => {
  if (sessionId.value) {
    loadSessionData();
  }
});
</script>

<style scoped>
/* Estilos generales */
.session-detail-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.loading, .seats-loading {
  text-align: center;
  padding: 20px;
}

.spinner {
  width: 40px;
  height: 40px;
  margin: 0 auto 15px;
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-left-color: #800040;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-message {
  text-align: center;
  padding: 20px;
  color: red;
}

.retry-button {
  margin-top: 10px;
  padding: 10px 20px;
  background-color: #800040;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.movie-header {
  display: flex;
  gap: 20px;
  margin-bottom: 30px;
}

.movie-image {
  width: 200px;
  height: 300px;
  object-fit: cover;
  border-radius: 8px;
}

.session-info {
  flex: 1;
}

.session-metadata {
  display: flex;
  gap: 15px;
  margin: 15px 0;
}

.metadata-item {
  display: flex;
  gap: 5px;
}

.special-day-badge {
  background-color: #ffcc00;
  color: #800040;
  padding: 5px 10px;
  border-radius: 4px;
  font-weight: bold;
}

.seat-availability {
  margin: 20px 0;
}

.availability-stats {
  display: flex;
  gap: 20px;
}

.availability-item {
  text-align: center;
}

.availability-value {
  font-size: 1.5rem;
  font-weight: bold;
  color: #800040;
}

.availability-label {
  font-size: 0.9rem;
  color: #555;
}

.select-seats-button {
  padding: 10px 20px;
  background-color: #800040;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.select-seats-button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

/* Estilos para el patio de butacas */
.seating-chart {
  margin-top: 30px;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  text-align: center;
}

.seating-chart h3 {
  margin-bottom: 20px;
  color: #800040;
}

.screen {
  height: 30px;
  background: linear-gradient(to bottom, #e0e0e0, #c0c0c0);
  border-radius: 6px;
  margin-bottom: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  color: #555;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.seating-grid {
  display: flex;
  flex-direction: column;
  gap: 10px;
  max-width: 800px;
  margin: 0 auto;
}

.seat-row {
  display: flex;
  align-items: center;
}

.row-label {
  width: 30px;
  text-align: center;
  font-weight: bold;
}

.seats-container {
  display: flex;
  flex: 1;
  justify-content: center;
  gap: 5px;
}

.seat {
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #e6e6e6;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 0.8rem;
}

.seat-vip {
  background-color: #f2e6eb;
  border: 1px dashed #800040;
}

.seat-occupied {
  background-color: #ccc;
  color: #888;
}

.seat-legend {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 5px;
}

.legend-seat {
  width: 20px;
  height: 20px;
  background-color: #e6e6e6;
  border: 1px solid #ccc;
  border-radius: 4px;
}

/* Media queries para responsividad */
@media (max-width: 768px) {
  .movie-header {
    flex-direction: column;
  }
  
  .movie-image {
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
  }
  
  .availability-stats {
    flex-direction: column;
    gap: 10px;
  }
}
</style>