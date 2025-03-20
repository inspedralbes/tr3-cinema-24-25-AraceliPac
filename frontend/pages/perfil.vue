<template>
  <div class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar importado directamente -->
    <NavBar />
    
    <!-- Contenido principal con padding adecuado -->
    <main class="flex-grow w-full py-6">
      <div class="w-full max-w-6xl mx-auto px-4">
        <!-- Título de sección -->
        <div class="bg-[#800040] rounded-lg shadow-md mb-6">
          <h1 class="text-center text-white py-4 text-xl font-bold">El Meu Perfil</h1>
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
                    Has d'iniciar sessió o registrar-te a la nostra pàgina web per accedir al teu perfil d'usuari.
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
        
        <!-- Contenido autenticado -->
        <template v-else>
          <!-- NAVEGACIÓN SUPERIOR PARA TODAS LAS PANTALLAS -->
          <div class="bg-white rounded-lg shadow-md p-2 mb-6">
            <div class="flex flex-wrap justify-center gap-2">
              <a href="/perfil" 
                 class="px-4 py-2 rounded-md flex items-center text-[#800040] font-bold bg-[#D4AF37]/10 border-b-2 border-[#D4AF37]">
                <ClientOnly>
                  <Icon name="mdi:account" class="w-5 h-5 mr-2 text-[#D4AF37]" />
                </ClientOnly>
                <span>Informació</span>
              </a>
              <a href="/usuari/historial" 
                 class="px-4 py-2 rounded-md flex items-center text-[#800040] hover:bg-[#D4AF37]/10">
                <ClientOnly>
                  <Icon name="mdi:history" class="w-5 h-5 mr-2 text-[#D4AF37]" />
                </ClientOnly>
                <span>Historial</span>
              </a>
              <!-- <a href="/usuari/favorits" 
                 class="px-4 py-2 rounded-md flex items-center text-[#800040] hover:bg-[#D4AF37]/10">
                <ClientOnly>
                  <Icon name="mdi:heart" class="w-5 h-5 mr-2 text-[#D4AF37]" />
                </ClientOnly>
                <span>Favorits</span>
              </a> -->
              <a href="/usuari/ajustos" 
                 class="px-4 py-2 rounded-md flex items-center text-[#800040] hover:bg-[#D4AF37]/10">
                <ClientOnly>
                  <Icon name="mdi:cog" class="w-5 h-5 mr-2 text-[#D4AF37]" />
                </ClientOnly>
                <span>Configuració</span>
              </a>
            </div>
          </div>
          
          <!-- CONTENIDO PRINCIPAL: Layout para escritorio y móvil -->
          <div class="w-full">
            <!-- CONTENIDO: Panel principal -->
            <div class="w-full">
              <usuario-informacio v-if="userData" :user-data="userData" />
            </div>
          </div>
        </template>
      </div>
    </main>
    
    <!-- Footer importado directamente -->
    <Footer />
  </div>
</template>

<script setup>
// Importar componentes y librerías
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import usuarioInformacio from '@/components/usuari/Informacio.vue'

// Inicializar store y router
const authStore = useAuthStore()
const router = useRouter()

// Estado local
const userData = ref(null)

// Computa si el usuario está autenticado
const isAuthenticated = computed(() => {
  return authStore.isAuthenticated
})

// Función para ir a iniciar sesión
const anarAIniciarSessio = () => {
  router.push('/usuari/iniciSessio')
}

// Función para cerrar sesión
const tancarSessio = async () => {
  try {
    await authStore.logout()
    // El store ya maneja la redirección
  } catch (error) {
    console.error('Error al cerrar sesión:', error)
    // Si hay un error, intentamos limpiar manualmente y redirigir
    authStore.token = null
    authStore.user = null
    authStore.isAuthenticated = false
    if (typeof localStorage !== 'undefined') {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
    router.push('/usuari/iniciSessio')
  }
}

// Cargar datos del usuario al montar el componente
onMounted(async () => {
  // Si el usuario no está autenticado, no hacemos nada
  if (!authStore.isAuthenticated) {
    return
  }
  
  // Si ya tenemos datos del usuario en el store, los usamos
  if (authStore.user) {
    userData.value = { ...authStore.user }
    return
  }
  
  // Intentamos obtener los datos del localStorage
  if (typeof localStorage !== 'undefined' && localStorage.getItem('user')) {
    try {
      const localUser = JSON.parse(localStorage.getItem('user'))
      if (localUser) {
        userData.value = localUser
        return
      }
    } catch (e) {
      console.error('Error al parsear datos de usuario del localStorage:', e)
    }
  }
  
  // Si no tenemos datos, los obtenemos de la API
  try {
    const response = await fetch('http://cinema.daw.inspedralbes.cat/tr3-cinema-24-25-AraceliPac/backend/public/api/user', {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Accept': 'application/json'
      }
    })
    
    if (response.ok) {
      const data = await response.json()
      userData.value = data
      
      // Actualizar el store y localStorage
      authStore.user = data
      if (typeof localStorage !== 'undefined') {
        localStorage.setItem('user', JSON.stringify(data))
      }
    } else {
      // Si la petición falla, podría ser un problema con el token
      console.error('Error al obtener datos del usuario:', response.status)
      // Verificar si es un error de autenticación
      if (response.status === 401) {
        authStore.logout()
      }
    }
  } catch (error) {
    console.error('Error de conexión al obtener datos del usuario:', error)
  }
})
</script>