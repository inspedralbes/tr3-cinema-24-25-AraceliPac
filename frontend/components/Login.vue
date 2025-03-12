<template>
  <div class="w-full max-w-xl mx-auto">
    <div class="bg-gradient-to-b from-gray-50 to-gray-100 shadow-2xl rounded-lg border-t-4 border-[#D4AF37] overflow-hidden">
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
      
      <form @submit.prevent="iniciarSessio" class="p-8 space-y-6">
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
                <i class="fas fa-envelope text-[#D4AF37] group-focus-within:text-[#800040] transition-colors"></i>
              </div>
              <input 
                type="email" 
                id="email" 
                v-model="email" 
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
                <i class="fas fa-lock text-[#D4AF37] group-focus-within:text-[#800040] transition-colors"></i>
              </div>
              <input 
                :type="mostrarPassword ? 'text' : 'password'" 
                id="password" 
                v-model="password" 
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
        </div>
        
        <!-- Opcions "Recordar-me" i "Contrasenya oblidada" -->
        <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:items-center sm:justify-between bg-gray-50 p-4 rounded-lg border border-gray-200">
          <div class="flex items-center">
            <input 
              id="remember-me" 
              name="remember-me" 
              type="checkbox" 
              v-model="recordarMe"
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
        
        <!-- Botó Iniciar sessió -->
        <div class="pt-4">
          <button 
            type="submit" 
            :disabled="loading"
            class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-lg shadow-lg text-white bg-gradient-to-r from-[#800040] to-[#9A0040] hover:from-[#9A0040] hover:to-[#B00040] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4AF37] font-bold text-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:scale-[1.02] active:scale-[0.98]"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <i class="fas fa-sign-in-alt mr-2"></i>
            <span>Iniciar sessió</span>
          </button>
        </div>
      </form>
      
      <!-- Secció de registre -->
      <div class="py-6 px-8 bg-gradient-to-r from-[#800040] to-[#f1948a] border-t-2 border-[#800040]">
        <div class="bg-white p-4 rounded-lg shadow-md">
          <p class="text-center text-[#800040] font-medium mb-4">
            Encara no tens compte a Cinema Pedralbes?
          </p>
          <router-link 
            to="/usuari/registre" 
            class="w-full flex justify-center items-center py-3 px-4 border-2 border-[#800040] rounded-lg shadow-md bg-white hover:bg-[#f8f9f9] transition-all transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#800040] font-semibold text-[#800040] text-base"
          >
            <i class="fas fa-user-plus mr-2"></i>
            Registra't ara
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const email = ref('');
const password = ref('');
const recordarMe = ref(false);
const mostrarPassword = ref(false);
const loading = ref(false);
const errors = ref({
  email: '',
  password: ''
});

async function iniciarSessio() {
  // Reiniciem els errors
  errors.value = {
    email: '',
    password: ''
  };
  
  // Activem l'estat de càrrega
  loading.value = true;
  
  try {
    // Crida a l'API
    const response = await loginApi();
    
    // Mostrem un alert de confirmació quan la connexió és exitosa
    alert("Connexió amb l'API correcta! Token rebut: " + response.token);
    
    // Processament de la resposta
    if (response.success) {
      // Guardem el token a localStorage si "recordarMe" està actiu
      if (recordarMe.value) {
        localStorage.setItem('authToken', response.token);
      } else {
        sessionStorage.setItem('authToken', response.token);
      }
      
      // Redireccionem a la pàgina principal o dashboard
      navigateTo('/');
    }
  } catch (error) {
    // Gestionem diferents tipus d'errors
    if (error.response && error.response.status === 422) {
      // Errors de validació
      const validationErrors = error.response.data.errors;
      if (validationErrors.email) errors.value.email = validationErrors.email[0];
      if (validationErrors.password) errors.value.password = validationErrors.password[0];
      
      // Alert per errors de validació
      alert("Error de validació: " + JSON.stringify(validationErrors));
    } else if (error.response && error.response.status === 401) {
      // Error d'autenticació
      errors.value.email = 'Credencials incorrectes';
      
      // Alert per error d'autenticació
      alert("Error d'autenticació: Credencials incorrectes");
    } else {
      // Altres errors
      console.error('Error d\'inici de sessió:', error);
      
      // Alert per altres errors
      alert("Error en la connexió amb l'API: " + (error.message || 'Error desconegut'));
    }
  } finally {
    // Desactivem l'estat de càrrega
    loading.value = false;
  }
}

// Funció per connectar amb l'API real
async function loginApi() {
  try {
    console.log('Intentant connexió amb l\'API...');
    console.log('Dades enviades:', { email: email.value, password: password.value });
    
    const response = await fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value
      })
    });
    
    console.log('Resposta rebuda de l\'API, status:', response.status);
    
    const data = await response.json();
    console.log('Dades rebudes:', data);
    
    if (!response.ok) {
      console.error('Resposta no OK:', response.status, data);
      throw {
        response: {
          status: response.status,
          data: data
        }
      };
    }
    
    return {
      success: true,
      token: data.token,
      user: data.user || { email: email.value }
    };
  } catch (error) {
    console.error('Error al connectar amb l\'API:', error);
    throw error;
  }
}
</script>