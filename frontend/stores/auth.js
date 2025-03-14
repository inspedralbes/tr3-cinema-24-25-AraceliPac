import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    token: localStorage.getItem("token") || null,
    user: JSON.parse(localStorage.getItem("user")) || null,
    isAuthenticated: !!localStorage.getItem("token"),
  }),

  actions: {
    // Inicialización del store
    initialize() {
      if (this.token) this.checkAuth();
    },

    // Verificar autenticación
    async checkAuth() {
      try {
        const response = await $fetch('http://localhost:8000/api/user', {
          headers: { Authorization: `Bearer ${this.token}` }
        });

        if (response.user) {
          this.user = response.user; // Guarda course y division
          localStorage.setItem("user", JSON.stringify(response.user));
        }
      } catch (error) {
        this.logout();
      }
    },

    // Establecer autenticación
    setAuth(token, user) {
      this.token = token;
      this.user = user;
      this.isAuthenticated = true;
      localStorage.setItem("token", token);
      localStorage.setItem("user", JSON.stringify(user));
    },

    // Cerrar sesión
    async logout() {
      try {
        if (this.token) {
          await $fetch('http://localhost:8000/api/logout', {
            method: 'POST',
            headers: {
              Authorization: `Bearer ${this.token}`,
              Accept: 'application/json',
            },
          });
        }
      } catch (error) {
        console.error("Error en logout:", error);
      } finally {
        this.token = null;
        this.user = null;
        this.isAuthenticated = false;
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        navigateTo('/login');
      }
    },
  },
});