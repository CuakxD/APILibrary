# API REST de Biblioteca 📚

Una API completa para gestión de biblioteca desarrollada en PHP y MySQL, diseñada especialmente para que los estudiantes aprendan a consumir APIs usando POSTMAN. Incluye detección automática de entorno, estadísticas avanzadas y endpoints de verificación.

## ✨ Características

- ✅ **CRUD completo** para gestión de libros
- ✅ **Detección automática de entorno** (local/producción)
- ✅ **Estadísticas dinámicas** en tiempo real
- ✅ **Endpoints de verificación** de autores y géneros
- ✅ **Filtros avanzados** para búsquedas
- ✅ **Ejemplos de errores** para aprendizaje
- ✅ **Documentación interactiva** con URLs de prueba
- ✅ **Colección de POSTMAN** incluida
- ✅ **Sin dependencia de .htaccess**

## 🚀 Configuración Inicial

### 1. Configurar Base de Datos

1. Abrir WAMP/XAMPP
2. Acceder a phpMyAdmin
3. Ejecutar el script `database.sql` para crear la base de datos y datos de ejemplo

### 2. Configurar la API

1. Verificar que los archivos estén en `C:\wamp64\www\APILibrary\`
2. Asegurase que WAMP esté corriendo
3. La API estará disponible en: `http://localhost/APILibrary/`

### 3. Verificar Funcionamiento

Accede a `http://localhost/APILibrary/` para ver la documentación interactiva con URLs de prueba automáticamente generadas según tu entorno.

## 📡 Endpoints Disponibles

### Información de la API

```
GET http://localhost/APILibrary/
```

### Gestión de Libros

| Método | Endpoint                 | Descripción              |
| ------ | ------------------------ | ------------------------ |
| GET    | `/index.php/libros`      | Obtener todos los libros |
| GET    | `/index.php/libros/{id}` | Obtener libro específico |
| POST   | `/index.php/libros`      | Crear nuevo libro        |
| PUT    | `/index.php/libros/{id}` | Actualizar libro         |
| DELETE | `/index.php/libros/{id}` | Eliminar libro           |

### Estadísticas y Análisis

| Método | Endpoint                           | Descripción                          |
| ------ | ---------------------------------- | ------------------------------------ |
| GET    | `/index.php/stats`                 | Estadísticas completas de biblioteca |
| GET    | `/index.php/check-author/{nombre}` | Verificar si existe un autor         |
| GET    | `/index.php/check-genre/{nombre}`  | Verificar si existe un género        |
| GET    | `/index.php/author-info/{nombre}`  | Información detallada de un autor    |

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

**Respuesta incluye:**

- Total de libros y disponibilidad
- Conteo de géneros y autores únicos
- Top 10 autores con más libros
- Lista completa de todos los autores
- Lista completa de todos los géneros
- Distribución de libros por década

### 9. Verificar Existencia de Autor

```
GET http://localhost/APILibrary/index.php/check-author/García Márquez
GET http://localhost/APILibrary/index.php/check-author/J.K. Rowling
```

### 10. Verificar Existencia de Género

```
GET http://localhost/APILibrary/index.php/check-genre/Realismo mágico
GET http://localhost/APILibrary/index.php/check-genre/Ciencia ficción
```

### 11. Información Detallada de Autor

```
GET http://localhost/APILibrary/index.php/author-info/García Márquez
```

**Respuesta incluye:**

- Total de libros del autor
- Libros disponibles
- Primer y último año de publicación
- Géneros en los que escribe

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

### Ejercicio 3: Estadísticas y Verificaciones

1. Obtener estadísticas completas de la biblioteca
2. Verificar si existe el autor "Antoine de Saint-Exupery"
3. Verificar si existe el género "Literatura infantil"
4. Obtener información detallada de "García Márquez"
5. Agregar un nuevo libro y verificar que las estadísticas se actualicen

### Ejercicio 4: Manejo de Errores

1. Intentar obtener un libro con ID que no existe (ej: 999)
2. Crear un libro sin campos obligatorios
3. Crear un libro con ISBN duplicado
4. Actualizar un libro que no existe
5. Probar todos los endpoints de ejemplo de errores

### Ejercicio 5: Validaciones

1. Crear un libro con año de publicación inválido (ej: 2030)
2. Crear un libro con ISBN de formato incorrecto
3. Intentar actualizar con campos demasiado largos
4. Enviar JSON malformado

### Ejercicio 6: Flujo Completo de Verificación

1. Verificar si existe un autor antes de agregarlo
2. Crear un libro con un autor nuevo
3. Verificar que el autor ahora existe
4. Obtener información detallada del nuevo autor
5. Verificar que las estadísticas reflejen el cambio

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
      "name": "Verificar Autor",
      "request": {
        "method": "GET",
        "header": [],
        "url": "http://localhost/APILibrary/check-author/García Márquez"
      }
    },
    {
      "name": "Verificar Género",
      "request": {
        "method": "GET",
        "header": [],
        "url": "http://localhost/APILibrary/check-genre/Realismo mágico"
      }
    },
    {
      "name": "Info del Autor",
      "request": {
        "method": "GET",
        "header": [],
        "url": "http://localhost/APILibrary/author-info/García Márquez"
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
- Análisis de estadísticas dinámicas
- Verificación de existencia de datos

Los ejemplos progresivos permiten avanzar desde conceptos básicos hasta manejo avanzado de errores y validaciones.

## 🚀 Despliegue en Producción

### 1. Preparación del Servidor

#### Requisitos del Servidor

- **PHP 7.4 o superior** (recomendado PHP 8.0+)
- **MySQL 5.7 o superior** / **MariaDB 10.2+**
- **Apache** con mod_rewrite habilitado
- **Extensiones PHP**: PDO, PDO_MySQL, JSON

#### Verificar Requisitos

```bash
php -v
mysql --version
php -m | grep -E "pdo|json"
```

### 2. Configuración del Dominio y Hosting

#### Opciones de Hosting Recomendadas

- **Hostinger** (económico, buen soporte PHP)
- **SiteGround** (rendimiento optimizado)
- **DigitalOcean** (VPS para mayor control)
- **000webhost** (gratuito para pruebas)

#### Subir Archivos al Servidor

1. Comprimir la carpeta `APILibrary` en un archivo ZIP
2. Subir mediante FTP/cPanel File Manager al directorio `public_html`
3. Extraer los archivos directamente en `public_html` o en una subcarpeta

### 3. Configuración de Base de Datos en Producción

#### Crear Base de Datos en cPanel/Hosting

```sql
-- En phpMyAdmin del hosting:
CREATE DATABASE nombre_usuario_biblioteca_api;
```

#### Ejecutar Script de Base de Datos

1. Acceder a phpMyAdmin del hosting
2. Seleccionar la base de datos creada
3. Importar el archivo `database.sql`
4. Verificar que la tabla `libros` se creó con datos

### 4. Configuración Automática de Entorno

La API detecta automáticamente el entorno:

```php
// environment.php - Detección automática
function detectEnvironment() {
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    if (strpos($host, 'localhost') !== false ||
        strpos($host, '127.0.0.1') !== false) {
        return 'local';
    }
    return 'production';
}
```

#### Configurar Credenciales de Producción

Editar `environment.php` si es necesario:

```php
case 'production':
    return [
        'host' => 'localhost', // O IP del servidor de BD
        'dbname' => 'tu_usuario_biblioteca_api',
        'username' => 'tu_usuario_db',
        'password' => 'tu_password_db',
        'charset' => 'utf8mb4'
    ];
```

### 5. URLs de Producción

Una vez desplegado, las URLs serán:

```
https://tudominio.com/APILibrary/
https://tudominio.com/APILibrary/index.php/libros
https://tudominio.com/APILibrary/index.php/stats
```

La API genera automáticamente las URLs correctas según el entorno detectado.

### 6. Verificación del Despliegue

#### Test Básico

```bash
curl https://tudominio.com/APILibrary/
```

#### Verificar Funcionalidad

1. Acceder a `https://tudominio.com/APILibrary/`
2. Probar los enlaces de la documentación
3. Verificar que las URLs se generan correctamente
4. Probar operaciones CRUD desde POSTMAN

### 7. Configuración de Seguridad (Opcional)

#### Limitar Acceso por IP (si es necesario)

```apache
# En .htaccess (si lo usas)
<RequireAll>
    Require ip 192.168.1.0/24
    Require ip 10.0.0.0/8
</RequireAll>
```

#### Headers de Seguridad Adicionales

```php
// En config.php, agregar al función setCorsHeaders():
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
```

### 8. Monitoreo y Logs

#### Habilitar Logs de Errores PHP

```php
// En environment.php para producción:
if ($env === 'production') {
    ini_set('log_errors', 1);
    ini_set('error_log', '/path/to/error.log');
    ini_set('display_errors', 0);
}
```

#### Monitorear Base de Datos

```sql
-- Verificar conexiones activas
SHOW PROCESSLIST;

-- Verificar performance
SHOW STATUS LIKE 'Threads_connected';
```

### 9. Backup y Mantenimiento

#### Backup Automático de Base de Datos

```bash
# Script de backup (ejecutar via cron)
mysqldump -u usuario -p biblioteca_api > backup_$(date +%Y%m%d).sql
```

#### Actualizaciones

1. Backup de archivos y BD
2. Subir nuevos archivos
3. Verificar funcionamiento
4. Actualizar documentación si es necesario

### 10. Solución de Problemas en Producción

#### Error 500 - Internal Server Error

```bash
# Verificar logs de error
tail -f /path/to/error.log

# Verificar permisos
chmod 644 *.php
chmod 755 .
```

#### Error de Conexión a BD

```php
// Verificar credenciales en environment.php
// Verificar que la BD esté activa
// Comprobar límites de conexiones del hosting
```

#### URLs Incorrectas

```php
// La detección automática debería funcionar
// Verificar $_SERVER['HTTP_HOST'] en environment.php
```

## 📊 Estadísticas de Uso

### Métricas que Puedes Rastrear

- Número total de requests por endpoint
- Libros más consultados
- Autores más buscados
- Errores más frecuentes
- Filtros más utilizados

### Implementar Logging (Opcional)

```php
// En Database.php, agregar a cada método:
error_log("API Call: {$method} {$endpoint} at " . date('Y-m-d H:i:s'));
```

## 🔄 Actualizaciones y Versionado

### Versión Actual: 1.0

- ✅ CRUD completo de libros
- ✅ Estadísticas avanzadas
- ✅ Endpoints de verificación
- ✅ Detección automática de entorno
- ✅ Documentación interactiva

### Próximas Mejoras Sugeridas

- 🔜 Autenticación con tokens
- 🔜 Paginación para listados grandes
- 🔜 Cache de estadísticas
- 🔜 Rate limiting
- 🔜 Logs de auditoría
