import { io } from 'socket.io-client';

export default defineNuxtPlugin((nuxtApp) => {
    if (process.client) {
        // Inicializar socket.io - Usar la URL correcta según la configuración de Docker
        const socket = io('http://localhost:3000', {
            transports: ['websocket', 'polling'],
            reconnection: true,
            reconnectionAttempts: 5,
            reconnectionDelay: 1000,
            timeout: 10000
        });

        // Debug
        socket.on('connect', () => {
            // console.log('Conectado al servidor de sockets con ID:', socket.id);
        });

        socket.on('connect_error', (error) => {
            console.error('Error de conexión al servidor de sockets:', error.message);
        });

        socket.on('disconnect', (reason) => {
            console.log('Desconectado del servidor de sockets:', reason);
        });

        // Hacer disponible el socket en toda la aplicación
        nuxtApp.provide('socket', socket);
    }
});