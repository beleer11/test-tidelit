# Symfony 6 + Vue 3 - Prueba Técnica

## Requisitos

- PHP 8.1+
- Composer
- Node.js 18+
- npm o yarn
- MySQL 8+ (o MariaDB)
- Docker & Docker Compose (opcional)

## Clonar Repositorio

1. Clonar el repositorio:

```
git clone https://github.com/beleer11/test-tidelit.git
cd test-tidelit
```

## Instalación Backend

1. Entrar al directorio backend:

```
cd backend
```

2. Copiar el archivo de configuración y ajustarlo si es necesario:

```
cp .env.example .env
```

Configurar la conexión a la base de datos (MySQL) en `.env`.

3. Instalar dependencias de PHP:

```
composer install
```

4. Crear base de datos y ejecutar migraciones:

```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. Cargar datos iniciales (fixtures):

```
php bin/console doctrine:fixtures:load
```

6. Levantar el servidor Symfony:

```
symfony server:start
# o con PHP nativo
php -S 127.0.0.1:8000 -t public
```

### Usando Docker

1. Construcción, levantamiento de contenedores y configuración de la base de datos:
   Se deben construir y levantar los contenedores del proyecto utilizando Docker. Una vez iniciados, se debe garantizar la correcta configuración de la base de datos, incluyendo la creación de la base de datos, tablas y la ejecución de seeders. Para asegurar que todas las operaciones de inicialización de la base de datos se completen correctamente, es necesario esperar a que el script de entrada (docker-entrypoint.sh) termine su ejecución antes de utilizar la aplicación.

```
docker-compose up --build -d
```

3. Acceder al backend en `http://localhost:8000`.

## Instalación Frontend

1. Entrar al directorio frontend:

```
cd frontend
```

2. Instalar dependencias:

```
npm install
# o
yarn install
```

3. Levantar el servidor de desarrollo:

```
npm run dev
# o
yarn dev
```

4. Acceder a la app en `http://localhost:5173` (Vite) o el puerto que indique tu consola.

### Usando Docker

Acceder a la app en http://localhost:8000 (Nginx) o el puerto parametrizado en el docker-compose.
Una vez ejecutados los contenedores con docker-compose up -d, la aplicación queda corriendo en segundo plano en los puertos configurados, permitiendo acceder a ella desde el navegador o interactuar con los servicios del backend sin necesidad de mantener la terminal abierta.

## Endpoints

### GET /api/books

- Retorna todos los libros con promedio de rating.
- Ejemplo de respuesta:

```
[
  {
    "title": "El Arte de Programar",
    "author": "Donald Knuth",
    "published_year": 1968,
    "average_rating": 4.5
  },
  {
    "title": "Clean Code",
    "author": "Robert C. Martin",
    "published_year": 2008,
    "average_rating": 3.5
  },
  {
    "title": "Refactoring",
    "author": "Martin Fowler",
    "published_year": 1999,
    "average_rating": 4.0
  }
]
```

### POST /api/reviews

- Crear una reseña de un libro.

Request body:

```
{
  "book_id": 1,
  "rating": 5,
  "comment": "Excelente libro"
}
```

Respuesta exitosa:

```
{
  "id": 7,
  "book_id": 1,
  "rating": 5,
  "comment": "Excelente libro",
  "created_at": "2025-09-17T20:00:00"
}
```

Errores de validación:

```
{
  "errors": [
    "book_id no existe",
    "rating debe estar entre 1 y 5",
    "comment no puede estar vacío"
  ]
}
```

## Captura de funcionamiento

```
curl http://localhost:8000/api/books
```

```
[
  {
    "title": "El Arte de Programar",
    "author": "Donald Knuth",
    "published_year": 1968,
    "average_rating": 4.5
  }
]
```

## Escalabilidad

Para manejar cientos de miles de libros y usuarios:

- Indexar campos más consultados (title, author) y relaciones (book_id en reviews).
- Usar cache (Redis, Memcached) para cálculos de `average_rating`.
- Paginación en endpoints para no devolver toda la base de datos.
- Optimizar consultas con JOINs y agregaciones en DB.
- Considerar CQRS y colas para operaciones pesadas de agregación.

**Branch evaluado:** `main`  
**Commit final:** `abcdef123456`

## Autor

Hecho por **Brahiam Andrés Musse**

- GitHub: [Brahiam Musse](https://github.com/beleer11)
- LinkedIn: [Brahiam Musse](https://www.linkedin.com/in/brahiamusse11/)
