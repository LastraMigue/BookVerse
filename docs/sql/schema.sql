-- ==============================================================================
-- BookVerse — Club de Lectura
-- Script de creación de base de datos y carga de datos iniciales
-- Módulos: DWC · DWS · DIW
-- ==============================================================================

-- Creación y selección de la base de datos
CREATE DATABASE IF NOT EXISTS `bookverse_db`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `bookverse_db`;

-- Desactivar temporalmente la comprobación de claves foráneas para borrado seguro
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `lista_lecturas`;
DROP TABLE IF EXISTS `resenas`;
DROP TABLE IF EXISTS `libros`;
DROP TABLE IF EXISTS `generos`;
DROP TABLE IF EXISTS `usuarios`;

SET FOREIGN_KEY_CHECKS = 1;

-- ==============================================================================
-- 1. TABLA: usuarios
-- ==============================================================================
CREATE TABLE `usuarios` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `es_admin` TINYINT(1) NOT NULL DEFAULT 0,
  `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuario_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- 2. TABLA: generos
-- ==============================================================================
CREATE TABLE `generos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(80) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_genero_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- 3. TABLA: libros (Entidad Principal)
-- ==============================================================================
CREATE TABLE `libros` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NOT NULL,
  `autor` VARCHAR(150) NOT NULL,
  `isbn` VARCHAR(20) NOT NULL,
  `descripcion` TEXT NOT NULL,
  `genero_id` INT UNSIGNED NOT NULL,
  `creado_por` INT UNSIGNED NOT NULL,
  `estado` ENUM('pendiente', 'publicado', 'rechazado') NOT NULL DEFAULT 'pendiente',
  `fecha_envio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_libro_isbn` (`isbn`),
  KEY `idx_libro_genero` (`genero_id`),
  KEY `idx_libro_creador` (`creado_por`),
  KEY `idx_libro_estado` (`estado`),
  CONSTRAINT `fk_libros_genero` FOREIGN KEY (`genero_id`) 
    REFERENCES `generos` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_libros_usuario` FOREIGN KEY (`creado_por`) 
    REFERENCES `usuarios` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- 4. TABLA: resenas (Entidad Relacionada)
-- ==============================================================================
CREATE TABLE `resenas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `libro_id` INT UNSIGNED NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `valoracion` TINYINT UNSIGNED NOT NULL,
  `texto` TEXT NOT NULL,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuario_libro` (`usuario_id`, `libro_id`),
  KEY `idx_resena_libro` (`libro_id`),
  KEY `idx_resena_usuario` (`usuario_id`),
  CONSTRAINT `fk_resenas_libro` FOREIGN KEY (`libro_id`) 
    REFERENCES `libros` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_resenas_usuario` FOREIGN KEY (`usuario_id`) 
    REFERENCES `usuarios` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `chk_resena_valoracion` CHECK (`valoracion` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- 5. TABLA: lista_lecturas (Relación N:M)
-- ==============================================================================
CREATE TABLE `lista_lecturas` (
  `usuario_id` INT UNSIGNED NOT NULL,
  `libro_id` INT UNSIGNED NOT NULL,
  `estado` ENUM('quiero_leer', 'leyendo', 'leido') NOT NULL DEFAULT 'quiero_leer',
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`usuario_id`, `libro_id`),
  KEY `idx_lista_usuario` (`usuario_id`),
  KEY `idx_lista_libro` (`libro_id`),
  CONSTRAINT `fk_lista_usuario` FOREIGN KEY (`usuario_id`) 
    REFERENCES `usuarios` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_lista_libro` FOREIGN KEY (`libro_id`) 
    REFERENCES `libros` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- INSERCIÓN DE DATOS DE PRUEBA
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- Usuarios de prueba:
-- 1. admin@bookverse.local   (Contraseña: Admin1234!)   [Administrador]
-- 2. lector@bookverse.local  (Contraseña: Lector1234!)  [Lector estándar]
-- 3. clara@bookverse.local   (Contraseña: Clara1234!)   [Lector estándar]
-- 4. marcos@bookverse.local  (Contraseña: Marcos1234!)  [Lector estándar]
-- Contraseñas generadas con password_hash(..., PASSWORD_BCRYPT)
-- ------------------------------------------------------------------------------
INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password_hash`, `es_admin`, `fecha_registro`) VALUES
(1, 'Administrador BookVerse', 'admin@bookverse.local', '$2y$10$DpG.xUI7zdwNZ1qb2w2QZ.tkO/AntSZzBttYnaUMYPKNWBEvoJmGu', 1, '2026-11-01 10:00:00'),
(2, 'Lector de Prueba', 'lector@bookverse.local', '$2y$10$/dXCKGrE4hGOXrnIskk8dOviyT6Frjp23x0S9QRBCdE2O/VCpNTfS', 0, '2026-11-02 11:30:00'),
(3, 'Clara Gómez', 'clara@bookverse.local', '$2y$10$lCNs5gnlPQg/v1czmbPtLeHltZgHphIaJRv28BKP1AlRh5v4wZjBW', 0, '2026-11-03 16:45:00'),
(4, 'Marcos Ruiz', 'marcos@bookverse.local', '$2y$10$/dXCKGrE4hGOXrnIskk8dOviyT6Frjp23x0S9QRBCdE2O/VCpNTfS', 0, '2026-11-04 09:15:00');

-- ------------------------------------------------------------------------------
-- Géneros literarios
-- ------------------------------------------------------------------------------
INSERT INTO `generos` (`id`, `nombre`) VALUES
(1, 'Ficción contemporánea'),
(2, 'Ciencia ficción'),
(3, 'Fantasía'),
(4, 'Novela negra y policiaca'),
(5, 'Clásicos universales'),
(6, 'Terror y suspense'),
(7, 'Ensayo y divulgación'),
(8, 'Aventuras y juvenil');

-- ------------------------------------------------------------------------------
-- Libros:
-- 17 publicados (aprobados por el administrador)
-- 3 pendientes (para probar el flujo de moderación de aprobación/rechazo)
-- 1 rechazado (para verificar histórico y estados en 'Mis libros')
-- ------------------------------------------------------------------------------
INSERT INTO `libros` (`id`, `titulo`, `autor`, `isbn`, `descripcion`, `genero_id`, `creado_por`, `estado`, `fecha_envio`) VALUES
-- 1
(1, 'Cien años de soledad', 'Gabriel García Márquez', '978-0307474728', 
'La historia épica de la familia Buendía a lo largo de siete generaciones en el mítico pueblo de Macondo, obra cumbre del realismo mágico.', 1, 2, 'publicado', '2026-11-05 10:00:00'),

-- 2
(2, '1984', 'George Orwell', '978-0451524935', 
'Inquietante distopía política que describe una sociedad totalitaria vigilada permanentemente por el Gran Hermano.', 2, 2, 'publicado', '2026-11-05 11:15:00'),

-- 3
(3, 'El nombre del viento', 'Patrick Rothfuss', '978-8401352836', 
'Crónica del asesino de reyes: las andanzas y la formación mágica de Kvothe contadas en primera persona por él mismo.', 3, 3, 'publicado', '2026-11-05 12:30:00'),

-- 4
(4, 'Dune', 'Frank Herbert', '978-0441172719', 
'En el árido planeta Arrakis, el joven Paul Atreides se verá envuelto en una colosal lucha por el control de la especia melange.', 2, 4, 'publicado', '2026-11-06 09:00:00'),

-- 5
(5, 'El sabueso de los Baskerville', 'Arthur Conan Doyle', '978-8420665436', 
'Sherlock Holmes y el doctor Watson investigan una ancestral maldición que amenaza a los herederos de la mansión de los Baskerville.', 4, 2, 'publicado', '2026-11-06 14:20:00'),

-- 6
(6, 'Orgullo y prejuicio', 'Jane Austen', '978-8491051329', 
'Elizabeth Bennet y el orgulloso señor Darcy deben superar sus prejuicios y malentendidos en la Inglaterra del siglo XIX.', 5, 3, 'publicado', '2026-11-07 10:10:00'),

-- 7
(7, 'Fahrenheit 451', 'Ray Bradbury', '978-8445074879', 
'Un futuro sombrío donde los bomberos tienen la misión de quemar libros porque leer hace pensar y cuestionar al sistema.', 2, 4, 'publicado', '2026-11-07 16:40:00'),

-- 8
(8, 'Drácula', 'Bram Stoker', '978-8491052081', 
'Novela gótica epistolar que narra la siniestra llegada del conde Drácula desde Transilvania a las costas victorianas de Inglaterra.', 6, 2, 'publicado', '2026-11-08 11:00:00'),

-- 9
(9, 'El hobbit', 'J.R.R. Tolkien', '978-8445071410', 
'El apacible hobbit Bilbo Bolsón es arrastrado por Gandalf y una compañía de enanos a una legendaria búsqueda del tesoro custodiado por Smaug.', 3, 3, 'publicado', '2026-11-08 15:30:00'),

-- 10
(10, 'Sapiens: De animales a dioses', 'Yuval Noah Harari', '978-8499926223', 
'Breve historia de la humanidad que repasa cómo una especie insignificante de simios logró dominar el planeta Tierra.', 7, 4, 'publicado', '2026-11-09 10:45:00'),

-- 11
(11, 'Crimen y castigo', 'Fiódor Dostoyevski', '978-8420651347', 
'El estudiante Raskólnikov comete un asesinato justificado por su filosofía, pero pronto se verá perseguido por su propia conciencia moral.', 5, 2, 'publicado', '2026-11-09 17:00:00'),

-- 12
(12, 'Fundación', 'Isaac Asimov', '978-8497599245', 
'Hari Seldon utiliza la psicohistoria para prever la caída del Imperio Galáctico y crea una Fundación para preservar el saber de la humanidad.', 2, 3, 'publicado', '2026-11-10 12:15:00'),

-- 13
(13, 'El resplandor', 'Stephen King', '978-8497593793', 
'Jack Torrance acepta el puesto de conserje de invierno en el hotel Overlook, donde fuerzas sobrenaturales acechan a su familia.', 6, 4, 'publicado', '2026-11-10 18:00:00'),

-- 14
(14, 'El principito', 'Antoine de Saint-Exupéry', '978-8498381498', 
'Una fábula poética y filosófica sobre el amor, la soledad y el sentido de la vida vista a través de los ojos de un pequeño príncipe.', 8, 2, 'publicado', '2026-11-11 09:30:00'),

-- 15
(15, 'La sombra del viento', 'Carlos Ruiz Zafón', '978-8408079545', 
'En la Barcelona de 1945, Daniel Sempere descubre el Cementerio de los Libros Olvidados y una misteriosa novela que cambiará su vida.', 1, 3, 'publicado', '2026-11-11 13:20:00'),

-- 16
(16, 'Los pilares de la Tierra', 'Ken Follett', '978-8497592451', 
'Una fascinante reconstrucción medieval en torno a la construcción de una catedral gótica en Kingsbridge.', 1, 4, 'publicado', '2026-11-12 11:00:00'),

-- 17
(17, 'El infinito en un junco', 'Irene Vallejo', '978-8417860790', 
'Un cautivador ensayo sobre la invención de los libros en el mundo antiguo y la lucha por preservar la palabra escrita.', 7, 2, 'publicado', '2026-11-12 16:30:00'),

-- 18 (PENDIENTE 1 - Propuesto por el usuario lector)
(18, 'Neuromante', 'William Gibson', '978-8445074091', 
'Case era el mejor vaquero del ciberespacio hasta que cometió un error fatal; ahora le ofrecen una última oportunidad de redención.', 2, 2, 'pendiente', '2026-11-15 10:20:00'),

-- 19 (PENDIENTE 2 - Propuesto por Clara)
(19, 'Revolución en la granja', 'George Orwell', '978-8499890944', 
'Sátira alegórica en la que los animales de una granja se rebelan contra los humanos, solo para caer bajo la tiranía de los cerdos.', 5, 3, 'pendiente', '2026-11-16 12:00:00'),

-- 20 (PENDIENTE 3 - Propuesto por Marcos)
(20, 'El problema de los tres cuerpos', 'Cixin Liu', '978-8466659734', 
'Un proyecto militar secreto en China contacta con una civilización extraterrestre al borde del colapso planetario.', 2, 4, 'pendiente', '2026-11-17 14:15:00'),

-- 21 (RECHAZADO - Para probar el listado de rechazados en Mis Libros)
(21, 'Manual de cocina rápida sin fogones', 'Autor Anónimo', '978-0000000001', 
'Recopilación rápida de trucos culinarios que no encaja en la temática de literatura del club.', 7, 2, 'rechazado', '2026-11-14 08:00:00');

-- ------------------------------------------------------------------------------
-- Reseñas de prueba (con valoraciones de 1 a 5 para cálculo de AVG)
-- ------------------------------------------------------------------------------
INSERT INTO `resenas` (`id`, `libro_id`, `usuario_id`, `valoracion`, `texto`, `fecha`) VALUES
(1, 1, 3, 5, 'Una de las mejores lecturas de mi vida. La forma en que García Márquez entrelaza las historias familiares es sublime.', '2026-11-06 18:00:00'),
(2, 1, 4, 4, 'Al principio cuesta situar a tantos personajes llamados Aureliano y José Arcadio, pero el final es insuperable.', '2026-11-07 19:20:00'),
(3, 2, 2, 5, 'Lectura obligatoria. Cada año que pasa, muchas de las advertencias de Orwell parecen cobrar más actualidad.', '2026-11-08 12:00:00'),
(4, 2, 3, 4, 'La atmósfera opresiva del Ministerio de la Verdad está perfectamente lograda. Deja una sensación desoladora.', '2026-11-09 14:30:00'),
(5, 3, 2, 5, 'La prosa de Rothfuss es música pura. La historia de Kvothe engancha desde la primera página del prólogo.', '2026-11-09 20:10:00'),
(6, 4, 3, 5, 'Worldbuilding insuperable. La ecología y la política de Arrakis están minuciosamente cuidadas.', '2026-11-10 11:00:00'),
(7, 4, 4, 3, 'Un libro magnífico aunque a ratos tiene un ritmo denso con demasiada jerga y diálogo interno.', '2026-11-10 17:40:00'),
(8, 5, 4, 4, 'Una de las aventuras más redondas de Sherlock Holmes. El misterio del páramo atrapa en todo momento.', '2026-11-11 16:00:00'),
(9, 6, 2, 5, 'La ironía de Austen y el choque de voluntades entre Darcy y Elizabeth nunca pasan de moda.', '2026-11-12 10:15:00'),
(10, 15, 4, 5, 'Un homenaje bellísimo a los libros y al amor por la lectura. Ambientación mágica de Barcelona.', '2026-11-13 18:25:00'),
(11, 17, 3, 5, 'Un ensayo que se lee como una novela apasionante. Recomendadísimo para cualquier amante de los libros.', '2026-11-14 15:30:00');

-- ------------------------------------------------------------------------------
-- Lista de lecturas personales de prueba (estados: quiero_leer, leyendo, leido)
-- ------------------------------------------------------------------------------
INSERT INTO `lista_lecturas` (`usuario_id`, `libro_id`, `estado`, `fecha`) VALUES
(2, 1, 'leido', '2026-11-06 18:30:00'),
(2, 2, 'leido', '2026-11-08 12:05:00'),
(2, 3, 'leyendo', '2026-11-09 20:15:00'),
(2, 4, 'quiero_leer', '2026-11-10 09:00:00'),
(3, 1, 'leido', '2026-11-06 18:05:00'),
(3, 4, 'leido', '2026-11-10 11:05:00'),
(3, 15, 'quiero_leer', '2026-11-12 14:00:00'),
(4, 5, 'leido', '2026-11-11 16:05:00'),
(4, 16, 'leyendo', '2026-11-13 10:00:00');
