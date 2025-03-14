import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
  state: () => {
    // Comprobamos si estamos en el navegador antes de acceder a localStorage
    const hasLocalStorage = typeof localStorage !== 'undefined';
    
    return {
      token: hasLocalStorage ? localStorage.getItem("token") || null : null,
      user: hasLocalStorage ? JSON.parse(localStorage.getItem("user") || "null") : null,
      isAuthenticated: hasLocalStorage ? !!localStorage.getItem("token") : false,
    };
  },
  
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
          this.user = response.user;
          // Guardar en localStorage solo si estamos en el navegador
          if (typeof localStorage !== 'undefined') {
            localStorage.setItem("user", JSON.stringify(response.user));
          }
        }
      } catch (error) {
        this.logout();
      }
    },
    
    // Establecer autenticación
    setAuth(token, user) {
      console.log('Setting auth - Token:', token);
      console.log('Setting auth - User:', user);
      
      this.token = token;
      this.user = user;
      this.isAuthenticated = true;
      
      // Guardar en localStorage solo si estamos en el navegador
      if (typeof localStorage !== 'undefined') {
        localStorage.setItem("token", token);
        
        // Asegurarnos de que user sea una cadena JSON válida antes de guardar
        if (user) {
          try {
            // Si es un objeto, lo convertimos a string
            const userStr = typeof user === 'object' ? JSON.stringify(user) : user;
            localStorage.setItem("user", userStr);
          } catch (error) {
            console.error('Error al guardar datos de usuario:', error);
            // En caso de error, guardamos un objeto vacío
            localStorage.setItem("user", JSON.stringify({email: 'usuario@ejemplo.com'}));
          }
        }
      }
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
        
        // Eliminar de localStorage solo si estamos en el navegador
        if (typeof localStorage !== 'undefined') {
          localStorage.removeItem("token");
          localStorage.removeItem("user");
        }
        
        navigateTo('/perfil');
      }
    },
  },
});