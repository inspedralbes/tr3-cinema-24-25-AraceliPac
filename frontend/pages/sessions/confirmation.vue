<template>
  <div>
    <NavBar />
    <div class="max-w-5xl mx-auto p-4 md:p-8">
      <div v-if="loading" class="flex flex-col items-center justify-center min-h-[50vh]">
        <div
          class="w-10 h-10 mb-4 border-4 border-gray-200 border-l-[#800040] rounded-full animate-spin"
        ></div>
        <p>Carregant informació de compra...</p>
      </div>

      <div v-else-if="error" class="text-center p-8 text-red-600">
        <p>{{ error }}</p>
        <button
          @click="goBackToSession"
          class="mt-4 px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors"
        >
          Tornar a la selecció de butaques
        </button>
      </div>

      <div v-else class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-[#800040] text-white p-6 border-b-3 border-[#D4AF37]">
          <h1 class="text-center text-2xl font-bold mb-4">Confirmació de Compra</h1>
          <div class="bg-white/10 p-4 rounded-lg">
            <h2 class="text-xl font-bold mb-2">{{ session?.movie?.title }}</h2>
            <div class="flex flex-col md:flex-row flex-wrap gap-2 md:gap-4">
              <p>
                <strong>Data:</strong>
                {{ session?.formattedDate }}
              </p>
              <p>
                <strong>Hora:</strong>
                {{ session?.formattedTime }}
              </p>
              <p
                v-if="session?.isSpecialDay"
                class="bg-[#FCFCB9] text-[#800040] px-2 py-1 rounded inline-block font-bold"
              >
                Dia de l'espectador
              </p>
            </div>
          </div>
        </div>

        <div class="p-6 border-b border-gray-200">
          <h3 class="text-lg font-semibold">Resum de la compra</h3>

          <div class="my-6">
            <h4 class="font-medium">Butaques seleccionades ({{ selectedSeats.length }}):</h4>
            <div class="flex flex-wrap gap-3 mt-3">
              <div
                v-for="seat in selectedSeats"
                :key="`${seat.row}-${seat.number}`"
                class="flex flex-col items-center gap-1"
              >
                <div
                  :class="[
                    'w-10 h-10 flex items-center justify-center rounded-md font-bold',
                    seat.is_vip ? 'bg-[#D4AF37] text-gray-900' : 'bg-blue-500 text-white',
                  ]"
                >
                  {{ seat.row }}{{ seat.number }}
                </div>
                <div>{{ calculateSeatPrice(seat) }}€</div>
              </div>
            </div>
          </div>

          <div class="bg-gray-50 p-4 rounded-lg mt-6">
            <div class="flex justify-between py-2 border-b border-gray-200">
              <span>Subtotal:</span>
              <span>{{ totalPrice }}€</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-200">
              <span>IVA (21%):</span>
              <span>{{ (totalPrice * 0.21).toFixed(2) }}€</span>
            </div>
            <div class="flex justify-between py-3 font-bold text-lg">
              <span>Total:</span>
              <span>{{ (totalPrice * 1.21).toFixed(2) }}€</span>
            </div>
          </div>
        </div>

        <div class="p-6">
          <h3 class="text-lg font-semibold">Mètode de pagament</h3>

          <div class="flex flex-col gap-4 my-6">
            <div
              v-for="method in paymentMethods"
              :key="method.id"
              :class="[
                'flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all',
                selectedPaymentMethod === method.id
                  ? 'border-[#800040] bg-[#800040]/5'
                  : 'border-gray-200 hover:border-[#800040]',
              ]"
              @click="selectPaymentMethod(method.id)"
            >
              <div class="mr-4 text-[#800040]">
                <Icon :name="method.icon" class="w-8 h-8" />
              </div>
              <div>
                <h4 class="font-bold mb-1">{{ method.name }}</h4>
                <p class="text-sm text-gray-600">{{ method.description }}</p>
              </div>
            </div>
          </div>

          <div class="flex flex-col md:flex-row justify-between gap-4 mt-8">
            <button
              @click="goBackToSession"
              class="flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-600 rounded-lg font-bold hover:bg-gray-300 transition-all"
            >
              <Icon name="mdi:arrow-left" class="mr-2" />
              Tornar a selecció
            </button>
            <button
              @click="confirmPurchase"
              :disabled="!selectedPaymentMethod || isProcessing"
              class="flex items-center justify-center px-6 py-3 bg-[#800040] text-white rounded-lg font-bold md:flex-1 hover:bg-[#9A0040] transition-all disabled:opacity-70 disabled:cursor-not-allowed"
            >
              <span v-if="isProcessing" class="flex items-center">
                <svg
                  class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                  ></circle>
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  ></path>
                </svg>
                Processant...
              </span>
              <span v-else class="flex items-center">
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
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useSessionsStore } from "~/stores/sessions";
import { useAuthStore } from "~/stores/auth";
import { useTicketStore } from "~/stores/ticketStore";
import { useNuxtApp } from "#app"; // Añadir esta importación

const route = useRoute();
const router = useRouter();
const sessionsStore = useSessionsStore();
const authStore = useAuthStore();
const ticketStore = useTicketStore();

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
    id: "credit_card",
    name: "Targeta de crèdit/dèbit",
    description: "Pagament segur amb Visa, Mastercard o American Express",
    icon: "mdi:credit-card",
  },
  {
    id: "paypal",
    name: "PayPal",
    description: "Pagament ràpid i segur amb la teva compte PayPal",
    icon: "mdi:paypal",
  },
  {
    id: "bizum",
    name: "Bizum",
    description: "Pagament immediat a través del teu mòbil",
    icon: "mdi:cellphone",
  },
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
      router.push("/usuari/iniciSessio");
      return;
    }

    // Obtener la información de compra de la URL
    const purchaseInfo = route.query.info;

    if (!purchaseInfo) {
      error.value = "No s'ha trobat informació de compra";
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
        throw new Error("ID de sessió no vàlid");
      }
    } catch (parseError) {
      console.error("Error al analizar la información de compra:", parseError);
      error.value = "Format d'informació de compra no vàlid";
      return;
    }
  } catch (err) {
    console.error("Error al cargar datos para confirmación:", err);
    error.value = "Error al carregar les dades de la compra";
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
    formattedDate: sessionDate.toLocaleDateString("ca-ES", {
      weekday: "long",
      day: "numeric",
      month: "long",
    }),
    formattedTime: sessionsStore.currentSession.screening_time.substring(0, 5),
    isSpecialDay: sessionsStore.currentSession.is_special_day === 1,
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
// Función confirmPurchase corregida
// Confirmar la compra
const confirmPurchase = async () => {
  if (!selectedPaymentMethod.value || isProcessing.value) return;

  isProcessing.value = true;
  let purchaseSuccessful = false;
  const purchasedTickets = [];

  try {
    // Procesamos cada asiento seleccionado como un ticket separado
    for (const seat of selectedSeats.value) {
      try {
        // Calcular el precio según el tipo de asiento y día especial
        const price = session.value.isSpecialDay ? (seat.is_vip ? 6 : 4) : seat.is_vip ? 8 : 6;

        // console.log(`Procesando asiento ${seat.row}${seat.number}, precio: ${price}€`);

        // Llamar a la función del store para crear el ticket en el backend
        const ticketResult = await ticketStore.purchaseTicket({
          screening_id: sessionId.value,
          seat_id: seat.id,
          price: price,
          user_id: authStore.user?.id,
        });

        // Verificar si el resultado indica éxito
        if (ticketResult) {
          // console.log("Ticket creado exitosamente:", ticketResult);
          purchasedTickets.push(ticketResult);
          purchaseSuccessful = true; // Marcar como éxito incluso si solo un ticket se crea

          // NUEVO: Notificar al servidor de sockets que la butaca fue comprada
          try {
            const { $socket } = useNuxtApp();
            $socket.emit("seat-purchased", {
              screeningId: sessionId.value,
              seatId: seat.id,
              userId: authStore.user?.id,
            });
            // console.log(`Notificada compra de butaca ${seat.id} al servidor de sockets`);
          } catch (socketError) {
            // Si hay un error con el socket, lo registramos pero continuamos
            console.warn("Error al notificar al servidor de sockets:", socketError);
          }
        } else {
          console.warn(`No se pudo crear ticket para asiento ${seat.row}${seat.number}`);
        }
      } catch (ticketError) {
        console.error(`Error al procesar asiento ${seat.row}${seat.number}:`, ticketError);
        // Continuamos con el siguiente asiento aunque este haya fallado
      }
    }

    // Verificar el resultado general de la operación
    // console.log(`Resultado de la compra: ${purchasedTickets.length} tickets procesados`);

    if (purchaseSuccessful) {
      if (process.client) {
        localStorage.removeItem("purchase_started");
        localStorage.removeItem("purchase_seats");
      }
      // Guardamos información sobre los tickets comprados para mostrarlos en la página de éxito
      const purchaseInfo = {
        count: purchasedTickets.length,
        totalAmount: totalPrice.value,
        ticketIds: purchasedTickets.map((ticket) => ticket.id || "unknown"),
      };

      localStorage.setItem("purchasedTickets", JSON.stringify(purchaseInfo));

      // Redirigir a la página de éxito
      // console.log("Redirigiendo a página de éxito...");
      router.push(`/compra/exit?session=${sessionId.value}&tickets=${purchasedTickets.length}`);
    } else {
      // No se pudo comprar ningún ticket
      console.error("No se completó ninguna compra");
      error.value = "No s'ha pogut completar la compra de cap entrada";
    }
  } catch (err) {
    console.error("Error general en el proceso de compra:", err);
    error.value = "Error al processar la compra";
  } finally {
    isProcessing.value = false;
  }
};
// Al montar el componente
onMounted(() => {
  loadSessionData();
  // Recuperar información de selección desde localStorage
  if (process.client) {
    const selectionInfo = localStorage.getItem("current_seat_selection");
    if (selectionInfo) {
      try {
        const selection = JSON.parse(selectionInfo);

        // Asegurarse de que la selección corresponde a la sesión actual
        if (selection.screeningId == sessionId.value) {
          // console.log("Manteniendo selección de butacas guardada:", selection.seats.length);

          // Opcionalmente: notificar al servidor de sockets que estas butacas siguen seleccionadas
          // (aunque esto no debería ser necesario si no las liberaste en primer lugar)
        }
      } catch (error) {
        console.error("Error al recuperar selección de butacas:", error);
      }
    }
  }
});
</script>
