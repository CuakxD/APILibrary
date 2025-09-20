<?php
/**
 * API REST para Biblioteca - Endpoint Principal
 * 
 * Esta API permite gestionar una biblioteca con operaciones CRUD completas
 * Incluye ejemplos de diferentes tipos de errores para aprendizaje con POSTMAN
 * 
 * Endpoints disponibles:
 * GET    /APILibrary/              -> Información de la API
 * GET    /APILibrary/libros        -> Obtener todos los libros
 * GET    /APILibrary/libros/{id}   -> Obtener libro específico
 * POST   /APILibrary/libros        -> Crear nuevo libro
 * PUT    /APILibrary/libros/{id}   -> Actualizar libro
 * DELETE /APILibrary/libros/{id}   -> Eliminar libro
 * GET    /APILibrary/stats         -> Estadísticas de la biblioteca
 * 
 * Endpoints para ejemplos de errores:
 * GET    /APILibrary/error/400     -> Bad Request
 * GET    /APILibrary/error/401     -> Unauthorized
 * GET    /APILibrary/error/403     -> Forbidden
 * GET    /APILibrary/error/404     -> Not Found
 * GET    /APILibrary/error/500     -> Internal Server Error
 */

require_once 'config.php';
require_once 'Database.php';

// Configurar headers CORS
setCorsHeaders();

// Función para detectar la URL base automáticamente
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    
    // Remover 'index.php' del final si está presente
    $basePath = str_replace('/index.php', '', $scriptName);
    
    return $protocol . '://' . $host . $basePath;
}

// Obtener la URL base dinámica
$baseUrl = getBaseUrl();

// Obtener el método HTTP y la ruta
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Obtener la parte del path sin query parameters
$parsedUrl = parse_url($requestUri);
$path = $parsedUrl['path'] ?? '';

// Remover el prefijo de la ruta (ajustar según tu configuración)
$basePath = '/APILibrary';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

// Eliminar index.php si está presente
$path = str_replace('/index.php', '', $path);
$path = trim($path, '/');

// Si no hay path y tenemos parámetros, usar parámetros GET para determinar el endpoint
if (empty($path) && isset($_GET['endpoint'])) {
    $path = $_GET['endpoint'];
    if (isset($_GET['id'])) {
        $path .= '/' . $_GET['id'];
    }
}

// Dividir la ruta en segmentos
$segments = $path ? explode('/', $path) : [''];

// Crear instancia de la base de datos
$database = new Database();
$db = $database->connect();

// Router principal
switch ($method) {
    case 'GET':
        handleGetRequest($segments, $database);
        break;
    
    case 'POST':
        handlePostRequest($segments, $database);
        break;
    
    case 'PUT':
        handlePutRequest($segments, $database);
        break;
    
    case 'DELETE':
        handleDeleteRequest($segments, $database);
        break;
    
    default:
        sendJsonResponse([
            'code' => 'METHOD_NOT_ALLOWED',
            'details' => 'Método HTTP no permitido',
            'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE']
        ], 405, 'Método no permitido');
}

/**
 * Manejar peticiones GET
 */
function handleGetRequest($segments, $database) {
    global $baseUrl;
    
    // Ruta raíz - información de la API
    if (empty($segments[0])) {
        sendJsonResponse([
            'api_name' => API_NAME,
            'version' => API_VERSION,
            'description' => 'API REST para gestión de biblioteca',
            'base_url' => $baseUrl,
            'current_environment' => [
                'detected_host' => $_SERVER['HTTP_HOST'],
                'is_localhost' => strpos($_SERVER['HTTP_HOST'], 'localhost') !== false,
                'protocol' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http'
            ],
            'documentation' => [
                'info' => 'Haz clic en las URLs para probarlas directamente en POSTMAN',
                'postman_collection' => 'Importa: Biblioteca_API_SinHtaccess.postman_collection.json',
                'note' => 'Las URLs se generan automáticamente según el entorno actual'
            ],
            'quick_test_urls' => [
                'Obtener todos los libros' => $baseUrl . '/index.php/libros',
                'Obtener libro específico' => $baseUrl . '/index.php/libros/1',
                'Crear nuevo libro (POST)' => $baseUrl . '/index.php/libros',
                'Actualizar libro (PUT)' => $baseUrl . '/index.php/libros/1',
                'Eliminar libro (DELETE)' => $baseUrl . '/index.php/libros/1',
                'Estadísticas' => $baseUrl . '/index.php/stats',
                'Verificar autor' => $baseUrl . '/index.php/check-author/García Márquez',
                'Verificar género' => $baseUrl . '/index.php/check-genre/Realismo mágico',
                'Info del autor' => $baseUrl . '/index.php/author-info/García Márquez'
            ],
            'filter_examples' => [
                'Filtrar por género' => $baseUrl . '/index.php/libros?genero=Realismo mágico',
                'Filtrar por autor' => $baseUrl . '/index.php/libros?autor=García Márquez',
                'Libros disponibles' => $baseUrl . '/index.php/libros?disponible=true',
                'Libros por año' => $baseUrl . '/index.php/libros?año_desde=1950&año_hasta=2000',
                'Filtros combinados' => $baseUrl . '/index.php/libros?genero=Novela&disponible=true'
            ],
            'error_test_urls' => [
                'Error 400 - Bad Request' => $baseUrl . '/index.php/error/400',
                'Error 401 - Unauthorized' => $baseUrl . '/index.php/error/401',
                'Error 403 - Forbidden' => $baseUrl . '/index.php/error/403',
                'Error 404 - Not Found' => $baseUrl . '/index.php/error/404',
                'Error 500 - Server Error' => $baseUrl . '/index.php/error/500',
                'Libro inexistente' => $baseUrl . '/index.php/libros/999'
            ],
            'endpoints_reference' => [
                'GET /' => 'Información de la API',
                'GET /index.php/libros' => 'Obtener todos los libros (acepta filtros)',
                'GET /index.php/libros/{id}' => 'Obtener libro específico',
                'POST /index.php/libros' => 'Crear nuevo libro',
                'PUT /index.php/libros/{id}' => 'Actualizar libro',
                'DELETE /index.php/libros/{id}' => 'Eliminar libro',
                'GET /index.php/stats' => 'Estadísticas de la biblioteca',
                'GET /index.php/check-author/{nombre}' => 'Verificar si un autor existe',
                'GET /index.php/check-genre/{nombre}' => 'Verificar si un género existe',
                'GET /index.php/author-info/{nombre}' => 'Información detallada del autor',
                'GET /index.php/error/{code}' => 'Ejemplos de errores'
            ],
            'available_filters' => [
                'genero' => 'Filtrar por género literario',
                'autor' => 'Filtrar por nombre del autor',
                'disponible' => 'Filtrar por disponibilidad (true/false)',
                'año_desde' => 'Filtrar desde año de publicación',
                'año_hasta' => 'Filtrar hasta año de publicación'
            ]
        ]);
    }
    
    // Ejemplos de errores
    if ($segments[0] === 'error') {
        handleErrorExamples($segments[1] ?? null);
    }
    
    // Estadísticas
    if ($segments[0] === 'stats') {
        $stats = $database->getStats();
        sendJsonResponse($stats, 200, 'Estadísticas de la biblioteca obtenidas exitosamente');
    }
    
    // Verificar si un autor existe
    if ($segments[0] === 'check-author' && isset($segments[1])) {
        $authorName = urldecode($segments[1]);
        $exists = $database->checkAuthorExists($authorName);
        sendJsonResponse([
            'author' => $authorName,
            'exists' => $exists,
            'message' => $exists ? 'El autor existe en la base de datos' : 'El autor no existe en la base de datos'
        ], 200, 'Verificación de autor completada');
    }
    
    // Verificar si un género existe
    if ($segments[0] === 'check-genre' && isset($segments[1])) {
        $genreName = urldecode($segments[1]);
        $exists = $database->checkGenreExists($genreName);
        sendJsonResponse([
            'genre' => $genreName,
            'exists' => $exists,
            'message' => $exists ? 'El género existe en la base de datos' : 'El género no existe en la base de datos'
        ], 200, 'Verificación de género completada');
    }
    
    // Obtener información detallada de un autor
    if ($segments[0] === 'author-info' && isset($segments[1])) {
        $authorName = urldecode($segments[1]);
        $info = $database->getAuthorInfo($authorName);
        if ($info) {
            sendJsonResponse([
                'author' => $authorName,
                'info' => $info,
                'message' => 'Información del autor obtenida correctamente'
            ], 200, 'Información del autor obtenida');
        } else {
            sendJsonResponse([
                'author' => $authorName,
                'message' => 'No se encontró información del autor'
            ], 404, 'Autor no encontrado');
        }
    }
    
    // Rutas de libros
    if ($segments[0] === 'libros') {
        // Obtener libro específico
        if (isset($segments[1]) && is_numeric($segments[1])) {
            $book = $database->getBookById($segments[1]);
            sendJsonResponse($book, 200, 'Libro obtenido exitosamente');
        }
        
        // Obtener todos los libros con filtros opcionales
        $filters = [];
        if (isset($_GET['genero'])) $filters['genero'] = $_GET['genero'];
        if (isset($_GET['autor'])) $filters['autor'] = $_GET['autor'];
        if (isset($_GET['disponible'])) $filters['disponible'] = filter_var($_GET['disponible'], FILTER_VALIDATE_BOOLEAN);
        if (isset($_GET['año_desde'])) $filters['año_desde'] = $_GET['año_desde'];
        if (isset($_GET['año_hasta'])) $filters['año_hasta'] = $_GET['año_hasta'];
        
        $books = $database->getAllBooks($filters);
        $message = empty($filters) ? 'Libros obtenidos exitosamente' : 'Libros filtrados obtenidos exitosamente';
        sendJsonResponse([
            'total' => count($books),
            'filters_applied' => $filters,
            'libros' => $books
        ], 200, $message);
    }
    
    // Ruta no encontrada
    sendJsonResponse([
        'code' => 'ENDPOINT_NOT_FOUND',
        'details' => 'El endpoint solicitado no existe',
        'path' => '/' . implode('/', $segments)
    ], 404, 'Endpoint no encontrado');
}

/**
 * Manejar peticiones POST
 */
function handlePostRequest($segments, $database) {
    if ($segments[0] !== 'libros') {
        sendJsonResponse([
            'code' => 'ENDPOINT_NOT_FOUND',
            'details' => 'Endpoint POST no válido'
        ], 404, 'Endpoint no encontrado');
    }
    
    // No debe tener ID en la URL para crear
    if (isset($segments[1])) {
        sendJsonResponse([
            'code' => 'INVALID_POST_ENDPOINT',
            'details' => 'Para crear un libro use POST /libros (sin ID)'
        ], 400, 'Endpoint POST inválido');
    }
    
    $data = getJsonInput();
    $newBook = $database->createBook($data);
    
    sendJsonResponse($newBook, 201, 'Libro creado exitosamente');
}

/**
 * Manejar peticiones PUT
 */
function handlePutRequest($segments, $database) {
    if ($segments[0] !== 'libros' || !isset($segments[1])) {
        sendJsonResponse([
            'code' => 'INVALID_PUT_ENDPOINT',
            'details' => 'Para actualizar un libro use PUT /libros/{id}'
        ], 400, 'Endpoint PUT inválido');
    }
    
    $id = $segments[1];
    $data = getJsonInput();
    $updatedBook = $database->updateBook($id, $data);
    
    sendJsonResponse($updatedBook, 200, 'Libro actualizado exitosamente');
}

/**
 * Manejar peticiones DELETE
 */
function handleDeleteRequest($segments, $database) {
    if ($segments[0] !== 'libros' || !isset($segments[1])) {
        sendJsonResponse([
            'code' => 'INVALID_DELETE_ENDPOINT',
            'details' => 'Para eliminar un libro use DELETE /libros/{id}'
        ], 400, 'Endpoint DELETE inválido');
    }
    
    $id = $segments[1];
    $deletedBook = $database->deleteBook($id);
    
    sendJsonResponse([
        'deleted_book' => $deletedBook,
        'message' => 'El libro ha sido eliminado permanentemente'
    ], 200, 'Libro eliminado exitosamente');
}

/**
 * Manejar ejemplos de diferentes tipos de errores
 */
function handleErrorExamples($errorCode) {
    switch ($errorCode) {
        case '400':
            sendJsonResponse([
                'code' => 'BAD_REQUEST_EXAMPLE',
                'details' => 'Este es un ejemplo de error 400 Bad Request',
                'common_causes' => [
                    'JSON malformado',
                    'Campos obligatorios faltantes',
                    'Tipos de datos incorrectos',
                    'Valores fuera de rango'
                ]
            ], 400, 'Solicitud incorrecta');
            
        case '401':
            sendJsonResponse([
                'code' => 'UNAUTHORIZED_EXAMPLE',
                'details' => 'Este es un ejemplo de error 401 Unauthorized',
                'common_causes' => [
                    'Token de autenticación faltante',
                    'Token expirado',
                    'Credenciales inválidas'
                ]
            ], 401, 'No autorizado');
            
        case '403':
            sendJsonResponse([
                'code' => 'FORBIDDEN_EXAMPLE',
                'details' => 'Este es un ejemplo de error 403 Forbidden',
                'common_causes' => [
                    'Permisos insuficientes',
                    'Acceso denegado al recurso',
                    'Operación no permitida'
                ]
            ], 403, 'Acceso prohibido');
            
        case '404':
            sendJsonResponse([
                'code' => 'NOT_FOUND_EXAMPLE',
                'details' => 'Este es un ejemplo de error 404 Not Found',
                'common_causes' => [
                    'Recurso no existe',
                    'URL incorrecta',
                    'ID inexistente'
                ]
            ], 404, 'Recurso no encontrado');
            
        case '500':
            sendJsonResponse([
                'code' => 'INTERNAL_SERVER_ERROR_EXAMPLE',
                'details' => 'Este es un ejemplo de error 500 Internal Server Error',
                'common_causes' => [
                    'Error en la base de datos',
                    'Error de conexión',
                    'Error interno del servidor'
                ]
            ], 500, 'Error interno del servidor');
            
        default:
            sendJsonResponse([
                'code' => 'INVALID_ERROR_CODE',
                'details' => 'Código de error no válido para ejemplo',
                'available_codes' => ['400', '401', '403', '404', '500']
            ], 400, 'Código de error inválido');
    }
}

?>