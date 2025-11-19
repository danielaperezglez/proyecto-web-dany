-- =======================
-- 1) Donadores
-- =======================
CREATE TABLE donadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(120),
    direccion VARCHAR(160),
    tipo ENUM('individual','empresa') DEFAULT 'individual',
    frecuencia_donacion VARCHAR(50), -- ejemplo: mensual, ocasional
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =======================
-- 2) Campana
-- =======================
CREATE TABLE campana (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    tipo ENUM('recaudacion','evento','emergencia') DEFAULT 'recaudacion',
    fecha_inicio DATE DEFAULT NULL,
    fecha_fin DATE DEFAULT NULL,
    estado ENUM('activa','inactiva') DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =======================
-- 3) Mapa de centros de acopio
-- =======================
CREATE TABLE mapa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    direccion VARCHAR(200),
    ciudad VARCHAR(100),
    estado VARCHAR(100),
    latitud DECIMAL(10,6),
    longitud DECIMAL(10,6),
    necesidades TEXT,
    telefono VARCHAR(20),
    email VARCHAR(120),
    responsable VARCHAR(100),
    horario VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =======================
-- 4) Donaciones
-- =======================
CREATE TABLE donaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donador_id INT NOT NULL,
    centro_id INT,
    campana_id INT,
    tipo_donacion ENUM('comida','medicinas','juguetes','otro') NOT NULL,
    cantidad DECIMAL(10,2),
    descripcion TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_donador (donador_id),
    INDEX idx_centro (centro_id),
    INDEX idx_campana (campana_id),
    CONSTRAINT fk_donaciones_donador FOREIGN KEY (donador_id) REFERENCES donadores(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_donaciones_centro FOREIGN KEY (centro_id) REFERENCES mapa(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_donaciones_campana FOREIGN KEY (campana_id) REFERENCES campana(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =======================
-- 5) Historias de impacto
-- =======================
CREATE TABLE historias_impacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('publicada','no publicada') DEFAULT 'no publicada',
    campana_id INT,
    donador_id INT,
    INDEX idx_campana (campana_id),
    INDEX idx_donador (donador_id),
    CONSTRAINT fk_historia_campana FOREIGN KEY (campana_id) REFERENCES campana(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_historia_donador FOREIGN KEY (donador_id) REFERENCES donadores(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =======================
-- 6) Voluntariado local
-- =======================
CREATE TABLE voluntariado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donador_id INT NOT NULL,
    centro_id INT,
    campana_id INT,
    area_interes VARCHAR(100),
    disponibilidad VARCHAR(100),
    horario_preferido VARCHAR(100),
    comentarios TEXT,
    estatus ENUM('activo','inactivo') DEFAULT 'activo',
    INDEX idx_donador (donador_id),
    INDEX idx_centro (centro_id),
    INDEX idx_campana (campana_id),
    CONSTRAINT fk_voluntariado_donador FOREIGN KEY (donador_id) REFERENCES donadores(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_voluntariado_centro FOREIGN KEY (centro_id) REFERENCES mapa(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_voluntariado_campana FOREIGN KEY (campana_id) REFERENCES campana(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =======================
-- 7) Crear tabla Impacto Social
-- =======================
CREATE TABLE impacto_social (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donador_id INT NOT NULL,                 -- Donador destacado
    tipo_donacion ENUM('comida','medicinas','juguetes','otro') NOT NULL,
    cantidad_total DECIMAL(10,2) DEFAULT 0, -- Total donado por tipo
    descripcion TEXT,                        -- Detalle o historia del impacto
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_donador (donador_id),
    CONSTRAINT fk_impacto_donador FOREIGN KEY (donador_id) 
        REFERENCES donadores(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;