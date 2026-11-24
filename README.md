# Library Management System

Una aplicación web y API RESTful desarrollada en Laravel para la gestión de una biblioteca. Permite realizar operaciones CRUD sobre libros y categorías, aplicando validaciones estrictas y reglas de negocio.

## Tecnologías Utilizadas

- **Framework:** Laravel (v13)
- **Lenguaje:** PHP (8.3+)
- **Base de Datos:** PostgreSQL
- **Frontend:** Blade + Bootstrap 5 (vía CDN)
- **Testing:** Pest PHP
- **Arquitectura:** MVC, API Resources, Form Requests

## Características Principales

- **Interfaz Web:** Gestión visual de libros y categorías utilizando Blade y Bootstrap.
- **API RESTful:** Endpoints para consumir los datos en formato JSON.
- **CRUD de Libros:** Listar, crear, editar y eliminar libros.
- **Filtrado de Libros:** Búsqueda por categoría mediante parámetros de consulta (query params).
- **Gestión de Categorías:** Creación y eliminación de categorías con nombres únicos.
- **Reglas de Negocio:**
  - No se permite eliminar una categoría si tiene libros asociados (devuelve `HTTP 409 Conflict`).
  - Validación estricta de datos en el servidor (título mínimo de 3 caracteres, año válido, categoría existente, etc.).
- **Respuestas JSON Limpias:** Uso de API Resources para estructurar la salida de datos (sin timestamps innecesarios).

## Requisitos Previos

- PHP >= 8.3
- Composer
- PostgreSQL
- Node.js y NPM (opcional, solo si se desea compilar assets localmente)

## Instalación y Configuración

Sigue estos pasos para ejecutar el proyecto en un entorno local:

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/HidalgoJTD4C/library.git
   cd library
   ```

2. **Instalar las dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Configurar las variables de entorno:**
   Copiar el archivo de ejemplo y generar la clave de la aplicación.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar la base de datos:**
   Abrir el archivo `.env` y actualizar las credenciales de PostgreSQL:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=library_db
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

5. **Crear las bases de datos en PostgreSQL:**
   Es necesario crear una base de datos para desarrollo y otra para las pruebas automatizadas.
   ```sql
   CREATE DATABASE library_db;
   CREATE DATABASE library_testing;
   ```

6. **Ejecutar las migraciones y seeders:**
   Esto creará las tablas y poblará la base de datos con datos de prueba (categorías y libros aleatorios).
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Iniciar el servidor de desarrollo:**
   ```bash
   php artisan serve
   ```
   La aplicación web estará disponible en `http://127.0.0.1:8000`.

## Ejecución de Pruebas

El proyecto cuenta con una suite de pruebas automatizadas (Feature Tests) que cubren la API, las validaciones y las reglas de negocio.

Para ejecutar las pruebas, primero debes crear el archivo de entorno para testing (`.env.testing`). Este archivo no se sube al repositorio por seguridad. Puedes crearlo copiando tu archivo `.env` actual:

```bash
cp .env .env.testing
```
Luego, abre el archivo .env.testing recién creado y asegúrate de cambiar el nombre de la base de datos para que apunte a la de pruebas:

```env
DB_DATABASE=library_testing
```
Finalmente, ejecuta la suite de pruebas con el siguiente comando:

```bash
./vendor/bin/pest
```
O si lo prefieres, usando Artisan:

```bash
php artisan test
```

Las pruebas utilizan transacciones (`RefreshDatabase`) para garantizar que la base de datos quede limpia después de cada test.

## Documentación de la API

La API está disponible bajo el prefijo `/api`. Todas las respuestas se devuelven en formato JSON.

### Libros

- `GET /api/books` - Lista todos los libros.
- `GET /api/books?category={id}` - Filtra los libros por el ID de categoría especificado.
- `POST /api/books` - Crea un nuevo libro.
- `PUT /api/books/{id}` - Actualiza un libro existente.
- `DELETE /api/books/{id}` - Elimina un libro.

**Ejemplo de Payload para `POST /api/books`:**

```json
{
  "title": "Cien años de soledad",
  "author": "Gabriel García Márquez",
  "description": "Novela cumbre del realismo mágico.",
  "published_year": 1967,
  "category_id": 1
}
```

### Categorías

- `GET /api/categories` - Lista todas las categorías junto con el conteo de libros asociados.
- `POST /api/categories` - Crea una nueva categoría. El nombre debe ser único.
- `DELETE /api/categories/{id}` - Elimina una categoría. Si la categoría tiene libros asociados, la API devolverá un error `HTTP 409 (Conflict)`.

**Ejemplo de Payload para `POST /api/categories`:**

```json
{
  "name": "Ciencia Ficción"
}
```

## Estructura del Proyecto

- `app/Http/Controllers/`: Controladores de lógica de negocio (`BookController`, `CategoryController`).
- `app/Http/Requests/`: Validaciones encapsuladas (`StoreBookRequest`, `StoreCategoryRequest` y `UpdateBookRequest`).
- `app/Http/Resources/`: Transformación de datos para la API (`BookResource`, `CategoryResource`).
- `app/Models/`: Modelos Eloquent (`Book`, `Category`).
- `database/migrations/`: Estructura de las tablas.
- `resources/views/`: Vistas de la interfaz web (Blade).
- `routes/web.php`: Rutas de la interfaz web.
- `routes/api.php`: Rutas de la API RESTful.
- `tests/Feature/`: Pruebas automatizadas (`BookTest`, `CategoryTest`).
