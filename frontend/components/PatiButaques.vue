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
                'seat-selected': isSelected(seat)
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
  import { computed, defineProps } from 'vue';
  
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
  
  // Verificar si hay asientos cargados
  const hasSeats = computed(() => {
    return Object.keys(props.seatsMap.rows || {}).length > 0;
  });
  
  // Obtener las etiquetas de filas ordenadas alfabéticamente
  const rowLabels = computed(() => {
    const rows = Object.keys(props.seatsMap.rows || {});
    return rows.sort();
  });
  
  // Verificar si un asiento está seleccionado
  const isSelected = (seat) => {
    return props.selectedSeats.some(s => s.row === seat.row && s.number === seat.number);
  };
  
  // Manejar la selección de asientos
  const handleSeatClick = (seat) => {
    if (seat.is_occupied !== 1) {
      emit('seat-selected', seat);
    }
  };
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