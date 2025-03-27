import { defineNuxtConfig } from "nuxt/config";

export default defineNuxtConfig({
  modules: [
    "@pinia/nuxt",
    "pinia-plugin-persistedstate/nuxt",
    "@nuxtjs/tailwindcss",
    "@nuxt/image",
    "@nuxt/icon",
  ],
  devtools: { enabled: true },

  // Configuración SSR global
  ssr: true,

  routeRules: {
    "/home": { ssr: true }, //  SSR
    "/**": { ssr: false }, //  (SPA)
  },

  vite: {
    define: {
      global: "globalThis",
    },
    resolve: {
      alias: {
        stream: "stream-browserify",
      },
    },
  },
  plugins: [
    { src: '~/plugins/socket.js', mode: 'client' }
  ],
  // 
  //  Configuración Nitro con la fecha de compatibilidad
  nitro: {
    compatibilityDate: "2025-03-19",
  },
});
