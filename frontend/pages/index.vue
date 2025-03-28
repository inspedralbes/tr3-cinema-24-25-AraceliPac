<template>
  <div class="cinema-landing">
    <!-- NavBar -->
    <NavBar />
    
    <main class="main">
      
      <!-- Secció Hero -->
      <section class="hero">
        <div class="hero-image-container">
          <img 
            :src="hallImage.image" 
            :alt="hallImage.alt" 
            class="hero-image"
          />
          <div class="overlay">
            <h2 class="hero-title">Benvinguts al millor cinema</h2>
            <p class="hero-subtitle">Descobreix la màgia del setè art</p>
          </div>
        </div>
      </section>
      
      <!-- Secció CTA -->
      <section class="cta">
        <h2 class="cta-title">No t'ho perdis!</h2>
        <p class="cta-text">Consulta la nostra cartellera i no et perdis cap estrena</p>
        <NuxtLink to="/pelicules/cartelera" class="cta-button">Veure cartellera</NuxtLink>
      </section>
    </main>
    
    <!-- Footer -->
    <Footer />
  </div>
</template>

<script>
import NavBar from '~/components/NavBar.vue';
import Footer from '~/components/Footer.vue';

export default {
  name: 'CinemaLanding',
  components: {
    NavBar,
    Footer
  },
  data() {
    return {
      hallImage: { 
        image: '/images/instalacions/cine2.jpg', 
        alt: 'Hall d\'entrada', 
        description: 'Hall d\'entrada' 
      }
    }
  }
}
</script>

<style scoped>
/* Estils generals i mobile-first */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Montserrat', sans-serif;
  color: #333;
  background-color: #f9f9f9;
  line-height: 1.6;
}

.cinema-landing {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* Secció Hero */
.hero {
  position: relative;
  width: 100%;
  max-height: 70vh;
 
}

.hero-image-container {
  position: relative;
  width: 100%;
  height: 100%;
}

.hero-image {
  width: 100%;
  height: auto;
  display: block;
  object-fit: cover;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  padding: 1rem;
  color: white;
}

.hero-title {
  font-size: 2rem;
  margin-bottom: 1rem;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
}

.hero-subtitle {
  font-size: 1.2rem;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
}

/* Secció Features */
.features {
  padding: 3rem 1rem;
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
  background-color: white;
}

.feature {
  text-align: center;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
}

.feature:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.feature-icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.feature-title {
  font-size: 1.3rem;
  margin-bottom: 0.8rem;
  color: #1a1a2e;
}

.feature-text {
  color: #666;
}

/* Secció CTA */
.cta {
  background-color: #800040; /* Color burdeos */
  color: white;
  padding: 3rem 1rem;
  text-align: center;
}

.cta-title {
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.cta-text {
  margin-bottom: 2rem;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.cta-button {
  display: inline-block;
  background-color: white;
  color: #800040; /* Color burdeos */
  padding: 0.8rem 2rem;
  border-radius: 50px;
  text-decoration: none;
  font-weight: bold;
  transition: background-color 0.3s, transform 0.3s;
}

.cta-button:hover {
  background-color: #f8f8f8;
  transform: scale(1.05);
}

/* Media Queries per a tablet i desktop */
@media (min-width: 768px) {
  .features {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .hero-title {
    font-size: 2.5rem;
  }
  
  .hero-subtitle {
    font-size: 1.5rem;
  }
}

@media (min-width: 1024px) {
  .features {
    grid-template-columns: repeat(3, 1fr);
    padding: 4rem 2rem;
  }
  
  .hero-title {
    font-size: 3rem;
  }
  
  .hero {
    max-height: 80vh;
  }
  
  .hero-image {
    height: 80vh;
    object-fit: cover;
  }
}
</style>