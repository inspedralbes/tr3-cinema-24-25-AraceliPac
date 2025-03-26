flowchart TB
    %% Actores
    U((Usuari no\nregistrat))
    R((Usuari\nregistrat))
    
    %% Casos de uso - Comunes a todos los usuarios
    subgraph Funcionalitats_Bàsiques
        CART[Consultar cartellera]
        PREU[Consultar preus i ofertes]
        SESS[Consultar sessions]
        DETA[Veure detalls\nde pel·lícula]
        TRAI[Veure tràilers]
        CONT[Contactar amb el cinema]
        LLEG[Llegir polítiques\nde privacitat]
        COND[Consultar condicions\nde compra]
    end
    
    %% Casos de uso - Acceso
    subgraph Accés
        REGI[Registrar-se]
        LOGI[Iniciar sessió]
    end
    
    %% Casos de uso - Compra
    subgraph Compra_i_Reserves
        COMP[Comprar entrades]
        HIST[Consultar historial\nde compres]
        TICK[Descarregar tickets]
        REST[Reservar entrades]
        ABON[Comprar abonaments]
        CANC[Cancel·lar reserves]
    end
    
    %% Casos de uso - Interacción
    subgraph Interacció
        VALO[Valorar pel·lícules]
        CMEN[Comentar pel·lícules]
        PREF[Gestionar preferències]
        PERF[Editar perfil]
        NOTI[Activar notificacions]
    end
    
    %% Relaciones - Usuario no registrado con básicas
    U --> Funcionalitats_Bàsiques
    
    %% Relación - Usuario no registrado con acceso
    U --> Accés
    
    %% Relaciones - Usuario registrado
    R --> Funcionalitats_Bàsiques
    R --> LOGI
    
    %% Relación - Usuario registrado con compra e interacción
    R --> Compra_i_Reserves
    R --> Interacció
    
    %% Estilo
    classDef usuario fill:#f9f,stroke:#333,stroke-width:2px
    classDef basico fill:#e0f7fa,stroke:#26c6da,stroke-width:1px
    classDef acceso fill:#fff9c4,stroke:#fbc02d,stroke-width:1px
    classDef compra fill:#c8e6c9,stroke:#66bb6a,stroke-width:1px
    classDef interaccion fill:#e1bee7,stroke:#8e24aa,stroke-width:1px
    
    class U,R usuario
    class Funcionalitats_Bàsiques basico
    class Accés acceso
    class Compra_i_Reserves compra
    class Interacció interaccion