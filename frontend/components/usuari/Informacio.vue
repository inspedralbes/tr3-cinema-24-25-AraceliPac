<template>
  <div class="bg-white rounded-lg shadow p-4">
    <!-- Animación de cierre de sesión -->
    <div v-if="logoutInProgress" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 animate-logout">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
            <ClientOnly>
              <Icon name="mdi:check-circle" class="h-10 w-10 text-green-600" />
            </ClientOnly>
          </div>
          <h3 class="text-xl font-medium text-gray-900 mb-2">Sessió tancada correctament</h3>
          <p class="text-gray-500 mb-6">
            Gràcies per visitar Cinema Pedralbes. Esperem veure't aviat!
          </p>
          <p class="text-sm text-[#800040] mb-6">
            Aquesta finestra es tancarà automàticament...
          </p>
          <div class="flex justify-center">
            <div class="animate-spin h-8 w-8 border-t-4 border-b-4 border-[#800040] rounded-full"></div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Loading state -->
    <div v-if="isLoading" class="py-8 text-center">
      <div class="inline-block w-8 h-8 border-4 border-[#800040] border-t-transparent rounded-full animate-spin"></div>
      <p class="mt-3 text-gray-600">Carregant informació de l'usuari...</p>
    </div>
    
    <!-- Error state -->
    <div v-else-if="error" class="bg-red-100 border-l-4 border-red-500 p-4 rounded">
      <div class="flex items-center">
        <ClientOnly>
          <Icon name="mdi:alert-circle" class="text-red-500 mr-2" />
        </ClientOnly>
        <span>{{ error }}</span>
      </div>
    </div>
    
    <!-- User profile content -->
    <div v-else class="space-y-6">
      <!-- User card -->
      <div class="flex flex-col items-center md:flex-row md:items-start bg-gray-50 p-4 rounded-lg">
        <div class="mb-4 md:mb-0 md:mr-6">
          <img :src="usuari.image || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(usuari.name + ' ' + usuari.last_name) + '&background=800040&color=fff&size=150'" 
               alt="Foto de perfil" 
               class="w-32 h-32 object-cover rounded-full border-4 border-[#D4AF37]">
        </div>
        <div class="text-center md:text-left">
          <h2 class="text-xl md:text-2xl font-bold text-[#800040]">{{ usuari.name }} {{ usuari.last_name }}</h2>
          <p class="text-gray-600 mt-2 flex items-center justify-center md:justify-start">
            <ClientOnly>
              <Icon name="mdi:email" class="mr-2 text-[#D4AF37]" />
            </ClientOnly>
            {{ usuari.email }}
          </p>
          <p class="text-gray-600 mt-1 flex items-center justify-center md:justify-start">
            <ClientOnly>
              <Icon name="mdi:phone" class="mr-2 text-[#D4AF37]" />
            </ClientOnly>
            {{ usuari.phone || 'No disponible' }}
          </p>
          <p v-if="usuari.created_at" class="text-gray-600 mt-1 flex items-center justify-center md:justify-start">
            <ClientOnly>
              <Icon name="mdi:account-check" class="mr-2 text-[#D4AF37]" />
            </ClientOnly>
            Client des de: {{ formatarData(usuari.created_at) }}
          </p>
        </div>
      </div>

      <!-- Information card -->
      <div class="rounded-lg border overflow-hidden">
        <div class="bg-[#800040] text-white p-3">
          <h3 class="text-lg font-medium">Informació Personal</h3>
        </div>
        <div class="p-4 divide-y">
          <div class="py-3 grid grid-cols-3">
            <div class="font-semibold col-span-1">Nom:</div>
            <div class="col-span-2">{{ usuari.name }}</div>
          </div>
          <div class="py-3 grid grid-cols-3">
            <div class="font-semibold col-span-1">Cognom:</div>
            <div class="col-span-2">{{ usuari.last_name || 'No disponible' }}</div>
          </div>
          <div class="py-3 grid grid-cols-3">
            <div class="font-semibold col-span-1">Correu:</div>
            <div class="col-span-2 break-all">{{ usuari.email }}</div>
          </div>
          <div class="py-3 grid grid-cols-3">
            <div class="font-semibold col-span-1">Telèfon:</div>
            <div class="col-span-2">{{ usuari.phone || 'No disponible' }}</div>
          </div>
          <div class="py-3 grid grid-cols-3">
            <div class="font-semibold col-span-1">Tipus de compte:</div>
            <div class="col-span-2">
              <span class="px-2 py-1 rounded text-white text-sm bg-[#D4AF37]">
                {{ obtindreNomRol(usuari.role_id) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Action buttons -->
      <div class="flex flex-col sm:flex-row justify-end gap-3">
        <button
          @click="tancarSessio"
          class="bg-red-600 text-white px-4 py-2 rounded flex items-center justify-center hover:bg-red-700 transition-colors">
          <ClientOnly>
            <Icon name="mdi:logout" class="mr-2" />
          </ClientOnly>
          Tancar Sessió
        </button>
        <button class="bg-[#800040] text-white px-4 py-2 rounded flex items-center justify-center hover:opacity-90 transition-opacity">
          <ClientOnly>
            <Icon name="mdi:pencil" class="mr-2" />
          </ClientOnly>
          Editar Perfil
        </button>
        <button class="bg-[#D4AF37] text-white px-4 py-2 rounded flex items-center justify-center hover:opacity-90 transition-opacity">
          <ClientOnly>
            <Icon name="mdi:lock-reset" class="mr-2" />
          </ClientOnly>
          Canviar Contrasenya
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const props = defineProps({
  userData: {
    type: Object,
    required: true
  }
})

const authStore = useAuthStore()
const router = useRouter()
const usuari = ref({})
const isLoading = ref(true)
const error = ref(null)
const logoutInProgress = ref(false) // Nueva variable para controlar la animación

// Función para cerrar sesión con animación
const tancarSessio = async () => {
  try {
    // Mostrar confirmación
    const confirmar = confirm('Estàs segur que vols tancar la sessió?');
    
    if (confirmar) {
      // Activar la animación de cierre de sesión
      logoutInProgress.value = true;
      
      // Esperar un tiempo muy extenso para que se aprecie bien la animación
      setTimeout(async () => {
        try {
          await authStore.logout();
          // No redirigimos inmediatamente, dejamos que se vea la animación
          setTimeout(() => {
            // La redirección a login
            router.push('/usuari/iniciSessio');
          }, 5000); // 5 segundos adicionales después del logout para ver bien la animación
        } catch (error) {
          console.error('Error al cerrar sesión:', error);
          // Si hay un error, intentamos limpiar manualmente
          if (typeof localStorage !== 'undefined') {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
          }
          setTimeout(() => {
            router.push('/usuari/iniciSessio');
          }, 5000);
        }
      }, 4000); // 4 segundos para ver la animación antes de cerrar sesión
    }
  } catch (error) {
    console.error('Error al procesar cierre de sesión:', error);
  }
}

// Funció per obtenir el rol de l'usuari
const obtindreNomRol = (roleId) => {
  const rols = {
    1: 'Administrador',
    2: 'Usuari Premium',
    3: 'Usuari Estàndard'
  }
  return rols[roleId] || 'Usuari'
}

// Funció per formatar dates
const formatarData = (dataStr) => {
  if (!dataStr) return 'N/A'
  
  const data = new Date(dataStr)
  return data.toLocaleDateString('ca-ES', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
}

// Carregar dades de l'usuari
onMounted(() => {
  try {
    // Usar los datos proporcionados por las props
    usuari.value = props.userData
    isLoading.value = false
  } catch (err) {
    error.value = `No s'ha pogut processar la informació de l'usuari: ${err.message}`
    console.error('Error en processar les dades de l\'usuari:', err)
    isLoading.value = false
  }
})
</script>

<style scoped>
/* Animación para el mensaje de cierre de sesión */
.animate-logout {
  animation: logoutAnimation 2s ease-out;
}

@keyframes logoutAnimation {
  0% {
    opacity: 0;
    transform: scale(0.8) translateY(-30px);
  }
  40% {
    transform: scale(1.05) translateY(0);
  }
  60% {
    transform: scale(0.95);
  }
  80% {
    transform: scale(1.02);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}
</style>