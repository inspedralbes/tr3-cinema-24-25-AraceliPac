<template>
    <div class="min-h-screen flex flex-col">
      <NavBar />
  
      <!-- Hero Section -->
      <div class="hero-section">
        <div class="container mx-auto px-4 py-10 md:py-16">
          <h1 class="text-3xl md:text-5xl font-bold text-white text-center mb-4 hero-title">Política de Privacitat</h1>
          <p class="text-white text-center max-w-2xl mx-auto text-lg">Informació sobre com protegim les teves dades personals</p>
        </div>
      </div>
  
      <!-- Contingut Principal con márgenes mejorados -->
      <div class="container mx-auto px-4 py-16 md:py-24 flex-grow">
        <!-- Contenedor con ancho máximo para el contenido -->
        <div class="max-w-3xl mx-auto">
          <div class="bg-white rounded-lg shadow-md p-8 md:p-12 mb-10">
            <div class="prose prose-lg max-w-none">
              <!-- Introducció con más separación del hero -->
              <div class="mb-20">
                <h2 class="text-2xl md:text-3xl font-semibold text-[#800040] mb-8">Introducció</h2>
                <p>Cinema Pedralbes, amb domicili a Av. d'Esplugues, 40, 08034 Barcelona, és el responsable del tractament de les dades personals de l'Usuari i l'informa que aquestes dades seran tractades de conformitat amb el que disposa el Reglament (UE) 2016/679 de 27 d'abril de 2016 (GDPR) relatiu a la protecció de les persones físiques pel que fa al tractament de dades personals i a la lliure circulació d'aquestes dades, per la qual cosa se li facilita la següent informació del tractament:</p>
              </div>
  
              <!-- Finalitat del tractament con más separación -->
              <div class="mb-20">
                <h2 class="text-2xl md:text-3xl font-semibold text-[#800040] mb-8">Finalitat del tractament</h2>
                <p class="mb-6">Les seves dades personals només s'utilitzaran amb les finalitats següents:</p>
                <ul class="mt-8 space-y-6 text-gray-700">
                  <li class="flex items-start">
                    <Icon name="mdi:check-circle" class="text-[#800040] mr-2 mt-1 flex-shrink-0" />
                    <span><strong>Gestió de la compra d'entrades:</strong> Per processar i gestionar la compra d'entrades i serveis relacionats amb el cinema.</span>
                  </li>
                  <li class="flex items-start">
                    <Icon name="mdi:check-circle" class="text-[#800040] mr-2 mt-1 flex-shrink-0" />
                    <span><strong>Comunicacions comercials:</strong> Enviament d'informació promocional, sempre que s'hagi autoritzat prèviament.</span>
                  </li>
                  <li class="flex items-start">
                    <Icon name="mdi:check-circle" class="text-[#800040] mr-2 mt-1 flex-shrink-0" />
                    <span><strong>Gestió del programa de fidelització:</strong> Si l'usuari s'ha donat d'alta en el programa de fidelització, per a la gestió d'aquest servei.</span>
                  </li>
                  <li class="flex items-start">
                    <Icon name="mdi:check-circle" class="text-[#800040] mr-2 mt-1 flex-shrink-0" />
                    <span><strong>Resolució de consultes:</strong> Per atendre i donar resposta a les consultes, sol·licituds o incidències que ens plantegi.</span>
                  </li>
                </ul>
              </div>
  
              <!-- Categories de dades -->
              <div class="mb-20">
                <h2 class="text-2xl md:text-3xl font-semibold text-[#800040] mb-8">Categories de dades</h2>
                <p class="mb-6">Les dades que podem arribar a tractar en aquests serveis són:</p>
                <ul class="mt-8 space-y-6 text-gray-700">
                  <li class="flex items-start">
                    <Icon name="mdi:account" class="text-[#D4AF37] mr-2 mt-1 flex-shrink-0" />
                    <span><strong>Dades identificatives:</strong> Nom, cognoms, adreça postal, adreça de correu electrònic, telèfon.</span>
                  </li>
                  <li class="flex items-start">
                    <Icon name="mdi:credit-card" class="text-[#D4AF37] mr-2 mt-1 flex-shrink-0" />
                    <span><strong>Dades transaccionals:</strong> Compres realitzades, imports, data, mètode de pagament (no emmagatzemem dades completes de targetes de crèdit).</span>
                  </li>
                  <li class="flex items-start">
                    <Icon name="mdi:web" class="text-[#D4AF37] mr-2 mt-1 flex-shrink-0" />
                    <span><strong>Dades de navegació:</strong> Adreça IP, tipus i versió del navegador, configuració de la zona horària, tipus i versions dels plugins del navegador, sistema operatiu i plataforma.</span>
                  </li>
                </ul>
              </div>
  
              <!-- Conservació de les dades -->
              <div class="mb-20">
                <h2 class="text-2xl md:text-3xl font-semibold text-[#800040] mb-8">Conservació de les dades</h2>
                <p class="mb-6">Les dades personals proporcionades es conservaran:</p>
                <ul class="mt-8 space-y-6 text-gray-700">
                  <li class="flex items-start">
                    <Icon name="mdi:clock-outline" class="text-[#D4AF37] mr-2 mt-1 flex-shrink-0" />
                    <span>Durant el temps necessari per complir amb la finalitat per a la qual es van recollir.</span>
                  </li>
                  <li class="flex items-start">
                    <Icon name="mdi:clock-outline" class="text-[#D4AF37] mr-2 mt-1 flex-shrink-0" />
                    <span>Mentre es mantingui la relació comercial o durant els anys necessaris per complir amb les obligacions legals.</span>
                  </li>
                  <li class="flex items-start">
                    <Icon name="mdi:clock-outline" class="text-[#D4AF37] mr-2 mt-1 flex-shrink-0" />
                    <span>Les dades de qui s'hagi subscrit a la nostra newsletter es conservaran mentre l'interessat no sol·liciti la seva supressió.</span>
                  </li>
                </ul>
              </div>
  
              <!-- Comunicació de les dades -->
              <div class="mb-20">
                <h2 class="text-2xl md:text-3xl font-semibold text-[#800040] mb-8">Comunicació de les dades</h2>
                <div class="bg-gray-50 p-6 rounded-lg mb-8">
                  <p class="font-medium mb-6">Les seves dades podran ser comunicades a:</p>
                  <ul class="space-y-6 text-gray-700">
                    <li class="flex items-start">
                      <Icon name="mdi:domain" class="text-[#800040] mr-2 mt-1 flex-shrink-0" />
                      <span><strong>Empreses del grup:</strong> Per finalitats administratives internes i gestió de productes i serveis contractats.</span>
                    </li>
                    <li class="flex items-start">
                      <Icon name="mdi:handshake" class="text-[#800040] mr-2 mt-1 flex-shrink-0" />
                      <span><strong>Proveïdors de serveis:</strong> Que ens ajuden a prestar serveis relacionats amb els nostres sistemes d'informació, plataforma web o serveis d'atenció al client.</span>
                    </li>
                    <li class="flex items-start">
                      <Icon name="mdi:gavel" class="text-[#800040] mr-2 mt-1 flex-shrink-0" />
                      <span><strong>Administracions públiques:</strong> Quan així ho exigeixi la normativa aplicable.</span>
                    </li>
                  </ul>
                </div>
                <p class="mb-8">Cinema Pedralbes no realitzarà transferències internacionals de dades fora de l'Espai Econòmic Europeu (EEE), excepte que sigui estrictament necessari per a l'execució del contracte o per obligació legal. En aquest cas, ens assegurarem que aquestes transferències comptin amb les garanties adequades.</p>
              </div>
  
              <!-- Drets dels interessats -->
              <div class="mb-20 bg-white p-8 border border-[#D4AF37] rounded-lg">
                <h2 class="text-2xl md:text-3xl font-semibold text-[#800040] mb-8">Els teus drets</h2>
                <p class="mb-8">Com a titular de les dades, vostè té dret a:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                  <div class="bg-gray-50 p-5 rounded-lg">
                    <h3 class="font-medium text-lg mb-3 flex items-center">
                      <Icon name="mdi:eye" class="text-[#D4AF37] mr-2" />
                      Dret d'accés
                    </h3>
                    <p>Obtenir confirmació de si estem tractant les seves dades personals i, en aquest cas, accedir a aquestes dades.</p>
                  </div>
                  <div class="bg-gray-50 p-5 rounded-lg">
                    <h3 class="font-medium text-lg mb-3 flex items-center">
                      <Icon name="mdi:pencil" class="text-[#D4AF37] mr-2" />
                      Dret de rectificació
                    </h3>
                    <p>Sol·licitar la rectificació de les dades inexactes o incompletes que tractem sobre vostè.</p>
                  </div>
                  <div class="bg-gray-50 p-5 rounded-lg">
                    <h3 class="font-medium text-lg mb-3 flex items-center">
                      <Icon name="mdi:delete" class="text-[#D4AF37] mr-2" />
                      Dret de supressió
                    </h3>
                    <p>Sol·licitar la supressió de les seves dades personals quan ja no siguin necessàries per a les finalitats per les quals van ser recollides.</p>
                  </div>
                  <div class="bg-gray-50 p-5 rounded-lg">
                    <h3 class="font-medium text-lg mb-3 flex items-center">
                      <Icon name="mdi:block-helper" class="text-[#D4AF37] mr-2" />
                      Dret d'oposició
                    </h3>
                    <p>Oposar-se al tractament de les seves dades per finalitats determinades, inclosa l'elaboració de perfils.</p>
                  </div>
                  <div class="bg-gray-50 p-5 rounded-lg">
                    <h3 class="font-medium text-lg mb-3 flex items-center">
                      <Icon name="mdi:tune" class="text-[#D4AF37] mr-2" />
                      Dret a la limitació
                    </h3>
                    <p>Sol·licitar la limitació del tractament de les seves dades en determinades circumstàncies.</p>
                  </div>
                  <div class="bg-gray-50 p-5 rounded-lg">
                    <h3 class="font-medium text-lg mb-3 flex items-center">
                      <Icon name="mdi:file-export" class="text-[#D4AF37] mr-2" />
                      Dret a la portabilitat
                    </h3>
                    <p>Rebre les seves dades en un format estructurat i d'ús comú, i transmetre-les a un altre responsable.</p>
                  </div>
                </div>
                <div class="mt-8">
                  <p>Pot exercir aquests drets mitjançant:</p>
                  <ul class="mt-5 space-y-4 text-gray-700">
                    <li class="flex items-start">
                      <Icon name="mdi:email" class="text-[#800040] mr-2 mt-1 flex-shrink-0" />
                      <span>Correu electrònic a: <a href="mailto:privacitat@cinemapedralbes.com" class="text-[#800040] hover:underline">privacitat@cinemapedralbes.com</a></span>
                    </li>
                    <li class="flex items-start">
                      <Icon name="mdi:post" class="text-[#800040] mr-2 mt-1 flex-shrink-0" />
                      <span>Correu postal a: Av. d'Esplugues, 40, 08034 Barcelona (A/A: Responsable de Privacitat)</span>
                    </li>
                  </ul>
                </div>
              </div>
  
              <!-- Mesures de seguretat -->
              <div class="mb-20">
                <h2 class="text-2xl md:text-3xl font-semibold text-[#800040] mb-8">Mesures de seguretat</h2>
                <p class="mb-6">Cinema Pedralbes ha adoptat les mesures de seguretat tècniques i organitzatives necessàries per garantir la seguretat de les dades de caràcter personal i evitar la seva alteració, pèrdua, tractament o accés no autoritzat, tenint en compte l'estat de la tecnologia, la naturalesa de les dades emmagatzemades i els riscos als quals estan exposats.</p>
                <p class="mb-8">Entre aquestes mesures s'inclouen:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
                  <div class="bg-gray-50 p-5 rounded-lg">
                    <h3 class="font-medium text-lg mb-3 flex items-center">
                      <Icon name="mdi:shield-lock" class="text-[#D4AF37] mr-2" />
                      Control d'accés
                    </h3>
                    <p>Limitem l'accés a la informació personal només a aquells empleats, contractistes i agents que necessiten conèixer aquesta informació per operar, desenvolupar o millorar els nostres serveis.</p>
                  </div>
                  <div class="bg-gray-50 p-5 rounded-lg">
                    <h3 class="font-medium text-lg mb-3 flex items-center">
                      <Icon name="mdi:lock" class="text-[#D4AF37] mr-2" />
                      Encriptació
                    </h3>
                    <p>Utilitzem protocols de seguretat i encriptació per protegir la transmissió de dades sensibles, com la informació de pagament.</p>
                  </div>
                  <div class="bg-gray-50 p-5 rounded-lg">
                    <h3 class="font-medium text-lg mb-3 flex items-center">
                      <Icon name="mdi:backup-restore" class="text-[#D4AF37] mr-2" />
                      Còpies de seguretat
                    </h3>
                    <p>Realitzem còpies de seguretat periòdiques per evitar la pèrdua accidental de dades.</p>
                  </div>
                  <div class="bg-gray-50 p-5 rounded-lg">
                    <h3 class="font-medium text-lg mb-3 flex items-center">
                      <Icon name="mdi:test-tube" class="text-[#D4AF37] mr-2" />
                      Auditories
                    </h3>
                    <p>Realitzem auditories periòdiques de seguretat per verificar l'eficàcia de les mesures implementades.</p>
                  </div>
                </div>
              </div>
  
              <!-- Cookies -->
              <div class="mb-20">
                <h2 class="text-2xl md:text-3xl font-semibold text-[#800040] mb-8">Política de Cookies</h2>
                <p class="mb-4">Cinema Pedralbes utilitza cookies pròpies i de tercers per millorar la seva experiència de navegació i oferir continguts d'interès. Pot obtenir més informació o modificar la configuració de les cookies a la nostra <a href="/cookies" class="text-[#800040] hover:underline">Política de Cookies</a>.</p>
              </div>
  
              <!-- Modificacions -->
              <div class="mb-10">
                <h2 class="text-2xl md:text-3xl font-semibold text-[#800040] mb-8">Modificacions de la política de privacitat</h2>
                <p class="mb-6">Cinema Pedralbes es reserva el dret de modificar la present política per adaptar-la a novetats legislatives o jurisprudencials, així com a pràctiques de la indústria. En aquests casos, Cinema Pedralbes anunciarà en aquesta pàgina els canvis introduïts amb raonable antelació a la seva posada en pràctica.</p>
                <div class="bg-gray-100 p-6 rounded-lg mt-10">
                  <p class="text-sm">Última actualització: 14 de març de 2025</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Footer -->
      <Footer />
    </div>
  </template>
  
  <style scoped>
  .hero-section {
    background: linear-gradient(rgba(128, 0, 64, 0.5), rgba(128, 0, 64, 0.6)),
                url('/images/instalacions/cine2.jpg') center/cover no-repeat;
    padding: 3rem 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    position: relative;
  }
  
  .hero-section::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #D4AF37;
  }
  
  .hero-title {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
  }
  
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
  
  .container {
    animation: fadeIn 0.5s ease-out;
  }
  </style>