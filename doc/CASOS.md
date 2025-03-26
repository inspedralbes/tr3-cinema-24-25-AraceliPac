# Diagrama de Casos d'Ús - Cinema Pedralbes

```mermaid
flowchart TD
    %% Actores
    U((Usuari no\nregistrat))
    R((Usuari\nregistrat))
    A((Administrador))
    
    %% Casos de uso - Comunes a todos los usuarios
    CART[Consultar cartellera]
    PREU[Consultar preus i ofertes]
    SESS[Consultar sessions\nde pel·lícules]
    DETA[Veure detalls\nde pel·lícula]
    TRAI[Veure tràilers]
    CONT[Contactar amb el cinema]
    LLEG[Llegir polítiques\nde privacitat]
    COND[Consultar condicions\nde compra]
    SALA[Consultar mapa\nde sales]
    SEAR[Cercar pel·lícules\nper gènere, director, etc.]
    COMM[Llegir comentaris i\nvaloracions de pel·lícules]
    
    %% Casos de uso - Usuario registrado
    REGI[Registrar-se]
    LOGI[Iniciar sessió]
    COMP[Comprar entrades]
    HIST[Consultar historial\nde compres]
    TICK[Descarregar tickets\nen PDF]
    PREF[Gestionar preferències\ni notificacions]
    PERF[Editar perfil]
    VALO[Valorar pel·lícules]
    CMEN[Comentar pel·lícules]
    REST[Reservar entrades\nsense pagar]
    ABON[Comprar abonaments\ni subscripcions]
    CANC[Cancel·lar reserves]
    NOTI[Activar notificacions\nde pel·lícules futures]
    RECM[Rebre recomanacions\npersonalitzades]
    
    %% Casos de uso - Administrador
    MOVI[Gestionar pel·lícules]
    SCRE[Gestionar sessions]
    USRS[Gestionar usuaris]
    PRIC[Gestionar preus\ni promocions]
    SALE[Consultar vendes\ni estadístiques]
    NEWM[Enviar novetats\nals clients]
    BANS[Gestionar banners\ni publicitat]
    REVI[Moderar comentaris\ni valoracions]
    OFER[Crear i gestionar\nofertes especials]
    REPT[Generar informes\nde rendiment]

    %% Relaciones - Usuario no registrado
    U --> CART
    U --> PREU
    U --> SESS
    U --> DETA
    U --> TRAI
    U --> CONT
    U --> LLEG
    U --> COND
    U --> SALA
    U --> SEAR
    U --> COMM
    U --> REGI
    U --> LOGI
    
    %% Relaciones - Usuario registrado (incluye todas las de no registrado)
    R --> CART
    R --> PREU
    R --> SESS
    R --> DETA
    R --> TRAI
    R --> CONT
    R --> LLEG
    R --> COND
    R --> SALA
    R --> SEAR
    R --> COMM
    R --> LOGI
    
    %% Relaciones - Casos específicos de usuario registrado
    R --> COMP
    R --> HIST
    R --> TICK
    R --> PREF
    R --> PERF
    R --> VALO
    R --> CMEN
    R --> REST
    R --> ABON
    R --> CANC
    R --> NOTI
    R --> RECM
    
    %% Relaciones - Administrador
    A --> MOVI
    A --> SCRE
    A --> USRS
    A --> PRIC
    A --> SALE
    A --> NEWM
    A --> BANS
    A --> REVI
    A --> OFER
    A --> REPT
    
    %% Estilo
    classDef usuario fill:#f9f,stroke:#333,stroke-width:2px
    classDef caso fill:#bbf,stroke:#33f,stroke-width:1px
    classDef casoAdmin fill:#bfb,stroke:#3b3,stroke-width:1px
    
    class U,R,A usuario
    class CART,PREU,SESS,DETA,TRAI,CONT,LLEG,COND,SALA,SEAR,COMM,REGI,LOGI,COMP,HIST,TICK,PREF,PERF,VALO,CMEN,REST,ABON,CANC,NOTI,RECM caso
    class MOVI,SCRE,USRS,PRIC,SALE,NEWM,BANS,REVI,OFER,REPT casoAdmin