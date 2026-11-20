-- =====================================================================
-- DIVM Educación Especial: estructura de la base de datos (SIN datos)
-- Para un servidor nuevo (Railway). Se importa en la base que Railway ya crea (se llama "railway").
-- No trae usuarios ni pacientes: el primer administrador se crea con
--     php artisan divm:crear-admin correo@ejemplo.com
-- Es el mismo esquema de divm.sql, con una sola collation (utf8mb4_unicode_ci) para que funcione
-- igual en MySQL 8/9 y MariaDB. Se puede ejecutar más de una vez sin borrar nada.
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apaterno` varchar(100) NOT NULL,
  `amaterno` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `codigo_verificacion` varchar(6) DEFAULT NULL,
  `dos_factores_activado` tinyint(1) DEFAULT '0',
  `rol` enum('Administrador','Personal') DEFAULT 'Personal',
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  `fecha_codigo` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pacientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `direccion` text,
  `edad` int NOT NULL,
  `diagnostico` varchar(255) DEFAULT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `motivo_consulta` text,
  `escolarizado` enum('Sí','No') DEFAULT 'No',
  `madre` varchar(150) DEFAULT NULL,
  `padre` varchar(150) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  `access_token` varchar(100) DEFAULT NULL,
  `qr_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `access_token` (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `avances_pacientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `paciente_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha_sesion` date NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `estatus_sesion` enum('Asistió','Falta Justificada','Falta Injustificada','Cancelada') DEFAULT 'Asistió',
  `actividades_realizadas` text,
  `observaciones_comportamiento` text,
  `logros_obtenidos` text,
  `tareas_o_recomendaciones` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `paciente_id` (`paciente_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `avances_pacientes_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  CONSTRAINT `avances_pacientes_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `horarios_atencion` (
  `id_horario` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `paciente` varchar(200) NOT NULL,
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  PRIMARY KEY (`id_horario`),
  KEY `fk_horarios_usuario` (`id_usuario`),
  CONSTRAINT `fk_horarios_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `informes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `paciente` varchar(200) NOT NULL,
  `nombre_padre` varchar(150) DEFAULT NULL,
  `grado_estudios_padre` varchar(100) DEFAULT NULL,
  `ocupacion_padre` varchar(150) DEFAULT NULL,
  `edad_padre` int DEFAULT NULL,
  `nombre_madre` varchar(150) DEFAULT NULL,
  `grado_estudios_madre` varchar(100) DEFAULT NULL,
  `ocupacion_madre` varchar(150) DEFAULT NULL,
  `edad_madre` int DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `domicilio` text,
  `prueba_aplicada` text,
  `fecha_aplicacion` date DEFAULT NULL,
  `aplicadora` text,
  `motivo_evaluacion` text,
  `hc_gestacion` text,
  `hc_talla_nacer` text,
  `hc_peso` text,
  `hc_apgar` text,
  `hc_alergias` text,
  `hc_diagnostico` text,
  `res_lenguaje` text,
  `res_anatomico` text,
  `res_discriminacion` text,
  `res_fonetica_auditiva` text,
  `res_lenguaje_espontaneo` text,
  `res_nivel_lenguaje` text,
  `res_conducta_valoracion` text,
  `des_cognitivo` text,
  `des_motor` text,
  `des_social` text,
  `observaciones` text,
  `ind_clinicas` text,
  `ind_familiares` text,
  `ind_adolescencia` text,
  `ind_escolares` text,
  `ind_rehabilitadores` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `informes_valoracion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_paciente` int NOT NULL,
  `id_usuario` int NOT NULL,
  `prueba_aplicada` varchar(255) NOT NULL,
  `aplicadora` varchar(255) NOT NULL,
  `motivo_evaluacion` text NOT NULL,
  `hc_gestacion` text,
  `hc_talla_nacer` varchar(100) DEFAULT NULL,
  `hc_diagnostico` text NOT NULL,
  `des_cognitivo` text NOT NULL,
  `des_motor` text NOT NULL,
  `des_social` text NOT NULL,
  `res_lenguaje` text NOT NULL,
  `res_conducta_valoracion` text NOT NULL,
  `ind_escolares` text,
  `ind_clinicas` text,
  `ind_familiares` text,
  `ind_rehabilitadores` text,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `planes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `paciente` varchar(200) DEFAULT NULL,
  `padre` varchar(100) DEFAULT NULL,
  `madre` varchar(100) DEFAULT NULL,
  `terapia_comunicativa` text,
  `terapia_sensorial` text,
  `terapia_aprendizaje` text,
  `terapia_ocupacional` text,
  `terapia_atencion_concentracion` text,
  `terapia_educacion_emocional` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `recomendaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `madre` varchar(150) NOT NULL,
  `padre` varchar(150) NOT NULL,
  `paciente` varchar(150) NOT NULL,
  `escuela_procedencia` varchar(255) DEFAULT NULL,
  `grado` enum('Estancia','Preescolar','Primaria','Secundaria','Preparatoria','Universidad') DEFAULT NULL,
  `recomendacion` text NOT NULL,
  `notas` text,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
