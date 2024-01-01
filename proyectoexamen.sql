-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3308
-- Tiempo de generación: 01-01-2024 a las 22:28:25
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyectoexamen`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audiovisual`
--

CREATE TABLE `audiovisual` (
  `id` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `estado` varchar(10) NOT NULL,
  `idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `audiovisual`
--

INSERT INTO `audiovisual` (`id`, `tipo`, `nombre`, `descripcion`, `estado`, `idUsuario`) VALUES
(25, 'pelicula', 'MATRIX', 'Me encanta la cámara lenta', 'vista', 3),
(26, 'pelicula', 'PELICULAEJEMPLO', 'Esto es para el video', 'vista', 6),
(28, 'pelicula', 'ATLETI', 'El mejor equipo del mundo', 'vista', 5),
(29, 'pelicula', 'EL FUEGO DE LA VENGANZA', 'Venganza', 'vista', 7),
(30, 'serie', 'CAMISETA', 'camisetilla de bartolo', 'pendiente', 7),
(32, 'pelicula', 'SISU', 'Me gusta esta pelicula de acción porque lucha contra los malos', 'vista', 1),
(33, 'serie', 'MIGUEL', 'miguel serie', 'pendiente', 3),
(34, 'pelicula', 'EL LADO BUENO DE LAS COSAS', 'Esta pelicula es muy bonita', 'vista', 1),
(35, 'pelicula', 'REBEL MOON', 'No me ha gustado nada, le director mezcla conceptos', 'vista', 1),
(36, 'pelicula', '2012', 'Me la recomendaron y aún no la vi', 'pendiente', 1),
(37, 'serie', 'THE BEAR', 'Me encanta como los personajes evolucionan', 'vista', 1),
(38, 'serie', 'THE WALKING DEAD: DEAD CITY', 'Secuela de The Walking Death', 'pendiente', 1),
(39, 'serie', 'INVASIÓN SECRETA', 'Me está pareciendo un poco lenta y aburrida', 'viendo', 1),
(40, 'pelicula', 'PABLO SE MERECE UN', 'aprobado', 'vista', 5),
(41, 'pelicula', 'PIRATAS DEL CARIBE', 'Viva el ron y los barcos', 'vista', 5),
(42, 'serie', 'THE ACT', 'Historia sobre la familia Blanchard', 'pendiente', 5),
(43, 'pelicula', 'RATATOUILLE', 'Pelicula de amor y comida', 'viendo', 5),
(44, 'pelicula', 'AVENGERS: ENDGAME', 'De las mejores peliculas de superheroes', 'vista', 5),
(45, 'serie', 'HIT MONKEY', 'Profunda y divertida', 'viendo', 5),
(46, 'pelicula', 'ESPERANDO AL REY', 'Divertida y sentimental', 'vista', 5),
(47, 'serie', 'CAPITANES DEL MUNDO', 'Pelicula sobre futbol', 'pendiente', 5),
(48, 'pelicula', 'INTERSTELLAR', 'La mejor pelicula que existe', 'vista', 5),
(49, 'pelicula', 'EL SILENCIO DE LOS CORDEROS', 'Cine inquietante y perfecto', 'vista', 5),
(50, 'serie', '30 MONEDAS', 'Serie de crimen y misterior española', 'pendiente', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `idMensaje` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `mensaje` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`idMensaje`, `idUsuario`, `mensaje`) VALUES
(8, 6, 'Esta aplicación es genial me encanta!'),
(9, 5, 'Pablo está aprobado, es un buen trabajo.'),
(10, 7, 'Esta aplicación merece la pena'),
(11, 3, 'Hola kukuuuu');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `contrasena` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `nombre`, `contrasena`) VALUES
(1, 'pablo@pablo.com', 'Pablo', '123'),
(3, 'prueba@prueba.com', 'prueba', '123'),
(5, 'javier@javier.com', 'Javier', '123'),
(6, 'jose@jose.com', 'Jose', '123'),
(7, 'brazales@brazales.com', 'Brazales', '123');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `audiovisual`
--
ALTER TABLE `audiovisual`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `nombre` (`nombre`) USING BTREE;

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`idMensaje`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `audiovisual`
--
ALTER TABLE `audiovisual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `idMensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `audiovisual`
--
ALTER TABLE `audiovisual`
  ADD CONSTRAINT `audiovisual_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
