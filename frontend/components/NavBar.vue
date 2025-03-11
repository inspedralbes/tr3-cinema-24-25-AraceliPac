<template>
  <nav class="bg-[#800040] shadow-md w-full">
    <div class="container mx-auto px-4">
      <div class="flex justify-between items-center py-3">
        <!-- Logo y título como enlaces a la ruta principal -->
        <div class="flex items-center">
          <NuxtLink to="/" class="flex items-center">
            <nuxt-img 
              src="../public/images/cine.svg" 
              alt="Cine Pedralbes" 
              class="h-20 md:h-24"
            />
            <h1 class="ml-2 text-[#D4AF37] text-xxxl md:text-3xl hidden sm:block retro-text">Cine Pedralbes</h1>
          </NuxtLink>
        </div>

        <!-- Navegació mòbil (hamburger) -->
        <div class="md:hidden">
          <button 
            @click="toggleMenu" 
            class="text-white focus:outline-none"
            aria-label="Menú principal"
          >
            <svg v-if="!isMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Navegació escriptori -->
        <ul class="hidden md:flex space-x-8">
          <li v-for="(item, index) in navItems" :key="index">
            <NuxtLink 
              :to="item.path" 
              class="text-white hover:text-[#D4AF37] transition-colors duration-200"
              :class="{ 'text-[#D4AF37]': isActive(item.path) }"
            >
              {{ item.name }}
            </NuxtLink>
          </li>
          <li>
            <NuxtLink 
              to="/perfil" 
              class="text-white hover:text-[#D4AF37] transition-colors duration-200 flex items-center"
              :class="{ 'text-[#D4AF37]': isActive('/perfil') }"
            >
              <span>Perfil</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </NuxtLink>
          </li>
        </ul>
      </div>

      <!-- Menú mòbil desplegable -->
      <div 
        v-if="isMenuOpen" 
        class="md:hidden"
      >
        <ul class="flex flex-col space-y-4 py-4">
          <li v-for="(item, index) in navItems" :key="index">
            <NuxtLink 
              :to="item.path" 
              class="text-white hover:text-[#D4AF37] transition-colors duration-200 py-2 block border-b border-[#D4AF37]/20"
              :class="{ 'text-[#D4AF37]': isActive(item.path) }"
              @click="closeMenu"
            >
              {{ item.name }}
            </NuxtLink>
          </li>
          <li>
            <NuxtLink 
              to="/perfil" 
              class="text-white hover:text-[#D4AF37] transition-colors duration-200 py-2 flex items-center"
              :class="{ 'text-[#D4AF37]': isActive('/perfil') }"
              @click="closeMenu"
            >
              <span>Perfil</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </NuxtLink>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const isMenuOpen = ref(false)

const navItems = [
  { name: 'Inici', path: '/home' },
  { name: 'Cartelera', path: '/pelicules/cartelera' },
  { name: 'Sessions', path: '/sessions' },
  { name: 'Preus', path: '/preus' }
]

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const closeMenu = () => {
  isMenuOpen.value = false
}

const isActive = (path) => {
  return route.path === path
}
</script>

<style scoped>
/* Importació de fonts Google que s'assemblin a l'estil Groovy */
@import url('https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');

.retro-text {
  font-family: 'Pacifico', 'Abril Fatface', cursive;
  letter-spacing: 0.05em;
  text-shadow: 
    3px 3px 0 #000,
    -1px -1px 0 #000,
    1px -1px 0 #000,
    -1px 1px 0 #000,
    0px 0px 8px rgba(212, 175, 55, 0.7);
  font-weight: 400;
  transform: perspective(500px) rotateX(5deg) skewX(-5deg);
  display: inline-block;
  position: relative;
  padding: 0 5px;
}

/* Defineix l'animació de brillantor per a l'efecte groovy */
@keyframes groovy-glow {
  0% {
    text-shadow: 
      3px 3px 0 #000,
      -1px -1px 0 #000,
      1px -1px 0 #000,
      -1px 1px 0 #000,
      0px 0px 8px rgba(212, 175, 55, 0.7);
  }
  50% {
    text-shadow: 
      3px 3px 0 #000,
      -1px -1px 0 #000,
      1px -1px 0 #000,
      -1px 1px 0 #000,
      0px 0px 15px rgba(212, 175, 55, 1),
      0px 0px 25px rgba(212, 175, 55, 0.5);
  }
  100% {
    text-shadow: 
      3px 3px 0 #000,
      -1px -1px 0 #000,
      1px -1px 0 #000,
      -1px 1px 0 #000,
      0px 0px 8px rgba(212, 175, 55, 0.7);
  }
}

.retro-text {
  animation: groovy-glow 3s ease-in-out infinite;
}

/* Afegeix un petit efecte de subratllat ondulat groovy */
.retro-text::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -2px;
  width: 100%;
  height: 3px;
  background: repeating-linear-gradient(
    45deg,
    #D4AF37,
    #D4AF37 10px,
    transparent 10px,
    transparent 20px
  );
  border-radius: 2px;
  opacity: 0.7;
}
</style>