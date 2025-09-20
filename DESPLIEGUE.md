# 🚀 Instrucciones para Subir la API al Servidor

## ✅ Configuración Automática

La API ahora detecta automáticamente si está corriendo en localhost o en un servidor y ajusta las URLs en consecuencia.

## 📋 Pasos para Subir al Servidor:

### 1. Subir Archivos

Sube todos los archivos a tu servidor web:

- `index.php`
- `config.php`
- `environment.php`
- `Database.php`
- `database.sql`
- `README.md`
- Archivos de POSTMAN (\*.json)

### 2. Configurar Base de Datos en el Servidor

1. Accede a phpMyAdmin (o tu gestor de BD)
2. Crea una nueva base de datos llamada `biblioteca_api`
3. Ejecuta el script `database.sql`

### 3. Actualizar Credenciales de BD (IMPORTANTE)

Edita el archivo `environment.php` y actualiza la sección de producción:

```php
// Configuración para servidor de producción
return [
    'db_host' => 'localhost', // O la IP de tu servidor de BD
    'db_name' => 'biblioteca_api',
    'db_user' => 'tu_usuario_real', // ⚠️ CAMBIAR ESTO
    'db_pass' => 'tu_password_real', // ⚠️ CAMBIAR ESTO
    'environment_name' => 'Production Server',
    'debug_mode' => false
];
```

### 4. Probar la API en el Servidor

Accede a: `https://tu-dominio.com/ruta-a-tu-api/`

## 🔧 URLs Dinámicas

### En Local (automático):

- Base: `http://localhost/APILibrary/`
- Libros: `http://localhost/APILibrary/index.php/libros`

### En Servidor (automático):

- Base: `https://tu-dominio.com/ruta-api/`
- Libros: `https://tu-dominio.com/ruta-api/index.php/libros`

## 📱 Para tus Alumnos

### Opción 1: Colección de POSTMAN con Variable

1. Importar: `Biblioteca_API_SinHtaccess.postman_collection.json`
2. Cambiar la variable `{{base_url}}` de:
   - `http://localhost/APILibrary` (local)
   - `https://tu-dominio.com/ruta-api` (servidor)

### Opción 2: URLs Automáticas

Los alumnos pueden acceder directamente a la URL principal y obtener URLs clickeables:

- Local: `http://localhost/APILibrary/`
- Servidor: `https://tu-dominio.com/ruta-api/`

## 🎯 Ventajas de la Configuración Automática

1. **Sin modificación de código** - Funciona automáticamente
2. **URLs dinámicas** - Se adaptan al entorno actual
3. **Información del entorno** - Muestra si es local o servidor
4. **Configuración separada** - Fácil de mantener
5. **Debug automático** - Activado solo en local

## 🔍 Verificación

La API mostrará información del entorno actual en la sección `current_environment`:

```json
{
  "current_environment": {
    "detected_host": "tu-dominio.com",
    "is_localhost": false,
    "protocol": "https"
  }
}
```

## ⚠️ Nota de Seguridad

Recuerda actualizar las credenciales de la base de datos en `environment.php` antes de subir al servidor de producción.

## 🆘 Solución de Problemas

### Error de Conexión a BD

1. Verificar credenciales en `environment.php`
2. Confirmar que la BD `biblioteca_api` existe
3. Verificar permisos del usuario de BD

### URLs Incorrectas

La API debería detectar automáticamente el entorno. Si no funciona:

1. Verificar el archivo `environment.php`
2. Revisar la configuración del servidor web
3. Confirmar que `$_SERVER['HTTP_HOST']` tiene el valor correcto
