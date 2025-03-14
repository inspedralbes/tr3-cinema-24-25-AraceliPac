<template>
  <div class="w-full max-w-xl mx-auto">
    <div class="bg-gradient-to-b from-gray-50 to-gray-100 shadow-2xl rounded-lg border-t-4 border-[#D4AF37] overflow-hidden">
      <!-- Cabecera con imagen de fondo -->
      <div class="relative py-8 px-6 bg-[#800040] bg-opacity-90 border-b-2 border-[#D4AF37]">
        <div class="absolute inset-0 opacity-10 bg-[url('/assets/cinema-pattern.png')]"></div>
        <h1 class="text-[#D4AF37] text-center text-3xl font-bold relative z-10">
          REGISTRAT A CINEMA PEDRALBES
        </h1>
        <p class="text-white text-center text-sm mt-2 relative z-10">
          Gaudeix dels millors avantatges per als nostres clients
        </p>
      </div>
      
      <form @submit.prevent="registrarUsuari" class="p-8 space-y-6">
        <!-- Secció Informació Personal -->
        <div class="space-y-4">
          <h2 class="text-[#800040] text-xl font-semibold pb-2">
            Informació Personal
          </h2>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Nom -->
            <div class="space-y-2">
              <label for="name" class="block text-[#800040] text-sm font-semibold">
                Nom <span class="text-red-500">*</span>
              </label>
              <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-user text-[#D4AF37] group-focus-within:text-[#800040] transition-colors"></i>
                </div>
                <input 
                  type="text" 
                  id="name" 
                  v-model="formData.name" 
                  required 
                  placeholder="El teu nom"
                  class="block w-full pl-10 pr-3 py-3.5 border-2 border-gray-200 rounded-lg bg-white text-[#800040] focus:outline-none focus:ring-2 focus:ring-[#D4AF37] focus:border-[#800040] transition-all shadow-sm hover:border-[#800040] placeholder-[#800040]/50"
                />
              </div>
              <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
            </div>
            
            <!-- Cognoms -->
            <div class="space-y-2">
              <label for="last_name" class="block text-[#800040] text-sm font-semibold">
                Cognoms <span class="text-red-500">*</span>
              </label>
              <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-user text-[#D4AF37] group-focus-within:text-[#800040] transition-colors"></i>
                </div>
                <input 
                  type="text" 
                  id="last_name" 
                  v-model="formData.last_name" 
                  required 
                  placeholder="Els teus cognoms"
                  class="block w-full pl-10 pr-3 py-3.5 border-2 border-[#800040] rounded-lg bg-white text-[#800040] focus:outline-none focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent transition-all shadow-sm placeholder-[#800040]/50"
                />
              </div>
              <p v-if="errors.last_name" class="text-red-500 text-xs mt-1">{{ errors.last_name }}</p>
            </div>
          </div>
        </div>
        
        <!-- Secció Informació de Contacte -->
        <div class="space-y-4">
          <h2 class="text-[#800040] text-xl font-semibold pb-2">
            Informació de Contacte
          </h2>
          
          <!-- Email -->
          <div class="space-y-2">
            <label for="email" class="block text-[#800040] text-sm font-semibold">
              Correu electrònic <span class="text-red-500">*</span>
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-[#D4AF37] group-focus-within:text-[#800040] transition-colors"></i>
              </div>
              <input 
                type="email" 
                id="email" 
                v-model="formData.email" 
                required 
                placeholder="El teu correu electrònic"
                class="block w-full pl-10 pr-3 py-3.5 border-2 border-gray-200 rounded-lg bg-white text-[#800040] focus:outline-none focus:ring-2 focus:ring-[#D4AF37] focus:border-[#800040] transition-all shadow-sm hover:border-[#800040] placeholder-[#800040]/50"
              />
            </div>
            <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
          </div>
          
          <!-- Telèfon -->
          <div class="space-y-2">
            <label for="phone" class="block text-[#800040] text-sm font-semibold">
              Telèfon
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-phone text-[#D4AF37] group-focus-within:text-[#800040] transition-colors"></i>
              </div>
              <input 
                type="tel" 
                id="phone" 
                v-model="formData.phone" 
                placeholder="El teu número de telèfon"
                class="block w-full pl-10 pr-3 py-3.5 border-2 border-gray-200 rounded-lg bg-white text-[#800040] focus:outline-none focus:ring-2 focus:ring-[#D4AF37] focus:border-[#800040] transition-all shadow-sm hover:border-[#800040] placeholder-[#800040]/50"
              />
            </div>
            <p v-if="errors.phone" class="text-red-500 text-xs mt-1">{{ errors.phone }}</p>
          </div>
        </div>
        
        <!-- Secció Contrasenya -->
        <div class="space-y-4">
          <h2 class="text-[#800040] text-xl font-semibold pb-2">
            Seguretat
          </h2>
          
          <!-- Contrasenya -->
          <div class="space-y-2">
            <label for="password" class="block text-[#800040] text-sm font-semibold">
              Contrasenya <span class="text-red-500">*</span>
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-[#D4AF37] group-focus-within:text-[#800040] transition-colors"></i>
              </div>
              <input 
                :type="mostrarPassword ? 'text' : 'password'" 
                id="password" 
                v-model="formData.password" 
                required 
                placeholder="La teva contrasenya"
                class="block w-full pl-10 pr-10 py-3.5 border-2 border-gray-200 rounded-lg bg-white text-[#800040] focus:outline-none focus:ring-2 focus:ring-[#D4AF37] focus:border-[#800040] transition-all shadow-sm hover:border-[#800040] placeholder-[#800040]/50"
              />
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button 
                  type="button" 
                  @click="mostrarPassword = !mostrarPassword"
                  class="text-[#D4AF37] hover:text-[#800040] focus:outline-none transition-colors"
                >
                  <i :class="mostrarPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
            </div>
            <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
          </div>
          
          <!-- Confirmar Contrasenya -->
          <div class="space-y-2">
            <label for="password_confirmation" class="block text-[#800040] text-sm font-semibold">
              Confirma la contrasenya <span class="text-red-500">*</span>
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-[#D4AF37] group-focus-within:text-[#800040] transition-colors"></i>
              </div>
              <input 
                :type="mostrarPasswordConfirmacio ? 'text' : 'password'" 
                id="password_confirmation" 
                v-model="formData.password_confirmation" 
                required 
                placeholder="Confirma la teva contrasenya"
                class="block w-full pl-10 pr-10 py-3.5 border-2 border-gray-200 rounded-lg bg-white text-[#800040] focus:outline-none focus:ring-2 focus:ring-[#D4AF37] focus:border-[#800040] transition-all shadow-sm hover:border-[#800040] placeholder-[#800040]/50"
              />
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button 
                  type="button" 
                  @click="mostrarPasswordConfirmacio = !mostrarPasswordConfirmacio"
                  class="text-[#D4AF37] hover:text-[#800040] focus:outline-none transition-colors"
                >
                  <i :class="mostrarPasswordConfirmacio ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
            </div>
            <div v-if="!coincideixenContrasenyes && formData.password_confirmation" class="flex items-center mt-2 text-red-500 text-xs">
              <i class="fas fa-exclamation-circle mr-1"></i>
              <p>Les contrasenyes no coincideixen</p>
            </div>
            
            <!-- Requisits de contrasenya -->
            <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-[#D4AF37]/30">
              <p class="text-[#800040] text-sm font-medium mb-2 flex items-center">
                <i class="fas fa-shield-alt text-[#D4AF37] mr-2"></i>
                Requisits de seguretat de la contrasenya:
              </p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-1">
                <div class="flex items-center text-xs" :class="validarMajuscula ? 'text-green-600' : 'text-gray-500'">
                  <i class="fas mr-2" :class="validarMajuscula ? 'fa-check-circle' : 'fa-circle'"></i>
                  <span>Una majúscula</span>
                </div>
                <div class="flex items-center text-xs" :class="validarMinuscula ? 'text-green-600' : 'text-gray-500'">
                  <i class="fas mr-2" :class="validarMinuscula ? 'fa-check-circle' : 'fa-circle'"></i>
                  <span>Una minúscula</span>
                </div>
                <div class="flex items-center text-xs" :class="validarNumero ? 'text-green-600' : 'text-gray-500'">
                  <i class="fas mr-2" :class="validarNumero ? 'fa-check-circle' : 'fa-circle'"></i>
                  <span>Un número</span>
                </div>
                <div class="flex items-center text-xs" :class="validarEspecial ? 'text-green-600' : 'text-gray-500'">
                  <i class="fas mr-2" :class="validarEspecial ? 'fa-check-circle' : 'fa-circle'"></i>
                  <span>Un caràcter especial</span>
                </div>
                <div class="flex items-center text-xs sm:col-span-2" :class="validarLlargada ? 'text-green-600' : 'text-gray-500'">
                  <i class="fas mr-2" :class="validarLlargada ? 'fa-check-circle' : 'fa-circle'"></i>
                  <span>Mínim 10 caràcters</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Termes i condicions -->
        <div class="space-y-4 pt-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
          <div class="flex items-start">
            <div class="flex items-center h-5">
              <input
                id="major_edat"
                type="checkbox"
                v-model="formData.major_edat"
                required
                class="h-5 w-5 text-[#800040] focus:ring-[#D4AF37] border-gray-300 rounded-md"
              />
            </div>
            <div class="ml-3 text-sm">
              <label for="major_edat" class="font-medium text-[#800040]">
                Confirmo que sóc major de 14 anys <span class="text-red-500">*</span>
              </label>
            </div>
          </div>
          
          <div class="flex items-start">
            <div class="flex items-center h-5">
              <input
                id="privacy_policy"
                type="checkbox"
                v-model="formData.privacy_policy"
                required
                class="h-5 w-5 text-[#800040] focus:ring-[#D4AF37] border-gray-300 rounded-md"
              />
            </div>
            <div class="ml-3 text-sm">
              <label for="privacy_policy" class="font-medium text-[#800040]">
                He llegit i accepto la <a href="#" class="text-[#D4AF37] underline hover:text-[#800040] font-semibold transition-colors">política de privacitat</a> <span class="text-red-500">*</span>
              </label>
            </div>
          </div>
          
          <div v-if="(!formData.major_edat || !formData.privacy_policy) && enviado" class="mt-2 bg-red-50 p-3 rounded-lg border border-red-200">
            <p v-if="!formData.major_edat && enviado" class="text-red-500 text-xs flex items-center">
              <i class="fas fa-exclamation-triangle mr-1"></i> Has de confirmar que ets major de 14 anys
            </p>
            <p v-if="!formData.privacy_policy && enviado" class="text-red-500 text-xs flex items-center">
              <i class="fas fa-exclamation-triangle mr-1"></i> Has d'acceptar la política de privacitat
            </p>
          </div>
        </div>
        
        <!-- Missatge d'error general -->
        <div v-if="errorMessage" class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
          <div class="flex">
            <div class="flex-shrink-0">
              <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm text-red-700">{{ errorMessage }}</p>
            </div>
          </div>
        </div>
        
        <!-- Botó Registrar-se -->
        <div class="pt-4">
          <button 
            type="submit" 
            :disabled="isLoading || !totesLesDadesValides"
            class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-lg shadow-lg text-white bg-gradient-to-r from-[#800040] to-[#9A0040] hover:from-[#9A0040] hover:to-[#B00040] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4AF37] font-bold text-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:scale-[1.02] active:scale-[0.98]"
          >
            <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <i class="fas fa-user-plus mr-2"></i>
            <span>Registrar-me</span>
          </button>
        </div>
      </form>
      
      <!-- Secció d'inici de sessió -->
      <div class="py-6 px-8 bg-gradient-to-r from-[#800040] to-[#f1948a] border-t-2 border-[#800040]">
        <div class="bg-white p-4 rounded-lg shadow-md">
          <p class="text-center text-[#800040] font-medium mb-4">
            Ja tens compte a Cinema Pedralbes?
          </p>
          <NuxtLink
            to="/login" 
            class="w-full flex justify-center items-center py-3 px-4 border-2 border-[#800040] rounded-lg shadow-md bg-white hover:bg-[#f8f9f9] transition-all transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#800040] font-semibold text-[#800040] text-base"
          >
            <i class="fas fa-sign-in-alt mr-2"></i>
            Iniciar sessió
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

// Store i router
const authStore = useAuthStore();
const router = useRouter();

// Comprova si l'usuari ja està autenticat
onMounted(() => {
  if (authStore.isAuthenticated) {
    router.push('/');
  }
});

// Dades del formulari
const formData = reactive({
  name: '',
  last_name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
  role_id: 3, // Client per defecte
  major_edat: false,
  privacy_policy: false
});

// Estat del component
const isLoading = ref(false);
const mostrarPassword = ref(false);
const mostrarPasswordConfirmacio = ref(false);
const errors = ref({});
const errorMessage = ref('');
const enviado = ref(false);

// Validacions de la contrasenya
const validarMajuscula = computed(() => /[A-Z]/.test(formData.password));
const validarMinuscula = computed(() => /[a-z]/.test(formData.password));
const validarNumero = computed(() => /[0-9]/.test(formData.password));
const validarEspecial = computed(() => /[!@#$%^&*(),.?":{}|<>]/.test(formData.password));
const validarLlargada = computed(() => formData.password.length >= 10);

const passwordCompleix = computed(() => 
  validarMajuscula.value && 
  validarMinuscula.value && 
  validarNumero.value && 
  validarEspecial.value && 
  validarLlargada.value
);

const coincideixenContrasenyes = computed(() => 
  formData.password === formData.password_confirmation
);

const totesLesDadesValides = computed(() => 
  formData.name &&
  formData.last_name &&
  formData.email &&
  passwordCompleix.value &&
  coincideixenContrasenyes.value &&
  formData.major_edat &&
  formData.privacy_policy
);

  // Funció per registrar l'usuari
async function registrarUsuari() {
  enviado.value = true;
  errorMessage.value = '';
  
  if (!totesLesDadesValides.value) {
    // Mostrar missatges d'error
    return;
  }
  
  // Reiniciem els errors
  errors.value = {};
  
  // Activem l'estat de càrrega
  isLoading.value = true;
  
  try {
    // Enviar les dades a l'API
    const response = await fetch('http://localhost:8000/api/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(formData)
    });
    
    const data = await response.json();
    
    if (!response.ok) {
      if (response.status === 422) {
        // Errors de validació
        errors.value = data.errors || {};
        
        // Missatge d'error general
        errorMessage.value = 'Hi ha errors en el formulari. Revisa els camps marcats.';
      } else {
        // Altres errors
        console.error('Error en el registre:', data);
        errorMessage.value = data.message || 'S\'ha produït un error en el registre. Torna-ho a provar més tard.';
      }
      return;
    }
    
    console.log('Resposta registre:', data); // Log para depuración
    
    // Si el registre és correcte i rebem token
    if (data.token) {
      // Si la respuesta ya incluye los datos del usuario
      if (data.user) {
        authStore.setAuth(data.token, data.user);
      } else {
        try {
          // Intentar obtener los datos del usuario con el token recibido
          const userResponse = await fetch('http://localhost:8000/api/user', {
            headers: {
              'Authorization': `Bearer ${data.token}`,
              'Accept': 'application/json'
            }
          });
          
          if (userResponse.ok) {
            const userData = await userResponse.json();
            console.log('Datos de usuario obtenidos:', userData);
            authStore.setAuth(data.token, userData);
          } else {
            // Si no podemos obtener el usuario, usamos los datos del formulario
            const userData = {
              name: formData.name,
              last_name: formData.last_name,
              email: formData.email,
              phone: formData.phone || '',
              role_id: formData.role_id
            };
            authStore.setAuth(data.token, userData);
          }
        } catch (error) {
          console.error('Error al obtener datos del usuario:', error);
          // Usar los datos del formulario como respaldo
          authStore.setAuth(data.token, {
            name: formData.name,
            last_name: formData.last_name,
            email: formData.email,
            phone: formData.phone || '',
            role_id: formData.role_id
          });
        }
      }
      
      // Redireccionem a la pàgina principal
      router.push('/');
    } else {
      // Si no rebem token però el registre és correcte
      errorMessage.value = 'Registre completat, però no s\'ha pogut iniciar sessió automàticament. Si us plau, inicia sessió.';
      setTimeout(() => {
        router.push('/login');
      }, 3000);
    }
    
  } catch (error) {
    console.error('Error en fer la petició:', error);
    errorMessage.value = 'Error de connexió amb el servidor. Comprova la teva connexió a internet i torna-ho a provar.';
  } finally {
    // Desactivem l'estat de càrrega
    isLoading.value = false;
  }
}
</script>