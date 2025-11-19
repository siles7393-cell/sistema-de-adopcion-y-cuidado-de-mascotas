-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-11-2025 a las 18:21:15
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `Id` int(11) NOT NULL,
  `AdminUsername` varchar(45) NOT NULL,
  `AdminPassword` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`Id`, `AdminUsername`, `AdminPassword`) VALUES
(1, 'Brian', 'Dona');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donaciones`
--

CREATE TABLE `donaciones` (
  `donacion_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `refugio_id` int(11) NOT NULL,
  `importe` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `donaciones`
--

INSERT INTO `donaciones` (`donacion_id`, `usuario_id`, `refugio_id`, `importe`, `fecha`) VALUES
(14, 3, 1, 100.00, '2025-11-18 00:55:26'),
(15, 3, 3, 199876.00, '2025-11-18 01:04:32'),
(16, 3, 3, 1.00, '2025-11-18 22:09:37'),
(17, 3, 3, 10000.00, '2025-11-19 14:14:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formularios`
--

CREATE TABLE `formularios` (
  `formulario_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mascota_id` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `domicilio` varchar(255) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `edad` int(11) NOT NULL,
  `convivencia` varchar(255) NOT NULL,
  `motivacion` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_clinico`
--

CREATE TABLE `historial_clinico` (
  `id` int(11) NOT NULL,
  `mascota_id` int(11) NOT NULL,
  `accion` text NOT NULL,
  `ID_veterinario` int(11) NOT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_clinico`
--

INSERT INTO `historial_clinico` (`id`, `mascota_id`, `accion`, `ID_veterinario`, `fecha`) VALUES
(8, 19, 'dianostico', 3, '2025-11-19'),
(9, 19, 'vacuna contra la rabia', 3, '2025-11-19'),
(10, 19, 'dianostico', 3, '2025-11-19'),
(11, 19, 'dianostico', 3, '2025-11-19'),
(12, 19, 'medicamento tipo a', 3, '2025-11-19'),
(13, 19, 'medicamento tipo a', 3, '2025-11-19'),
(15, 19, 'vacuna contra la rabia', 3, '2025-11-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_acciones`
--

CREATE TABLE `log_acciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `tabla` varchar(50) DEFAULT NULL,
  `accion` varchar(20) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

CREATE TABLE `mascotas` (
  `nombre` varchar(45) NOT NULL,
  `edad` int(11) NOT NULL,
  `peso` float NOT NULL,
  `raza` varchar(45) NOT NULL,
  `descripcion` text NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `mascota_id` int(11) NOT NULL,
  `genero` varchar(45) NOT NULL,
  `titulo` varchar(45) NOT NULL,
  `URL_de_foto` varchar(100) NOT NULL,
  `estado` varchar(45) NOT NULL DEFAULT 'en proceso de adopcion',
  `refugio_id` int(11) DEFAULT NULL,
  `estado_adopcion` enum('disponible','en_proceso','adoptado') DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`nombre`, `edad`, `peso`, `raza`, `descripcion`, `tipo`, `mascota_id`, `genero`, `titulo`, `URL_de_foto`, `estado`, `refugio_id`, `estado_adopcion`) VALUES
('josefa', 69, 696969, 'humano', 'muy sexy', 'perro', 1, 'macho', '0', 'fotos/68dad574caa6a_descargar.jpg', 'adoptado', NULL, 'en_proceso'),
('josefa2', 12, 696969, 'momdongo', 'demasiado sexy', 'gato', 7, 'hembra', 'ola', 'fotos/68daf6d7d60a3_dea7463b-7304-4a45-9d77-0851e0c6bcd4_16-9-discover_1200x675.jpg', 'adoptado', NULL, 'disponible'),
('brian', 89, 0, 'roca', 'comunista', 'perro', 8, 'hembra', '8', 'fotos/68dbcbf3bc254_HD-wallpaper-meme-oscuro-blanco-dark-humor-momazo-momo-oscuridad-risa.jpg', 'adoptado', 1, 'en_proceso'),
('dan', 0, 9999, 'pc', 'barata', 'perro', 9, 'macho', 'pc gratis', 'fotos/68dbcd3e0d697_1628235001395-1024x680.jpg', 'adoptado', 1, 'en_proceso'),
('keiloro', 2, 6, 'desconocida', '1000', 'perro', 19, '0', 'adoptameeee', 'fotos/691bfabca7b05_giga.webp', 'en proceso de adopcion', 3, 'adoptado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `refugios`
--

CREATE TABLE `refugios` (
  `Id` int(11) NOT NULL,
  `NombreRefugio` varchar(45) NOT NULL,
  `UbicacionRefugio` varchar(45) NOT NULL,
  `TelefonoRefugio` int(12) NOT NULL,
  `UsernameRefugio` varchar(45) NOT NULL,
  `PasswordRefugio` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `refugios`
--

INSERT INTO `refugios` (`Id`, `NombreRefugio`, `UbicacionRefugio`, `TelefonoRefugio`, `UsernameRefugio`, `PasswordRefugio`) VALUES
(1, 'oshi', 'ayer', 12, 'Patata', '1234'),
(3, 'afortunado', 'flores 129', 1156348263, 'mario', '1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_adopcion`
--

CREATE TABLE `solicitudes_adopcion` (
  `solicitud_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mascota_id` int(11) NOT NULL,
  `estado` enum('pendiente','aceptada','rechazada') DEFAULT 'pendiente',
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_respuesta` timestamp NULL DEFAULT NULL,
  `dni` varchar(20) NOT NULL,
  `domicilio` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `edad` int(11) NOT NULL,
  `convivencia` text DEFAULT NULL,
  `motivacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes_adopcion`
--

INSERT INTO `solicitudes_adopcion` (`solicitud_id`, `usuario_id`, `mascota_id`, `estado`, `fecha_solicitud`, `fecha_respuesta`, `dni`, `domicilio`, `telefono`, `edad`, `convivencia`, `motivacion`) VALUES
(4, 3, 19, 'aceptada', '2025-11-19 01:24:33', '2025-11-19 01:26:01', '15769845', 'santander6100', '1123547689', 18, 'si, estan de acuerdo, de hecho lo necesitamos mi papa es alergico', 'mi papa :o'),
(6, 3, 9, 'pendiente', '2025-11-19 02:46:19', NULL, '15769845', 'santander6100', '1123547689', 18, 'si, estan de acuerdo, de hecho lo necesitamos mi papa es alergico', 'mi papa :o'),
(7, 3, 1, 'pendiente', '2025-11-19 04:10:54', NULL, '15769845', 'santander6100', '1123547689', 18, 'si, estan de acuerdo, de hecho lo necesitamos mi papa es alergico', 'mi papa :o'),
(8, 3, 8, 'pendiente', '2025-11-19 17:03:28', NULL, '15769845', 'santander6100', '1123547689', 18, 'si, estan de acuerdo, de hecho lo necesitamos mi papa es alergico', 'mi papa :o');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `idTurno` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idMascota` int(11) NOT NULL,
  `idVeterinario` int(11) NOT NULL,
  `estado` varchar(50) DEFAULT 'pendiente',
  `metodoPago` varchar(45) NOT NULL DEFAULT 'En Persona'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`idTurno`, `fecha`, `idUsuario`, `idMascota`, `idVeterinario`, `estado`, `metodoPago`) VALUES
(13, '2025-10-30 23:46:00', 3, 19, 3, 'pendiente', 'En_Persona');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Id` int(11) NOT NULL,
  `NombreUsuario` varchar(45) NOT NULL,
  `ApellidoUsuario` varchar(45) NOT NULL,
  `FechaNacUsuario` varchar(45) NOT NULL,
  `TelefonoUsuario` int(12) NOT NULL,
  `Username` varchar(45) NOT NULL,
  `Password` varchar(45) NOT NULL,
  `Puntos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Id`, `NombreUsuario`, `ApellidoUsuario`, `FechaNacUsuario`, `TelefonoUsuario`, `Username`, `Password`, `Puntos`) VALUES
(3, 'brian', 'loayza', '2005-12-17', 1124972624, 'joe', '1234', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `veterinarios`
--

CREATE TABLE `veterinarios` (
  `ID` int(5) NOT NULL,
  `NombreVeterinario` varchar(45) NOT NULL,
  `ApellidoVeterinario` varchar(45) NOT NULL,
  `FechaNacVeterinario` int(6) NOT NULL,
  `NumeroMatricula` int(10) NOT NULL,
  `TelefonoVeterinario` int(12) NOT NULL,
  `Usuario` varchar(45) NOT NULL,
  `Contrasenia` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `veterinarios`
--

INSERT INTO `veterinarios` (`ID`, `NombreVeterinario`, `ApellidoVeterinario`, `FechaNacVeterinario`, `NumeroMatricula`, `TelefonoVeterinario`, `Usuario`, `Contrasenia`) VALUES
(3, 'jeje', 'kaka', 2005, 4543, 1124972624, 'koi', '1234');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `donaciones`
--
ALTER TABLE `donaciones`
  ADD PRIMARY KEY (`donacion_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `refugio_id` (`refugio_id`);

--
-- Indices de la tabla `formularios`
--
ALTER TABLE `formularios`
  ADD PRIMARY KEY (`formulario_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `mascota_id` (`mascota_id`);

--
-- Indices de la tabla `historial_clinico`
--
ALTER TABLE `historial_clinico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_clinico_ibfk_1` (`mascota_id`),
  ADD KEY `historial_clinico_ibfk_2` (`ID_veterinario`);

--
-- Indices de la tabla `log_acciones`
--
ALTER TABLE `log_acciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD PRIMARY KEY (`mascota_id`),
  ADD KEY `fk_mascotas_refugios` (`refugio_id`);

--
-- Indices de la tabla `refugios`
--
ALTER TABLE `refugios`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `solicitudes_adopcion`
--
ALTER TABLE `solicitudes_adopcion`
  ADD PRIMARY KEY (`solicitud_id`),
  ADD KEY `idx_solicitudes_usuario` (`usuario_id`),
  ADD KEY `idx_solicitudes_mascota` (`mascota_id`),
  ADD KEY `idx_solicitudes_estado` (`estado`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`idTurno`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idMascota` (`idMascota`),
  ADD KEY `idVeterinario` (`idVeterinario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `veterinarios`
--
ALTER TABLE `veterinarios`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `donaciones`
--
ALTER TABLE `donaciones`
  MODIFY `donacion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `formularios`
--
ALTER TABLE `formularios`
  MODIFY `formulario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `historial_clinico`
--
ALTER TABLE `historial_clinico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `log_acciones`
--
ALTER TABLE `log_acciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  MODIFY `mascota_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `refugios`
--
ALTER TABLE `refugios`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `solicitudes_adopcion`
--
ALTER TABLE `solicitudes_adopcion`
  MODIFY `solicitud_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `idTurno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `veterinarios`
--
ALTER TABLE `veterinarios`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `donaciones`
--
ALTER TABLE `donaciones`
  ADD CONSTRAINT `donaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `donaciones_ibfk_2` FOREIGN KEY (`refugio_id`) REFERENCES `refugios` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `formularios`
--
ALTER TABLE `formularios`
  ADD CONSTRAINT `formularios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `formularios_ibfk_2` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`mascota_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_clinico`
--
ALTER TABLE `historial_clinico`
  ADD CONSTRAINT `historial_clinico_ibfk_1` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`mascota_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `historial_clinico_ibfk_2` FOREIGN KEY (`ID_veterinario`) REFERENCES `veterinarios` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD CONSTRAINT `fk_mascotas_refugios` FOREIGN KEY (`refugio_id`) REFERENCES `refugios` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitudes_adopcion`
--
ALTER TABLE `solicitudes_adopcion`
  ADD CONSTRAINT `solicitudes_adopcion_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `solicitudes_adopcion_ibfk_2` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`mascota_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `turnos_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `turnos_ibfk_2` FOREIGN KEY (`idMascota`) REFERENCES `mascotas` (`mascota_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `turnos_ibfk_3` FOREIGN KEY (`idVeterinario`) REFERENCES `veterinarios` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
