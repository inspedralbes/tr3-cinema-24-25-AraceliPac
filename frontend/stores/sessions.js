import { defineStore } from 'pinia';

export const useSessionsStore = defineStore('sessions', {
  state: () => ({
    upcomingSessions: [], // Sesiones próximas
    currentSession: null, // Sesión actual seleccionada
    sessionSeats: [], // Asientos de la sesión actual
    isLoading: false, // Estado de carga
    error: null, // Mensaje de error
  }),

  getters: {
    // Verifica si hay sesiones próximas
    hasUpcomingSessions: (state) => state.upcomingSessions.length > 0,

    // Formatea las sesiones próximas con fechas y horas legibles
    formattedUpcomingSessions: (state) => {
      return state.upcomingSessions.map(session => {
        const sessionDate = new Date(session.screening_date);
        return {
          ...session,
          formattedDate: sessionDate.toLocaleDateString('ca-ES', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
          }),
          formattedTime: session.screening_time.substring(0, 5), // Formato HH:MM
          isSpecialDay: session.is_special_day === 1, // Día especial
        };
      });
    },

    // Calcula los asientos disponibles para la sesión actual

    availableSeatsMap: (state) => {
      const seatsMap = {
        rows: {},
        totalAvailable: 0,
        totalOccupied: 0,
        vipAvailable: 0,
        normalAvailable: 0,
      };

      // Debug
      // console.log("sessionSeats en getter:", state.sessionSeats);

      // Verificación adicional
      if (!state.sessionSeats || !Array.isArray(state.sessionSeats) || state.sessionSeats.length === 0) {
        console.warn("No hay asientos disponibles o format incorrecto:", state.sessionSeats);
        return seatsMap;
      }

      // Procesar los asientos
      state.sessionSeats.forEach(seat => {
        // Verificar que el asiento es válido
        if (!seat || typeof seat !== 'object' || !seat.row) {
          console.warn("Asiento inválido:", seat);
          return; // Saltar este asiento
        }

        if (!seatsMap.rows[seat.row]) {
          seatsMap.rows[seat.row] = [];
        }

        seatsMap.rows[seat.row].push(seat);

        // Contabilizar asientos - convertir a números para comparaciones
        const isOccupied = seat.is_occupied == 1 || seat.is_occupied === true;
        const isVip = seat.is_vip == 1 || seat.is_vip === true;

        if (!isOccupied) {
          seatsMap.totalAvailable++;
          if (isVip) {
            seatsMap.vipAvailable++;
          } else {
            seatsMap.normalAvailable++;
          }
        } else {
          seatsMap.totalOccupied++;
        }
      });

      // Ordenar las filas alfabéticamente y los asientos por número
      Object.keys(seatsMap.rows).forEach(row => {
        seatsMap.rows[row].sort((a, b) => a.number - b.number);
      });

      return seatsMap;
    },
    // Busca una sesión por su ID
    sessionById: (state) => (id) => {
      return state.upcomingSessions.find(session => session.id === id);
    },

    // Filtra sesiones por día (formato "YYYY-MM-DD")
    sessionsByDay: (state) => (date) => {
      return state.upcomingSessions.filter(session => session.screening_date === date);
    },

    // Filtra sesiones por hora (formato "HH:MM")
    sessionsByHour: (state) => (time) => {
      return state.upcomingSessions.filter(session => session.screening_time.startsWith(time));
    },
  },

  actions: {
    // Obtiene las sesiones próximas desde la API
    async fetchUpcomingSessions() {
      this.isLoading = true;
      this.error = null;

      try {
        const response = await fetch('http://localhost:8000/api/screenings');
        if (response.status === 200) {
          const data = await response.json();
          this.upcomingSessions = data;
        } else {
          const errorData = await response.json();
          throw new Error(errorData.error || 'Error obteniendo las sesiones');
        }
      } catch (error) {
        this.error = error.message || 'Error desconocido';
        console.error('Error en fetchUpcomingSessions:', error);
      } finally {
        this.isLoading = false;
      }
    },

    // Obtiene una sesión específica por su ID
    async fetchSessionById(sessionId) {
      this.isLoading = true;
      this.error = null;
    
      try {
        const response = await fetch(`http://localhost:8000/api/screenings/${sessionId}`);
        
        if (response.status === 200) {
          const data = await response.json();
          // console.log("Session API response:", data);
          
          // La API devuelve directamente el objeto de sesión
          this.currentSession = data;
        } else {
          throw new Error('Error obteniendo la sesión');
        }
      } catch (error) {
        this.error = error.message || 'Error desconocido';
        console.error('Error en fetchSessionById:', error);
      } finally {
        this.isLoading = false;
      }
    },

    // Obtiene los asientos de una sesión específica
    async fetchSessionSeats(sessionId) {
      this.isLoading = true;
      this.error = null;

      try {
        const response = await fetch(`http://localhost:8000/api/screenings/${sessionId}/seats`);
        if (response.status === 200) {
          // Asegurémonos de que los datos se procesen correctamente
          const data = await response.json();

          // Si los datos vienen directamente como un array, asignarlo directamente
          if (Array.isArray(data)) {
            this.sessionSeats = data;
          } else if (data.data && Array.isArray(data.data)) {
            // Si los datos vienen dentro de una propiedad "data"
            this.sessionSeats = data.data;
          } else {
            // Si no está claro cómo vienen los datos, logeamos y usamos lo que tenemos
            console.warn('Estructura de datos no reconocida:', data);
            this.sessionSeats = data;
          }

          // console.log('Session Seats después de asignar:', this.sessionSeats);
        } else {
          throw new Error('Error obteniendo los asientos de la sesión');
        }
      } catch (error) {
        this.error = error.message || 'Error desconocido';
        console.error('Error en fetchSessionSeats:', error);
      } finally {
        this.isLoading = false;
      }
    },

    // Calcula el precio de un asiento
    getSeatPrice(seat, isSpecialDay = false) {
      if (!seat) throw new Error('Asiento no válido');

      const isVip = seat.is_vip === 1 || seat.is_vip === true;

      if (isSpecialDay) {
        return isVip ? 6 : 4; // Día del espectador
      } else {
        return isVip ? 8 : 6; // Precio normal
      }
    },

    // Reserva asientos para una sesión
    async bookSeats(sessionId, seatIds, userData) {
      this.isLoading = true;
      this.error = null;

      try {
        const bookingData = {
          sessionId,
          seatIds,
          userData,
        };

        const response = await fetch('http://localhost:8000/api/bookings', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(bookingData),
        });

        if (response.status === 200) {
          const data = await response.json();
          // Actualiza los asientos como ocupados
          if (this.sessionSeats && this.sessionSeats.length > 0) {
            this.sessionSeats = this.sessionSeats.map(seat => {
              if (seatIds.includes(seat.id)) {
                return { ...seat, is_occupied: 1 };
              }
              return seat;
            });
          }
          return data;
        } else {
          const errorData = await response.json();
          throw {
            message: errorData.error || 'Error realizando la reserva',
            details: errorData.details,
          };
        }
      } catch (error) {
        this.error = error.message || 'Error desconocido';
        console.error('Error en bookSeats:', error);
        throw error;
      } finally {
        this.isLoading = false;
      }
    },
  },
});