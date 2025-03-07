// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-11-01', // Esto es específico de Cloudflare Pages, no necesario para Pinia
  devtools: { enabled: true }, // Habilita las herramientas de desarrollo de Nuxt

  // Añade el módulo de Pinia
  modules: [
    '@pinia/nuxt', // Integra Pinia en Nuxt
  ],
});