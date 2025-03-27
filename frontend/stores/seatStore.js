// ./frontend/stores/seatStore.js
import { defineStore } from "pinia";
import { useAuthStore } from "./auth";

export const useSeatStore = defineStore("seat", {
    state: () => ({
        seats: [],
        selectedSeatId: null,
        temporarilySelectedSeats: {}, // Para almacenar selecciones temporales de otros usuarios
        loading: false,
        error: null,
        listenersActive: false, // Para controlar si ya se están escuchando eventos
    }),

    actions: {
        // Obtener todas las butacas para una sesión
        async fetchSeats(screeningId) {
            this.loading = true;
            this.error = null;

            try {
                const response = await $fetch(`http://localhost:8000/api/screenings/${screeningId}/seats`, {
                    headers: {
                        Accept: "application/json",
                    },
                });

                this.seats = response;

                // Iniciar la escucha de eventos para esta sesión
                this.startListeningForSeatUpdates(screeningId);

                return response;
            } catch (error) {
                console.error("Error al obtener butacas:", error);
                this.error = "No se pudieron cargar las butacas";
                return [];
            } finally {
                this.loading = false;
            }
        },

        // Iniciar la escucha de eventos para las actualizaciones de butacas
        startListeningForSeatUpdates(screeningId) {
            if (this.listenersActive) return; // Evitar duplicar listeners

            const { $socket } = useNuxtApp();
            const authStore = useAuthStore();

            // Unirse a la sala de la sesión
            $socket.emit('join-screening', screeningId);

            // Escuchar actualizaciones de estado de butacas
            $socket.on('seat-status-changed', (data) => {
                console.log('Evento de actualización de butaca recibido:', data);

                // Actualizar el estado según el tipo de evento
                switch (data.status) {
                    case 'selected':
                        // Si la selección es mía, actualizar selectedSeatId
                        if (data.userId === authStore.user?.id) {
                            this.selectedSeatId = data.seatId;
                        } else {
                            // Marcar butaca como seleccionada temporalmente por otro usuario
                            this.temporarilySelectedSeats[data.seatId] = {
                                userId: data.userId
                            };
                        }
                        break;

                    case 'released':
                        // Si era mi selección, limpiar
                        if (this.selectedSeatId === data.seatId) {
                            this.selectedSeatId = null;
                        }
                        // Eliminar la marca temporal
                        delete this.temporarilySelectedSeats[data.seatId];
                        break;

                    case 'purchased':
                        // Marcar la butaca como comprada/ocupada permanentemente
                        const seat = this.seats.find(s => s.id === data.seatId);
                        if (seat) {
                            seat.is_occupied = true;
                        }
                        // Si era mi selección, limpiar
                        if (this.selectedSeatId === data.seatId) {
                            this.selectedSeatId = null;
                        }
                        // También eliminar cualquier selección temporal
                        delete this.temporarilySelectedSeats[data.seatId];
                        break;
                }
            });

            // Escuchar errores en la selección
            $socket.on('seat-selection-error', (data) => {
                this.error = data.message;
            });

            this.listenersActive = true;
        },

        // Seleccionar una butaca (temporal)
        async selectSeat(screeningId, seatId) {
            const authStore = useAuthStore();
            if (!authStore.user || !authStore.user.id) return false;

            try {
                // Verificar si la butaca ya está seleccionada por otro usuario o está ocupada
                const seat = this.seats.find(s => s.id === seatId);
                if (!seat || seat.is_occupied) {
                    this.error = "Esta butaca ya no está disponible";
                    return false;
                }

                if (this.temporarilySelectedSeats[seatId]) {
                    this.error = "Esta butaca está siendo seleccionada por otro usuario";
                    return false;
                }

                // Si ya tenemos otra butaca seleccionada, liberarla primero
                if (this.selectedSeatId) {
                    this.releaseSeat(screeningId);
                }

                // Enviar evento de selección
                const { $socket } = useNuxtApp();
                $socket.emit('select-seat', {
                    screeningId,
                    seatId,
                    userId: authStore.user.id
                });

                return true;
            } catch (error) {
                console.error("Error al seleccionar butaca:", error);
                this.error = "No se pudo seleccionar la butaca";
                return false;
            }
        },

        // Liberar una butaca seleccionada temporalmente
        async releaseSeat(screeningId) {
            if (!this.selectedSeatId) return true;

            const authStore = useAuthStore();
            if (!authStore.user || !authStore.user.id) return false;

            try {
                const { $socket } = useNuxtApp();
                $socket.emit('release-seat', {
                    screeningId,
                    seatId: this.selectedSeatId,
                    userId: authStore.user.id
                });

                // La actualización del estado se hará cuando se reciba el evento de confirmación

                return true;
            } catch (error) {
                console.error("Error al liberar butaca:", error);
                this.error = "No se pudo liberar la butaca";
                return false;
            }
        },

        // Notificar una compra confirmada
        notifyPurchase(screeningId, seatId) {
            const authStore = useAuthStore();
            if (!authStore.user || !authStore.user.id) return;

            const { $socket } = useNuxtApp();
            $socket.emit('seat-purchased', {
                screeningId,
                seatId,
                userId: authStore.user.id
            });
        },

        // Limpiar la selección al salir de la página
        cleanup(screeningId) {
            // Si hay una butaca seleccionada, liberarla
            if (this.selectedSeatId) {
                this.releaseSeat(screeningId);
            }

            // Limpiar estado
            this.seats = [];
            this.selectedSeatId = null;
            this.temporarilySelectedSeats = {};
        }
    },

    getters: {
        // Verificar si una butaca está disponible
        isSeatAvailable: (state) => (seatId) => {
            const seat = state.seats.find(s => s.id === seatId);

            // No disponible si está ocupada o está seleccionada por otro usuario
            return seat &&
                !seat.is_occupied &&
                !state.temporarilySelectedSeats[seatId];
        },

        // Verificar si esta butaca está seleccionada por mí
        isSeatSelectedByMe: (state) => (seatId) => {
            return state.selectedSeatId === seatId;
        },

        // Verificar si esta butaca está seleccionada por otro usuario
        isSeatSelectedByOther: (state) => (seatId) => {
            return !!state.temporarilySelectedSeats[seatId];
        }
    }
});