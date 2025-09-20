<?php
require_once 'config.php';

/**
 * Clase Database para manejar las operaciones de la base de datos
 * Incluye métodos para todos los CRUD de libros y manejo de errores
 */
class Database {
    private $connection;
    private $host;
    private $db_name;
    private $username;
    private $password;

    public function __construct() {
        $this->host = DB_HOST;
        $this->db_name = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
    }

    /**
     * Conectar a la base de datos
     */
    public function connect() {
        $this->connection = null;

        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=" . DB_CHARSET,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch(PDOException $e) {
            sendJsonResponse([
                'code' => 'DATABASE_CONNECTION_ERROR',
                'details' => 'No se pudo conectar a la base de datos'
            ], 500, 'Error interno del servidor');
        }

        return $this->connection;
    }

    /**
     * Obtener todos los libros con filtros opcionales
     */
    public function getAllBooks($filters = []) {
        // Asegurar conexión
        if (!$this->connection) {
            $this->connect();
        }
        
        $query = "SELECT * FROM libros WHERE 1=1";
        $params = [];

        // Aplicar filtros
        if (isset($filters['genero']) && !empty($filters['genero'])) {
            $query .= " AND genero LIKE :genero";
            $params[':genero'] = '%' . $filters['genero'] . '%';
        }

        if (isset($filters['autor']) && !empty($filters['autor'])) {
            $query .= " AND autor LIKE :autor";
            $params[':autor'] = '%' . $filters['autor'] . '%';
        }

        if (isset($filters['disponible'])) {
            $query .= " AND disponible = :disponible";
            $params[':disponible'] = $filters['disponible'] ? 1 : 0;
        }

        if (isset($filters['año_desde'])) {
            $query .= " AND `año_publicacion` >= :ano_desde";
            $params[':ano_desde'] = $filters['año_desde'];
        }

        if (isset($filters['año_hasta'])) {
            $query .= " AND `año_publicacion` <= :ano_hasta";
            $params[':ano_hasta'] = $filters['año_hasta'];
        }

        $query .= " ORDER BY titulo ASC";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            sendJsonResponse([
                'code' => 'DATABASE_QUERY_ERROR',
                'details' => 'Error al consultar los libros'
            ], 500, 'Error interno del servidor');
        }
    }

    /**
     * Obtener un libro por ID
     */
    public function getBookById($id) {
        // Asegurar conexión
        if (!$this->connection) {
            $this->connect();
        }
        
        if (!is_numeric($id) || $id <= 0) {
            sendJsonResponse([
                'code' => 'INVALID_ID',
                'details' => 'El ID debe ser un número entero positivo'
            ], 400, 'ID inválido');
        }

        $query = "SELECT * FROM libros WHERE id = :id";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $book = $stmt->fetch();
            
            if (!$book) {
                sendJsonResponse([
                    'code' => 'BOOK_NOT_FOUND',
                    'details' => "No se encontró un libro con ID: {$id}"
                ], 404, 'Libro no encontrado');
            }

            return $book;
        } catch(PDOException $e) {
            sendJsonResponse([
                'code' => 'DATABASE_QUERY_ERROR',
                'details' => 'Error al consultar el libro'
            ], 500, 'Error interno del servidor');
        }
    }

    /**
     * Crear un nuevo libro
     */
    public function createBook($data) {
        // Asegurar conexión
        if (!$this->connection) {
            $this->connect();
        }
        
        // Validar campos requeridos
        $requiredFields = ['titulo', 'autor', 'isbn', 'genero', 'año_publicacion'];
        validateRequiredFields($data, $requiredFields);

        // Validaciones adicionales
        $this->validateBookData($data);

        $query = "INSERT INTO libros (titulo, autor, isbn, genero, `año_publicacion`, disponible) 
                  VALUES (:titulo, :autor, :isbn, :genero, :ano_publicacion, :disponible)";

        try {
            $stmt = $this->connection->prepare($query);
            
            $disponible = isset($data['disponible']) ? (bool)$data['disponible'] : true;
            
            $stmt->bindParam(':titulo', $data['titulo']);
            $stmt->bindParam(':autor', $data['autor']);
            $stmt->bindParam(':isbn', $data['isbn']);
            $stmt->bindParam(':genero', $data['genero']);
            $stmt->bindParam(':ano_publicacion', $data['año_publicacion']);
            $stmt->bindParam(':disponible', $disponible, PDO::PARAM_BOOL);

            if ($stmt->execute()) {
                $newId = $this->connection->lastInsertId();
                return $this->getBookById($newId);
            }

        } catch(PDOException $e) {
            if ($e->getCode() == 23000) { // Error de duplicado
                sendJsonResponse([
                    'code' => 'DUPLICATE_ISBN',
                    'details' => 'Ya existe un libro con este ISBN'
                ], 409, 'Conflicto - ISBN duplicado');
            }
            
            // Si estamos en modo debug (local), mostrar más detalles del error
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                sendJsonResponse([
                    'code' => 'DATABASE_INSERT_ERROR',
                    'details' => 'Error al crear el libro',
                    'debug_info' => [
                        'error_message' => $e->getMessage(),
                        'error_code' => $e->getCode(),
                        'sql_state' => $e->errorInfo[0] ?? 'N/A'
                    ]
                ], 500, 'Error interno del servidor');
            }
            
            sendJsonResponse([
                'code' => 'DATABASE_INSERT_ERROR',
                'details' => 'Error al crear el libro'
            ], 500, 'Error interno del servidor');
        }
    }

    /**
     * Actualizar un libro existente
     */
    public function updateBook($id, $data) {
        // Asegurar conexión
        if (!$this->connection) {
            $this->connect();
        }
        
        // Verificar que el libro existe
        $existingBook = $this->getBookById($id);

        // Validar datos si se proporcionan
        if (isset($data['año_publicacion']) || isset($data['isbn'])) {
            $this->validateBookData($data, $id);
        }

        // Construir query dinámicamente
        $allowedFields = ['titulo', 'autor', 'isbn', 'genero', 'año_publicacion', 'disponible'];
        $updateFields = [];
        $params = [':id' => $id];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                // Manejar el campo especial con ñ
                if ($field === 'año_publicacion') {
                    $updateFields[] = "`año_publicacion` = :ano_publicacion";
                    $params[":ano_publicacion"] = $data[$field];
                } else {
                    $updateFields[] = "`{$field}` = :{$field}";
                    $params[":{$field}"] = $data[$field];
                }
            }
        }

        if (empty($updateFields)) {
            sendJsonResponse([
                'code' => 'NO_FIELDS_TO_UPDATE',
                'details' => 'No se proporcionaron campos válidos para actualizar'
            ], 400, 'Datos insuficientes');
        }

        $query = "UPDATE libros SET " . implode(', ', $updateFields) . " WHERE id = :id";

        try {
            $stmt = $this->connection->prepare($query);
            
            if ($stmt->execute($params)) {
                return $this->getBookById($id);
            }

        } catch(PDOException $e) {
            if ($e->getCode() == 23000) { // Error de duplicado
                sendJsonResponse([
                    'code' => 'DUPLICATE_ISBN',
                    'details' => 'Ya existe otro libro con este ISBN'
                ], 409, 'Conflicto - ISBN duplicado');
            }
            
            sendJsonResponse([
                'code' => 'DATABASE_UPDATE_ERROR',
                'details' => 'Error al actualizar el libro'
            ], 500, 'Error interno del servidor');
        }
    }

    /**
     * Eliminar un libro
     */
    public function deleteBook($id) {
        // Asegurar conexión
        if (!$this->connection) {
            $this->connect();
        }
        
        // Verificar que el libro exists
        $book = $this->getBookById($id);

        $query = "DELETE FROM libros WHERE id = :id";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return $book; // Retornar los datos del libro eliminado
            }

        } catch(PDOException $e) {
            sendJsonResponse([
                'code' => 'DATABASE_DELETE_ERROR',
                'details' => 'Error al eliminar el libro'
            ], 500, 'Error interno del servidor');
        }
    }

    /**
     * Validar datos del libro
     */
    private function validateBookData($data, $excludeId = null) {
        // Validar año de publicación
        if (isset($data['año_publicacion'])) {
            $currentYear = date('Y');
            if (!is_numeric($data['año_publicacion']) || 
                $data['año_publicacion'] < 1000 || 
                $data['año_publicacion'] > $currentYear) {
                sendJsonResponse([
                    'code' => 'INVALID_YEAR',
                    'details' => "El año de publicación debe estar entre 1000 y {$currentYear}"
                ], 400, 'Año de publicación inválido');
            }
        }

        // Validar ISBN (formato básico)
        if (isset($data['isbn'])) {
            $isbn = preg_replace('/[^0-9X]/', '', $data['isbn']);
            if (strlen($isbn) !== 10 && strlen($isbn) !== 13) {
                sendJsonResponse([
                    'code' => 'INVALID_ISBN',
                    'details' => 'El ISBN debe tener 10 o 13 dígitos'
                ], 400, 'ISBN inválido');
            }
        }

        // Validar longitud de campos de texto
        $textFields = [
            'titulo' => 255,
            'autor' => 255,
            'genero' => 100
        ];

        foreach ($textFields as $field => $maxLength) {
            if (isset($data[$field]) && strlen($data[$field]) > $maxLength) {
                sendJsonResponse([
                    'code' => 'FIELD_TOO_LONG',
                    'details' => "El campo '{$field}' no puede tener más de {$maxLength} caracteres"
                ], 400, 'Campo demasiado largo');
            }
        }
    }

    /**
     * Obtener estadísticas de la biblioteca
     */
    public function getStats() {
        // Asegurar conexión
        if (!$this->connection) {
            $this->connect();
        }
        
        $queries = [
            'total_libros' => "SELECT COUNT(*) as count FROM libros",
            'libros_disponibles' => "SELECT COUNT(*) as count FROM libros WHERE disponible = 1",
            'libros_no_disponibles' => "SELECT COUNT(*) as count FROM libros WHERE disponible = 0",
            'total_generos' => "SELECT COUNT(DISTINCT genero) as count FROM libros",
            'total_autores' => "SELECT COUNT(DISTINCT autor) as count FROM libros",
            'generos' => "SELECT genero, COUNT(*) as count FROM libros GROUP BY genero ORDER BY count DESC",
            'autores_mas_libros' => "SELECT autor, COUNT(*) as count FROM libros GROUP BY autor ORDER BY count DESC LIMIT 10",
            'todos_los_autores' => "SELECT DISTINCT autor FROM libros ORDER BY autor ASC",
            'todos_los_generos' => "SELECT DISTINCT genero FROM libros ORDER BY genero ASC",
            'libros_por_decada' => "SELECT FLOOR(`año_publicacion`/10)*10 AS decada, COUNT(*) as count FROM libros GROUP BY FLOOR(`año_publicacion`/10)*10 ORDER BY decada DESC"
        ];

        $stats = [];

        try {
            foreach ($queries as $key => $query) {
                $stmt = $this->connection->prepare($query);
                $stmt->execute();
                
                if (in_array($key, ['generos', 'autores_mas_libros', 'libros_por_decada'])) {
                    $stats[$key] = $stmt->fetchAll();
                } else if (in_array($key, ['todos_los_autores', 'todos_los_generos'])) {
                    $results = $stmt->fetchAll();
                    $stats[$key] = array_column($results, array_keys($results[0])[0]);
                } else {
                    $result = $stmt->fetch();
                    $stats[$key] = $result['count'];
                }
            }

            return $stats;
        } catch(PDOException $e) {
            // Si estamos en modo debug (local), mostrar más detalles del error
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                sendJsonResponse([
                    'code' => 'DATABASE_QUERY_ERROR',
                    'details' => 'Error al obtener estadísticas',
                    'debug_info' => [
                        'error_message' => $e->getMessage(),
                        'error_code' => $e->getCode()
                    ]
                ], 500, 'Error interno del servidor');
            }
            
            sendJsonResponse([
                'code' => 'DATABASE_QUERY_ERROR',
                'details' => 'Error al obtener estadísticas'
            ], 500, 'Error interno del servidor');
        }
    }

    /**
     * Verificar si un autor específico existe en la base de datos
     */
    public function checkAuthorExists($authorName) {
        // Asegurar conexión
        if (!$this->connection) {
            $this->connect();
        }
        
        $query = "SELECT COUNT(*) as count FROM libros WHERE autor = :autor";
        
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':autor', $authorName);
            $stmt->execute();
            
            $result = $stmt->fetch();
            return $result['count'] > 0;
        } catch(PDOException $e) {
            return false;
        }
    }

    /**
     * Verificar si un género específico existe en la base de datos  
     */
    public function checkGenreExists($genreName) {
        // Asegurar conexión
        if (!$this->connection) {
            $this->connect();
        }
        
        $query = "SELECT COUNT(*) as count FROM libros WHERE genero = :genero";
        
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':genero', $genreName);
            $stmt->execute();
            
            $result = $stmt->fetch();
            return $result['count'] > 0;
        } catch(PDOException $e) {
            return false;
        }
    }

    /**
     * Obtener información detallada de un autor
     */
    public function getAuthorInfo($authorName) {
        // Asegurar conexión
        if (!$this->connection) {
            $this->connect();
        }
        
        $query = "SELECT 
                    COUNT(*) as total_libros,
                    SUM(CASE WHEN disponible = 1 THEN 1 ELSE 0 END) as libros_disponibles,
                    MIN(`año_publicacion`) as primer_libro,
                    MAX(`año_publicacion`) as ultimo_libro,
                    GROUP_CONCAT(DISTINCT genero) as generos
                  FROM libros WHERE autor = :autor";
        
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':autor', $authorName);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch(PDOException $e) {
            return null;
        }
    }
}
?>