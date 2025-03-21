# Usar una imagen base ligera y estable
FROM node:18-alpine

# Establecer el directorio de trabajo en el contenedor
WORKDIR /app

# Copiar solo package.json y package-lock.json primero
COPY package*.json ./

# Instalar las dependencias
RUN npm install

# Copiar el resto de los archivos del proyecto
COPY . .

# Exponer el puerto utilizado por Nuxt
EXPOSE 3000

# Comando para iniciar el servidor de desarrollo con hot-reload
CMD ["npm", "run", "dev", "--", "--host"]