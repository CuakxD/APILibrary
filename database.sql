-- Base de datos para API de Biblioteca
-- Este archivo contiene la estructura de la tabla y datos de ejemplo

CREATE DATABASE IF NOT EXISTS biblioteca_api;
USE biblioteca_api;

-- Crear tabla de libros
CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    isbn VARCHAR(13) UNIQUE NOT NULL,
    genero VARCHAR(100) NOT NULL,
    año_publicacion YEAR NOT NULL,
    disponible BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertar datos de ejemplo
INSERT INTO libros (titulo, autor, isbn, genero, año_publicacion, disponible) VALUES
('Cien años de soledad', 'Gabriel García Márquez', '9780060883287', 'Realismo mágico', 1967, TRUE),
('Don Quijote de la Mancha', 'Miguel de Cervantes', '9788424116675', 'Novela', 1605, TRUE),
('1984', 'George Orwell', '9780451524935', 'Distopía', 1949, FALSE),
('El amor en los tiempos del cólera', 'Gabriel García Márquez', '9780307389732', 'Romance', 1985, TRUE),
('La sombra del viento', 'Carlos Ruiz Zafón', '9788408043645', 'Misterio', 2001, TRUE),
('Rayuela', 'Julio Cortázar', '9788437604572', 'Literatura experimental', 1963, FALSE),
('El túnel', 'Ernesto Sabato', '9789507313509', 'Existencialismo', 1948, TRUE),
('Crónica de una muerte anunciada', 'Gabriel García Márquez', '9780307387400', 'Realismo mágico', 1981, TRUE),
('Los detectives salvajes', 'Roberto Bolaño', '9788433920799', 'Novela', 1998, TRUE),
('Pedro Páramo', 'Juan Rulfo', '9786070118401', 'Realismo mágico', 1955, FALSE),
('El laberinto de la soledad', 'Octavio Paz', '9786071113047', 'Ensayo', 1950, TRUE),
('Como agua para chocolate', 'Laura Esquivel', '9788466306225', 'Realismo mágico', 1989, TRUE);

-- Crear usuario específico para la API (opcional, para mayor seguridad)
-- CREATE USER 'api_biblioteca'@'localhost' IDENTIFIED BY 'password123';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON biblioteca_api.* TO 'api_biblioteca'@'localhost';
-- FLUSH PRIVILEGES;