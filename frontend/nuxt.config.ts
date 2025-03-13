// https://nuxt.com/docs/api/configuration/nuxt-config
import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  modules: ["@pinia/nuxt", "@nuxtjs/tailwindcss", "@nuxt/image", "@nuxt/icon"],
  devtools: { enabled: true },
  ssr: true,
  vite: {
    define: {
      global: 'globalThis'
    },
    resolve: {
      alias: {
        stream: 'stream-browserify'
      }
    }
  }
});