<template>
  <div class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <NavBar />

    <!-- Hero Section -->
    <div class="hero-section mt-0">
      <div class="container mx-auto px-4 py-12 md:py-20">
        <h1 class="text-3xl md:text-5xl font-bold text-white text-center mb-4">Contacte</h1>
        <p class="text-white text-center max-w-2xl mx-auto text-lg">
          Estem aquí per ajudar-te amb qualsevol dubte o consulta. No dubtis en posar-te en contacte
          amb nosaltres.
        </p>
      </div>
    </div>

    <!-- Contingut Principal - Estructura en grid 2x2 -->
    <div class="container mx-auto px-4 py-12 md:py-16 flex-grow">
      <!-- Grid principal 2x2 -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Fila 1, Columna 1: Informació de Contacte -->
        <div
          class="bg-white rounded-lg shadow-md p-8 border-t-4 border-[#D4AF37] transform hover:translate-y-[-5px] transition-transform duration-300"
        >
          <h2
            class="text-2xl md:text-3xl font-semibold text-[#800040] mb-8 pb-4 border-b border-gray-200"
          >
            Informació de Contacte
          </h2>

          <div class="space-y-8">
            <div class="flex items-start">
              <div class="mr-4 text-[#D4AF37]">
                <Icon name="mdi:email" class="text-3xl" />
              </div>
              <div>
                <h3 class="font-medium text-gray-900 text-lg">Correu Electrònic</h3>
                <p class="mt-1">
                  <a
                    href="mailto:info@cinemapedralbes.cat"
                    class="text-[#800040] hover:text-[#D4AF37] transition-colors"
                  >
                    info@cinemapedralbes.cat
                  </a>
                </p>
              </div>
            </div>

            <div class="flex items-start">
              <div class="mr-4 text-[#D4AF37]">
                <Icon name="mdi:map-marker" class="text-3xl" />
              </div>
              <div>
                <h3 class="font-medium text-gray-900 text-lg">Direcció</h3>
                <p class="mt-1">Av. d'Esplugues, 400, Les Corts, 08034 Barcelona</p>
                <p class="mt-1">Província: Barcelona</p>
              </div>
            </div>

            <div class="flex items-start">
              <div class="mr-4 text-[#D4AF37]">
                <Icon name="mdi:phone" class="text-3xl" />
              </div>
              <div>
                <h3 class="font-medium text-gray-900 text-lg">Telèfon</h3>
                <p class="mt-1">
                  <a
                    href="tel:932033332"
                    class="text-[#800040] hover:text-[#D4AF37] transition-colors"
                  >
                    911 11 11 31
                  </a>
                </p>
              </div>
            </div>

            <div class="flex items-start">
              <div class="mr-4 text-[#D4AF37]">
                <Icon name="mdi:clock-outline" class="text-3xl" />
              </div>
              <div>
                <h3 class="font-medium text-gray-900 text-lg">Horari d'Atenció</h3>
                <p class="mt-1">Dilluns a Divendres: 10:00h - 22:00h</p>
                <p>Caps de setmana i festius: 10:00h - 00:00h</p>
              </div>
            </div>
          </div>

          <!-- Xarxes Socials -->
          <div class="mt-10 pt-6 border-t border-gray-200">
            <h3 class="font-medium text-gray-900 text-lg mb-4">Segueix-nos</h3>
            <div class="flex space-x-6">
              <a href="#" class="text-[#D4AF37] hover:text-[#800040] transition-colors">
                <Icon name="mdi:facebook" class="text-3xl" />
              </a>
              <a href="#" class="text-[#D4AF37] hover:text-[#800040] transition-colors">
                <Icon name="mdi:instagram" class="text-3xl" />
              </a>
              <a href="#" class="text-[#D4AF37] hover:text-[#800040] transition-colors">
                <Icon name="mdi:twitter" class="text-3xl" />
              </a>
            </div>
          </div>
        </div>

        <!-- Fila 1, Columna 2: Formulari de Contacte -->
        <div
          class="bg-white rounded-lg shadow-md p-8 border-t-4 border-[#800040] transform hover:translate-y-[-5px] transition-transform duration-300"
        >
          <h2
            class="text-2xl md:text-3xl font-semibold text-[#800040] mb-8 pb-4 border-b border-gray-200"
          >
            Envia'ns un Missatge
          </h2>

          <form @submit.prevent="enviarFormulari" class="space-y-6">
            <div>
              <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                Nom i Cognoms *
              </label>
              <input
                type="text"
                id="nom"
                v-model="formulari.nom"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#800040] focus:ring focus:ring-[#800040] focus:ring-opacity-50"
                required
              />
            </div>

            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Correu Electrònic *
              </label>
              <input
                type="email"
                id="email"
                v-model="formulari.email"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#800040] focus:ring focus:ring-[#800040] focus:ring-opacity-50"
                required
              />
            </div>

            <div>
              <label for="telefon" class="block text-sm font-medium text-gray-700 mb-1">
                Telèfon
              </label>
              <input
                type="tel"
                id="telefon"
                v-model="formulari.telefon"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#800040] focus:ring focus:ring-[#800040] focus:ring-opacity-50"
              />
            </div>

            <div>
              <label for="assumpte" class="block text-sm font-medium text-gray-700 mb-1">
                Assumpte *
              </label>
              <input
                type="text"
                id="assumpte"
                v-model="formulari.assumpte"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#800040] focus:ring focus:ring-[#800040] focus:ring-opacity-50"
                required
              />
            </div>

            <div>
              <label for="missatge" class="block text-sm font-medium text-gray-700 mb-1">
                Missatge *
              </label>
              <textarea
                id="missatge"
                v-model="formulari.missatge"
                rows="5"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#800040] focus:ring focus:ring-[#800040] focus:ring-opacity-50"
                required
              ></textarea>
            </div>

            <div class="flex items-start">
              <div class="flex items-center h-5">
                <input
                  id="privacitat"
                  type="checkbox"
                  v-model="formulari.privacitat"
                  class="h-4 w-4 text-[#800040] border-gray-300 rounded focus:ring-[#800040]"
                  required
                />
              </div>
              <div class="ml-3 text-sm">
                <label for="privacitat" class="font-medium text-gray-700">
                  Accepto la
                  <a href="/privacitat" class="text-[#800040] hover:text-[#D4AF37]">
                    política de privacitat
                  </a>
                  *
                </label>
              </div>
            </div>

            <div>
              <button
                type="submit"
                class="w-full px-6 py-3 rounded-md bg-[#800040] text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#800040] transition-colors text-lg font-medium shadow-md"
                :disabled="enviament.carregant"
              >
                <div v-if="enviament.carregant" class="flex items-center justify-center">
                  <div
                    class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full mr-2"
                  ></div>
                  Enviant...
                </div>
                <span v-else class="flex items-center justify-center">
                  <Icon name="mdi:send" class="mr-2" />
                  Enviar Missatge
                </span>
              </button>
            </div>

            <div
              v-if="enviament.missatge"
              class="mt-4 p-4 rounded-md"
              :class="enviament.error ? 'bg-red-50 text-red-800' : 'bg-green-50 text-green-800'"
            >
              {{ enviament.missatge }}
            </div>
          </form>
        </div>

        <!-- Fila 2, Columna 1: Sobre Nosaltres -->
        <div
          class="bg-white rounded-lg shadow-md p-8 border-l-4 border-[#D4AF37] transform hover:translate-y-[-5px] transition-transform duration-300"
        >
          <h2
            class="text-2xl md:text-3xl font-semibold text-[#800040] mb-8 pb-4 border-b border-gray-200"
          >
            Sobre Cinema Pedralbes
          </h2>

          <div class="prose max-w-none text-lg">
            <p>
              Cinema Pedralbes és un referent cultural a Catalunya des de fa més de 30 anys. Situat al
              cor de Les Corts, el nostre cinema combina l'elegància d'una sala clàssica amb les
              tecnologies més avançades per oferir la millor experiència cinematogràfica.
            </p>

            <p>
              El nostre compromís amb el cinema de qualitat ens ha portat a ser un dels espais més
              reconeguts per la projecció tant de grans estrenes internacionals com de pel·lícules del
              cinema independent i català.
            </p>

            <p>
              Disposem de 5 sales equipades amb la darrera tecnologia en so i imatge, butaques d'alta
              comoditat i una acurada selecció gastronòmica al nostre bar. A més, tenim espais
              adaptats per a persones amb mobilitat reduïda i sistemes d'audiodescripció.
            </p>

            <p>
              A Cinema Pedralbes no només oferim projeccions, sinó que també organitzem esdeveniments
              especials, cicles temàtics, col·loquis amb directors i actors, i activitats per a
              escoles i famílies. El nostre objectiu és apropar el cinema de qualitat a tots els
              públics i ser un espai de trobada per als amants del setè art.
            </p>
          </div>
        </div>

        <!-- Fila 2, Columna 2: Mapa -->
        <div
          class="bg-white rounded-lg shadow-md overflow-hidden border-b-4 border-[#800040] transform hover:translate-y-[-5px] transition-transform duration-300"
        >
          <h2
            class="text-2xl md:text-3xl font-semibold text-[#800040] p-8 pb-4 border-b border-gray-200"
          >
            Com Arribar
          </h2>
          <div class="aspect-w-16 aspect-h-9">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2993.099539309797!2d2.1111373!3d41.3871251!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12a498503bd07693%3A0x8cf6ba7c7300b55f!2sAv.%20d&#39;Esplugues%2C%2040%2C%2008034%20Barcelona!5e0!3m2!1ses!2ses!4v1653345678901!5m2!1ses!2ses"
              width="100%"
              height="450"
              style="border: 0"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>
        </div>
      </div>
    </div>

    <Footer />
  </div>
</template>

<script setup>
import { ref, onMounted, defineAsyncComponent } from "vue";

// Estat del formulari
const formulari = ref({
  nom: "",
  email: "",
  telefon: "",
  assumpte: "",
  missatge: "",
  privacitat: false,
});

// Estat d'enviament
const enviament = ref({
  carregant: false,
  error: false,
  missatge: "",
});

// Comprovar si els components de Navbar i Footer s'han carregat correctament
onMounted(() => {
  // console.log("Pàgina de contacte carregada");
});

// Funció per enviar el formulari
const enviarFormulari = async () => {
  // Aquí aniria la lògica real d'enviament de formulari

  enviament.value.carregant = true;
  enviament.value.missatge = "";

  try {
    // Simulació d'una crida a API
    await new Promise((resolve) => setTimeout(resolve, 1500));

    // Resposta exitosa
    enviament.value.error = false;
    enviament.value.missatge =
      "El teu missatge ha estat enviat correctament. Et respondrem el més aviat possible.";

    // Reiniciar el formulari
    formulari.value = {
      nom: "",
      email: "",
      telefon: "",
      assumpte: "",
      missatge: "",
      privacitat: false,
    };
  } catch (error) {
    // Gestió d'errors
    enviament.value.error = true;
    enviament.value.missatge =
      "Hi ha hagut un error enviant el missatge. Si us plau, intenta-ho de nou més tard.";
  } finally {
    enviament.value.carregant = false;
  }
};
</script>

<style scoped>
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

/* Aspect ratio polyfill per al mapa */
.aspect-w-16 {
  position: relative;
  padding-bottom: 56.25%; /* 16:9 */
  height: 0;
}

.aspect-w-16 iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

/* Millores en els inputs del formulari */
input,
textarea {
  transition: all 0.3s ease;
}

input:focus,
textarea:focus {
  border-color: #800040 !important;
  box-shadow: 0 0 0 2px rgba(128, 0, 64, 0.2) !important;
}

/* Animacions subtils */
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

/* Asegurar que todos los cuadrantes del grid tengan la misma altura */
@media (min-width: 768px) {
  .grid > div {
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .grid > div > .prose {
    flex-grow: 1;
  }

  /* Asegurar que el grid sea 2x2 en escritorio */
  .grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(2, auto);
    gap: 2rem;
  }

  /* Primera fila: Informació de Contacte y Envia'ns un Missatge */
  .grid > div:nth-child(1),
  .grid > div:nth-child(2) {
    grid-row: 1;
  }

  /* Segunda fila: Sobre Cinema Pedralbes y Com Arribar-Mapa */
  .grid > div:nth-child(3),
  .grid > div:nth-child(4) {
    grid-row: 2;
  }
}
</style>