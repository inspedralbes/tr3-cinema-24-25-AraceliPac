<template>
    <div class="bg-gray-50 min-h-screen">
      <NavBar />
      
      <!-- Capçalera amb imatge -->
      <div class="relative">
        <img 
          src="/public/images/serveis/serveis1.jpg" 
          alt="Serveis de Cinema Pedralbes" 
          class="w-full h-32 sm:h-48 md:h-56 object-cover object-center"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
          <div class="container mx-auto px-4 pb-4 md:pb-8">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white">Preus i Productes</h1>
            <p class="text-sm sm:text-base md:text-xl text-white/80 mt-1 md:mt-2">Descobreix totes les nostres opcions i tarifes</p>
          </div>
        </div>
      </div>
      
      <div class="container mx-auto px-4 py-6 md:py-12">
        <div class="max-w-5xl mx-auto">
          <!-- Introducció -->
          <div class="text-center mb-8 md:mb-12">
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-[#800040] mb-2 md:mb-4">Els millors preus per a la millor experiència</h2>
            <p class="text-sm sm:text-base text-gray-600 max-w-3xl mx-auto">A Cinema Pedralbes et oferim els millors preus per gaudir del cinema amb la millor qualitat. Descobreix les nostres tarifes i els nostres combos més populars.</p>
          </div>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-10">
            <!-- Secció d'Entrades -->
            <Productes 
              titol="Tipus d'Entrades i Preus" 
              tipus="entrades"
              descripcio="Escull entre les nostres diferents opcions de localitats i aprofita els nostres descomptes."
              icona="mdi:ticket"
              :productes="entrades"
            />
            
            <!-- Secció de Combos -->
            <Productes 
              titol="Combos i Menús" 
              tipus="combos"
              descripcio="Completa la teva experiència cinematogràfica amb els nostres combos."
              icona="mdi:food"
              :productes="combos"
            />
          </div>
          
          <!-- Banner promocional -->
          <div class="mt-8 md:mt-16 bg-[#800040]/10 rounded-xl p-4 md:p-8 text-center">
            <h3 class="text-xl md:text-2xl font-bold text-[#800040] mb-2 md:mb-3">Oferta especial!</h3>
            <p class="text-sm sm:text-base text-gray-700 mb-4 md:mb-6">Compra les teves entrades online i aconsegueix un 5% de descompte en el teu menú a la barra.</p>
            <button class="bg-[#D4AF37] text-white px-4 py-2 md:px-6 md:py-3 rounded-lg font-medium hover:bg-[#800040] transition-colors text-sm md:text-base">
              <Icon name="mdi:shopping-cart" class="inline-block mr-1" /> Comprar entrades
            </button>
          </div>
        </div>
      </div>
      
      <Footer />
    </div>
  </template>
  
  <script setup>
  import Productes from '@/components/Productes.vue';
  import { ref } from 'vue';
  
  // Dades d'entrades
  const entrades = ref([
    {
      id: 1,
      nom: "Normal",
      descripcio: "Entrada estàndard per a totes les sessions",
      preu: 6,
      destacat: false
    },
    {
      id: 2,
      nom: "VIP (fila 6)",
      descripcio: "Millors localitats amb més espai i visibilitat",
      preu: 8,
      destacat: true
    },
    {
      id: 3,
      nom: "Dia de l'espectador (Normal)",
      descripcio: "Dimecres no festius",
      preu: 4,
      destacat: false
    },
    {
      id: 4,
      nom: "Dia de l'espectador (VIP)",
      descripcio: "Dimecres no festius, fila 6",
      preu: 6,
      destacat: false
    }
  ]);
  
  // Dades de combos
  const combos = ref([
    {
      id: 1,
      nom: "Menú Cine (CocaCola)",
      descripcio: "Crispetes (225 gr) + Refresc (75 cl)",
      preu: 6.90,
      destacat: true
    },
    {
      id: 2,
      nom: "Menú Duo (CocaCola)",
      descripcio: "Crispetes (350 gr) + 2 Begudes (100 cl)",
      preu: 9.80,
      destacat: true
    },
    {
      id: 3,
      nom: "Menú Infantil (CocaCola)",
      descripcio: "Crispetes (90 gr) + beguda petita (40 cl)",
      preu: 5.10,
      destacat: false
    }
  ]);
  </script>