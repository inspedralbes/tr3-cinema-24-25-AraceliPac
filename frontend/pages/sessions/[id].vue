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
          <img :src="session.movie.image" :alt="session.movie.title" class="movie-image" />

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

              <!-- Información de asientos seleccionados y total a pagar -->
              <div class="selected-seats-info">
                <h3>Butaques Seleccionades: {{ selectedSeats.length }}</h3>
                <h3>Total a Pagar: {{ totalPrice }}€</h3>
                <button
                  @click="buyTickets"
                  class="select-seats-button"
                  :disabled="selectedSeats.length === 0 || selectedSeats.length > 10"
                >
                  Comprar entrades
                </button>
                <p v-if="selectedSeats.length > 10" class="error-message">
                  Has arribat al límit de 10 entrades per usuari.
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Utilizamos el componente PatiButaques -->
        <PatiButaques
          :seatsMap="seatsMap"
          :selectedSeats="selectedSeats"
          :loading="loadingSeats"
          @seat-selected="handleSeatSelection"
        />
      </div>
    </div>

    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, onUnmounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useSessionsStore } from "~/stores/sessions";
import { useAuthStore } from "~/stores/auth";
import { useTicketStore } from "~/stores/ticketStore";
import { onBeforeRouteLeave } from 'vue-router';
import { useNuxtApp } from '#app';

const route = useRoute();
const router = useRouter();
const sessionsStore = useSessionsStore();
const authStore = useAuthStore();
const ticketStore = useTicketStore();

const loading = ref(true);
const error = ref(null);
const loadingSeats = ref(false);
const session = ref(null);
const seatsLoaded = ref(false);
const selectedSeats = ref([]);
const totalPrice = ref(0);

// Función para asegurarse de que el body no tenga overflow: hidden
const resetBodyStyles = () => {
  if (document && document.body) {
    document.body.style.overflow = '';
  }
};

// Session ID de la ruta
const sessionId = computed(() => route.params.id);

// Obtener datos de la sesión y asientos
const loadSessionData = async () => {
  loading.value = true;
  error.value = null;
  seatsLoaded.value = false;

  try {
    // Asegurarse de que el body no tenga overflow hidden al cargar datos
    resetBodyStyles();
    
    // Cargar datos de la sesión
    await sessionsStore.fetchSessionById(sessionId.value);

    // Verificar si se cargó la sesión
    if (!sessionsStore.currentSession) {
      throw new Error("No es va poder carregar la informació de la sessió");
    }

    // Formatear la sesión
    const sessionDate = new Date(sessionsStore.currentSession.screening_date);
    session.value = {
      ...sessionsStore.currentSession,
      formattedDate: sessionDate.toLocaleDateString("ca-ES", {
        weekday: "long",
        day: "numeric",
        month: "long",
      }),
      formattedTime: sessionsStore.currentSession.screening_time.substring(0, 5),
      isSpecialDay: sessionsStore.currentSession.is_special_day === 1,
    };

    // Cargar datos de los asientos
    loadingSeats.value = true;
    await sessionsStore.fetchSessionSeats(sessionId.value);
    seatsLoaded.value = true;
  } catch (err) {
    error.value = "Error al carregar les dades de la sessió";
    console.error("Error carregant dades de la sessió:", err);
  } finally {
    loading.value = false;
    loadingSeats.value = false;
  }
};

// Mapa de asientos disponibles
const seatsMap = computed(() => {
  const map = sessionsStore.availableSeatsMap;
  // Comprobar si tenemos datos válidos
  if (!map || typeof map !== "object") {
    console.warn("El mapa de butaques no és un objecte vàlid:", map);
    return {
      rows: {},
      totalAvailable: 0,
      totalOccupied: 0,
      vipAvailable: 0,
      normalAvailable: 0,
    };
  }
  return map;
});

// Función para obtener el ID del usuario actual (autenticado o temporal)
const getCurrentUserId = () => {
  return authStore.user?.id || (process.client ? sessionStorage.getItem('temp_user_id') : null);
};

// Manejar la selección de asientos
const handleSeatSelection = (seat) => {
  if (seat.is_occupied) return; // No permitir seleccionar asientos ocupados

  const seatIndex = selectedSeats.value.findIndex(
    (s) => s.row === seat.row && s.number === seat.number
  );

  if (seatIndex === -1) {
    // Si el asiento no está seleccionado, lo añadimos (si no se ha alcanzado el límite de 10)
    if (selectedSeats.value.length < 10) {
      selectedSeats.value.push(seat);
      totalPrice.value += session.value.isSpecialDay ? (seat.is_vip ? 6 : 4) : seat.is_vip ? 8 : 6;
    }
  } else {
    // Si el asiento ya está seleccionado, lo eliminamos
    selectedSeats.value.splice(seatIndex, 1);
    totalPrice.value -= session.value.isSpecialDay ? (seat.is_vip ? 6 : 4) : seat.is_vip ? 8 : 6;
  }
};

// Función para comprar entradas
const buyTickets = () => {
  if (selectedSeats.value.length === 0 || selectedSeats.value.length > 10) return;

  // Crear objeto con los datos de la compra
  const purchaseInfo = {
    sessionId: sessionId.value,
    seats: selectedSeats.value,
    totalPrice: totalPrice.value,
  };

  // Verificar si el usuario está autenticado
  if (!authStore.isAuthenticated || !authStore.token) {
    // Guardar la información directamente en localStorage (solución temporal)
    if (process.client && localStorage) {
      localStorage.setItem('pendingPurchase', JSON.stringify(purchaseInfo));
    }

    // Redirigir a la página de inicio de sesión
    router.push("/usuari/iniciSessio");
    return;
  }

  // Si el usuario está autenticado, proceder a la confirmación
  console.log("Asientos seleccionados:", selectedSeats.value);
  console.log("Total a pagar:", totalPrice.value);

  // Establecer un temporizador para liberar las butacas después de 30 segundos
  if (process.client) {
    // Guardar los IDs de los asientos seleccionados
    const seatIds = selectedSeats.value.map(seat => seat.id);
    
    // Guardar timestamp de cuando se inició la compra
    localStorage.setItem('purchase_started', Date.now().toString());
    localStorage.setItem('purchase_seats', JSON.stringify(seatIds));
    localStorage.setItem('purchase_screening', sessionId.value);
    
    // Configurar el timeout
    setTimeout(() => {
      // Verificar si ha pasado suficiente tiempo y si estamos en la página de confirmación
      const purchaseStarted = localStorage.getItem('purchase_started');
      const savedSeats = JSON.parse(localStorage.getItem('purchase_seats') || '[]');
      const savedScreening = localStorage.getItem('purchase_screening');
      
      // Si han pasado más de 30 segundos y hay asientos guardados
      if (purchaseStarted && savedSeats.length > 0 && savedScreening) {
        const elapsed = Date.now() - parseInt(purchaseStarted);
        
        if (elapsed > 30000) { // 30 segundos
          console.log('Han pasado 30 segundos, liberando butacas');
          
          // Comprobar si todavía estamos en proceso de compra (no se ha completado)
          const currentPath = window.location.pathname;
          const isStillPurchasing = currentPath.includes('/sessions/confirmation');
          
          if (isStillPurchasing) {
            // Si seguimos en la página de confirmación después de 30 segundos
            // podríamos mostrar un mensaje o redirigir, pero permitiremos que continúe
            console.log('Usuario aún en proceso de compra, manteniendo butacas');
          } else {
            // Liberar los asientos si han pasado más de 30 segundos y no estamos en confirmación
            try {
              const { $socket } = useNuxtApp();
              const userId = getCurrentUserId();
              
              if ($socket && $socket.connected && userId) {
                savedSeats.forEach(seatId => {
                  $socket.emit('release-seat', {
                    screeningId: parseInt(savedScreening, 10),
                    seatId: parseInt(seatId, 10),
                    userId: userId
                  });
                  console.log('Tiempo expirado: liberando butaca:', seatId);
                });
              }
              
              // Limpiar localStorage
              localStorage.removeItem('purchase_started');
              localStorage.removeItem('purchase_seats');
              localStorage.removeItem('purchase_screening');
            } catch (error) {
              console.error('Error al liberar butacas después del timeout:', error);
            }
          }
        }
      }
    }, 30000); // 30 segundos
  }

  // Codificar la información para pasarla como parámetro de URL
  const encodedInfo = encodeURIComponent(JSON.stringify(purchaseInfo));

  // Redirigir a la página de confirmación
  router.push(`/sessions/confirmation?info=${encodedInfo}`);
};

// También necesitamos actualizar la función para recuperar compras pendientes
const recoverPendingPurchase = () => {
  // Solución temporal: Obtener directamente de localStorage
  if (process.client && localStorage) {
    const pendingData = localStorage.getItem('pendingPurchase');
    if (pendingData) {
      try {
        const pendingPurchase = JSON.parse(pendingData);
        
        // Verificar que la pendingPurchase corresponde a esta sesión
        if (pendingPurchase.sessionId === sessionId.value) {
          // Restaurar los asientos seleccionados
          selectedSeats.value = pendingPurchase.seats || [];
          totalPrice.value = pendingPurchase.totalPrice || 0;
          
          // Limpiar la compra pendiente
          localStorage.removeItem('pendingPurchase');
        } else {
          // Si la compra pendiente es para otra sesión, simplemente limpiarla
          localStorage.removeItem('pendingPurchase');
        }
      } catch (error) {
        console.error('Error al recuperar compra pendiente:', error);
        localStorage.removeItem('pendingPurchase');
      }
    }
  }
};

// Asegurarse de que el body no tenga overflow hidden al salir de la ruta
onBeforeRouteLeave((to, from, next) => {
  // Si vamos a la página de confirmación, no liberamos las butacas
  // ya que queremos mantenerlas seleccionadas durante el proceso de compra
  const isGoingToConfirmation = to.path.includes('/sessions/confirmation');
  
  if (!isGoingToConfirmation && selectedSeats.value.length > 0) {
    // Solo si NO vamos a confirmación, liberamos las butacas
    try {
      const { $socket } = useNuxtApp();
      const userId = getCurrentUserId();
      
      if ($socket && $socket.connected && userId) {
        selectedSeats.value.forEach(seat => {
          $socket.emit('release-seat', {
            screeningId: parseInt(sessionId.value, 10),
            seatId: parseInt(seat.id, 10),
            userId: userId
          });
          console.log('Liberando butaca al abandonar página:', seat.id);
        });
      }
    } catch (error) {
      console.error('Error al liberar butacas:', error);
    }
  }
  
  // Resetear estilos del body
  resetBodyStyles();
  next();
});

// Garantizar que el overflow se resetea al desmontar el componente
onUnmounted(() => {
  resetBodyStyles();
  
  // Limpiar cualquier temporizador pendiente
  if (process.client) {
    // No necesitamos una limpieza específica aquí ya que los timeouts
    // se gestionan con localStorage, pero podríamos añadir lógica adicional si fuera necesario
  }
});

// Inicialización al montar el componente
onMounted(() => {
  // Asegurarse de que el body no tenga overflow hidden al inicio
  resetBodyStyles();
  
  // Cargar datos de la sesión
  loadSessionData();
  
  // Si el usuario está autenticado, verificar si hay una compra pendiente
  if (authStore.isAuthenticated) {
    recoverPendingPurchase();
  }
  
  // Verificar si tenemos butacas que deberían liberarse (compra no completada)
  if (process.client) {
    const purchaseStarted = localStorage.getItem('purchase_started');
    const savedSeats = JSON.parse(localStorage.getItem('purchase_seats') || '[]');
    const savedScreening = localStorage.getItem('purchase_screening');
    
    if (purchaseStarted && savedSeats.length > 0) {
      const elapsed = Date.now() - parseInt(purchaseStarted);
      
      // Si han pasado más de 2 minutos, consideramos que la compra se abandonó
      if (elapsed > 120000) { // 2 minutos
        try {
          const { $socket } = useNuxtApp();
          const userId = getCurrentUserId();
          
          if ($socket && $socket.connected && userId && savedScreening) {
            savedSeats.forEach(seatId => {
              $socket.emit('release-seat', {
                screeningId: parseInt(savedScreening, 10),
                seatId: parseInt(seatId, 10),
                userId: userId
              });
              console.log('Liberando butaca de compra abandonada:', seatId);
            });
          }
          
          // Limpiar localStorage
          localStorage.removeItem('purchase_started');
          localStorage.removeItem('purchase_seats');
          localStorage.removeItem('purchase_screening');
        } catch (error) {
          console.error('Error al liberar butacas de compra abandonada:', error);
        }
      }
    }
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

.loading,
.seats-loading {
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
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
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

/* Información de asientos seleccionados */
.selected-seats-info {
  margin-top: 20px;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  text-align: center;
}

.selected-seats-info h3 {
  margin-bottom: 10px;
  color: #800040;
}

.error-message {
  color: red;
  margin-top: 10px;
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

  .selected-seats-info {
    padding: 10px;
  }

  .selected-seats-info h3 {
    font-size: 1rem;
  }
}
</style>