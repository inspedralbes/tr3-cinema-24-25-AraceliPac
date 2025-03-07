// stores/sessions.js
import { defineStore } from 'pinia';

export const useSessionsStore = defineStore('sessions', {
  state: () => ({
    upcomingSessions: [],
    currentSession: null,
    isLoading: false,
    error: null
  }),
  
  getters: {
    hasUpcomingSessions: (state) => state.upcomingSessions.length > 0,
    formattedUpcomingSessions: (state) => {
      return state.upcomingSessions.map(session => {
        const sessionDate = new Date(session.screening_date);
        return {
          ...session,
          formattedDate: sessionDate.toLocaleDateString('ca-ES', {
            weekday: 'long',
            day: 'numeric',
            month: 'long'
          }),
          formattedTime: session.screening_time.substring(0, 5), // Quitamos los segundos de HH:MM:SS
          isSpecialDay: session.is_special_day === 1
        };
      });
    },
    availableSeats: (state) => {
      if (!state.currentSession) return 0;
      const totalSeats = 120; // 12 filas x 10 butaques
      return totalSeats - (state.currentSession.occupiedSeats || []).length;
    }
  },
  
  actions: {
    async fetchUpcomingSessions() {
      this.isLoading = true;
      this.error = null;
      
      try {
        // Llamada a la API real
        const response = await fetch('http://localhost:8000/api/screenings');
        
        if (response.status === 200) {
          const data = await response.json();
          this.upcomingSessions = data;
        } else {
          throw new Error('Error obteniendo las sesiones');
        }
      } catch (error) {
        this.error = error.message || 'Error desconocido';
        console.error('Error en fetchUpcomingSessions:', error);
      } finally {
        this.isLoading = false;
      }
    },
    
    async fetchSessionById(sessionId) {
      this.isLoading = true;
      this.error = null;
      
      try {
        // En un entorno real, aquí harías una llamada a la API
        // Ejemplo: const response = await fetch(`/api/sessions/${sessionId}`);
        
        // Simulamos la respuesta para el ejemplo
        const response = await new Promise(resolve => {
          setTimeout(() => {
            const foundSession = this.upcomingSessions.find(session => session.id == sessionId);
            if (foundSession) {
              resolve({
                status: 200,
                json: () => Promise.resolve({ session: foundSession })
              });
            } else {
              resolve({
                status: 404,
                json: () => Promise.resolve({ error: 'Sessió no trobada' })
              });
            }
          }, 500);
        });
        
        if (response.status === 200) {
          const data = await response.json();
          this.currentSession = data.session;
        } else {
          const errorData = await response.json();
          throw new Error(errorData.error || 'Error obteniendo la sesión');
        }
      } catch (error) {
        this.error = error.message || 'Error desconocido';
        console.error('Error en fetchSessionById:', error);
      } finally {
        this.isLoading = false;
      }
    },
    
    async bookSeats(sessionId, seats, userData) {
      this.isLoading = true;
      this.error = null;
      
      try {
        // En un entorno real, aquí harías una llamada a la API
        // Ejemplo: const response = await fetch('/api/bookings', {
        //   method: 'POST',
        //   body: JSON.stringify({ sessionId, seats, userData }),
        // });
        
        // Simulamos la respuesta para el ejemplo
        const response = await new Promise(resolve => {
          setTimeout(() => {
            // Verificar si el usuario ya tiene entradas para esta sesión
            const hasExistingBooking = Math.random() > 0.8; // Simulación
            
            if (hasExistingBooking) {
              resolve({
                status: 400,
                json: () => Promise.resolve({ 
                  error: 'Ja tens entrades per a aquesta sessió',
                  existingBooking: {
                    seats: ['C3', 'C4'],
                    date: '2025-03-08',
                    time: '16:00'
                  }
                })
              });
            } else {
              resolve({
                status: 200,
                json: () => Promise.resolve({ 
                  success: true,
                  bookingReference: 'REF-' + Math.floor(Math.random() * 1000000),
                  seats
                })
              });
            }
          }, 1000);
        });
        
        if (response.status === 200) {
          const data = await response.json();
          // Actualizamos el estado si es necesario
          if (this.currentSession && this.currentSession.id == sessionId) {
            const updatedOccupiedSeats = [
              ...(this.currentSession.occupiedSeats || []),
              ...seats
            ];
            this.currentSession = {
              ...this.currentSession,
              occupiedSeats: updatedOccupiedSeats
            };
          }
          return data;
        } else {
          const errorData = await response.json();
          throw {
            message: errorData.error || 'Error realizando la reserva',
            existingBooking: errorData.existingBooking
          };
        }
      } catch (error) {
        this.error = error.message || 'Error desconocido';
        console.error('Error en bookSeats:', error);
        throw error; // Re-lanzamos el error para manejarlo en el componente
      } finally {
        this.isLoading = false;
      }
    }
  }
});