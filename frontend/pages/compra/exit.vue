<template>
  <div>
    <NavBar />
    <div class="min-h-screen bg-gradient-to-b from-white to-gray-100 py-10 px-4 sm:px-6 md:px-8">
      <div class="max-w-lg mx-auto" ref="successContainer">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <!-- Cabecera -->
           <div class="cabecera">
          <div class="bg-[#800040] py-8 px-6 flex flex-col items-center relative">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-pattern"></div>

            <div class="relative z-10 mb-2" ref="iconContainer">
              <div
                class="w-20 h-15 bg-white rounded-full flex items-center justify-center shadow-lg"
              >
                <Icon name="mdi:check-circle" class="text-[#800040] text-5xl" />
              </div>
            </div>

            <h1 class="mt-6 text-white text-2xl sm:text-3xl font-bold text-center">
              Compra Realitzada amb Èxit!
            </h1>
            <p class="mt-2 text-white text-sm sm:text-base text-center opacity-90">
              Les teves entrades ja estan disponibles al teu perfil
            </p>
          </div>
        </div>

          <!-- Contenido -->
          <div class="px-6 py-8" ref="contentContainer">
            <div class="flex flex-col items-center mb-6">
              <div
                class="w-16 h-16 bg-[#D4AF37] bg-opacity-20 rounded-full flex items-center justify-center mb-3"
              >
                <Icon name="mdi:ticket-confirmation" class="text-[#D4AF37] text-3xl" />
              </div>
              <p class="text-gray-700 text-center">
                Hem enviat un correu electrònic amb els detalls de la teva compra i les entrades.
              </p>
            </div>

            <!-- Detalles de reserva con borde dorado -->
            <div class="border-2 border-[#D4AF37] rounded-lg p-4 mb-6" ref="detailsContainer">
              <h3 class="text-[#800040] font-semibold text-center mb-3">Detalls de la Reserva</h3>
              <div class="flex flex-col space-y-2 text-sm">
             
                <div class="flex justify-between">
                  <span class="text-gray-600">Data de compra:</span>
                  <span>{{ formattedDate }}</span>
                </div>

                <!-- Información de la sesión -->
                <div v-if="session" class="pt-2 mt-2 border-t border-gray-200">
                  <div class="flex justify-between mb-1">
                    <span class="text-gray-600">Pel·lícula:</span>
                    <span class="font-medium">{{ session.movie?.title }}</span>
                  </div>
                  <div class="flex justify-between mb-1">
                    <span class="text-gray-600">Sessió:</span>
                    <span>
                      {{
                        new Date(session.screening_date).toLocaleDateString("ca-ES", {
                          weekday: "long",
                          day: "numeric",
                          month: "long",
                        })
                      }}
                      a les {{ session.screening_time?.substring(0, 5) }}
                    </span>
                  </div>
                </div>

                <!-- Mostrar detalles de los asientos -->
                <div v-if="purchasedTickets.length > 0" class="pt-2 mt-2 border-t border-gray-200">
                  <h4 class="font-medium text-[#800040] mb-2">Butaques:</h4>
                  <div class="flex flex-wrap gap-2">
                    <div
                      v-for="ticket in purchasedTickets"
                      :key="ticket.id"
                      :class="{
                        'px-2 py-1 rounded-md text-xs font-medium': true,
                        'bg-[#D4AF37] bg-opacity-20 text-[#D4AF37]': ticket.seat?.is_vip,
                        'bg-blue-100 text-blue-700': !ticket.seat?.is_vip,
                      }"
                    >
                      {{ ticket.seat?.row }}{{ ticket.seat?.number }}
                      <span
                        v-if="ticket.seat?.is_vip"
                        class="text-[0.65rem] ml-1 py-0.5 px-1 bg-[#D4AF37] text-white rounded-sm"
                      >
                        VIP
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Botones -->
            <div class="space-y-3 sm:space-y-0 sm:flex sm:space-x-3" ref="buttonsContainer">
              <NuxtLink
                to="/perfil"
                class="w-full block text-center py-3 px-4 bg-[#800040] text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow sm:flex-1"
              >
                <Icon name="mdi:ticket" class="mr-1" />
                Veure les meves entrades
              </NuxtLink>
              <NuxtLink
                to="/"
                class="w-full block text-center py-3 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all sm:flex-1"
              >
                <Icon name="mdi:home" class="mr-1" />
                Tornar a l'inici
              </NuxtLink>
            </div>
          </div>

          <!-- Footer decorativo -->
          <div class="h-2 bg-[#D4AF37]"></div>
        </div>

        <!-- Banner promocional -->
        <div class="banner">
        <div
          class="mt-8 bg-gray-100 border border-gray-200 rounded-lg p-4 flex items-center"
          ref="promoContainer"
        >
          <div class="mr-4 hidden sm:block">
            <Icon name="mdi:movie" class="text-[#800040] text-4xl" />
          </div>
          <div>
            <h3 class="font-semibold text-[#800040]">Cinema Pedralbes et recomana</h3>
            <p class="text-sm text-gray-600 mt-1">
              No et perdis les nostres properes estrenes. Consulta la nostra cartellera!
            </p>
            <NuxtLink
              to="/pelicules/cartelera"
              class="inline-block mt-2 text-[#D4AF37] hover:underline text-sm font-medium"
            >
              Veure cartellera
              <Icon name="mdi:arrow-right" class="inline text-xs" />
            </NuxtLink>
          </div>
        </div>
      </div>
      </div>
    </div>
    <Footer />
  </div>
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useAutoAnimate } from "@formkit/auto-animate/vue";
import { useSessionsStore } from "~/stores/sessions";
import { useAuthStore } from "~/stores/auth";
import { useTicketStore } from "~/stores/ticketStore";

const route = useRoute();
const router = useRouter();
const sessionsStore = useSessionsStore();
const authStore = useAuthStore();
const ticketStore = useTicketStore();

const sessionId = route.query.session;
const loading = ref(true);
const error = ref(null);
const purchasedTickets = ref([]);

// Referencias para auto-animate
const [successContainer] = useAutoAnimate();
const [iconContainer] = useAutoAnimate({ duration: 500 });
const [contentContainer] = useAutoAnimate();
const [detailsContainer] = useAutoAnimate();
const [buttonsContainer] = useAutoAnimate();
const [promoContainer] = useAutoAnimate({ duration: 700 });

// Fecha actual formateada como valor calculado
const formattedDate = computed(() => {
  const date = new Date();
  return date.toLocaleDateString("ca-ES", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });
});

// Información de la sesión
const session = computed(() => {
  return sessionsStore.currentSession;
});

// Función para formatear precio
const formatPrice = (price) => {
  return Number(price).toFixed(2);
};

// Cargar datos de los tickets comprados
const loadTicketData = async () => {
  loading.value = true;
  error.value = null;

  try {
    // Verificar si el usuario está autenticado
    if (!authStore.isAuthenticated) {
      router.push("/usuari/iniciSessio");
      return;
    }

    // Verificar si hay un ID de sesión
    if (!sessionId) {
      error.value = "No s'ha trobat la sessió";
      return;
    }

    // Cargar datos de la sesión
    if (sessionId) {
      await sessionsStore.fetchSessionById(sessionId);
    }

    // Cargar tickets del usuario
    await ticketStore.fetchUserTickets();

    // Filtrar solo los tickets de esta sesión
    purchasedTickets.value = ticketStore.tickets.filter(
      (ticket) => ticket.screening_id.toString() === sessionId
    );
  } catch (err) {
    console.error("Error al cargar datos de tickets:", err);
    error.value = "Error al carregar les dades de les entrades";
  } finally {
    loading.value = false;
  }
};

// Efecto de aparición secuencial al cargar la página
const elementVisible = ref({
  content: false,
  details: false,
  buttons: false,
  promo: false,
});

// Ejecutar carga de datos al montar el componente
onMounted(async () => {
  await loadTicketData();
  
  // Simulación de carga secuencial para el efecto visual
  setTimeout(() => { elementVisible.value.content = true }, 300);
  setTimeout(() => { elementVisible.value.details = true }, 600);
  setTimeout(() => { elementVisible.value.buttons = true }, 900);
  setTimeout(() => { elementVisible.value.promo = true }, 1200);
});
</script>
<style scoped>
.cabecera {
  margin-top: 10px;
}
.banner {
  margin-top: 10px;
  margin-bottom: 10px;
}
</style>