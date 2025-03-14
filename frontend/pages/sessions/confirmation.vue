<template>
    <div>
      <NavBar />
      <div class="confirmation-page">
        <div v-if="loading" class="loading">
          <div class="spinner"></div>
          <p>Carregant informació de compra...</p>
        </div>
        
        <div v-else-if="error" class="error-message">
          <p>{{ error }}</p>
          <button @click="goBackToSession" class="retry-button">Tornar a la selecció de butaques</button>
        </div>
        
        <div v-else class="confirmation-container">
          <div class="confirmation-header">
            <h1>Confirmació de Compra</h1>
            <div class="session-info">
              <h2>{{ session?.movie?.title }}</h2>
              <div class="session-details">
                <p><strong>Data:</strong> {{ session?.formattedDate }}</p>
                <p><strong>Hora:</strong> {{ session?.formattedTime }}</p>
                <p v-if="session?.isSpecialDay" class="special-day">Dia de l'espectador</p>
              </div>
            </div>
          </div>
          
          <div class="tickets-summary">
            <h3>Resum de la compra</h3>
            
            <div class="seats-list">
              <h4>Butaques seleccionades ({{ selectedSeats.length }}):</h4>
              <div class="seats-grid">
                <div v-for="seat in selectedSeats" :key="`${seat.row}-${seat.number}`" class="seat-item">
                  <div :class="['seat-icon', seat.is_vip ? 'vip-seat' : 'normal-seat']">
                    {{ seat.row }}{{ seat.number }}
                  </div>
                  <div class="seat-price">
                    {{ calculateSeatPrice(seat) }}€
                  </div>
                </div>
              </div>
            </div>
            
            <div class="price-summary">
              <div class="price-row">
                <span>Subtotal:</span>
                <span>{{ totalPrice }}€</span>
              </div>
              <div class="price-row">
                <span>IVA (21%):</span>
                <span>{{ (totalPrice * 0.21).toFixed(2) }}€</span>
              </div>
              <div class="price-row total">
                <span>Total:</span>
                <span>{{ (totalPrice * 1.21).toFixed(2) }}€</span>
              </div>
            </div>
          </div>
          
          <div class="payment-section">
            <h3>Mètode de pagament</h3>
            
            <div class="payment-methods">
              <div 
                v-for="method in paymentMethods" 
                :key="method.id"
                :class="['payment-method', { active: selectedPaymentMethod === method.id }]"
                @click="selectPaymentMethod(method.id)"
              >
                <div class="payment-icon">
                  <Icon :name="method.icon" class="w-8 h-8" />
                </div>
                <div class="payment-details">
                  <h4>{{ method.name }}</h4>
                  <p>{{ method.description }}</p>
                </div>
              </div>
            </div>
            
            <div class="actions">
              <button @click="goBackToSession" class="secondary-button">
                <Icon name="mdi:arrow-left" class="mr-2" />
                Tornar a selecció
              </button>
              <button 
                @click="confirmPurchase" 
                :disabled="!selectedPaymentMethod || isProcessing"
                class="primary-button"
              >
                <span v-if="isProcessing">
                  <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Processant...
                </span>
                <span v-else>
                  <Icon name="mdi:check" class="mr-2" />
                  Confirmar Compra
                </span>
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <Footer />
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import { useSessionsStore } from '~/stores/sessions';
  import { useAuthStore } from '~/stores/auth';
  
  const route = useRoute();
  const router = useRouter();
  const sessionsStore = useSessionsStore();
  const authStore = useAuthStore();
  
  // Variables de estado
  const loading = ref(true);
  const error = ref(null);
  const isProcessing = ref(false);
  const selectedSeats = ref([]);
  const totalPrice = ref(0);
  const selectedPaymentMethod = ref(null);
  const sessionId = ref(null);
  
  // Opciones de pago
  const paymentMethods = [
    {
      id: 'credit_card',
      name: 'Targeta de crèdit/dèbit',
      description: 'Pagament segur amb Visa, Mastercard o American Express',
      icon: 'mdi:credit-card'
    },
    {
      id: 'paypal',
      name: 'PayPal',
      description: 'Pagament ràpid i segur amb la teva compte PayPal',
      icon: 'mdi:paypal'
    },
    {
      id: 'bizum',
      name: 'Bizum',
      description: 'Pagament immediat a través del teu mòbil',
      icon: 'mdi:cellphone'
    }
  ];
  
  // Función para seleccionar método de pago
  const selectPaymentMethod = (methodId) => {
    selectedPaymentMethod.value = methodId;
  };
  
  // Carga de datos iniciales
  const loadSessionData = async () => {
    loading.value = true;
    error.value = null;
    
    try {
      // Verificar si el usuario está autenticado
      if (!authStore.isAuthenticated) {
        router.push('/usuari/iniciSessio');
        return;
      }
      
      // Obtener la información de compra de la URL
      const purchaseInfo = route.query.info;
      
      if (!purchaseInfo) {
        error.value = 'No s\'ha trobat informació de compra';
        return;
      }
      
      // Decodificar y analizar la información
      try {
        const decodedInfo = JSON.parse(decodeURIComponent(purchaseInfo));
        sessionId.value = decodedInfo.sessionId;
        selectedSeats.value = decodedInfo.seats || [];
        totalPrice.value = decodedInfo.totalPrice || 0;
        
        // Cargar datos completos de la sesión
        if (sessionId.value) {
          await sessionsStore.fetchSessionById(sessionId.value);
        } else {
          throw new Error('ID de sessió no vàlid');
        }
      } catch (parseError) {
        console.error('Error al analizar la información de compra:', parseError);
        error.value = 'Format d\'informació de compra no vàlid';
        return;
      }
      
    } catch (err) {
      console.error('Error al cargar datos para confirmación:', err);
      error.value = 'Error al carregar les dades de la compra';
    } finally {
      loading.value = false;
    }
  };
  
  // Acceso a la sesión formateada
  const session = computed(() => {
    if (!sessionsStore.currentSession) return null;
    
    const sessionDate = new Date(sessionsStore.currentSession.screening_date);
    return {
      ...sessionsStore.currentSession,
      formattedDate: sessionDate.toLocaleDateString('ca-ES', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
      }),
      formattedTime: sessionsStore.currentSession.screening_time.substring(0, 5),
      isSpecialDay: sessionsStore.currentSession.is_special_day === 1
    };
  });
  
  // Función para calcular el precio de un asiento
  const calculateSeatPrice = (seat) => {
    if (!session.value) return 0;
    
    if (session.value.isSpecialDay) {
      return seat.is_vip ? 6 : 4;
    } else {
      return seat.is_vip ? 8 : 6;
    }
  };
  
  // Volver a la página de selección de asientos
  const goBackToSession = () => {
    router.push(`/sessions/${sessionId.value}`);
  };
  
  // Confirmar la compra
  const confirmPurchase = async () => {
    if (!selectedPaymentMethod.value) return;
    
    isProcessing.value = true;
    
    try {
      // Aquí iría la lógica de conexión con la API para procesar el pago
      // Por ahora, simulamos un proceso con un timeout
      await new Promise(resolve => setTimeout(resolve, 2000));
      
      // Redirigir a la página de éxito
      router.push(`/compra/exit?session=${sessionId.value}`);
      
    } catch (error) {
      console.error('Error al procesar la compra:', error);
      // Manejar el error
    } finally {
      isProcessing.value = false;
    }
  };
  
  // Al montar el componente
  onMounted(() => {
    loadSessionData();
  });
  </script>
  
  <style scoped>
  .confirmation-page {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem;
  }
  
  .loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 50vh;
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
    padding: 2rem;
    color: #e53e3e;
  }
  
  .confirmation-container {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }
  
  .confirmation-header {
    background-color: #800040;
    color: white;
    padding: 1.5rem;
    border-bottom: a3px solid #D4AF37;
  }
  
  .confirmation-header h1 {
    text-align: center;
    font-size: 1.75rem;
    font-weight: bold;
    margin-bottom: 1rem;
  }
  
  .session-info {
    background-color: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 8px;
  }
  
  .session-info h2 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    font-weight: bold;
  }
  
  .session-details {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
  }
  
  .tickets-summary, .payment-section {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
  }
  
  .seats-list {
    margin: 1.5rem 0;
  }
  
  .seats-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 0.75rem;
  }
  
  .seat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
  }
  
  .seat-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-weight: bold;
  }
  
  .normal-seat {
    background-color: #4299e1;
    color: white;
  }
  
  .vip-seat {
    background-color: #D4AF37;
    color: #1a202c;
  }
  
  .price-summary {
    background-color: #f7fafc;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1.5rem;
  }
  
  .price-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e2e8f0;
  }
  
  .price-row.total {
    border-bottom: none;
    font-weight: bold;
    font-size: 1.1rem;
    padding-top: 0.75rem;
  }
  
  .payment-methods {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin: 1.5rem 0;
  }
  
  .payment-method {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .payment-method:hover {
    border-color: #800040;
  }
  
  .payment-method.active {
    border-color: #800040;
    background-color: rgba(128, 0, 64, 0.05);
  }
  
  .payment-icon {
    margin-right: 1rem;
    color: #800040;
  }
  
  .payment-details h4 {
    font-weight: bold;
    margin-bottom: 0.25rem;
  }
  
  .payment-details p {
    font-size: 0.875rem;
    color: #4a5568;
  }
  
  .actions {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 2rem;
  }
  
  .secondary-button, .primary-button {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: bold;
    transition: all 0.2s;
  }
  
  .secondary-button {
    background-color: #e2e8f0;
    color: #4a5568;
  }
  
  .secondary-button:hover {
    background-color: #cbd5e0;
  }
  
  .primary-button {
    background-color: #800040;
    color: white;
    flex: 1;
  }
  
  .primary-button:hover:not(:disabled) {
    background-color: #9A0040;
  }
  
  .primary-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
  }
  
  .special-day {
    background-color: #FCFCB9;
    color: #800040;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-weight: bold;
    display: inline-block;
  }
  
  @media (max-width: 768px) {
    .confirmation-page {
      padding: 1rem;
    }
    
    .actions {
      flex-direction: column;
    }
    
    .session-details {
      flex-direction: column;
      gap: 0.5rem;
    }
  }
  </style>