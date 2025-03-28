// middleware/auth-redirect.js

export default defineNuxtRouteMiddleware((to, from) => {
    // Solo ejecutar en el cliente
    if (process.client) {
      const pendingPurchase = localStorage.getItem('pendingPurchase');
      
      // Si existe una compra pendiente y venimos de la página de inicio de sesión
      if (pendingPurchase && from.path.includes('/usuari/iniciSessio')) {
        const { sessionId } = JSON.parse(pendingPurchase);
        
        // Eliminar la información de compra pendiente
        localStorage.removeItem('pendingPurchase');
        
        // Redirigir a la página de la sesión
        return navigateTo(`/sessions/${sessionId}`);
      }
    }
  });