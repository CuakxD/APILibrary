<?php
/**
 * Configuración de la base de datos para API de Biblioteca
 * Ahora detecta automáticamente el entorno (local vs servidor)
 */

require_once 'environment.php';

// Obtener configuración según el entorno
$envConfig = getEnvironmentConfig();

// Configuración de la base de datos (dinámica según entorno)
define('DB_HOST', $envConfig['db_host']);
define('DB_NAME', $envConfig['db_name']);
define('DB_USER', $envConfig['db_user']);
define('DB_PASS', $envConfig['db_pass']);
define('DB_CHARSET', 'utf8');

// Configuración de la API
define('API_VERSION', '1.0');
define('API_NAME', 'Biblioteca API');
define('DEBUG_MODE', $envConfig['debug_mode']);

// Configuración de headers CORS para permitir peticiones desde cualquier origen
function setCorsHeaders() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Content-Type: application/json; charset=UTF-8');
    
    // Manejar peticiones OPTIONS (preflight)
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

// Función para enviar respuesta JSON estandarizada
function sendJsonResponse($data, $statusCode = 200, $message = null) {
    http_response_code($statusCode);
    
    $response = [
        'status' => $statusCode < 400 ? 'success' : 'error',
        'timestamp' => date('c'),
        'api_version' => API_VERSION
    ];
    
    if ($message) {
        $response['message'] = $message;
    }
    
    if ($statusCode < 400) {
        $response['data'] = $data;
    } else {
        $response['error'] = $data;
    }
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
}

// Función para validar JSON de entrada
function getJsonInput() {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendJsonResponse([
            'code' => 'INVALID_JSON',
            'details' => 'El JSON enviado no es válido: ' . json_last_error_msg()
        ], 400, 'Error en el formato JSON');
    }
    
    return $data;
}

// Función para validar campos requeridos
function validateRequiredFields($data, $requiredFields) {
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $missingFields[] = $field;
        }
    }
    
    if (!empty($missingFields)) {
        sendJsonResponse([
            'code' => 'MISSING_FIELDS',
            'missing_fields' => $missingFields,
            'details' => 'Los siguientes campos son obligatorios: ' . implode(', ', $missingFields)
        ], 400, 'Campos obligatorios faltantes');
    }
}

?>