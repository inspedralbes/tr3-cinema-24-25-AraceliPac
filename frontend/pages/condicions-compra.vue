<template>
  <div class="min-h-screen flex flex-col">
    <NavBar />

    <!-- Hero Section -->
    <div class="hero-section">
      <div class="container mx-auto px-4 py-10 md:py-16">
        <h1 class="text-3xl md:text-5xl font-bold text-white text-center mb-4 hero-title">
          Condicions de Compra
        </h1>
        <p class="text-white text-center max-w-2xl mx-auto text-lg">
          Informació sobre els termes aplicables a la compra d'entrades
        </p>
      </div>
    </div>

    <!-- Contingut Principal con mayor separación del hero -->
    <div class="container mx-auto px-4 py-16 md:py-20 flex-grow">
      <div class="flex flex-col lg:flex-row gap-10">
        <!-- Contenido principal -->
        <div class="bg-white rounded-lg shadow-md p-8 md:p-10 mb-10 lg:mb-0 lg:w-2/3">
          <!-- Introducción -->
          <div class="prose prose-lg max-w-none mb-16 text-center">
            <p class="mb-4">Les presents condicions regulen l'adquisició d'entrades a través de la web i l'app de Cinema Pedralbes.</p>
            <p>La compra d'entrades suposa l'acceptació d'aquestes condicions generals.</p>
          </div>

          <!-- Seccions de Condicions con mayor espacio -->
          <div class="space-y-16">
            <!-- Procés de Compra -->
            <div class="p-8 border-l-4 border-[#800040] bg-gray-50 rounded-r-lg condition-card">
              <h2 class="text-xl md:text-2xl font-semibold text-[#800040] mb-5 pl-2">
                1. Procés de Compra
              </h2>
              <div class="text-gray-700 space-y-3 pl-2">
                <p>
                  Seleccioni la pel·lícula, sessió i butaques. Rebrà les entrades amb codi QR per
                  email, que haurà de presentar a l'entrada.
                </p>
                <p class="font-medium">
                  Verifiqui que les dades són correctes abans de finalitzar el procés.
                </p>
              </div>
            </div>

            <!-- Restriccions d'accés i comportament -->
            <div class="p-8 border-l-4 border-[#D4AF37] bg-gray-50 rounded-r-lg condition-card">
              <h2 class="text-xl md:text-2xl font-semibold text-[#800040] mb-5 pl-2">
                2. Restriccions d'Accés
              </h2>
              <div class="text-gray-700 space-y-3 pl-2">
                <p>
                  Cinema Pedralbes es reserva el dret d'admissió. No s'admet l'entrada 15 minuts
                  després de l'inici, està prohibit fumar i utilitzar dispositius durant la
                  projecció. No es permeten aliments o begudes externes.
                </p>
              </div>
            </div>

            <!-- Canvis, devolucions i cancel·lacions -->
            <div class="p-8 border-l-4 border-[#800040] bg-gray-50 rounded-r-lg condition-card">
              <h2 class="text-xl md:text-2xl font-semibold text-[#800040] mb-5 pl-2">
                3. Canvis i Devolucions
              </h2>
              <div class="text-gray-700 space-y-3 pl-2">
                <p>
                  No s'admeten canvis ni devolucions excepte per cancel·lació de la projecció,
                  problemes tècnics o error d'emissió imputable al cinema.
                </p>
                <p>
                  La sol·licitud ha de realitzar-se dins de les 24 hores següents a la incidència.
                </p>

                <div class="bg-[#FEF3C7] text-[#92400E] p-4 rounded-md mt-4 ml-2">
                  <p class="flex items-center text-sm">
                    <Icon name="mdi:alert-circle" class="mr-2 flex-shrink-0" />
                    No es fan devolucions per errors en la selecció o causes de força major alienes
                    al cinema.
                  </p>
                </div>
              </div>
            </div>

            <!-- Preus, recàrrecs i descomptes -->
            <div class="p-8 border-l-4 border-[#D4AF37] bg-gray-50 rounded-r-lg condition-card">
              <h2 class="text-xl md:text-2xl font-semibold text-[#800040] mb-5 pl-2">
                4. Preus i Descomptes
              </h2>
              <div class="text-gray-700 space-y-3 pl-2">
                <p>
                  Els preus inclouen IVA i taxa de gestió (0,50€). Per aplicar descomptes (carnet
                  jove, estudiants, etc.), s'ha de presentar l'acreditació corresponent.
                </p>
              </div>
            </div>

            <!-- Butaques i aforament -->
            <div class="p-8 border-l-4 border-[#800040] bg-gray-50 rounded-r-lg condition-card">
              <h2 class="text-xl md:text-2xl font-semibold text-[#800040] mb-5 pl-2">5. Butaques</h2>
              <div class="text-gray-700 space-y-3 pl-2">
                <p>
                  L'espectador ha d'ocupar la butaca assignada. El cinema pot reconfigurar
                  l'aforament per motius tècnics, oferint alternatives o reemborsament.
                </p>
              </div>
            </div>

            <!-- Menors d'edat -->
            <div
              class="p-8 border-l-4 border-[#D4AF37] bg-gray-50 rounded-r-lg condition-card"
            >
              <h2 class="text-xl md:text-2xl font-semibold text-[#800040] mb-5 pl-2">
                6. Menors d'Edat
              </h2>
              <div class="text-gray-700 space-y-3 pl-2">
                <p>
                  Els menors de 14 anys han d'anar acompanyats d'un adult. Es respecten les
                  qualificacions per edats i es pot sol·licitar document acreditatiu.
                </p>
              </div>
            </div>
          </div>

          <!-- Data Actualització -->
          <div class="mt-16 mb-8 text-center text-gray-500 text-sm">
            <p>Última actualització: Març 2025</p>
          </div>
        </div>
      </div>
    </div>

    <Footer />
  </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
</script>

<style scoped>
:root {
  --primary-color: #800040;
  --accent-color: #d4af37;
}

.hero-title {
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}

.condition-card {
  transition: all 0.3s ease;
}

.condition-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.hero-section {
  background: linear-gradient(rgba(128, 0, 64, 0.5), rgba(128, 0, 64, 0.6)),
    url("/images/instalacions/cine2.jpg") center/cover no-repeat;
  padding: 3rem 0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  position: relative;
}

.hero-section::after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: #d4af37;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.container {
  animation: fadeIn 0.5s ease-out;
}
</style>