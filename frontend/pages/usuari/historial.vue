<template>
  <div class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar importado directamente -->
    <NavBar />

    <!-- Contenido principal con padding adecuado -->
    <main class="flex-grow w-full py-6">
      <div class="w-full max-w-6xl mx-auto px-4">
        <!-- Título de sección -->
        <div class="bg-[#800040] rounded-lg shadow-md mb-6">
          <h1 class="text-center text-white py-4 text-xl font-bold">El Meu Historial de Compres</h1>
        </div>

        <!-- Estado no autenticado -->
        <div v-if="!isAuthenticated" class="bg-white rounded-lg shadow-md p-8 text-center">
          <div class="max-w-md mx-auto">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded mb-6 text-left">
              <div class="flex">
                <div class="flex-shrink-0">
                  <ClientOnly>
                    <Icon name="mdi:lock" class="text-yellow-400 w-5 h-5" />
                  </ClientOnly>
                </div>
                <div class="ml-3">
                  <p class="text-sm text-yellow-700">
                    Has d'iniciar sessió o registrar-te a la nostra pàgina web per accedir al teu
                    historial de compres.
                  </p>
                </div>
              </div>
            </div>
            <button
              @click="anarAIniciarSessio"
              class="bg-[#800040] text-white px-6 py-3 rounded-lg flex items-center justify-center mx-auto hover:bg-[#9A0040] transition-colors"
            >
              <ClientOnly>
                <Icon name="mdi:login" class="mr-2" />
              </ClientOnly>
              Iniciar Sessió
            </button>
          </div>
        </div>

        <!-- NAVEGACIÓN SUPERIOR (solo visible cuando está autenticado) -->
        <div v-else class="bg-white rounded-lg shadow-md p-2 mb-6">
          <div class="flex flex-wrap justify-center gap-2">
            <a
              href="/perfil"
              class="px-4 py-2 rounded-md flex items-center text-[#800040] hover:bg-[#D4AF37]/10"
            >
              <ClientOnly>
                <Icon name="mdi:account" class="w-5 h-5 mr-2 text-[#D4AF37]" />
              </ClientOnly>
              <span>Informació</span>
            </a>
            <a
              href="/usuari/historial"
              class="px-4 py-2 rounded-md flex items-center text-[#800040] font-bold bg-[#D4AF37]/10 border-b-2 border-[#D4AF37]"
            >
              <ClientOnly>
                <Icon name="mdi:history" class="w-5 h-5 mr-2 text-[#D4AF37]" />
              </ClientOnly>
              <span>Historial</span>
            </a>
            <a
              href="/favorits"
              class="px-4 py-2 rounded-md flex items-center text-[#800040] hover:bg-[#D4AF37]/10"
            >
              <ClientOnly>
                <Icon name="mdi:heart" class="w-5 h-5 mr-2 text-[#D4AF37]" />
              </ClientOnly>
              <span>Favorits</span>
            </a>
            <a
              href="/ajustos"
              class="px-4 py-2 rounded-md flex items-center text-[#800040] hover:bg-[#D4AF37]/10"
            >
              <ClientOnly>
                <Icon name="mdi:cog" class="w-5 h-5 mr-2 text-[#D4AF37]" />
              </ClientOnly>
              <span>Configuració</span>
            </a>
          </div>
        </div>

        <!-- CONTENIDO PRINCIPAL: Estado de carga -->
        <div v-if="loading" class="bg-white rounded-lg shadow-md p-8 text-center">
          <div class="flex flex-col items-center">
            <ClientOnly>
              <Icon name="mdi:loading" class="text-[#800040] w-12 h-12 animate-spin mb-4" />
            </ClientOnly>
            <p class="text-gray-600">Carregant el teu historial de compres...</p>
          </div>
        </div>

        <!-- Mensaje de error -->
        <div v-else-if="error" class="bg-white rounded-lg shadow-md p-8">
          <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <div class="flex">
              <div class="flex-shrink-0">
                <ClientOnly>
                  <Icon name="mdi:alert-circle" class="text-red-400 w-5 h-5" />
                </ClientOnly>
              </div>
              <div class="ml-3">
                <p class="text-sm text-red-700">{{ error }}</p>
              </div>
            </div>
          </div>
          <div class="mt-4">
            <button
              @click="fetchTickets"
              class="bg-[#800040] text-white px-6 py-3 rounded-lg flex items-center justify-center mx-auto hover:bg-[#9A0040] transition-colors"
            >
              <ClientOnly>
                <Icon name="mdi:refresh" class="mr-2" />
              </ClientOnly>
              Tornar a Intentar
            </button>
          </div>
        </div>

        <!-- Sin tickets -->
        <div v-else-if="!tickets.length" class="bg-white rounded-lg shadow-md p-8 text-center">
          <div class="max-w-md mx-auto">
            <div class="flex flex-col items-center mb-6">
              <ClientOnly>
                <Icon name="mdi:ticket-outline" class="text-gray-400 w-16 h-16 mb-4" />
              </ClientOnly>
              <h3 class="text-lg font-medium text-gray-900">No tens entrades comprades</h3>
              <p class="mt-1 text-sm text-gray-500">
                Aquí es mostraran les teves compres quan adquireixis entrades per a pel·lícules.
              </p>
            </div>
            <NuxtLink
              to="/pelicules/cartelera"
              class="bg-[#800040] text-white px-6 py-3 rounded-lg flex items-center justify-center mx-auto hover:bg-[#9A0040] transition-colors"
            >
              <ClientOnly>
                <Icon name="mdi:movie-open" class="mr-2" />
              </ClientOnly>
              Explorar Cartellera
            </NuxtLink>
          </div>
        </div>

        <!-- Lista de tickets (agrupados por fecha) -->
        <div v-else>
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Les meves entrades</h2>

          <div class="space-y-4">
            <div
              v-for="group in groupedTickets"
              :key="group.date"
              class="bg-white rounded-lg shadow-md overflow-hidden"
            >
              <!-- Encabezado de fecha -->
              <div class="bg-[#800040]/10 p-3 border-b border-gray-200">
                <h3 class="font-medium text-[#800040]">{{ formatDate(group.date) }}</h3>
              </div>

              <!-- Tickets del grupo -->
              <div class="divide-y divide-gray-100">
                <div v-for="ticket in group.tickets" :key="ticket.id" class="p-4">
                  <!-- Versión móvil -->
                  <div class="block md:hidden">
                    <div class="flex flex-col">
                      <div class="flex items-center justify-between mb-3">
                        <span class="font-medium">
                          {{ getMovieTitle(ticket) }}
                        </span>
                        <span class="font-medium text-[#D4AF37]">
                          {{ formatPrice(ticket.price) }}
                        </span>
                      </div>

                      <div class="text-sm text-gray-500 flex items-center mb-1">
                        <ClientOnly>
                          <Icon name="mdi:calendar-clock" class="w-4 h-4 mr-1" />
                        </ClientOnly>
                        <span>{{ formatTicketDateTime(ticket) }}</span>
                      </div>

                      <div class="text-sm text-gray-500 flex items-center mb-1">
                        <ClientOnly>
                          <Icon name="mdi:seat" class="w-4 h-4 mr-1" />
                        </ClientOnly>
                        <span>
                          Butaca: Fila {{ ticket.seat?.row || "?" }}, Núm.
                          {{ ticket.seat?.number || "?" }}
                        </span>
                      </div>

                      <div class="text-sm text-gray-500 flex items-center mb-3">
                        <ClientOnly>
                          <Icon name="mdi:ticket-confirmation" class="w-4 h-4 mr-1" />
                        </ClientOnly>
                        <span>Codi: {{ ticket.ticket_number }}</span>
                      </div>

                      <div class="flex justify-end space-x-2">
                        <button
                          @click="downloadTicket(ticket.id, ticket.ticket_number)"
                          class="p-2 text-[#800040] bg-[#800040]/5 hover:bg-[#800040]/10 rounded transition-colors text-sm"
                        >
                          <ClientOnly>
                            <Icon name="mdi:download" class="w-5 h-5 inline-block mr-1" />
                          </ClientOnly>
                          PDF
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Versión escritorio -->
                  <div class="hidden md:flex">
                    <div class="flex items-center w-full justify-between">
                      <!-- Información de película y hora -->
                      <div class="flex-grow mr-4">
                        <h4 class="font-medium text-[#800040]">
                          {{ getMovieTitle(ticket) }}
                        </h4>
                        <div class="text-sm text-gray-500">
                          {{ formatTicketDateTime(ticket) }}
                        </div>
                      </div>

                      <!-- Información de butaca -->
                      <div class="px-6 min-w-[100px]">
                        <div class="text-center">
                          <div class="text-sm font-medium mb-1">Butaca</div>
                          <div class="font-medium">
                            {{ ticket.seat?.row || "?" }}-{{ ticket.seat?.number || "?" }}
                          </div>
                        </div>
                      </div>

                      <!-- Código del ticket -->
                      <div class="px-6 min-w-[120px]">
                        <div class="text-center">
                          <div class="text-sm font-medium mb-1">Codi</div>
                          <div class="text-sm font-mono truncate max-w-[120px]">
                            {{ ticket.ticket_number }}
                          </div>
                        </div>
                      </div>

                      <!-- Precio -->
                      <div class="px-6 min-w-[100px]">
                        <div class="text-center">
                          <div class="text-sm font-medium mb-1">Preu</div>
                          <div class="font-medium text-[#D4AF37]">
                            {{ formatPrice(ticket.price) }}
                          </div>
                        </div>
                      </div>

                      <!-- Botón de descarga -->
                      <div class="ml-4">
                        <button
                          @click="downloadTicket(ticket.id, ticket.ticket_number)"
                          class="p-2 text-[#800040] hover:bg-[#800040]/10 rounded-full transition-colors"
                          title="Descarregar PDF"
                        >
                          <ClientOnly>
                            <Icon name="mdi:download" class="w-5 h-5" />
                          </ClientOnly>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer importado directamente -->
    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { useTicketStore } from "@/stores/ticketStore";
import { format, parseISO } from "date-fns";
import { ca } from "date-fns/locale";

// Inicializar stores y router
const authStore = useAuthStore();
const ticketStore = useTicketStore();
const router = useRouter();

// Estado local
const loading = ref(false);
const error = ref(null);
const tickets = ref([]);
const movieCache = ref({}); // Cache para datos de películas

// Computa si el usuario está autenticado
const isAuthenticated = computed(() => {
  return authStore.isAuthenticated;
});

// Agrupar tickets por fecha
const groupedTickets = computed(() => {
  if (!tickets.value || !tickets.value.length) {
    console.log("No hay tickets para agrupar");
    return [];
  }

  console.log("Intentando agrupar", tickets.value.length, "tickets");

  // Organizar tickets por fecha
  const groups = {};

  tickets.value.forEach((ticket) => {
    if (!ticket.screening) {
      console.warn("Ticket sin screening:", ticket.id);
      return;
    }

    if (!ticket.screening.screening_date) {
      console.warn("Ticket sin screening_date:", ticket.id);
      return;
    }

    const dateKey = ticket.screening.screening_date;

    if (!groups[dateKey]) {
      groups[dateKey] = {
        date: dateKey,
        tickets: [],
      };
    }

    groups[dateKey].tickets.push(ticket);
  });

  // Convertir a array y ordenar por fecha (más reciente primero)
  const result = Object.values(groups).sort((a, b) => {
    return new Date(b.date) - new Date(a.date);
  });

  console.log("Grupos resultantes:", result);
  return result;
});

// Función para ir a iniciar sesión
const anarAIniciarSessio = () => {
  router.push("/usuari/iniciSessio");
};

// Función para obtener el título de la película
const getMovieTitle = (ticket) => {
  if (!ticket || !ticket.screening) return "Pel·lícula";

  const movieId = ticket.screening.movie_id;

  // Si tenemos el título en caché, lo usamos
  if (movieCache.value[movieId]) {
    return movieCache.value[movieId].title;
  }

  // Si no tenemos el título, devolvemos un valor genérico
  return `Pel·lícula (ID: ${movieId})`;
};

// Función para formatear fecha
const formatDate = (dateString) => {
  if (!dateString) return "Data no disponible";

  try {
    const date = parseISO(dateString);
    // Formato: "Dimecres, 19 de març de 2025"
    return format(date, "EEEE, d 'de' MMMM 'de' yyyy", { locale: ca });
  } catch (error) {
    console.error("Error al formatear fecha:", error);
    return "Data incorrecta";
  }
};

// Función para formatear fecha y hora de un ticket
const formatTicketDateTime = (ticket) => {
  if (!ticket || !ticket.screening) return "Data i hora no disponibles";

  const { screening_date, screening_time } = ticket.screening;
  if (!screening_date) return "Data no disponible";

  try {
    // Combinar fecha y hora
    const fechaCompleta = `${screening_date}T${screening_time || "00:00:00"}`;
    const date = parseISO(fechaCompleta);

    // Formato: "19 de març a les 16:00h"
    return format(date, "d 'de' MMMM 'a les' HH:mm'h'", { locale: ca });
  } catch (error) {
    console.error("Error al formatear fecha y hora:", error);
    return "Data i hora incorrectes";
  }
};

// Función para formatear precio
const formatPrice = (price) => {
  if (price === null || price === undefined) return "N/A";

  // Asegurarse de que sea un número
  const numericPrice = typeof price === "string" ? parseFloat(price) : price;

  if (isNaN(numericPrice)) return "Preu incorrecte";

  // Formatear con 2 decimales y símbolo de euro
  return numericPrice.toFixed(2) + " €";
};

// Función para descargar un ticket
const downloadTicket = (ticketId, ticketNumber) => {
  // Utilizar la función del store para descargar el PDF
  console.log("Descargando ticket ID:", ticketId, "Código:", ticketNumber);
  ticketStore.downloadTicketPdf(ticketId);
};

// Función para obtener los datos de películas
const fetchMoviesData = async () => {
  try {
    // Extraer IDs de películas de los tickets
    const movieIds = new Set();
    tickets.value.forEach((ticket) => {
      if (ticket.screening && ticket.screening.movie_id) {
        movieIds.add(ticket.screening.movie_id);
      }
    });

    // Para cada película, obtener sus datos completos
    for (const movieId of movieIds) {
      try {
        const response = await fetch(`http://localhost:8000/api/movies/${movieId}`);
        if (response.ok) {
          const movieData = await response.json();
          // Guardar en caché
          movieCache.value[movieId] = movieData;
        }
      } catch (err) {
        console.error(`Error al obtener datos de la película ${movieId}:`, err);
      }
    }
  } catch (err) {
    console.error("Error al obtener datos de películas:", err);
  }
};

// Función para obtener los tickets
const fetchTickets = async () => {
  if (!authStore.isAuthenticated) return;

  loading.value = true;
  error.value = null;

  try {
    await ticketStore.fetchUserTickets();
    tickets.value = ticketStore.tickets;

    console.log("Tickets cargados:", tickets.value);

    // Verificar si hay errores en el store
    if (ticketStore.error) {
      error.value = ticketStore.error;
    } else {
      // Si tenemos tickets, cargar datos adicionales de películas
      if (tickets.value && tickets.value.length > 0) {
        await fetchMoviesData();
      }
    }
  } catch (err) {
    console.error("Error al obtener tickets:", err);
    error.value =
      "No s'ha pogut carregar el teu historial de compres. Si us plau, torna-ho a provar més tard.";
  } finally {
    loading.value = false;
  }
};

// Cargar tickets al montar el componente
onMounted(() => {
  // Pequeño retraso para asegurar que todo esté cargado
  setTimeout(() => {
    if (authStore.isAuthenticated) {
      fetchTickets();
    }
  }, 100);
});

// Observar cambios en la autenticación para recargar tickets
watch(
  () => authStore.isAuthenticated,
  (newValue) => {
    if (newValue) {
      fetchTickets();
    } else {
      tickets.value = [];
    }
  }
);
</script>
