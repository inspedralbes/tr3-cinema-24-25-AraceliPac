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

# 🚀 Desplegament a Producció

## 🔄 Arquitectura del Desplegament

<div align="center">

| Entorn     | Domini                        | Directori Base                                                 |
|------------|-------------------------------|----------------------------------------------------------------|
| 🖥️ Backend  | cinema.daw.inspedralbes.cat   | /home/a23arapacmun/web/cinema.daw.inspedralbes.cat/public_html |
| 🌐 Frontend | cine.daw.inspedralbes.cat     | /home/a23arapacmun/web/cine.daw.inspedralbes.cat/public_html   |

</div>

## 💻 Desplegament del Backend (Laravel)

### 1. Clonar el repositori
```bash
cd /home/a23arapacmun/web/cinema.daw.inspedralbes.cat/public_html
git clone https://github.com/inspedralbes/tr3-cinema-24-25-AraceliPac.git
```

### 2. Configurar Document Root en Hestia
Modificar la configuració al panell de Hestia per apuntar a:
```
/home/a23arapacmun/web/cinema.daw.inspedralbes.cat/public_html/tr3-cinema-24-25-AraceliPac/backend/public
```

### 3. Configurar l'entorn
Crear i configurar l'arxiu `.env` amb les dades de connexió a la base de dades de Hestia:
```bash
cd tr3-cinema-24-25-AraceliPac/backend
cp .env.example .env
# Editar .env amb les dades correctes de connexió
```

### 4. Instal·lar dependències i preparar l'aplicació
```bash
composer install
php artisan key:generate
php artisan migrate:refresh --seed
php artisan storage:link
```

### 5. Configurar CORS per permetre peticions del frontend
```bash
php artisan make:middleware CorsMiddleware
```

Editar el fitxer creat a `app/Http/Middleware/CorsMiddleware.php` amb la configuració adequada:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        $response->headers->set('Access-Control-Allow-Origin', 'http://cine.daw.inspedralbes.cat');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        
        return $response;
    }
}
```

Registrar el middleware a `app/Http/Kernel.php` en el grup `api`:
```php
protected $middlewareGroups = [
    'api' => [
        // ... altres middlewares
        \App\Http\Middleware\CorsMiddleware::class,
    ],
];
```

## 🎨 Desplegament del Frontend (Nuxt.js)

### 1. Clonar el repositori
```bash
cd /home/a23arapacmun/web/cine.daw.inspedralbes.cat/public_html
git clone https://github.com/inspedralbes/tr3-cinema-24-25-AraceliPac.git
```

### 2. Configurar Document Root en Hestia
Modificar la configuració al panell de Hestia per apuntar a:
```
/home/a23arapacmun/web/cine.daw.inspedralbes.cat/public_html/tr3-cinema-24-25-AraceliPac/frontend
```

### 3. Configurar GitHub Actions per automatitzar el desplegament
Crear un fitxer `.github/workflows/deploy-frontend.yml`:

```yaml
name: Deploy Frontend

on:
  push:
    branches: [ main ]
    paths:
      - 'frontend/**'

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '18'
          
      - name: Install dependencies
        run: |
          cd frontend
          npm ci
          
      - name: Build & Generate
        run: |
          cd frontend
          npm run build
          npm run generate
          
      # Aquí s'afegirien els passos per desplegar a producció
      # Normalment amb SSH o FTP per pujar el contingut de .output
```

### 4. Adaptar les rutes d'API al frontend

Modificar la configuració de l'API al frontend per utilitzar les URL de producció:

```js
// frontend/nuxt.config.js o equivalent
export default {
  publicRuntimeConfig: {
    apiBase: process.env.NODE_ENV === 'production' 
      ? 'http://cinema.daw.inspedralbes.cat/api'
      : 'http://localhost:8000/api'
  }
}
```

Exemple d'ús en els components:
```js
// En un component o servei
const apiUrl = useRuntimeConfig().public.apiBase;
const users = await fetch(`${apiUrl}/users`);
```

## ✅ Verificació del Desplegament

1. Comprovar que el backend respon correctament: 
   - [http://cinema.daw.inspedralbes.cat](http://cinema.daw.inspedralbes.cat)

2. Comprovar que el frontend carrega i pot comunicar-se amb el backend:
   - [http://cine.daw.inspedralbes.cat](http://cine.daw.inspedralbes.cat)

3. Revisar els logs per detectar possibles errors:
   - Backend: `/home/a23arapacmun/web/cinema.daw.inspedralbes.cat/public_html/tr3-cinema-24-25-AraceliPac/backend/storage/logs/laravel.log`

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