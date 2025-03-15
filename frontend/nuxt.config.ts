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
});
