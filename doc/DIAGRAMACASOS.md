```mermaid
graph TD
    U[Usuari no registrat] --> B1[Consultar cartellera]
    U --> B2[Consultar preus]
    U --> B3[Veure detalls pel·lícules]
    U --> B4[Consultar sessions]
    U --> R[Registrar-se]
    
    C[Usuari registrat] --> B1
    C --> B2
    C --> B3
    C --> B4
    C --> C1[Comprar entrades]
    C --> C2[Descarregar tickets]
    C --> C3[Valorar pel·lícules]
    C --> C4[Editar perfil]
