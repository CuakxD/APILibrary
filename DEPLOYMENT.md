# 🚀 Guía de Despliegue - API Biblioteca

Esta guía te llevará paso a paso para desplegar la API de Biblioteca en un servidor de producción.

## 📋 Lista de Verificación Pre-Despliegue

### ✅ Requisitos Verificados

- [ ] Servidor con PHP 7.4+
- [ ] MySQL 5.7+ o MariaDB 10.2+
- [ ] Hosting con soporte PHP/MySQL
- [ ] Acceso a phpMyAdmin o similar
- [ ] FTP/cPanel para subir archivos

### ✅ Archivos Preparados

- [ ] `database.sql` - Script de base de datos
- [ ] `config.php` - Configuración general
- [ ] `environment.php` - Detección de entorno
- [ ] `Database.php` - Clase principal de BD
- [ ] `index.php` - Router principal
- [ ] `README.md` - Documentación
- [ ] Colecciones de POSTMAN

## 🎯 Proceso de Despliegue Paso a Paso

### Paso 1: Preparar Hosting

#### 1.1 Elegir Hosting

Recomendaciones por presupuesto:

**Gratuito (para pruebas):**

- 000webhost
- InfinityFree
- AwardSpace

**Económico (recomendado):**

- Hostinger ($1-3/mes)
- Namecheap ($2-5/mes)
- SiteGround ($4-7/mes)

**Profesional:**

- DigitalOcean VPS ($5-10/mes)
- AWS EC2
- Google Cloud Platform

#### 1.2 Verificar Especificaciones

```bash
# Verificar versión PHP
php -v

# Verificar módulos PHP necesarios
php -m | grep -E "pdo|mysql|json"

# Verificar versión MySQL
mysql --version
```

### Paso 2: Configurar Base de Datos

#### 2.1 Crear Base de Datos

En cPanel/phpMyAdmin del hosting:

1. Ir a "MySQL Databases" o "phpMyAdmin"
2. Crear nueva base de datos: `nombreusuario_biblioteca_api`
3. Crear usuario MySQL con permisos completos
4. Anotar credenciales:
   - Host: `localhost` (usualmente)
   - Database: `nombreusuario_biblioteca_api`
   - Username: `nombreusuario_dbuser`
   - Password: `password_seguro`

#### 2.2 Importar Estructura

1. Acceder a phpMyAdmin
2. Seleccionar la base de datos creada
3. Ir a pestaña "Importar"
4. Subir archivo `database.sql`
5. Ejecutar importación
6. Verificar que se creó la tabla `libros` con 12 registros

### Paso 3: Subir Archivos

#### 3.1 Preparar Archivos

1. Comprimir carpeta `APILibrary` en ZIP
2. O usar FTP cliente como FileZilla

#### 3.2 Subir al Servidor

**Opción A: cPanel File Manager**

1. Ir a File Manager en cPanel
2. Navegar a `public_html`
3. Subir archivo ZIP
4. Extraer archivos

**Opción B: FTP**

```bash
# Usando FileZilla o similar
Host: ftp.tudominio.com
Usuario: tu_usuario_ftp
Password: tu_password_ftp
Puerto: 21 (o 22 para SFTP)
```

#### 3.3 Estructura Final

```
public_html/
├── APILibrary/          # O directamente en public_html/
│   ├── config.php
│   ├── environment.php
│   ├── Database.php
│   ├── index.php
│   ├── database.sql
│   └── README.md
```

### Paso 4: Configurar Ambiente de Producción

#### 4.1 Verificar Detección Automática

El archivo `environment.php` detecta automáticamente el entorno:

```php
function detectEnvironment() {
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Local development
    if (strpos($host, 'localhost') !== false ||
        strpos($host, '127.0.0.1') !== false ||
        strpos($host, '192.168.') !== false ||
        strpos($host, '10.0.') !== false) {
        return 'local';
    }

    // Production
    return 'production';
}
```

#### 4.2 Configurar Credenciales de Producción

Si es necesario, edita `environment.php`:

```php
case 'production':
    return [
        'host' => 'localhost',                    # Host de BD (usualmente localhost)
        'dbname' => 'nombreusuario_biblioteca_api', # Nombre de tu BD
        'username' => 'nombreusuario_dbuser',      # Usuario MySQL
        'password' => 'tu_password_seguro',        # Password MySQL
        'charset' => 'utf8mb4'
    ];
```

### Paso 5: Verificar Funcionamiento

#### 5.1 Test Inicial

Accede a: `https://tudominio.com/APILibrary/`

Deberías ver:

```json
{
  "status": "success",
  "api_name": "Biblioteca API",
  "current_environment": {
    "detected_host": "tudominio.com",
    "is_localhost": false,
    "protocol": "https"
  }
}
```

#### 5.2 Probar Endpoints Básicos

```bash
# Información API
curl https://tudominio.com/APILibrary/

# Listar libros
curl https://tudominio.com/APILibrary/index.php/libros

# Estadísticas
curl https://tudominio.com/APILibrary/index.php/stats

# Verificar autor
curl https://tudominio.com/APILibrary/index.php/check-author/García%20Márquez
```

#### 5.3 Test con POSTMAN

1. Abrir POSTMAN
2. Cambiar las URLs base de:
   - `http://localhost/APILibrary/`
   - A: `https://tudominio.com/APILibrary/`
3. Probar todos los endpoints

### Paso 6: Configuración de Seguridad

#### 6.1 Permisos de Archivos

```bash
# Establecer permisos seguros
chmod 644 *.php
chmod 755 .
chmod 600 environment.php  # Archivo sensible
```

#### 6.2 Headers de Seguridad

En `config.php`, la función `setCorsHeaders()` ya incluye:

```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-Type: application/json; charset=utf-8');
```

Para mayor seguridad, puedes restringir origins:

```php
header('Access-Control-Allow-Origin: https://tudominio.com');
```

#### 6.3 Ocultar Errores en Producción

En `environment.php`, para producción:

```php
if ($env === 'production') {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', '/path/to/php_errors.log');
}
```

### Paso 7: Optimización de Performance

#### 7.1 Cache de Base de Datos

Para APIs con mucho tráfico, considera implementar cache:

```php
// En Database.php, método getStats()
$cacheFile = '/tmp/stats_cache.json';
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 300) {
    return json_decode(file_get_contents($cacheFile), true);
}
```

#### 7.2 Compresión GZIP

En `.htaccess` (si lo usas):

```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE application/json
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/javascript
</IfModule>
```

### Paso 8: Monitoreo y Mantenimiento

#### 8.1 Logs de Acceso

Monitorea los logs del servidor para:

- Endpoints más utilizados
- Errores frecuentes
- Intentos de acceso malicioso

#### 8.2 Backup Automático

Script para backup diario:

```bash
#!/bin/bash
# backup_api.sh
DATE=$(date +%Y%m%d)
mysqldump -u usuario -p biblioteca_api > /backups/api_backup_$DATE.sql
tar -czf /backups/files_backup_$DATE.tar.gz /path/to/APILibrary/
```

Configurar en crontab:

```bash
# Backup diario a las 2 AM
0 2 * * * /path/to/backup_api.sh
```

## 🔧 Solución de Problemas Comunes

### Error 500 - Internal Server Error

**Causas posibles:**

1. Error de sintaxis PHP
2. Permisos incorrectos
3. Credenciales de BD incorrectas
4. Módulos PHP faltantes

**Diagnóstico:**

```bash
# Verificar logs de error
tail -f /var/log/apache2/error.log
# O en cPanel: Error Logs

# Verificar sintaxis PHP
php -l index.php
```

### Error de Conexión a Base de Datos

**Verificar:**

1. Credenciales en `environment.php`
2. Base de datos existe
3. Usuario tiene permisos
4. Servidor MySQL activo

**Test de conexión manual:**

```php
<?php
// test_db.php - Archivo temporal para probar
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=nombreusuario_biblioteca_api",
        "nombreusuario_dbuser",
        "password_seguro"
    );
    echo "Conexión exitosa";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

### URLs Incorrectas en Respuestas

**Problema:** Las URLs generadas no coinciden con el dominio real.

**Solución:** Verificar `$_SERVER['HTTP_HOST']` en `environment.php`:

```php
// Debug temporal
var_dump($_SERVER['HTTP_HOST']);
var_dump($_SERVER['HTTPS']);
```

### Performance Lenta

**Optimizaciones:**

1. Agregar índices en BD:

```sql
CREATE INDEX idx_autor ON libros(autor);
CREATE INDEX idx_genero ON libros(genero);
CREATE INDEX idx_disponible ON libros(disponible);
```

2. Limitar resultados por defecto:

```php
// En Database.php
$query .= " LIMIT 100"; // Limitar a 100 resultados
```

## 📞 Soporte y Contacto

### Logs de Debug

Para habilitar debug temporal en producción:

```php
// En environment.php
define('DEBUG_MODE', true); // Solo temporalmente
```

### Verificación de Estado

Crear endpoint de health check:

```php
// En index.php, agregar:
if ($segments[0] === 'health') {
    sendJsonResponse([
        'status' => 'healthy',
        'database' => 'connected',
        'timestamp' => date('c')
    ], 200, 'API is healthy');
}
```

### Documentación de la API

La documentación siempre está disponible en:
`https://tudominio.com/APILibrary/`

¡Tu API de Biblioteca está lista para ser utilizada por tus estudiantes! 🎉
