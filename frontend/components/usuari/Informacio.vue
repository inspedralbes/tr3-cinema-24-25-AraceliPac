<template>
    <div class="bg-white rounded-lg shadow p-4">
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
            <img :src="usuari.image" alt="Foto de perfil" 
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
              {{ usuari.phone }}
            </p>
            <p class="text-gray-600 mt-1 flex items-center justify-center md:justify-start">
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
              <div class="col-span-2">{{ usuari.last_name }}</div>
            </div>
            <div class="py-3 grid grid-cols-3">
              <div class="font-semibold col-span-1">Correu:</div>
              <div class="col-span-2 break-all">{{ usuari.email }}</div>
            </div>
            <div class="py-3 grid grid-cols-3">
              <div class="font-semibold col-span-1">Telèfon:</div>
              <div class="col-span-2">{{ usuari.phone }}</div>
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
  
  const usuari = ref({})
  const isLoading = ref(true)
  const error = ref(null)
  
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
  onMounted(async () => {
    try {
      const resposta = await fetch('http://localhost:8000/api/users/1')
      
      if (!resposta.ok) {
        throw new Error(`Error de servidor: ${resposta.status}`)
      }
      
      const dades = await resposta.json()
      usuari.value = dades
    } catch (err) {
      error.value = `No s'ha pogut carregar la informació de l'usuari: ${err.message}`
      console.error('Error en carregar les dades de l\'usuari:', err)
    } finally {
      isLoading.value = false
    }
  })
  </script>