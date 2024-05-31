SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';

START TRANSACTION;

CREATE DATABASE IF NOT EXISTS manejadordeproyectos;
USE manejadordeproyectos;

CREATE TABLE IF NOT EXISTS personas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(60) DEFAULT NULL,
    apellido VARCHAR(60) DEFAULT NULL,
    email VARCHAR(60) DEFAULT NULL,
    direccion varchar(255) NOT NULL,
    password VARCHAR(60) DEFAULT NULL,
    telefono VARCHAR(10) DEFAULT NULL,
    sexo ENUM('Masculino', 'Femenino'),
    fecha_nacimiento date NOT NULL,
    profesion varchar(50) NOT NULL,
    rol ENUM('Default', 'Admin') DEFAULT 'Default',
    confirmado TINYINT(1) DEFAULT NULL,
    token VARCHAR(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE IF NOT EXISTS proyectos (
  id int PRIMARY KEY AUTO_INCREMENT,
  descripcion text NOT NULL,
  fecha_inicio date NOT NULL,
  fecha_entrega date NOT NULL,
  valor int NOT NULL,
  lugar varchar(255) NOT NULL,
  responsable int DEFAULT NULL,
  estado ENUM('En Proceso', 'Entregado', 'No Entregado') DEFAULT 'En Proceso',
  FOREIGN KEY (responsable) REFERENCES personas(id) ON DELETE SET NULL ON UPDATE CASCADE,
  INDEX responsable (responsable)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Crear las tablas con dependencias después
CREATE TABLE IF NOT EXISTS actividades (
  id int PRIMARY KEY AUTO_INCREMENT,
  descripcion text NOT NULL,
  fecha_inicio date NOT NULL,
  fecha_final date NOT NULL,
  id_proyecto int NOT NULL,
  responsable int DEFAULT NULL,
  estado ENUM('En Proceso', 'Entregado', 'No Entregado') DEFAULT 'En Proceso',
  presupuesto int NOT NULL,
  FOREIGN KEY (id_proyecto) REFERENCES proyectos(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (responsable) REFERENCES personas(id) ON DELETE SET NULL ON UPDATE CASCADE,
  INDEX idproyecto (id_proyecto),
  INDEX responsable (responsable)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS tareas (
  id int PRIMARY KEY AUTO_INCREMENT,
  descripcion text NOT NULL,
  fecha_inicio date NOT NULL,
  fecha_final date NOT NULL,
  id_actividad int NOT NULL,
  estado ENUM('En Proceso', 'Entregado', 'No Entregado') DEFAULT 'En Proceso',
  presupuesto int NOT NULL,
  FOREIGN KEY (Id_actividad) REFERENCES actividades(id) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX idactividad (id_actividad)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS recursos (
  id int PRIMARY KEY AUTO_INCREMENT,
  descripcion text NOT NULL,
  valor int NOT NULL,
  unidad ENUM('Metro', 'Kilogramo', 'Segundo', 'Amperio', 'Kelvin', 'Mol', 'Slug', 'Litro', 'Newton', 'Joule', 'Voltio')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS tareaxrecurso (
  id int PRIMARY KEY AUTO_INCREMENT,
  id_tarea int NOT NULL,
  id_recurso int NOT NULL,
  cantidades int NOT NULL,
  FOREIGN KEY (id_tarea) REFERENCES tareas(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_recurso) REFERENCES recursos(id) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX id_recurso (id_recurso),
  INDEX id_tarea (id_tarea)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS tareaxpersona (
  id int PRIMARY KEY AUTO_INCREMENT,
  id_tarea int NOT NULL,
  id_persona int NOT NULL,
  duracion int NOT NULL,
  FOREIGN KEY (id_tarea) REFERENCES tareas(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_persona) REFERENCES personas(id) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX id_tarea (id_tarea),
  INDEX id_persona (id_persona)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;


