# API REST de Biblioteca 📚

Una API completa para gestión de biblioteca desarrollada en PHP y MySQL, diseñada especialmente para que los estudiantes aprendan a consumir APIs usando POSTMAN.

## 🚀 Configuración Inicial

### 1. Configurar Base de Datos

1. Abrir WAMP/XAMPP
2. Acceder a phpMyAdmin
3. Ejecutar el script `database.sql` para crear la base de datos y datos de ejemplo

### 2. Configurar la API

1. Verificar que los archivos estén en `C:\wamp64\www\APILibrary\`
2. Asegurase que WAMP esté corriendo
3. La API estará disponible en: `http://localhost/APILibrary/`

## 📡 Endpoints Disponibles

### Información de la API

```
GET http://localhost/APILibrary/
```

### Gestión de Libros

| Método | Endpoint                 | Descripción                   |
| ------ | ------------------------ | ----------------------------- |
| GET    | `/index.php/libros`      | Obtener todos los libros      |
| GET    | `/index.php/libros/{id}` | Obtener libro específico      |
| POST   | `/index.php/libros`      | Crear nuevo libro             |
| PUT    | `/index.php/libros/{id}` | Actualizar libro              |
| DELETE | `/index.php/libros/{id}` | Eliminar libro                |
| GET    | `/index.php/stats`       | Estadísticas de la biblioteca |

### Ejemplos de Errores (Para Aprendizaje)

| Método | Endpoint               | Error                 |
| ------ | ---------------------- | --------------------- |
| GET    | `/index.php/error/400` | Bad Request           |
| GET    | `/index.php/error/401` | Unauthorized          |
| GET    | `/index.php/error/403` | Forbidden             |
| GET    | `/index.php/error/404` | Not Found             |
| GET    | `/index.php/error/500` | Internal Server Error |

## 📖 Ejemplos Prácticos para POSTMAN

### 1. Obtener Información de la API

```
GET http://localhost/APILibrary/
```

### 2. Obtener Todos los Libros

```
GET http://localhost/APILibrary/index.php/libros
```

### 3. Obtener Libros con Filtros

```
GET http://localhost/APILibrary/index.php/libros?genero=Realismo mágico
GET http://localhost/APILibrary/index.php/libros?autor=García Márquez
GET http://localhost/APILibrary/index.php/libros?disponible=true
GET http://localhost/APILibrary/index.php/libros?año_desde=1950&año_hasta=2000
GET http://localhost/APILibrary/index.php/libros?genero=Novela&disponible=false
```

### 4. Obtener Libro Específico

```
GET http://localhost/APILibrary/index.php/libros/1
GET http://localhost/APILibrary/index.php/libros/5
```

### 5. Crear Nuevo Libro

```
POST http://localhost/APILibrary/index.php/libros
Content-Type: application/json

{
    "titulo": "El nombre del viento",
    "autor": "Patrick Rothfuss",
    "isbn": "9788401352836",
    "genero": "Fantasía",
    "año_publicacion": 2007,
    "disponible": true
}
```

### 6. Actualizar Libro

```
PUT http://localhost/APILibrary/index.php/libros/13
Content-Type: application/json

{
    "disponible": false,
    "genero": "Fantasía épica"
}
```

### 7. Eliminar Libro

```
DELETE http://localhost/APILibrary/index.php/libros/13
```

### 8. Obtener Estadísticas

```
GET http://localhost/APILibrary/index.php/stats
```

## ❌ Ejemplos de Errores para Practicar

### Error 400 - Bad Request

```
GET http://localhost/APILibrary/index.php/error/400
```

### Error 401 - Unauthorized

```
GET http://localhost/APILibrary/index.php/error/401
```

### Error 403 - Forbidden

```
GET http://localhost/APILibrary/index.php/error/403
```

### Error 404 - Not Found

```
GET http://localhost/APILibrary/index.php/error/404
```

### Error 500 - Internal Server Error

```
GET http://localhost/APILibrary/index.php/error/500
```

## 🧪 Ejercicios para Practicar con POSTMAN

### Ejercicio 1: Operaciones Básicas

1. Obtener la información de la API
2. Listar todos los libros
3. Obtener el libro con ID 3
4. Crear un nuevo libro de tu autor favorito
5. Actualizar la disponibilidad del libro creado
6. Eliminar el libro creado

### Ejercicio 2: Filtros y Búsquedas

1. Buscar todos los libros de "Gabriel García Márquez"
2. Obtener solo los libros disponibles
3. Filtrar libros del género "Realismo mágico"
4. Buscar libros publicados entre 1960 y 1990
5. Combinar filtros: libros de fantasía disponibles

### Ejercicio 3: Manejo de Errores

1. Intentar obtener un libro con ID que no existe (ej: 999)
2. Crear un libro sin campos obligatorios
3. Crear un libro con ISBN duplicado
4. Actualizar un libro que no existe
5. Probar todos los endpoints de ejemplo de errores

### Ejercicio 4: Validaciones

1. Crear un libro con año de publicación inválido (ej: 2030)
2. Crear un libro con ISBN de formato incorrecto
3. Intentar actualizar con campos demasiado largos
4. Enviar JSON malformado

## 📥 Colección de POSTMAN

Puedes importar esta colección en POSTMAN:

```json
{
  "info": {
    "name": "Biblioteca API",
    "description": "Colección completa para la API de Biblioteca"
  },
  "item": [
    {
      "name": "Info API",
      "request": {
        "method": "GET",
        "header": [],
        "url": "http://localhost/APILibrary/"
      }
    },
    {
      "name": "Obtener Todos los Libros",
      "request": {
        "method": "GET",
        "header": [],
        "url": "http://localhost/APILibrary/libros"
      }
    },
    {
      "name": "Obtener Libro por ID",
      "request": {
        "method": "GET",
        "header": [],
        "url": "http://localhost/APILibrary/libros/1"
      }
    },
    {
      "name": "Crear Libro",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n    \"titulo\": \"Nuevo Libro\",\n    \"autor\": \"Autor Ejemplo\",\n    \"isbn\": \"9781234567890\",\n    \"genero\": \"Ficción\",\n    \"año_publicacion\": 2023,\n    \"disponible\": true\n}"
        },
        "url": "http://localhost/APILibrary/libros"
      }
    },
    {
      "name": "Actualizar Libro",
      "request": {
        "method": "PUT",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n    \"disponible\": false\n}"
        },
        "url": "http://localhost/APILibrary/libros/1"
      }
    },
    {
      "name": "Eliminar Libro",
      "request": {
        "method": "DELETE",
        "header": [],
        "url": "http://localhost/APILibrary/libros/1"
      }
    },
    {
      "name": "Estadísticas",
      "request": {
        "method": "GET",
        "header": [],
        "url": "http://localhost/APILibrary/stats"
      }
    },
    {
      "name": "Error 400",
      "request": {
        "method": "GET",
        "header": [],
        "url": "http://localhost/APILibrary/error/400"
      }
    },
    {
      "name": "Error 404",
      "request": {
        "method": "GET",
        "header": [],
        "url": "http://localhost/APILibrary/error/404"
      }
    }
  ]
}
```

## 🔧 Estructura de Respuestas

Todas las respuestas siguen este formato estándar:

### Respuesta Exitosa

```json
{
  "status": "success",
  "timestamp": "2024-01-01T12:00:00+00:00",
  "api_version": "1.0",
  "message": "Operación exitosa",
  "data": {
    // Datos solicitados
  }
}
```

### Respuesta de Error

```json
{
  "status": "error",
  "timestamp": "2024-01-01T12:00:00+00:00",
  "api_version": "1.0",
  "message": "Descripción del error",
  "error": {
    "code": "ERROR_CODE",
    "details": "Detalles específicos del error"
  }
}
```

## 📚 Esquema de Base de Datos

### Tabla: libros

| Campo               | Tipo         | Descripción          |
| ------------------- | ------------ | -------------------- |
| id                  | INT (PK, AI) | Identificador único  |
| titulo              | VARCHAR(255) | Título del libro     |
| autor               | VARCHAR(255) | Autor del libro      |
| isbn                | VARCHAR(13)  | ISBN único           |
| genero              | VARCHAR(100) | Género literario     |
| año_publicacion     | YEAR         | Año de publicación   |
| disponible          | BOOLEAN      | Disponibilidad       |
| fecha_registro      | TIMESTAMP    | Fecha de creación    |
| fecha_actualizacion | TIMESTAMP    | Última actualización |

## 🎯 Objetivos de Aprendizaje

Al usar esta API, los estudiantes aprenderán:

1. **Métodos HTTP**: GET, POST, PUT, DELETE
2. **Códigos de Estado**: 200, 201, 400, 401, 403, 404, 500
3. **Headers**: Content-Type, Accept
4. **Formato JSON**: Estructura de datos
5. **Parámetros**: Query params y path params
6. **Manejo de Errores**: Diferentes tipos y respuestas
7. **Validaciones**: Campos obligatorios y formatos
8. **Filtros**: Búsquedas y consultas específicas

## 🔧 Solución de Problemas

### Error de Conexión

- Verificar que WAMP esté ejecutándose
- Confirmar que MySQL esté activo
- Revisar credenciales en `config.php`

### Error 404 en API

- Verificar la URL base: `http://localhost/APILibrary/`
- Asegurar que los archivos estén en la carpeta correcta

### Error de Base de Datos

- Ejecutar el script `database.sql`
- Verificar que la base de datos `biblioteca_api` exista
- Confirmar que la tabla `libros` tenga datos

## 👨‍🏫 Para Profesores

Esta API está diseñada para enseñar:

- Conceptos fundamentales de APIs REST
- Uso práctico de POSTMAN
- Diferentes tipos de errores HTTP
- Validaciones y manejo de datos
- Operaciones CRUD completas

Los ejemplos progresivos permiten avanzar desde conceptos básicos hasta manejo avanzado de errores y validaciones.
