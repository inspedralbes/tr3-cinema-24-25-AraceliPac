# 🎬 Documentació - Projecte Cinema

## 📋 Índex
- [Objectius](#objectius)
- [Arquitectura](#arquitectura)
- [Entorn de Desenvolupament](#entorn-de-desenvolupament)
- [Desplegament a Producció](#desplegament-a-producció)
- [API Backend](#api-backend)
- [Aplicació Android](#aplicació-android)
- [Altres Elements](#altres-elements)

## 🎯 Objectius
El meu projecte té dos objectius principals:
- Desenvolupar una plataforma de cinema en línia
- Crear un panell d'administració per gestionar contingut i usuaris

## 🏗️ Arquitectura
### Tecnologies Utilitzades
- **Backend**: Laravel (PHP)
- **Frontend**: Nuxt.js (Vue.js)
- **Base de Dades**: MySQL 
- **Autenticació API**: Laravel Sanctum
- **Servei Addicional**: Node.js
- **Containerització**: Docker i Docker Compose
- **Gestor de BD**: Adminer

### Interrelació entre Components
```
┌─────────────┐      ┌─────────────┐      ┌─────────────┐
│   Frontend  │ <--> │   Backend   │ <--> │   Base de   │
│   (Nuxt)    │      │  (Laravel)  │      │    Dades    │
│   Port 3001 │      │  Port 8000  │      │  (MySQL)    │
└─────────────┘      └─────────────┘      └─────────────┘
                           ↑                     ↑
                           |                     |
                     ┌─────┴─────┐         ┌─────┴─────┐
                     │  Node.js  │         │  Adminer  │
                     │  Servei   │         │  Gestor   │
                     │  Port 3000│         │  Port 9090│
                     └───────────┘         └───────────┘
```

## 💻 Entorn de Desenvolupament

### Prerequisits
- Docker i Docker Compose instal·lats
- Git instal·lat

### Passos per Configurar l'Entorn

1. **Clonar el repositori**
   ```bash
   git clone https://github.com/inspedralbes/tr3-cinema-24-25-AraceliPac.git
   ```

2. **Configurar l'arxiu .env**
   Abans d'aixecar els serveis, cal configurar l'arxiu .env al directori backend:
   ```bash
   cd ~/tr3-cinema-24-25-AraceliPac/backend
   ```
   
   Contingut mínim necessari per a l'arxiu `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=db   # Nom del servei a Docker
   DB_PORT=3306
   DB_DATABASE=cinema
   DB_USERNAME=
   DB_PASSWORD=
   ```

3. **Iniciar els contenidors Docker**
   ```bash
   docker compose up   # Engegar els contenidors
   ```

   Això iniciarà tots els serveis definits al docker-compose.yml:
   - Base de dades MySQL (port 3306)
   - Adminer per gestionar la base de dades (port 9090)
   - Backend Laravel (port 8000)
   - Servei Node.js (port 3000)
   - Frontend Nuxt.js (port 3001)

4. **Aturar els contenidors (quan sigui necessari)**
   ```bash
   docker compose down
   ```

## 🚀 Desplegament a Producció
> *Aquesta secció s'ha de completar amb el procés específic de desplegament.*

## 🔌 API Backend

### Llistat d'Endpoints API

#### 🔐 Rutes amb autenticació (Sanctum)
| Mètode | Ruta | Descripció |
|--------|------|------------|
| GET/POST/PUT/DELETE | `/api/users/*` | Gestió completa d'usuaris |
| GET/POST/PUT/DELETE | `/api/tickets/*` | Gestió de tickets |
| GET | `/api/tickets/{id}/download` | Descarregar ticket en PDF |
| POST | `/api/logout` | Tancar sessió |

#### 🔓 Rutes públiques
| Mètode | Ruta | Descripció |
|--------|------|------------|
| POST | `/api/login` | Iniciar sessió |
| POST | `/api/register` | Registrar-se |
| GET | `/api/movies` | Llistar pel·lícules |
| GET | `/api/movies/{id}` | Detalls d'una pel·lícula |
| GET | `/api/movies/{id}/actors` | Actors d'una pel·lícula |
| GET/POST/PUT/DELETE | `/api/genres/*` | Gestió de gèneres |
| GET/POST/PUT/DELETE | `/api/directors/*` | Gestió de directors |
| GET/POST/PUT/DELETE | `/api/screenings/*` | Gestió de projeccions |
| GET/POST/PUT/DELETE | `/api/actors/*` | Gestió d'actors |

#### 🪑 Gestió de butaques
| Mètode | Ruta | Descripció |
|--------|------|------------|
| GET | `/api/screenings/{screening}/seats` | Llistar butaques d'una projecció |
| POST | `/api/screenings/{screening}/seats` | Crear butaca per a una projecció |
| GET/PUT/DELETE | `/api/seats/*` | Operacions sobre butaques específiques |

### ⚙️ Rutes d'administració (Web)
| Mètode | Ruta | Descripció |
|--------|------|------------|
| GET | `/admin` | Panell principal d'administració |
| GET/POST/PUT/DELETE | `/admin/movies/*` | Gestió de pel·lícules |
| GET/POST/PUT/DELETE | `/admin/screenings/*` | Gestió de projeccions |
| GET/POST/PUT/DELETE | `/admin/tickets/*` | Gestió de tickets |
| GET/POST/PUT/DELETE | `/admin/users/*` | Gestió d'usuaris |
| GET | `/admin/sales/*` | Informes de vendes |
| GET | `/admin/settings/*` | Configuracions |

### Exemples de JSON

#### Petició d'autenticació
```json
POST /api/login
{
  "email": "usuari@exemple.com",
  "password": "contrasenya123"
}
```

#### Resposta d'autenticació (Codi 200)
```json
{
  "status": "success",
  "token": "6|laravel_sanctum_ZrDpKiSMEXoQGtBYd5s2b8HVZoRrNZxj0rOAgSeZ",
  "user": {
    "id": 5,
    "name": "Usuari Exemple",
    "email": "usuari@exemple.com"
  }
}
```

#### Petició de compra de tickets
```json
POST /api/tickets
{
  "screening_id": 12,
  "seats": ["A1", "A2"],
  "payment_method": "credit"
}
```

#### Resposta de tickets (Codi 200)
```json
{
  "status": "success",
  "data": {
    "id": 145,
    "confirmation_code": "ABC123XYZ",
    "seats": ["A1", "A2"],
    "total_price": 18.00,
    "screening": {
      "id": 12,
      "movie_title": "Avatar",
      "date": "2025-04-15 18:30:00"
    }
  }
}
```

#### Resposta d'error (Codi 404)
```json
{
  "status": "error",
  "message": "La projecció sol·licitada no existeix"
}
```



---
© 2025 Projecte Cinema. Tots els drets reservats.