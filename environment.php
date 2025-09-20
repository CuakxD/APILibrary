<?php
/**
 * Configuración de entornos para la API de Biblioteca
 * Este archivo permite configurar fácilmente diferentes entornos
 */

// Configuración automática de entorno
function detectEnvironment() {
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Detectar si es entorno local
    $isLocal = in_array($host, ['localhost', '127.0.0.1']) || 
               strpos($host, 'localhost:') === 0 ||
               strpos($host, '127.0.0.1:') === 0;
    
    return [
        'is_local' => $isLocal,
        'host' => $host,
        'protocol' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http'
    ];
}

// Configuración específica por entorno
function getEnvironmentConfig() {
    $env = detectEnvironment();
    
    if ($env['is_local']) {
        // Configuración para desarrollo local
        return [
            'db_host' => 'localhost',
            'db_name' => 'biblioteca_api',
            'db_user' => 'root',
            'db_pass' => '',
            'environment_name' => 'Local Development',
            'debug_mode' => true
        ];
    } else {
        // Configuración para servidor de producción
        // IMPORTANTE: Actualiza estos valores cuando subas al servidor
        return [
            'db_host' => 'localhost', // O la IP/host de tu servidor de BD
            'db_name' => 'biblioteca_api',
            'db_user' => 'tu_usuario_bd', // Cambiar por el usuario real
            'db_pass' => 'tu_password_bd', // Cambiar por la contraseña real
            'environment_name' => 'Production Server',
            'debug_mode' => false
        ];
    }
}

// Función para mostrar información del entorno actual
function getEnvironmentInfo() {
    $env = detectEnvironment();
    $config = getEnvironmentConfig();
    
    return [
        'environment' => $config['environment_name'],
        'host' => $env['host'],
        'protocol' => $env['protocol'],
        'is_local' => $env['is_local'],
        'debug_mode' => $config['debug_mode'],
        'detected_at' => date('Y-m-d H:i:s')
    ];
}

?>