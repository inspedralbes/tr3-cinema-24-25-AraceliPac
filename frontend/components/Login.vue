<template>
  <div class="w-full max-w-xl mx-auto">
    <div class="bg-gradient-to-b from-gray-50 to-gray-100 shadow-2xl rounded-lg border-t-4 border-[#D4AF37]">
      <!-- Cabecera con imagen de fondo -->
      <div class="relative py-8 px-6 bg-[#800040] bg-opacity-90 border-b-2 border-[#D4AF37]">
        <div class="absolute inset-0 opacity-10 bg-[#600030]"></div>
        <h1 class="text-[#D4AF37] text-center text-3xl font-bold relative z-10">
          INICIAR SESSIÓ EN CINEMA PEDRALBES
        </h1>
        <p class="text-white text-center text-sm mt-2 relative z-10">
          Accedeix al teu compte i gaudeix de tots els avantatges
        </p>
      </div>
      
      <!-- Contenedor principal con animaciones CSS en lugar de auto-animate -->
      <div>
        <!-- Mensaje de éxito (mostrado solo cuando loginSuccess es true) -->
        <div v-if="loginSuccess" 
             class="p-8 flex flex-col items-center justify-center space-y-6 animate-fadeIn">
          <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg w-full animate-bounce-once">
            <div class="flex">
              <div class="flex-shrink-0">
                <Icon name="mdi:check-circle" class="text-green-500 text-2xl" />
              </div>
              <div class="ml-3">
                <p class="text-green-700 font-medium">Inici de sessió correcte!</p>
                <p class="text-green-600 text-sm mt-1">Redirigint a la pàgina principal...</p>
              </div>
            </div>
          </div>
          
          <div class="flex justify-center">
            <svg class="animate-spin h-12 w-12 text-[#800040]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>
        </div>
        
        <!-- Formulario (oculto cuando loginSuccess es true con transición) -->
        <form v-else @submit.prevent="iniciarSessio" 
              class="p-8 space-y-6">
          <!-- Secció Credencials -->
          <div class="space-y-4">
            <h2 class="text-[#800040] text-xl font-semibold pb-2">
              Credencials d'Accés
            </h2>
            
            <!-- Camp Email -->
            <div class="space-y-2">
              <label for="email" class="block text-[#800040] text-sm font-semibold">
                Correu electrònic <span class="text-red-500">*</span>
              </label>
              <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <Icon name="mdi:email" class="text-[#D4AF37] group-focus-within:text-[#800040] transition-colors" />
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
            
            <!-- Camp Contrasenya -->
            <div class="space-y-2">
              <label for="password" class="block text-[#800040] text-sm font-semibold">
                Contrasenya <span class="text-red-500">*</span>
              </label>
              <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <Icon name="mdi:lock" class="text-[#D4AF37] group-focus-within:text-[#800040] transition-colors" />
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
                    <Icon 
                      :name="mostrarPassword ? 'mdi:eye-off' : 'mdi:eye'" 
                      class="w-5 h-5 transition-all"
                    />
                  </button>
                </div>
              </div>
              <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
            </div>
          </div>
          
          <!-- Opcions "Recordar-me" i "Contrasenya oblidada" -->
          <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:items-center sm:justify-between bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
              <input 
                id="remember-me" 
                name="remember-me" 
                type="checkbox" 
                v-model="formData.recordarMe"
                class="h-5 w-5 text-[#800040] focus:ring-[#D4AF37] border-gray-300 rounded-md"
              />
              <label for="remember-me" class="ml-3 block text-sm font-medium text-[#800040]">
                Recordar-me
              </label>
            </div>
            <div class="text-sm">
              <a href="#" class="font-medium text-[#D4AF37] hover:text-[#800040] transition-colors">
                Has oblidat la contrasenya?
              </a>
            </div>
          </div>
          
          <!-- Missatge d'error general -->
          <div v-if="errorMessage" class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex">
              <div class="flex-shrink-0">
                <Icon name="mdi:alert-circle" class="text-red-500" />
              </div>
              <div class="ml-3">
                <p class="text-sm text-red-700">{{ errorMessage }}</p>
              </div>
            </div>
          </div>
          
          <!-- Botó Iniciar sessió -->
          <div class="pt-4">
            <button 
              type="submit" 
              :disabled="isLoading"
              class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-lg shadow-lg text-white bg-gradient-to-r from-[#800040] to-[#9A0040] hover:from-[#9A0040] hover:to-[#B00040] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4AF37] font-bold text-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:scale-[1.02] active:scale-[0.98]"
            >
              <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <Icon name="mdi:login" class="mr-2" />
              <span>Iniciar sessió</span>
            </button>
          </div>
        </form>
      </div>
      
      <!-- Secció de registre (solo visible cuando no hay éxito de login) -->
      <div v-if="!loginSuccess" 
           class="py-6 px-8 bg-gradient-to-r from-[#800040] to-[#f1948a] border-t-2 border-[#800040]">
        <div class="bg-white p-4 rounded-lg shadow-md">
          <p class="text-center text-[#800040] font-medium mb-4">
            Encara no tens compte a Cinema Pedralbes?
          </p>
          <NuxtLink 
            to="/usuari/registre" 
            class="w-full flex justify-center items-center py-3 px-4 border-2 border-[#800040] rounded-lg shadow-md bg-white hover:bg-[#f8f9f9] transition-all transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#800040] font-semibold text-[#800040] text-base"
          >
            <Icon name="mdi:account-plus" class="mr-2" />
            Registra't ara
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

// Store i router
const authStore = useAuthStore();
const router = useRouter();

// Dades del formulari
const formData = reactive({
  email: '',
  password: '',
  recordarMe: false
});

// Variables d'estat
const mostrarPassword = ref(false);
const isLoading = ref(false);
const errorMessage = ref('');
const errors = ref({
  email: '',
  password: ''
});
// Variable para controlar la animación de éxito
const loginSuccess = ref(false);

// Comprova si l'usuari ja està autenticat
onMounted(() => {
  if (authStore.isAuthenticated) {
    router.push('/');
  }
});

// Funció per iniciar sessió amb animacions CSS
async function iniciarSessio() {
  // Reiniciem els errors
  errors.value = { email: '', password: '' };
  errorMessage.value = '';
  
  // Activem l'estat de càrrega
  isLoading.value = true;
  
  try {
    // Fem la petició a l'API
    const response = await fetch('http://cinema.daw.inspedralbes.cat/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        email: formData.email,
        password: formData.password
      })
    });
    
    const data = await response.json();
    
    // Si hi ha error en la resposta
    if (!response.ok) {
      // Gestionem errors de validació
      if (response.status === 422 && data.errors) {
        if (data.errors.email) errors.value.email = data.errors.email[0];
        if (data.errors.password) errors.value.password = data.errors.password[0];
      } 
      // Error d'autenticació
      else if (response.status === 401) {
        errorMessage.value = 'Credencials incorrectes. Si us plau, verifica el teu correu i contrasenya.';
      } 
      // Altres errors
      else {
        errorMessage.value = data.message || 'Error en iniciar sessió. Torna-ho a provar més tard.';
      }
      return;
    }
    
    // Inicialització correcta - Guardem al store d'autenticació
    
    // Verificamos que la respuesta contenga la información del usuario
    if (data.token) {
      // Si el usuario no viene en la respuesta, creamos un objeto básico
      const userData = data.user || {
        email: formData.email,
        // Podemos añadir más datos básicos si es necesario
      };
      
      // Guardar en el store
      authStore.setAuth(data.token, userData);
      
      // Activamos el estado de éxito para mostrar la animación
      loginSuccess.value = true;
      
      // Comprobamos si hay una compra pendiente
      const pendingPurchase = localStorage.getItem('pendingPurchase');
      
      // Esperamos un poco para que se vea la animación antes de redirigir
      setTimeout(() => {
        if (pendingPurchase) {
          // Si hay una compra pendiente, extraemos el ID de la sesión
          const { sessionId } = JSON.parse(pendingPurchase);
          
          // Redirigimos a la página de la sesión
          router.push(`/sessions/${sessionId}`);
        } else {
          // Si no hay compra pendiente, redireccionamos a la página de perfil
          router.push('/perfil');
        }
      }, 2000); // 2 segundos para la animación
    } else {
      errorMessage.value = 'Error al processar la resposta del servidor. No s\'ha pogut obtenir el token.';
    }
    
  } catch (error) {
    console.error('Error en la connexió amb l\'API:', error);
    errorMessage.value = 'Error de connexió amb el servidor. Si us plau, comprova la teva connexió i torna-ho a provar.';
  } finally {
    isLoading.value = false;
  }
}
</script>

<style scoped>
/* Animaciones personalizadas */
.animate-fadeIn {
  animation: fadeIn 0.5s ease-in-out;
}

.animate-bounce-once {
  animation: bounceOnce 1s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes bounceOnce {
  0%, 20%, 50%, 80%, 100% {
    transform: translateY(0);
  }
  40% {
    transform: translateY(-10px);
  }
  60% {
    transform: translateY(-5px);
  }
}
</style>