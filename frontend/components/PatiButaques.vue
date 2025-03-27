<template>
  <div v-if="!loading && hasSeats" class="seating-chart">
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
              'seat-occupied': seat.is_occupied == 1,
              'seat-selected': isSelected(seat),
              'seat-temp-locked': isTempLocked(seat.id)
            }]"
            @click="handleSeatClick(seat)"
          >
            {{ seat.number }}
          </div>
        </div>
        
        <div class="row-label">{{ rowLabel }}</div>
      </div>
    </div>
    
    <div class="seat-legend">
      <div class="legend-item">
        <div class="legend-seat seat-available"></div>
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
      <div class="legend-item">
        <div class="legend-seat seat-selected"></div>
        <span>Seleccionada</span>
      </div>
      <div class="legend-item">
        <div class="legend-seat seat-temp-locked"></div>
        <span>Seleccionada por otro usuario</span>
      </div>
    </div>
  </div>
  <div v-else-if="loading" class="seats-loading">
    <p>Carregant disponibilitat de butaques...</p>
  </div>
  <div v-else class="no-seats-available">
    <p>No hi ha butaques disponibles per a aquesta sessió.</p>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '~/stores/auth';
import { useNuxtApp } from '#app';

const props = defineProps({
  seatsMap: {
    type: Object,
    required: true
  },
  selectedSeats: {
    type: Array,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['seat-selected']);
const route = useRoute();
const authStore = useAuthStore();

// Evitar clics rápidos consecutivos
const isProcessingClick = ref(false);

// Estado para almacenar butacas bloqueadas temporalmente por otros usuarios
const tempLockedSeats = ref({});

// Verificar si hay asientos cargados
const hasSeats = computed(() => {
  return Object.keys(props.seatsMap.rows || {}).length > 0;
});

// Obtener las etiquetas de filas ordenadas alfabéticamente
const rowLabels = computed(() => {
  const rows = Object.keys(props.seatsMap.rows || {});
  return rows.sort();
});

// Verificar si un asiento está seleccionado por el usuario actual
const isSelected = (seat) => {
  return props.selectedSeats.some(s => s.row === seat.row && s.number === seat.number);
};

// Verificar si un asiento está bloqueado temporalmente por otro usuario
const isTempLocked = (seatId) => {
  return tempLockedSeats.value[seatId] === true;
};

// Función para generar un ID temporal para usuarios no autenticados
const generateTemporaryUserId = () => {
  // Usar sessionStorage para mantener el mismo ID durante la sesión del navegador
  if (process.client) {
    let tempId = sessionStorage.getItem('temp_user_id');
    if (!tempId) {
      tempId = 'temp_' + Date.now() + '_' + Math.random().toString(36).substring(2, 9);
      sessionStorage.setItem('temp_user_id', tempId);
    }
    return tempId;
  }
  return 'temp_' + Date.now(); // Fallback
};

// Obtener el ID del usuario actual (autenticado o temporal)
const getCurrentUserId = () => {
  return authStore.user?.id || generateTemporaryUserId();
};

// Manejar la selección de asientos
const handleSeatClick = (seat) => {
  // No permitir seleccionar butacas ocupadas o bloqueadas temporalmente
  if (seat.is_occupied == 1 || isTempLocked(seat.id)) {
    console.log('Butaca no disponible:', seat.id);
    return;
  }
  
  // Evitar clics rápidos consecutivos
  if (isProcessingClick.value) return;
  isProcessingClick.value = true;
  
  // Verificar si la butaca ya estaba seleccionada
  const wasSelected = isSelected(seat);
  
  // Emitir el evento para la lógica de la aplicación (actualizar array de selección)
  emit('seat-selected', seat);
  
  try {
    // Obtener el socket
    const { $socket } = useNuxtApp();
    
    // Obtener el ID del usuario actual
    const userId = getCurrentUserId();
    
    // Preparar datos para el evento
    const eventData = {
      screeningId: parseInt(route.params.id, 10),
      seatId: parseInt(seat.id, 10),
      userId: userId
    };
    
    // Verificamos nuevamente después de emitir el evento local,
    // ya que ahora el estado podría haber cambiado
    const isNowSelected = isSelected(seat);
    
    console.log(`Butaca ${seat.id}: wasSelected=${wasSelected}, isNowSelected=${isNowSelected}`);
    
    if (isNowSelected) {
      // La butaca ahora está seleccionada, enviar select-seat
      console.log('Enviando evento select-seat para butaca:', seat.id);
      $socket.emit('select-seat', eventData);
    } else {
      // La butaca ya no está seleccionada, enviar release-seat
      console.log('Enviando evento release-seat para butaca:', seat.id);
      $socket.emit('release-seat', eventData);
    }
  } catch (error) {
    console.error('Error al comunicarse con el socket:', error);
  } finally {
    // Permitir otro clic después de un breve tiempo
    setTimeout(() => {
      isProcessingClick.value = false;
    }, 300); // 300ms bloqueo entre clics
  }
};

// Función para actualizar una butaca como ocupada
const updateSeatAsOccupied = (seatId) => {
  // Convertir a número para asegurar la comparación correcta
  const numericSeatId = parseInt(seatId, 10);
  
  for (const rowKey in props.seatsMap.rows) {
    const seats = props.seatsMap.rows[rowKey];
    const seatIndex = seats.findIndex(s => parseInt(s.id, 10) === numericSeatId);
    
    if (seatIndex !== -1) {
      // Marcar como ocupada
      seats[seatIndex].is_occupied = 1;
      // Eliminar de las bloqueadas temporalmente
      delete tempLockedSeats.value[numericSeatId];
      break;
    }
  }
};

// Configurar socket.io al montar el componente
onMounted(() => {
  try {
    const { $socket } = useNuxtApp();

    // Estado de conexión
    console.log("Socket conectado:", $socket.connected);

    // Unirse a la sala de la sesión
    const screeningId = parseInt(route.params.id, 10);
    console.log("Uniendo a la sala de proyección:", screeningId);
    
    // Solo unirse si el socket está conectado
    if ($socket.connected) {
      $socket.emit("join-screening", screeningId);
    } else {
      // Si no está conectado, esperar a que se conecte
      $socket.on('connect', () => {
        console.log("Socket conectado, uniéndose a la sala:", screeningId);
        $socket.emit("join-screening", screeningId);
      });
    }
    
    // Escuchar el estado actual de butacas
    $socket.on("current-seat-state", (data) => {
      console.log("Estado actual de butacas recibido:", data);

      // Actualizar el estado local con las butacas seleccionadas
      if (data.screeningId == route.params.id) {
        const selections = data.selections;
        const myUserId = getCurrentUserId();
        
        for (const seatId in selections) {
          const userId = selections[seatId];
          // Ignorar selecciones hechas por este mismo usuario
          if (userId !== myUserId) {
            // Convertir a número el ID de la butaca
            const numericSeatId = parseInt(seatId, 10);
            tempLockedSeats.value[numericSeatId] = true;
            console.log("Marcando butaca como bloqueada temporalmente:", numericSeatId);
          }
        }
      }
    });

    // Escuchar eventos de actualización de butacas
    $socket.on("seat-status-changed", (data) => {
      console.log("Evento de butaca recibido:", data);

      const { seatId, status, userId } = data;
      
      // Convertir a número el ID de la butaca
      const numericSeatId = parseInt(seatId, 10);

      // Obtener ID del usuario actual
      const myUserId = getCurrentUserId();

      // No procesar eventos iniciados por este mismo usuario
      if (userId === myUserId) {
        console.log("Ignorando evento propio");
        return;
      }

      // Actualizar según el tipo de evento
      if (status === "selected") {
        // Otro usuario seleccionó una butaca
        tempLockedSeats.value[numericSeatId] = true;
        console.log("Butaca bloqueada temporalmente:", numericSeatId);
      } else if (status === "released") {
        // Un usuario liberó una butaca
        delete tempLockedSeats.value[numericSeatId];
        console.log("Butaca liberada:", numericSeatId);
      } else if (status === "purchased") {
        // Una butaca fue comprada, marcarla como ocupada
        updateSeatAsOccupied(numericSeatId);
        console.log("Butaca marcada como ocupada:", numericSeatId);
      }
    });

    // Registrar escucha de errores
    $socket.on("seat-selection-error", (data) => {
      console.warn("Error al seleccionar butaca:", data.message);
    });
  } catch (error) {
    console.error("Error al configurar sockets:", error);
  }
});

// Limpiar listeners y liberar butacas al desmontar
onBeforeUnmount(() => {
  try {
    const { $socket } = useNuxtApp();
    
    // Obtener ID del usuario actual
    const userId = getCurrentUserId();
    
    // Liberar todas las butacas que este usuario tenía seleccionadas
    if (userId) {
      props.selectedSeats.forEach(seat => {
        $socket.emit('release-seat', {
          screeningId: parseInt(route.params.id, 10),
          seatId: parseInt(seat.id, 10),
          userId: userId
        });
        console.log('Liberando butaca al desmontar:', seat.id);
      });
    }
    
    // Quitar los listeners para evitar memory leaks
    $socket.off('seat-status-changed');
    $socket.off('seat-selection-error');
    $socket.off('current-seat-state');
  } catch (error) {
    console.error('Error al limpiar sockets:', error);
  }
});
</script>

<style scoped>
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
  background-color: #008f4c; /* Verde para butaques disponibles */
  border: 1px solid #008f4c;
  border-radius: 4px;
  font-size: 0.8rem;
  color: white;
  cursor: pointer;
  transition: all 0.2s ease; /* Añade una transición suave */
}

.seat:active {
  transform: scale(0.95); /* Hace que la butaca se vea "presionada" */
  opacity: 0.8;
}

.seat-vip {
  background-color: #D4AF37; /* Amarillo para butaques VIP */
  border: 1px solid #FFC107;
}

.seat-occupied {
  background-color: #a2231d; /* Rojo para butaques ocupades */
  border: 1px solid #a2231d;
  cursor: not-allowed;
}

.seat-selected {
  background-color: #800040; /* Morado para asientos seleccionados */
  border: 1px solid #800040;
}

/* Estilo para butacas bloqueadas temporalmente */
.seat-temp-locked {
  background-color: #2196F3; /* Azul para butacas seleccionadas por otros */
  border: 1px solid #2196F3;
  cursor: not-allowed;
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
  border: 1px solid #ccc;
  border-radius: 4px;
}

.legend-seat.seat-available {
  background-color: #008f4c; /* Verde para disponible */
}

.legend-seat.seat-vip {
  background-color: #D4AF37; /* Amarillo para VIP */
}

.legend-seat.seat-occupied {
  background-color: #a2231d; /* Rojo para ocupada */
}

.legend-seat.seat-selected {
  background-color: #800040; /* Morado para seleccionada */
}

/* Estilo para la leyenda de butacas bloqueadas temporalmente */
.legend-seat.seat-temp-locked {
  background-color: #2196F3; /* Azul para butacas seleccionadas por otros */
}

.seats-loading, .no-seats-available {
  text-align: center;
  padding: 20px;
}

/* Media queries para responsividad */
@media (max-width: 768px) {
  .seating-grid {
    gap: 5px;
  }

  .seat {
    width: 25px;
    height: 25px;
    font-size: 0.7rem;
  }
}
</style>