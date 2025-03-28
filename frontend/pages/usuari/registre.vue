<template>
    <div class="page-container">
      <NavBar />
      <!-- Contingut principal amb més separació -->
      <div class="flex-grow flex justify-center items-center py-16 px-4 bg-[#ccd1d1] mt-24 md:mt-28">
        <Register />
      </div>
      <Footer />
    </div>
  </template>
  
  <style>
  .page-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
  
  .flex-grow {
    flex: 1;
    min-height: 400px; /* Alçada mínima per evitar que sigui massa petit */
  
  }
  
  /* Media query per a mobile first */
  @media (max-width: 640px) {
    /* Ajustem l'espai en dispositius mòbils */
    .flex-grow {
      padding-top: 3rem;
      padding-bottom: 3rem;
    }
  }
  </style>