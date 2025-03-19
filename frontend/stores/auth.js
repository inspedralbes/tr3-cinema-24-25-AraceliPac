import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
  state: () => {
    return {
      token: null,
      user: null,
      isAuthenticated: false,
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

        navigateTo('/perfil');
      }
    },

    // Actualizar datos del perfil
    async updateProfile(profileData) {
      if (!this.token || !this.user || !this.user.id) {
        throw new Error('Usuario no autenticado');
      }

      try {
        const response = await $fetch(`http://localhost:8000/api/users/${this.user.id}`, {
          method: 'PUT',
          body: profileData,
          headers: {
            Authorization: `Bearer ${this.token}`,
            Accept: 'application/json',
            'Content-Type': 'application/json'
          }
        });

        // Actualizar los datos del usuario en el store
        this.user = {
          ...this.user,
          ...response // Actualizar solo los campos que vienen en la respuesta
        };

        return response;
      } catch (error) {
        console.error('Error al actualizar el perfil:', error);
        throw error;
      }
    },

    // Actualizar contraseña
    async updatePassword(passwordData) {
      if (!this.token || !this.user || !this.user.id) {
        throw new Error('Usuario no autenticado');
      }

      try {
        const response = await $fetch(`http://localhost:8000/api/users/${this.user.id}`, {
          method: 'PUT',
          body: passwordData,
          headers: {
            Authorization: `Bearer ${this.token}`,
            Accept: 'application/json',
            'Content-Type': 'application/json'
          }
        });

        return response;
      } catch (error) {
        console.error('Error al actualizar la contraseña:', error);
        throw error;
      }
    },

    // Actualizar imagen de perfil
    async updateProfileImage(formData) {
      if (!this.token || !this.user || !this.user.id) {
        throw new Error('Usuario no autenticado');
      }

      try {
        // Para FormData necesitamos usar fetch en lugar de $fetch
        const url = `http://localhost:8000/api/users/${this.user.id}`;
        const fetchOptions = {
          method: 'PUT',
          body: formData,
          headers: {
            Authorization: `Bearer ${this.token}`
          }
        };

        const fetchResponse = await fetch(url, fetchOptions);
        if (!fetchResponse.ok) {
          throw new Error(`Error HTTP: ${fetchResponse.status}`);
        }

        const response = await fetchResponse.json();

        // Actualizar la imagen en el estado del usuario
        if (response.image) {
          this.user.image = response.image;
        }

        return response;
      } catch (error) {
        console.error('Error al actualizar la imagen de perfil:', error);
        throw error;
      }
    },

    // Obtener datos actualizados del usuario
    async refreshUserData() {
      if (!this.token || !this.user || !this.user.id) {
        throw new Error('Usuario no autenticado');
      }

      try {
        const response = await $fetch(`http://localhost:8000/api/users/${this.user.id}`, {
          headers: {
            Authorization: `Bearer ${this.token}`,
            Accept: 'application/json'
          }
        });

        // Actualizar los datos del usuario en el store
        this.user = response;

        return response;
      } catch (error) {
        console.error('Error al obtener datos del usuario:', error);
        throw error;
      }
    },

    // Eliminar cuenta de usuario
    async deleteAccount() {
      if (!this.token || !this.user || !this.user.id) {
        throw new Error('Usuario no autenticado');
      }

      try {
        await $fetch(`http://localhost:8000/api/users/${this.user.id}`, {
          method: 'DELETE',
          headers: {
            Authorization: `Bearer ${this.token}`,
            Accept: 'application/json'
          }
        });

        // Cerrar sesión después de eliminar la cuenta
        this.token = null;
        this.user = null;
        this.isAuthenticated = false;

        return true;
      } catch (error) {
        console.error('Error al eliminar la cuenta:', error);
        throw error;
      }
    }
  },
  persist: true
});