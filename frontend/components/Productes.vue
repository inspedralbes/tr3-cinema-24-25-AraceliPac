<template>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 h-full">
      <!-- Capçalera del component -->
      <div class="p-4 md:p-6 bg-[#800040] text-white">
        <div class="flex items-center">
          <div class="bg-[#D4AF37] p-2 md:p-3 rounded-full mr-3 md:mr-4">
            <Icon :name="icona" class="text-[#800040] text-lg md:text-xl" />
          </div>
          <div>
            <h3 class="text-lg md:text-xl font-bold">{{ titol }}</h3>
            <p class="text-white/80 text-xs md:text-sm mt-1">{{ descripcio }}</p>
          </div>
        </div>
      </div>
      
      <!-- Llistat de productes -->
      <div class="p-4 md:p-6">
        <ul class="space-y-4">
          <li v-for="producte in productes" :key="producte.id" 
              class="border-b border-gray-100 pb-4 last:border-b-0 last:pb-0"
              :class="{'bg-[#F9F5E3] -mx-4 md:-mx-6 px-4 md:px-6 py-3 md:py-4 rounded-md': producte.destacat}">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
              <div>
                <h4 class="font-bold text-[#800040] flex items-center flex-wrap">
                  {{ producte.nom }}
                  <span v-if="producte.destacat" class="bg-[#D4AF37] text-white text-xs px-2 py-0.5 rounded-full ml-2 mt-1 inline-block">
                    RECOMANAT
                  </span>
                </h4>
                <p class="text-gray-600 text-xs md:text-sm mt-1">{{ producte.descripcio }}</p>
              </div>
              <div :class="[
                'text-base md:text-lg font-bold', 
                tipus === 'entrades' ? 'text-[#800040]' : 'text-[#D4AF37]'
              ]">
                {{ formatPrice(producte.preu) }}€
              </div>
            </div>
          </li>
        </ul>
      </div>
      
      <!-- Peu del component -->
      <div class="p-3 md:p-4 bg-gray-50 border-t border-gray-200">
        <div v-if="tipus === 'entrades'" class="text-center text-xs md:text-sm text-gray-500">
          <p>Els preus poden variar en esdeveniments especials i estrenes.</p>
        </div>
        <div v-else class="text-center text-xs md:text-sm text-gray-500">
          <p>Tots els nostres productes estan elaborats el mateix dia.</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    titol: {
      type: String,
      required: true
    },
    descripcio: {
      type: String,
      default: ''
    },
    tipus: {
      type: String,
      validator: (value) => ['entrades', 'combos'].includes(value),
      default: 'entrades'
    },
    icona: {
      type: String,
      default: 'mdi:ticket'
    },
    productes: {
      type: Array,
      required: true
    }
  });
  
  // Funció per formatejar el preu
  function formatPrice(value) {
    return value.toFixed(2).replace('.', ',');
  }
  </script>