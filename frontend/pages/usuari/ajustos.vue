<template>
  <div class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar importado directamente -->
    <NavBar />

    <!-- Contenido principal con padding adecuado -->
    <main class="flex-grow w-full py-6">
      <div class="w-full max-w-6xl mx-auto px-4">
        <!-- Título de sección -->
        <div class="bg-[#800040] rounded-lg shadow-md mb-6">
          <h1 class="text-center text-white py-4 text-xl font-bold">Configuració del Perfil</h1>
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
                    Has d'iniciar sessió o registrar-te a la nostra pàgina web per accedir a la
                    configuració del teu perfil.
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
              class="px-4 py-2 rounded-md flex items-center text-[#800040] hover:bg-[#D4AF37]/10"
            >
              <ClientOnly>
                <Icon name="mdi:history" class="w-5 h-5 mr-2 text-[#D4AF37]" />
              </ClientOnly>
              <span>Historial</span>
            </a>
            <!-- <a
              href="/usuari/favorits"
              class="px-4 py-2 rounded-md flex items-center text-[#800040] hover:bg-[#D4AF37]/10"
            >
              <ClientOnly>
                <Icon name="mdi:heart" class="w-5 h-5 mr-2 text-[#D4AF37]" />
              </ClientOnly>
              <span>Favorits</span>
            </a> -->
            <a
              href="/usuari/ajustos"
              class="px-4 py-2 rounded-md flex items-center text-[#800040] font-bold bg-[#D4AF37]/10 border-b-2 border-[#D4AF37]"
            >
              <ClientOnly>
                <Icon name="mdi:cog" class="w-5 h-5 mr-2 text-[#D4AF37]" />
              </ClientOnly>
              <span>Configuració</span>
            </a>
          </div>
        </div>

        <!-- CONTENIDO PRINCIPAL: Formulario de ajustes -->
        <div v-if="isAuthenticated" class="space-y-6" v-auto-animate>
          <!-- Estado de carga -->
          <div v-if="loading" class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="flex flex-col items-center">
              <ClientOnly>
                <Icon name="mdi:loading" class="text-[#800040] w-12 h-12 animate-spin mb-4" />
              </ClientOnly>
              <p class="text-gray-600">Carregant la informació del teu perfil...</p>
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
                @click="fetchUserData"
                class="bg-[#800040] text-white px-6 py-3 rounded-lg flex items-center justify-center mx-auto hover:bg-[#9A0040] transition-colors"
              >
                <ClientOnly>
                  <Icon name="mdi:refresh" class="mr-2" />
                </ClientOnly>
                Tornar a Intentar
              </button>
            </div>
          </div>

          <!-- Formulario de ajustes -->
          <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Sección de datos personales -->
            <form @submit.prevent="updateUser('profile')" class="p-6">
              <h2 class="text-lg font-semibold text-gray-800 mb-4">Dades Personals</h2>

              <div class="space-y-4">
                <!-- Nombre -->
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                  <div class="relative">
                    <ClientOnly>
                      <Icon
                        name="mdi:account"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                      />
                    </ClientOnly>
                    <input
                      id="name"
                      v-model="formData.name"
                      type="text"
                      class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#800040] focus:border-[#800040] sm:text-sm"
                    />
                  </div>
                </div>

                <!-- Apellido -->
                <div>
                  <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">
                    Cognoms
                  </label>
                  <div class="relative">
                    <ClientOnly>
                      <Icon
                        name="mdi:account"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                      />
                    </ClientOnly>
                    <input
                      id="lastName"
                      v-model="formData.last_name"
                      type="text"
                      class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#800040] focus:border-[#800040] sm:text-sm"
                    />
                  </div>
                </div>

                <!-- Email -->
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Correu Electrònic
                  </label>
                  <div class="relative">
                    <ClientOnly>
                      <Icon
                        name="mdi:email"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                      />
                    </ClientOnly>
                    <input
                      id="email"
                      v-model="formData.email"
                      type="email"
                      class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#800040] focus:border-[#800040] sm:text-sm"
                      disabled
                    />
                    <div
                      class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                    >
                      <ClientOnly>
                        <Icon name="mdi:lock" class="h-4 w-4 text-gray-400" />
                      </ClientOnly>
                    </div>
                  </div>
                  <p class="mt-1 text-xs text-gray-500">
                    El correu electrònic no es pot modificar.
                  </p>
                </div>

                <!-- Teléfono -->
                <div>
                  <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                    Telèfon
                  </label>
                  <div class="relative">
                    <ClientOnly>
                      <Icon
                        name="mdi:phone"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                      />
                    </ClientOnly>
                    <input
                      id="phone"
                      v-model="formData.phone"
                      type="tel"
                      class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#800040] focus:border-[#800040] sm:text-sm"
                    />
                  </div>
                </div>
              </div>

              <!-- Botones de acción -->
              <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
                <button
                  type="submit"
                  class="flex-1 bg-[#800040] text-white px-4 py-2 rounded-md shadow-sm hover:bg-[#9A0040] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#800040] flex items-center justify-center"
                  :disabled="isSaving"
                >
                  <ClientOnly>
                    <Icon v-if="isSaving" name="mdi:loading" class="animate-spin w-5 h-5 mr-2" />
                    <Icon v-else name="mdi:content-save" class="w-5 h-5 mr-2" />
                  </ClientOnly>
                  Guardar Canvis
                </button>
                <button
                  type="button"
                  @click="resetForm"
                  class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 flex items-center justify-center"
                >
                  <ClientOnly>
                    <Icon name="mdi:refresh" class="w-5 h-5 mr-2" />
                  </ClientOnly>
                  Restablir
                </button>
              </div>
            </form>

            <!-- Sección de cambio de contraseña -->
            <div class="p-6 border-t border-gray-200">
              <h2 class="text-lg font-semibold text-gray-800 mb-4">Canviar Contrasenya</h2>
              <!-- Requisits de contrasenya -->
              <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-[#D4AF37]/30">
                <p class="text-[#800040] text-sm font-medium mb-2 flex items-center">
                  <Icon name="mdi:shield" class="text-[#D4AF37] mr-2" />
                  Requisits de seguretat de la contrasenya:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-1">
                  <div
                    class="flex items-center text-xs"
                    :class="validarMajuscula ? 'text-green-600' : 'text-gray-500'"
                  >
                    <Icon
                      :name="validarMajuscula ? 'mdi:check-circle' : 'mdi:circle-outline'"
                      class="mr-2"
                    />
                    <span>Una majúscula</span>
                  </div>
                  <div
                    class="flex items-center text-xs"
                    :class="validarMinuscula ? 'text-green-600' : 'text-gray-500'"
                  >
                    <Icon
                      :name="validarMinuscula ? 'mdi:check-circle' : 'mdi:circle-outline'"
                      class="mr-2"
                    />
                    <span>Una minúscula</span>
                  </div>
                  <div
                    class="flex items-center text-xs"
                    :class="validarNumero ? 'text-green-600' : 'text-gray-500'"
                  >
                    <Icon
                      :name="validarNumero ? 'mdi:check-circle' : 'mdi:circle-outline'"
                      class="mr-2"
                    />
                    <span>Un número</span>
                  </div>
                  <div
                    class="flex items-center text-xs"
                    :class="validarEspecial ? 'text-green-600' : 'text-gray-500'"
                  >
                    <Icon
                      :name="validarEspecial ? 'mdi:check-circle' : 'mdi:circle-outline'"
                      class="mr-2"
                    />
                    <span>Un caràcter especial</span>
                  </div>
                  <div
                    class="flex items-center text-xs sm:col-span-2"
                    :class="validarLlargada ? 'text-green-600' : 'text-gray-500'"
                  >
                    <Icon
                      :name="validarLlargada ? 'mdi:check-circle' : 'mdi:circle-outline'"
                      class="mr-2"
                    />
                    <span>Mínim 10 caràcters</span>
                  </div>
                </div>
              </div>

              <form @submit.prevent="updateUser('password')" class="space-y-4">
                <!-- Contraseña actual -->
                <div>
                  <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">
                    Contrasenya Actual
                  </label>
                  <div class="relative">
                    <ClientOnly>
                      <Icon
                        name="mdi:lock"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                      />
                    </ClientOnly>
                    <input
                      id="currentPassword"
                      v-model="passwordData.current_password"
                      :type="showCurrentPassword ? 'text' : 'password'"
                      class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#800040] focus:border-[#800040] sm:text-sm"
                    />
                    <button
                      type="button"
                      @click="showCurrentPassword = !showCurrentPassword"
                      class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    >
                      <ClientOnly>
                        <Icon
                          :name="showCurrentPassword ? 'mdi:eye-off' : 'mdi:eye'"
                          class="h-5 w-5 text-gray-400"
                        />
                      </ClientOnly>
                    </button>
                  </div>
                </div>

                <!-- Nueva contraseña -->
                <div>
                  <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">
                    Nova Contrasenya
                  </label>
                  <div class="relative">
                    <ClientOnly>
                      <Icon
                        name="mdi:lock-reset"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                      />
                    </ClientOnly>
                    <input
                      id="newPassword"
                      v-model="passwordData.new_password"
                      :type="showNewPassword ? 'text' : 'password'"
                      class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#800040] focus:border-[#800040] sm:text-sm"
                    />
                    <button
                      type="button"
                      @click="showNewPassword = !showNewPassword"
                      class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    >
                      <ClientOnly>
                        <Icon
                          :name="showNewPassword ? 'mdi:eye-off' : 'mdi:eye'"
                          class="h-5 w-5 text-gray-400"
                        />
                      </ClientOnly>
                    </button>
                  </div>
                </div>

                <!-- Confirmar contraseña -->
                <div>
                  <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirmar Nova Contrasenya
                  </label>
                  <div class="relative">
                    <ClientOnly>
                      <Icon
                        name="mdi:lock-check"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                      />
                    </ClientOnly>
                    <input
                      id="confirmPassword"
                      v-model="passwordData.new_password_confirmation"
                      :type="showConfirmPassword ? 'text' : 'password'"
                      class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#800040] focus:border-[#800040] sm:text-sm"
                    />
                    <button
                      type="button"
                      @click="showConfirmPassword = !showConfirmPassword"
                      class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    >
                      <ClientOnly>
                        <Icon
                          :name="showConfirmPassword ? 'mdi:eye-off' : 'mdi:eye'"
                          class="h-5 w-5 text-gray-400"
                        />
                      </ClientOnly>
                    </button>
                  </div>
                </div>

                <!-- Botón de actualizar contraseña -->
                <div>
                  <button
                    type="submit"
                    class="w-full bg-[#800040] text-white px-4 py-2 rounded-md shadow-sm hover:bg-[#9A0040] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#800040] flex items-center justify-center"
                    :disabled="
                      isUpdatingPassword || !passwordCompleix || !passwordData.current_password
                    "
                  >
                    <ClientOnly>
                      <Icon
                        v-if="isUpdatingPassword"
                        name="mdi:loading"
                        class="animate-spin w-5 h-5 mr-2"
                      />
                      <Icon v-else name="mdi:key" class="w-5 h-5 mr-2" />
                    </ClientOnly>
                    Actualitzar Contrasenya
                  </button>
                </div>
              </form>
            </div>

            <!-- Sección para eliminar la cuenta -->
            <div class="p-6 border-t border-gray-200">
              <h2 class="text-lg font-semibold text-gray-800 mb-4">Eliminar Compte</h2>
              <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded mb-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <ClientOnly>
                      <Icon name="mdi:alert-circle" class="text-red-500 w-5 h-5" />
                    </ClientOnly>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm text-red-700">
                      Atenció: Aquesta acció no es pot desfer. Si elimines el teu compte, perdràs
                      tot l'accés a les teves entrades i informació personal.
                    </p>
                  </div>
                </div>
              </div>
              <button
                @click="confirmDeleteAccount"
                class="w-full bg-red-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 flex items-center justify-center"
                :disabled="isDeletingAccount"
              >
                <ClientOnly>
                  <Icon
                    v-if="isDeletingAccount"
                    name="mdi:loading"
                    class="animate-spin w-5 h-5 mr-2"
                  />
                  <Icon v-else name="mdi:delete-forever" class="w-5 h-5 mr-2" />
                </ClientOnly>
                Eliminar el Meu Compte
              </button>
            </div>

            <!-- Mensajes de notificación -->
            <div v-if="successMessage" class="p-4 border-t border-gray-200 bg-green-50">
              <div class="flex">
                <div class="flex-shrink-0">
                  <ClientOnly>
                    <Icon name="mdi:check-circle" class="text-green-500 w-5 h-5" />
                  </ClientOnly>
                </div>
                <div class="ml-3">
                  <p class="text-sm text-green-700">{{ successMessage }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal de confirmación para eliminar cuenta -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-lg max-w-md w-full">
        <div class="p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4">
            Estàs segur que vols eliminar el teu compte?
          </h3>
          <p class="text-gray-700 mb-6">
            Aquesta acció no es pot desfer. Es perdran totes les teves dades, incloent-hi el teu
            historial de compres.
          </p>
          <div class="flex flex-col sm:flex-row gap-3">
            <button
              @click="deleteAccount"
              class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 flex items-center justify-center"
              :disabled="isDeletingAccount"
            >
              <ClientOnly>
                <Icon
                  v-if="isDeletingAccount"
                  name="mdi:loading"
                  class="animate-spin w-5 h-5 mr-2"
                />
                <Icon v-else name="mdi:delete-forever" class="w-5 h-5 mr-2" />
              </ClientOnly>
              Sí, Eliminar Compte
            </button>
            <button
              @click="showDeleteModal = false"
              class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 flex items-center justify-center"
            >
              <ClientOnly>
                <Icon name="mdi:close" class="w-5 h-5 mr-2" />
              </ClientOnly>
              Cancel·lar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer importado directamente -->
    <Footer />
  </div>
</template>
<script setup>
import { ref, computed, onMounted, reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";

// Inicializar store y router
const authStore = useAuthStore();
const router = useRouter();

// Estado local
const loading = ref(false);
const error = ref(null);
const successMessage = ref(null);
const isSaving = ref(false);
const isUpdatingPassword = ref(false);
const isDeletingAccount = ref(false);
const showDeleteModal = ref(false);

// Estado para mostrar/ocultar contraseñas
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

// Validacions de la contrasenya
const validarMajuscula = computed(() => /[A-Z]/.test(passwordData.new_password));
const validarMinuscula = computed(() => /[a-z]/.test(passwordData.new_password));
const validarNumero = computed(() => /[0-9]/.test(passwordData.new_password));
const validarEspecial = computed(() => /[!@#$%^&*(),.?":{}|<>]/.test(passwordData.new_password));
const validarLlargada = computed(() => passwordData.new_password.length >= 10);
const passwordCompleix = computed(
  () =>
    validarMajuscula.value &&
    validarMinuscula.value &&
    validarNumero.value &&
    validarEspecial.value &&
    validarLlargada.value
);
// Datos del formulario
const formData = reactive({
  id: null,
  name: "",
  last_name: "",
  email: "",
  phone: "",
  image: "", // Mantenerlo para compatibilidad, pero no se mostrará ni modificará
});

// Datos de cambio de contraseña
const passwordData = reactive({
  current_password: "",
  new_password: "",
  new_password_confirmation: "",
});

// Computa si el usuario está autenticado
const isAuthenticated = computed(() => {
  return authStore.isAuthenticated;
});

// Función para ir a iniciar sesión
const anarAIniciarSessio = () => {
  router.push("/usuari/iniciSessio");
};

// Función para obtener datos del usuario
const fetchUserData = async () => {
  if (!authStore.isAuthenticated) return;

  loading.value = true;
  error.value = null;

  try {
    // Si tienes el usuario en el store, úsalo directamente
    if (authStore.user) {
      populateFormData(authStore.user);
    } else {
      // Si no hay usuario en el store, intenta refrescar los datos
      await authStore.refreshUserData();
      populateFormData(authStore.user);
    }
  } catch (err) {
    console.error("Error al obtenir dades del perfil:", err);
    error.value = "No s'ha pogut carregar la informació del teu perfil";
  } finally {
    loading.value = false;
  }
};

// Función para llenar el formulario con los datos del usuario
const populateFormData = (userData) => {
  if (!userData) return;

  formData.id = userData.id;
  formData.name = userData.name || "";
  formData.last_name = userData.last_name || "";
  formData.email = userData.email || "";
  formData.phone = userData.phone || "";
  formData.image = userData.image || "";
};

// Función unificada para actualizar el perfil o la contraseña
const updateUser = async (updateType) => {
  error.value = null;

  if (updateType === "profile") {
    isSaving.value = true;
  } else if (updateType === "password") {
    // Validar que las contraseñas coincidan
    if (passwordData.new_password !== passwordData.new_password_confirmation) {
      error.value = "Les contrasenyes no coincideixen";
      return;
    }
    isUpdatingPassword.value = true;
  }

  try {
    if (updateType === "profile") {
      const profileData = {
        name: formData.name,
        last_name: formData.last_name,
        phone: formData.phone,
      };

      // Usar la función del AuthStore
      await authStore.updateProfile(profileData);

      // Actualizar datos del formulario
      populateFormData(authStore.user);

      showSuccessMessage("Perfil actualitzat correctament");
    } else if (updateType === "password") {
      const passwordPayload = {
        current_password: passwordData.current_password,
        password: passwordData.new_password,
        password_confirmation: passwordData.new_password_confirmation,
      };
      console.log(passwordPayload);

      // Usar la función del AuthStore
      await authStore.updatePassword(passwordPayload);

      // Limpiar campos de contraseña
      passwordData.current_password = "";
      passwordData.new_password = "";
      passwordData.new_password_confirmation = "";

      showSuccessMessage("Contrasenya actualitzada correctament");
    }
  } catch (err) {
    console.error(`Error al actualitzar ${updateType}:`, err);

    if (updateType === "profile") {
      error.value = "No s'ha pogut actualitzar el perfil";
    } else if (updateType === "password") {
      error.value = "No s'ha pogut actualitzar la contrasenya";
    }

    // Intentar extraer mensaje de error específico
    if (err.response) {
      try {
        const errorData = await err.response.json();
        if (errorData.message) {
          error.value = errorData.message;
        }
      } catch {
        // Si no se puede parsear la respuesta, usar el mensaje genérico ya establecido
      }
    }
  } finally {
    isSaving.value = false;
    isUpdatingPassword.value = false;
  }
};

// Mostrar modal de confirmación para eliminar cuenta
const confirmDeleteAccount = () => {
  showDeleteModal.value = true;
};

// Función para eliminar la cuenta
const deleteAccount = async () => {
  isDeletingAccount.value = true;

  try {
    await authStore.deleteAccount();

    // Redirigir al usuario a la página principal después de eliminar la cuenta
    router.push("/");
  } catch (err) {
    console.error("Error al eliminar la cuenta:", err);
    error.value = "No s'ha pogut eliminar el compte";
    showDeleteModal.value = false;
  } finally {
    isDeletingAccount.value = false;
  }
};

// Función para mostrar mensaje de éxito (con auto-ocultado)
const showSuccessMessage = (message) => {
  successMessage.value = message;

  // Ocultar después de 3 segundos
  setTimeout(() => {
    successMessage.value = null;
  }, 3000);
};

// Función para resetear el formulario
const resetForm = () => {
  fetchUserData();
};

// Cargar datos al montar el componente
onMounted(() => {
  if (authStore.isAuthenticated) {
    fetchUserData();
  }
});
</script>
