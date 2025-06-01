-- Base mejorada para sistema de notas (Nicaragua secundaria)

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `dbnota1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `dbnota1` ;

-- Roles
CREATE TABLE IF NOT EXISTS `rol_usuario` (
  `id_rol` INT NOT NULL AUTO_INCREMENT,
  `nombre_rol` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_rol`),
  UNIQUE INDEX `nombre_rol_UNIQUE` (`nombre_rol`)
) ENGINE=InnoDB;

-- Usuarios
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `nombre_usuario` VARCHAR(45) NOT NULL,
  `contrasena` VARCHAR(255) NOT NULL,
  `id_rol` INT NOT NULL,
  `primer_nombre` VARCHAR(45) NOT NULL,
  `segundo_nombre` VARCHAR(45),
  `primer_apellido` VARCHAR(45) NOT NULL,
  `segundo_apellido` VARCHAR(45),
  `correo` VARCHAR(45),
  `telefono` VARCHAR(12),
  PRIMARY KEY (`id_usuario`),
  UNIQUE INDEX `nombre_usuario_UNIQUE` (`nombre_usuario`),
  UNIQUE INDEX `telefono_UNIQUE` (`telefono`),
  INDEX `rol_usuario_idx` (`id_rol`),
  CONSTRAINT `fk_usuario_rol`
    FOREIGN KEY (`id_rol`)
    REFERENCES `rol_usuario` (`id_rol`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Cursos (ahora con año lectivo)
CREATE TABLE IF NOT EXISTS `curso` (
  `id_curso` INT NOT NULL AUTO_INCREMENT,
  `grado` VARCHAR(45) NOT NULL,
  `anio_lectivo` INT NOT NULL,
  PRIMARY KEY (`id_curso`)
) ENGINE=InnoDB;

-- Asignaturas
CREATE TABLE IF NOT EXISTS `asignatura` (
  `id_asignatura` INT NOT NULL AUTO_INCREMENT,
  `nombre_asignatura` VARCHAR(45) NOT NULL,
  `id_curso` INT NOT NULL,
  PRIMARY KEY (`id_asignatura`),
  INDEX `fk_asignatura_curso_idx` (`id_curso`),
  CONSTRAINT `fk_asignatura_curso`
    FOREIGN KEY (`id_curso`)
    REFERENCES `curso` (`id_curso`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Relación asignatura-docente
CREATE TABLE IF NOT EXISTS `asignatura_docente` (
  `id_asig_doc` INT NOT NULL AUTO_INCREMENT,
  `id_usuario_docente` INT NOT NULL,
  `id_asignatura` INT NOT NULL,
  PRIMARY KEY (`id_asig_doc`),
  INDEX `fk_asignatura_docente_usuario_idx` (`id_usuario_docente`),
  INDEX `fk_asignatura_docente_asignatura_idx` (`id_asignatura`),
  CONSTRAINT `fk_asignatura_docente_usuario`
    FOREIGN KEY (`id_usuario_docente`)
    REFERENCES `usuario` (`id_usuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_asignatura_docente_asignatura`
    FOREIGN KEY (`id_asignatura`)
    REFERENCES `asignatura` (`id_asignatura`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Lista de participantes (estudiantes inscritos en la asignatura con ese docente)
CREATE TABLE IF NOT EXISTS `lista_participante` (
  `id_lista` INT NOT NULL AUTO_INCREMENT,
  `id_usuario_estudiante` INT NOT NULL,
  `id_asig_doc` INT NOT NULL,
  PRIMARY KEY (`id_lista`),
  UNIQUE INDEX `id_lista_UNIQUE` (`id_lista`),
  INDEX `fk_lista_participante_usuario_idx` (`id_usuario_estudiante`),
  INDEX `fk_lista_participante_asignatura_docente_idx` (`id_asig_doc`),
  CONSTRAINT `fk_lista_participante_usuario`
    FOREIGN KEY (`id_usuario_estudiante`)
    REFERENCES `usuario` (`id_usuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_lista_participante_asignatura_docente`
    FOREIGN KEY (`id_asig_doc`)
    REFERENCES `asignatura_docente` (`id_asig_doc`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Notas finales
CREATE TABLE IF NOT EXISTS `nota` (
  `id_nota` INT NOT NULL AUTO_INCREMENT,
  `valor_nota` INT NOT NULL,
  `comentarios` VARCHAR(100),
  `id_lista` INT NOT NULL,
  PRIMARY KEY (`id_nota`),
  UNIQUE INDEX `id_nota_UNIQUE` (`id_nota`),
  INDEX `fk_nota_lista_participante_idx` (`id_lista`),
  CONSTRAINT `fk_nota_lista_participante`
    FOREIGN KEY (`id_lista`)
    REFERENCES `lista_participante` (`id_lista`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- Roles
INSERT INTO rol_usuario (nombre_rol) VALUES
  ('admin'), ('docente'), ('estudiante');

-- Usuarios: admin, 3 docentes, 8 estudiantes
INSERT INTO usuario (nombre_usuario, contrasena, id_rol, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo, telefono) VALUES
  ('admin',     '$2y$10$pruebaAdminHash', 1, 'Raquel',  'Maria',    'Gonzalez',   'Perez',   'raquel.admin@demo.com',   '55510001'),
  ('docente1',  '$2y$10$pruebaDoc1Hash',  2, 'Adolfo',  NULL,       'Urbina',     'Lopez',   'adolfo.urbina@demo.com',  '55510002'),
  ('docente2',  '$2y$10$pruebaDoc2Hash',  2, 'Ana',     'Luisa',    'Martinez',   'Sosa',    'ana.martinez@demo.com',   '55510003'),
  ('docente3',  '$2y$10$pruebaDoc3Hash',  2, 'Carlos',  NULL,       'Ramirez',    'Bermudez','carlos.ramirez@demo.com', '55510004'),
  ('estu1',     '$2y$10$pruebaEstu1Hash', 3, 'Juan',    'Jose',     'Lopez',      'Castro',  'juan.lopez@demo.com',     '55520001'),
  ('estu2',     '$2y$10$pruebaEstu2Hash', 3, 'Maria',   NULL,       'Perez',      'Mora',    'maria.perez@demo.com',    '55520002'),
  ('estu3',     '$2y$10$pruebaEstu3Hash', 3, 'Luis',    NULL,       'Garcia',     'Rios',    'luis.garcia@demo.com',    '55520003'),
  ('estu4',     '$2y$10$pruebaEstu4Hash', 3, 'Ana',     'Teresa',   'Martinez',   'Ramos',   'ana.martinez2@demo.com',  '55520004'),
  ('estu5',     '$2y$10$pruebaEstu5Hash', 3, 'Pedro',   'Antonio',  'Vargas',     NULL,      'pedro.vargas@demo.com',   '55520005'),
  ('estu6',     '$2y$10$pruebaEstu6Hash', 3, 'Rosa',    NULL,       'Gomez',      NULL,      'rosa.gomez@demo.com',     '55520006'),
  ('estu7',     '$2y$10$pruebaEstu7Hash', 3, 'Miguel',  NULL,       'Alvarez',    NULL,      'miguel.alvarez@demo.com', '55520007'),
  ('estu8',     '$2y$10$pruebaEstu8Hash', 3, 'Julia',   NULL,       'Ruiz',       NULL,      'julia.ruiz@demo.com',     '55520008');

  INSERT INTO usuario (nombre_usuario, contrasena, id_rol, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo, telefono) VALUES   ('estu9',     '$2y$10$pruebaEstu9Hash', 3, 'Martha',   NULL,       'Lopez',       NULL,      'martha.lopez@demo.com',     '55530008');
  

-- Cursos (dos grados, dos años lectivos)
INSERT INTO curso (grado, anio_lectivo) VALUES
  ('1ro A', 2025),
  ('1ro B', 2025),
  ('2do A', 2025),
  ('2do B', 2025),
  ('1ro A', 2024),
  ('2do A', 2024);

-- Asignaturas por curso
INSERT INTO asignatura (nombre_asignatura, id_curso) VALUES
  ('Matematicas', 1),
  ('Lengua y Literatura', 1),
  ('Inglés', 1),
  ('Ciencias Naturales', 2),
  ('Matematicas', 3),
  ('Inglés', 3),
  ('Ciencias Sociales', 4),
  ('Matematicas', 5),
  ('Lengua y Literatura', 5),
  ('Matematicas', 6);

-- Docente imparte asignatura (asignatura_docente)
INSERT INTO asignatura_docente (id_usuario_docente, id_asignatura) VALUES
  (2, 1),  -- docente1, Matematicas 1ro A 2025
  (2, 5),  -- docente1, Matematicas 2do A 2025
  (2, 8),  -- docente1, Matematicas 1ro A 2024
  (2, 10), -- docente1, Matematicas 2do A 2024
  (3, 2),  -- docente2, Lengua y Literatura 1ro A 2025
  (3, 9),  -- docente2, Lengua y Literatura 1ro A 2024
  (4, 3),  -- docente3, Inglés 1ro A 2025
  (4, 6);  -- docente3, Inglés 2do A 2025

-- 8 estudiantes inscritos en 1ro A 2025 (Matematicas y Lengua y Literatura)
-- id_asig_doc: 1 (Matematicas), 5 (Lengua y Literatura)
INSERT INTO lista_participante (id_usuario_estudiante, id_asig_doc) VALUES
  (5,1), (5,5),
  (6,1), (6,5),
  (7,1), (7,5),
  (8,1), (8,5),
  (9,1), (9,5),
  (10,1), (10,5),
  (11,1), (11,5),
  (12,1), (12,5);

-- Estudiantes inscritos en Inglés 1ro A 2025 (id_asig_doc: 7)
INSERT INTO lista_participante (id_usuario_estudiante, id_asig_doc) VALUES
  (5,7), (6,7), (7,7), (8,7);

-- 4 estudiantes en Matematicas 2do A 2025 (id_asig_doc: 2)
INSERT INTO lista_participante (id_usuario_estudiante, id_asig_doc) VALUES
  (5,2), (6,2), (7,2), (8,2);

-- Notas para Matematicas 1ro A 2025 (id_asig_doc: 1, lista_participante id=1 al 8)
INSERT INTO nota (valor_nota, comentarios, id_lista) VALUES
  (95, 'Excelente', 1),
  (87, 'Muy bien', 3),
  (75, 'Aceptable', 5),
  (66, 'Debe mejorar', 7),
  (89, 'Buen trabajo', 9),
  (73, 'Regular', 11),
  (82, 'Bien', 13),
  (90, 'Muy bien', 15);

-- Notas para Lengua y Literatura 1ro A 2025 (id_asig_doc: 5, lista_participante id=2 al 16, pares)
INSERT INTO nota (valor_nota, comentarios, id_lista) VALUES
  (88, 'Buen desempeño', 2),
  (75, 'Aceptable', 4),
  (92, 'Excelente', 6),
  (70, 'Puede mejorar', 8),
  (95, 'Excelente', 10),
  (80, 'Bien', 12),
  (77, 'Regular', 14),
  (85, 'Muy bien', 16);

-- Notas para Inglés 1ro A 2025 (id_asig_doc: 7, lista_participante id=17 al 20)
INSERT INTO nota (valor_nota, comentarios, id_lista) VALUES
  (93, 'Excelente', 17),
  (82, 'Bien', 18),
  (74, 'Aceptable', 19),
  (67, 'Debe mejorar', 20);

-- Notas para Matematicas 2do A 2025 (id_asig_doc: 2, lista_participante id=21 al 24)
INSERT INTO nota (valor_nota, comentarios, id_lista) VALUES
  (85, 'Muy bien', 21),
  (78, 'Bien', 22),
  (88, 'Buen trabajo', 23),
  (69, 'Debe esforzarse más', 24);

-- Consultas:
-- 1. Listar todos los estudiantes de una asignatura específica, junto con su nota y datos
SELECT
  u.id_usuario,
  u.primer_nombre,
  u.primer_apellido,
  n.valor_nota,
  n.comentarios
FROM
  lista_participante lp
  INNER JOIN usuario u ON lp.id_usuario_estudiante = u.id_usuario
  LEFT JOIN nota n ON n.id_lista = lp.id_lista
WHERE
  lp.id_asig_doc = 2; -- (id de la asignatura_docente, es decir, de la asignatura con ese docente)
  
-- 2. Listar todas las asignaturas que imparte un docente (con curso y año lectivo)
SELECT
  ad.id_asig_doc,
  a.nombre_asignatura,
  c.grado,
  c.anio_lectivo
FROM
  asignatura_docente ad
  INNER JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
  INNER JOIN curso c ON a.id_curso = c.id_curso
WHERE
  ad.id_usuario_docente = 1; -- (id del docente)
  
-- 3. Ver las notas de un estudiante en todas sus asignaturas y años
SELECT
  n.valor_nota,
  n.comentarios,
  a.nombre_asignatura,
  c.grado,
  c.anio_lectivo,
  u_docente.primer_nombre AS docente_nombre,
  u_docente.primer_apellido AS docente_apellido
FROM
  lista_participante lp
  INNER JOIN nota n ON n.id_lista = lp.id_lista
  INNER JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
  INNER JOIN usuario u_docente ON ad.id_usuario_docente = u_docente.id_usuario
  INNER JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
  INNER JOIN curso c ON a.id_curso = c.id_curso
WHERE
  lp.id_usuario_estudiante = 1; -- (id del estudiante)
  
-- 4 Listar todos los cursos y asignaturas de un año lectivo
SELECT
  c.id_curso,
  c.grado,
  c.anio_lectivo,
  a.id_asignatura,
  a.nombre_asignatura
FROM
  curso c
  INNER JOIN asignatura a ON c.id_curso = a.id_curso
WHERE
  c.anio_lectivo = 2025;
  
-- 5. Ver todos los estudiantes de un curso y año
SELECT
  u.id_usuario,
  u.primer_nombre,
  u.primer_apellido,
  c.grado,
  c.anio_lectivo
FROM
  lista_participante lp
  INNER JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
  INNER JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
  INNER JOIN curso c ON a.id_curso = c.id_curso
  INNER JOIN usuario u ON lp.id_usuario_estudiante = u.id_usuario
WHERE
  c.id_curso = 1 AND c.anio_lectivo = 1
GROUP BY u.id_usuario;

-- 6. Notas de todos los estudiantes de una asignatura en un curso y año
SELECT
  u.id_usuario,
  u.primer_nombre,
  u.primer_apellido,
  n.valor_nota,
  n.comentarios
FROM
  lista_participante lp
  INNER JOIN usuario u ON lp.id_usuario_estudiante = u.id_usuario
  INNER JOIN nota n ON n.id_lista = lp.id_lista
  INNER JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
  INNER JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
  INNER JOIN curso c ON a.id_curso = c.id_curso
WHERE
  a.id_asignatura = 1 AND c.id_curso = 1 AND c.anio_lectivo = 1;
  
-- 7. Obtener el listado de docentes con la(s) asignaturas y cursos que imparten
SELECT
  u.id_usuario,
  u.primer_nombre,
  u.primer_apellido,
  a.nombre_asignatura,
  c.grado,
  c.anio_lectivo
FROM
  asignatura_docente ad
  INNER JOIN usuario u ON ad.id_usuario_docente = u.id_usuario
  INNER JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
  INNER JOIN curso c ON a.id_curso = c.id_curso
ORDER BY u.primer_apellido, u.primer_nombre;

-- 8. Buscar usuario (estudiante, docente o admin) por nombre o apellido
SELECT *
FROM usuario
WHERE primer_nombre LIKE '%{busqueda}%'
   OR primer_apellido LIKE '%{busqueda}%';
   
-- 9. Notas de todos los cursos de un estudiante en un año específico
SELECT
  c.grado,
  c.anio_lectivo,
  a.nombre_asignatura,
  n.valor_nota,
  n.comentarios
FROM
  lista_participante lp
  INNER JOIN nota n ON n.id_lista = lp.id_lista
  INNER JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
  INNER JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
  INNER JOIN curso c ON a.id_curso = c.id_curso
WHERE
  lp.id_usuario_estudiante = 1
  AND c.anio_lectivo = 2025;
  
-- 10. Obtener detalles de una nota específica
SELECT
  n.*,
  u.primer_nombre AS estudiante_nombre,
  u.primer_apellido AS estudiante_apellido,
  a.nombre_asignatura,
  c.grado,
  c.anio_lectivo
FROM
  nota n
  INNER JOIN lista_participante lp ON n.id_lista = lp.id_lista
  INNER JOIN usuario u ON lp.id_usuario_estudiante = u.id_usuario
  INNER JOIN asignatura_docente ad ON lp.id_asig_doc = ad.id_asig_doc
  INNER JOIN asignatura a ON ad.id_asignatura = a.id_asignatura
  INNER JOIN curso c ON a.id_curso = c.id_curso
WHERE
  n.id_nota = 2;



SELECT DISTINCT c.id_curso, c.grado, c.anio_lectivo
            FROM curso c
            JOIN asignatura a ON c.id_curso = a.id_curso
            JOIN asignatura_docente ad ON a.id_asignatura = ad.id_asignatura
            WHERE ad.id_usuario_docente = 2
            ORDER BY c.anio_lectivo DESC, c.grado
