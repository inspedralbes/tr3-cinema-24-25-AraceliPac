const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');
const bodyParser = require('body-parser');

// Inicializar la aplicación Express
const app = express();
app.use(cors());
app.use(bodyParser.json());

// Crear el servidor HTTP
const server = http.createServer(app);

// Configurar Socket.IO
const io = socketIo(server, {
  cors: {
    origin: '*', // En producción, limita esto a tu dominio
    methods: ['GET', 'POST'],
    credentials: true
  }
});

// Almacenamiento temporal de butacas seleccionadas
const selectedSeats = {};

// Limpieza de selecciones: elimina selecciones después de 5 minutos
const cleanupInterval = 5 * 60 * 1000; // 5 minutos en milisegundos
setInterval(() => {
  const now = Date.now();
  Object.keys(selectedSeats).forEach(key => {
    if (now - selectedSeats[key].timestamp > cleanupInterval) {
      const [screeningId, seatId] = key.split(':');

      // Emitir evento para liberar la butaca
      io.to(`screening:${screeningId}`).emit('seat-status-changed', {
        seatId: parseInt(seatId, 10),
        status: 'released',
        userId: selectedSeats[key].userId
      });

      // Eliminar del almacenamiento
      delete selectedSeats[key];
      console.log(`Auto-liberada butaca ${seatId} de la sesión ${screeningId} (tiempo expirado)`);
    }
  });
}, 30000); // Revisar cada 30 segundos

// Manejar conexiones de Socket.IO
io.on('connection', (socket) => {
  console.log('Nuevo cliente conectado con ID:', socket.id);

  // Unirse a una sala para una proyección específica
  socket.on('join-screening', (screeningId) => {
    // Asegurarse de que screeningId es un número
    const scrId = parseInt(screeningId, 10);
    const roomName = `screening:${scrId}`;
    socket.join(roomName);
    console.log(`Cliente ${socket.id} unido a la sala de proyección: ${scrId}, Room: ${roomName}`);

    // Enviar el estado actual de butacas seleccionadas temporalmente
    const currentSelections = {};

    // Recorrer todas las butacas seleccionadas para esta sesión
    Object.keys(selectedSeats).forEach(key => {
      const [storedScreeningId, seatId] = key.split(':');
      if (parseInt(storedScreeningId, 10) === scrId) {
        currentSelections[seatId] = selectedSeats[key].userId;
      }
    });

    // Enviar el estado actual al cliente que acaba de conectarse
    if (Object.keys(currentSelections).length > 0) {
      console.log(`Enviando estado actual de ${Object.keys(currentSelections).length} butacas a cliente ${socket.id}:`, currentSelections);
      socket.emit('current-seat-state', {
        screeningId: scrId,
        selections: currentSelections
      });
    } else {
      console.log(`No hay butacas seleccionadas para la sesión ${scrId}`);
    }
  });

  // Manejar la selección de butaca
  socket.on('select-seat', (data) => {
    console.log('Evento select-seat recibido:', data);

    const { screeningId, seatId, userId } = data;

    // Asegurarnos de que los valores son números
    const sId = parseInt(seatId, 10);
    const scrId = parseInt(screeningId, 10);

    // Verificar que tenemos al menos un identificador para el usuario
    if (!userId) {
      console.warn(`Intento de selección de butaca sin userId - screeningId: ${scrId}, seatId: ${sId}`);
      socket.emit('seat-selection-error', {
        seatId: sId,
        message: 'Se requiere identificación de usuario para seleccionar butacas'
      });
      return;
    }

    const key = `${scrId}:${sId}`;

    // Verificar si ya está seleccionada por otro usuario
    if (selectedSeats[key] && selectedSeats[key].userId !== userId) {
      console.log(`Butaca ${sId} ya seleccionada por usuario ${selectedSeats[key].userId}`);
      socket.emit('seat-selection-error', {
        seatId: sId,
        message: 'Esta butaca ya está seleccionada por otro usuario'
      });
      return;
    }

    // Guardar selección temporal
    selectedSeats[key] = {
      userId,
      timestamp: Date.now()
    };

    // Notificar a todos en la sala
    const roomName = `screening:${scrId}`;
    const notificationData = {
      seatId: sId,
      status: 'selected',
      userId
    };

    console.log(`Notificando selección de butaca ${sId} a sala ${roomName}`);
    io.to(roomName).emit('seat-status-changed', notificationData);

    console.log(`Butaca ${sId} seleccionada por usuario ${userId} en sesión ${scrId}`);
  });

  // Manejar la liberación de butaca
  socket.on('release-seat', (data) => {
    console.log('Evento release-seat recibido:', data);

    const { screeningId, seatId, userId } = data;

    // Asegurarnos de que los valores son números
    const sId = parseInt(seatId, 10);
    const scrId = parseInt(screeningId, 10);

    const key = `${scrId}:${sId}`;

    // Verificar que sea el mismo usuario que seleccionó o que simplemente no exista
    if (selectedSeats[key] && selectedSeats[key].userId !== userId) {
      console.log(`Intento no autorizado de liberar butaca ${sId} por usuario ${userId}`);
      return;
    }

    // Eliminar selección
    delete selectedSeats[key];

    // Notificar a todos en la sala
    const roomName = `screening:${scrId}`;
    const notificationData = {
      seatId: sId,
      status: 'released',
      userId
    };

    console.log(`Notificando liberación de butaca ${sId} a sala ${roomName}`);
    io.to(roomName).emit('seat-status-changed', notificationData);

    console.log(`Butaca ${sId} liberada por usuario ${userId} en sesión ${scrId}`);
  });

  // Manejar compra confirmada
  socket.on('seat-purchased', (data) => {
    console.log('Evento seat-purchased recibido:', data);

    const { screeningId, seatId, userId } = data;

    // Asegurarnos de que los valores son números
    const sId = parseInt(seatId, 10);
    const scrId = parseInt(screeningId, 10);

    const key = `${scrId}:${sId}`;

    // Eliminar de selecciones temporales si existe
    if (selectedSeats[key]) {
      delete selectedSeats[key];
    }

    // Notificar a todos en la sala
    const roomName = `screening:${scrId}`;
    const notificationData = {
      seatId: sId,
      status: 'purchased',
      userId
    };

    console.log(`Notificando compra de butaca ${sId} a sala ${roomName}`);
    io.to(roomName).emit('seat-status-changed', notificationData);

    console.log(`Butaca ${sId} comprada por usuario ${userId} en sesión ${scrId}`);
  });

  // Agregar manejador para cualquier evento (depuración)
  socket.onAny((event, ...args) => {
    if (event !== 'seat-status-changed') { // Evitar log recursivo
      console.log(`Evento recibido: ${event}`, args);
    }
  });

  // Manejar desconexión
  socket.on('disconnect', (reason) => {
    console.log(`Cliente ${socket.id} desconectado: ${reason}`);

    // Opcionalmente: liberar butacas seleccionadas por este socket
    // (Esto requeriría mantener un registro de qué socket seleccionó qué butacas)
  });
});

// Ruta para comprobar que el servidor está funcionando
app.get('/', (req, res) => {
  res.send('Servidor de Sockets para el Sistema de Cinema');
});

// Iniciar el servidor
const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
  console.log(`Servidor de sockets ejecutándose en el puerto ${PORT}`);
});