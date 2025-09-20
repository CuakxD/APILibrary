# 🔧 Configuración de Entorno - API Biblioteca

## Variables de Entorno para Producción

Copia este archivo como referencia para configurar tu entorno de producción.

### 📝 Credenciales de Base de Datos

Edita `environment.php` con tus credenciales reales:

```php
case 'production':
    return [
        'host' => 'localhost',                    // Host de tu base de datos
        'dbname' => 'CAMBIA_POR_TU_BD',          // Nombre de tu base de datos
        'username' => 'CAMBIA_POR_TU_USUARIO',   // Usuario de MySQL
        'password' => 'CAMBIA_POR_TU_PASSWORD',  // Password de MySQL
        'charset' => 'utf8mb4'
    ];
```

### 🏠 Ejemplos por Hosting

#### Hostinger

```php
'host' => 'localhost',
'dbname' => 'u123456_biblioteca_api',
'username' => 'u123456_dbuser',
'password' => 'TuPasswordSeguro123',
```

#### cPanel/WHM General

```php
'host' => 'localhost',
'dbname' => 'tuusuario_biblioteca_api',
'username' => 'tuusuario_dbuser',
'password' => 'password_generado',
```

#### DigitalOcean/VPS

```php
'host' => 'localhost',  // o IP privada
'dbname' => 'biblioteca_api',
'username' => 'api_user',
'password' => 'password_muy_seguro',
```

### 🔒 Configuración de Seguridad

#### Variables de Configuración Adicionales

```php
// En environment.php, agregar al array de producción:
'debug_mode' => false,           // Nunca true en producción
'log_errors' => true,            // Siempre logear errores
'display_errors' => false,       // Nunca mostrar errores al cliente
'log_file' => '/logs/api.log',   // Ruta para logs personalizados
'max_results' => 100,            // Límite de resultados por consulta
'cache_enabled' => true,         // Habilitar cache si es necesario
'cache_duration' => 300,         // 5 minutos de cache
```

#### Headers de Seguridad Personalizados

```php
// En config.php, personalizar setCorsHeaders():
function setCorsHeaders() {
    // Para producción, restringir origins específicos
    $allowedOrigins = [
        'https://tudominio.com',
        'https://www.tudominio.com',
        'https://admin.tudominio.com'
    ];

    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if (in_array($origin, $allowedOrigins)) {
        header("Access-Control-Allow-Origin: $origin");
    }

    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Content-Type: application/json; charset=utf-8');

    // Headers adicionales de seguridad
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}
```

### 🌐 URLs de Producción

Una vez desplegado, actualiza estas URLs en tu documentación:

```
Base URL: https://tudominio.com/APILibrary/

Endpoints principales:
https://tudominio.com/APILibrary/index.php/libros
https://tudominio.com/APILibrary/index.php/stats
https://tudominio.com/APILibrary/index.php/check-author/{nombre}
https://tudominio.com/APILibrary/index.php/check-genre/{nombre}
https://tudominio.com/APILibrary/index.php/author-info/{nombre}
```

### 📊 Configuración de Performance

#### Índices de Base de Datos Recomendados

```sql
-- Ejecutar en phpMyAdmin después del despliegue
USE tu_base_de_datos;

-- Índices para mejorar performance
CREATE INDEX idx_autor ON libros(autor);
CREATE INDEX idx_genero ON libros(genero);
CREATE INDEX idx_disponible ON libros(disponible);
CREATE INDEX idx_ano_publicacion ON libros(año_publicacion);

-- Índice compuesto para filtros comunes
CREATE INDEX idx_genero_disponible ON libros(genero, disponible);
CREATE INDEX idx_autor_ano ON libros(autor, año_publicacion);
```

#### Cache de Estadísticas (Opcional)

```php
// En Database.php, método getStats() modificado:
public function getStats() {
    $cacheFile = sys_get_temp_dir() . '/biblioteca_stats.json';
    $cacheTime = 300; // 5 minutos

    // Verificar si el cache es válido
    if (file_exists($cacheFile) &&
        (time() - filemtime($cacheFile)) < $cacheTime) {
        return json_decode(file_get_contents($cacheFile), true);
    }

    // Generar estadísticas...
    $stats = [/* datos de estadísticas */];

    // Guardar en cache
    file_put_contents($cacheFile, json_encode($stats));

    return $stats;
}
```

### 🔐 Variables de Entorno de Sistema (Avanzado)

Para VPS o servidores dedicados:

```bash
# /etc/environment o ~/.bashrc
export DB_HOST="localhost"
export DB_NAME="biblioteca_api"
export DB_USER="api_user"
export DB_PASS="password_super_seguro"
export API_ENV="production"
export API_DEBUG="false"
```

```php
// En environment.php, usar variables de sistema:
case 'production':
    return [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'dbname' => getenv('DB_NAME') ?: 'biblioteca_api',
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASS'),
        'charset' => 'utf8mb4'
    ];
```

### 📝 Lista de Verificación Pre-Despliegue

#### ✅ Base de Datos

- [ ] Base de datos creada
- [ ] Usuario MySQL configurado con permisos
- [ ] Script `database.sql` ejecutado exitosamente
- [ ] Tabla `libros` contiene 13 registros (12 originales + El Principito)
- [ ] Índices de performance aplicados

#### ✅ Archivos

- [ ] Todos los archivos PHP subidos
- [ ] Permisos configurados (644 para archivos, 755 para directorios)
- [ ] `environment.php` configurado con credenciales correctas
- [ ] Archivos sensibles protegidos

#### ✅ Configuración

- [ ] Detección automática de entorno funcionando
- [ ] Headers CORS configurados según necesidades
- [ ] Logs de error configurados
- [ ] Debug mode deshabilitado en producción

#### ✅ Pruebas

- [ ] URL principal responde correctamente
- [ ] Endpoints básicos funcionan (GET /libros)
- [ ] Operaciones CRUD funcionan
- [ ] Estadísticas se generan correctamente
- [ ] Endpoints de verificación responden
- [ ] Manejo de errores funciona apropiadamente

### 🆘 Contactos de Emergencia

```php
// Endpoint de estado para monitoreo:
// GET /health
{
    "status": "healthy",
    "database": "connected",
    "environment": "production",
    "version": "1.0",
    "last_check": "2024-01-01T12:00:00Z"
}
```

### 📚 Recursos Adicionales

- **Documentación oficial**: Disponible en tu dominio/APILibrary/
- **Colección POSTMAN**: `Biblioteca_API_SinHtaccess.postman_collection.json`
- **Script de BD**: `database.sql`
- **Guía de despliegue**: `DEPLOYMENT.md`
- **Este archivo**: `ENVIRONMENT_CONFIG.md`

¡Tu configuración está lista para producción! 🚀
