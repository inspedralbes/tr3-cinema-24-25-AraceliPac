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
              <a href="/historial" 
                 class="px-4 py-2 rounded-md flex items-center text-[#800040] hover:bg-[#D4AF37]/10">
                <ClientOnly>
                  <Icon name="mdi:history" class="w-5 h-5 mr-2 text-[#D4AF37]" />
                </ClientOnly>
                <span>Historial</span>
              </a>
              <a href="/favorits" 
                 class="px-4 py-2 rounded-md flex items-center text-[#800040] hover:bg-[#D4AF37]/10">
                <ClientOnly>
                  <Icon name="mdi:heart" class="w-5 h-5 mr-2 text-[#D4AF37]" />
                </ClientOnly>
                <span>Favorits</span>
              </a>
              <a href="/ajustos" 
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
              <usuario-informacio />
            </div>
          </div>
        </div>
      </main>
      
      <!-- Footer importado directamente -->
      <Footer />
    </div>
  </template>
  
  <script setup>
  // Importar componentes explícitamente
  import NavBar from '@/components/NavBar.vue'
  import Footer from '@/components/Footer.vue'
  import usuarioInformacio from '@/components/usuari/Informacio.vue'
  </script>