// stores/ticketStore.js
import { defineStore } from "pinia";
import { useAuthStore } from "./auth";

export const useTicketStore = defineStore("ticket", {
  state: () => ({
    tickets: [],
    currentTicket: null,
    loading: false,
    error: null,
    pendingPurchase: null, // Para guardar compras pendientes
  }),

  actions: {
    // Obtener tickets del usuario actual
    async fetchUserTickets() {
      const authStore = useAuthStore();
      if (!authStore.token) return;

      this.loading = true;
      this.error = null;

      try {
        const response = await $fetch("http://localhost:8000/api/tickets", {
          headers: {
            Authorization: `Bearer ${authStore.token}`,
            Accept: "application/json",
          },
        });

        this.tickets = response.tickets || [];
      } catch (error) {
        console.error("Error al obtener tickets:", error);
        this.error = "No se pudieron cargar los tickets";
      } finally {
        this.loading = false;
      }
    },

    // Comprar un nuevo ticket
    async purchaseTicket(ticketData) {
      const authStore = useAuthStore();
      if (!authStore.token) return null;
    
      this.loading = true;
      this.error = null;
    
      try {
        // Asegurarse de que el precio esté en el formato correcto (string con dos decimales)
        const priceFormatted = 
          typeof ticketData.price === "number" ? ticketData.price.toFixed(2) : ticketData.price;
    
        // Crear el objeto de datos en el formato esperado por la API
        const ticketPayload = {
          user_id: authStore.user?.id, // Usar optional chaining para evitar errores
          screening_id: ticketData.screening_id,
          seat_id: ticketData.seat_id,
          price: priceFormatted,
        };
    
        console.log("Enviando datos de ticket:", ticketPayload);
    
        // Realizar la solicitud al backend
        try {
          const response = await $fetch("http://localhost:8000/api/tickets", {
            method: "POST",
            body: ticketPayload,
            headers: {
              Authorization: `Bearer ${authStore.token}`,
              Accept: "application/json",
              "Content-Type": "application/json",
            },
          });
    
          console.log("Respuesta exitosa del servidor:", response);
    
          // Agregar el nuevo ticket a la lista local si está disponible
          if (response.ticket) {
            this.tickets.push(response.ticket);
            this.currentTicket = response.ticket;
            return response.ticket;
          } 
          
          // Si no hay ticket pero la respuesta es exitosa, devolver un objeto que indique éxito
          // Esto permitirá que el frontend sepa que la compra fue exitosa
          if (response.message && response.message.includes("éxito")) {
            // Crear un objeto ticket simulado si no recibimos uno del backend
            const dummyTicket = {
              id: Date.now(), // ID temporal
              screening_id: ticketData.screening_id,
              seat_id: ticketData.seat_id,
              price: priceFormatted,
              _dummy: true // Marcar como simulado
            };
            
            // Opcional: añadir a la lista local para consistencia
            this.tickets.push(dummyTicket);
            
            return dummyTicket; // Devolver ticket simulado
          }
          
          // Incluso si no recibimos un ticket o mensaje de éxito específico
          // pero la solicitud fue exitosa (no lanzó excepción), consideramos éxito
          return { success: true, _simulated: true };
          
        } catch (fetchError) {
          // Capturar errores específicos de la solicitud fetch
          console.error("Error en la solicitud al servidor:", fetchError);
          
          // Intentar extraer información detallada del error
          if (fetchError.response) {
            const errorResponse = await fetchError.response.json().catch(() => ({}));
            console.error("Detalles del error:", errorResponse);
            
            // Si el asiento ya está ocupado, esto es un error de negocio esperado
            if (errorResponse.message && errorResponse.message.includes("asiento ya está ocupado")) {
              this.error = "El asiento seleccionado ya ha sido reservado por otro usuario";
              throw new Error(this.error);
            }
            
            this.error = errorResponse.message || "Error en la compra del ticket";
          } else {
            this.error = fetchError.message || "No se pudo completar la solicitud";
          }
          
          throw fetchError; // Re-lanzar para manejo externo
        }
      } catch (error) {
        console.error("Error general al comprar ticket:", error);
        this.error = this.error || error.message || "No se pudo completar la compra";
        return null;
      } finally {
        this.loading = false;
      }
    },

    // Obtener detalles de un ticket específico
    async getTicketDetails(ticketId) {
      const authStore = useAuthStore();
      if (!authStore.token) return null;

      this.loading = true;
      this.error = null;

      try {
        const response = await $fetch(`http://localhost:8000/api/tickets/${ticketId}`, {
          headers: {
            Authorization: `Bearer ${authStore.token}`,
            Accept: "application/json",
          },
        });

        this.currentTicket = response.ticket;
        return response.ticket;
      } catch (error) {
        console.error("Error al obtener detalles del ticket:", error);
        this.error = "No se pudieron cargar los detalles del ticket";
        return null;
      } finally {
        this.loading = false;
      }
    },

    // Cancelar un ticket (si tu aplicación lo permite)
    async cancelTicket(ticketId) {
      const authStore = useAuthStore();
      if (!authStore.token) return false;

      this.loading = true;
      this.error = null;

      try {
        await $fetch(`http://localhost:8000/api/tickets/${ticketId}`, {
          method: "DELETE",
          headers: {
            Authorization: `Bearer ${authStore.token}`,
            Accept: "application/json",
          },
        });

        // Eliminar el ticket de la lista local
        this.tickets = this.tickets.filter((ticket) => ticket.id !== ticketId);
        if (this.currentTicket && this.currentTicket.id === ticketId) {
          this.currentTicket = null;
        }

        return true;
      } catch (error) {
        console.error("Error al cancelar ticket:", error);
        this.error = "No se pudo cancelar el ticket";
        return false;
      } finally {
        this.loading = false;
      }
    },

    // Guardar compra pendiente (cuando el usuario no está autenticado)
    savePendingPurchase(purchaseData) {
      this.pendingPurchase = purchaseData;

      // Opcionalmente, guardar en localStorage como respaldo
      if (process.client && localStorage) {
        localStorage.setItem("pendingPurchase", JSON.stringify(purchaseData));
      }
    },

    // Recuperar compra pendiente
    getPendingPurchase() {
      // Si hay una compra pendiente en el store, usarla primero
      if (this.pendingPurchase) {
        return this.pendingPurchase;
      }

      // Como respaldo, intentar recuperar del localStorage
      if (process.client && localStorage) {
        const pendingData = localStorage.getItem("pendingPurchase");
        if (pendingData) {
          try {
            const parsedData = JSON.parse(pendingData);
            this.pendingPurchase = parsedData;
            return parsedData;
          } catch (error) {
            console.error("Error al recuperar compra pendiente:", error);
            localStorage.removeItem("pendingPurchase");
          }
        }
      }

      return null;
    },

    // Limpiar compra pendiente
    clearPendingPurchase() {
      this.pendingPurchase = null;

      if (process.client && localStorage) {
        localStorage.removeItem("pendingPurchase");
      }
    },

    // Limpiar los datos de tickets (útil al cerrar sesión)
    clearTickets() {
      this.tickets = [];
      this.currentTicket = null;
      this.error = null;
      // Opcional: también podrías limpiar la compra pendiente
      // this.clearPendingPurchase();
    },
  },

  // Getters para acceder a los datos de forma organizada
  getters: {
    // Tickets ordenados por fecha de proyección
    upcomingTickets: (state) => {
      return [...state.tickets].sort((a, b) => {
        // Asumiendo que tienes acceso a la fecha de proyección a través de la relación
        return new Date(a.screening.date) - new Date(b.screening.date);
      });
    },

    // Puedes agregar más getters según necesites
  },

  persist: true, // Mantener persistencia igual que en authStore
});
