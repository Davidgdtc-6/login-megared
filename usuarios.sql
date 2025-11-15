-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 15-11-2025 a las 03:18:04
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db-megared`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `cedula` varchar(13) NOT NULL,
  `genero` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `cedula`, `genero`, `correo`, `contrasena`, `fecha_registro`) VALUES
(1, 'David', 'Torres', '0705767028', 'Masculino', 'gustavotorresc2001@gmail.com', '$2y$10$U9PyEZXYY4sbJLgKgS7uyOAwPhyXyj4pVYxSBN.zZvsZudoqHEiZ.', '2025-11-07 16:12:25'),
(2, 'Gustavo', 'Torres', '0705767051', 'Masculino', 'gdtorres6@utpl.edu.ec', '$2y$10$7zmcOmnUPGZ5Xz/2Qllh3eTJG50/MO3dJ7QMoDoxdcLhh1.JlXW/S', '2025-11-07 16:32:56'),
(3, 'Joel', 'Espinoza', '0706152055', 'Masculino', 'joelespinoza07@gmail.com', '$2y$10$hhL1nbsLltRje23.RFstpewWF8SSGRZxrJEYx/xm0BLVL//bJHD8W', '2025-11-09 17:35:08'),
(4, 'Nayeli', 'Procel', '0705770147', 'Femenino', 'nayeliprocel25@outlook.es', '$2y$10$zEYm6T.mW7WmlEr/VcUcielWT2cd5iQkX7j58eas07E76fez63t8K', '2025-11-10 15:14:19'),
(5, 'MELISSA', 'LOPEZ', '0704355981', 'Femenino', 'lopezmely@gmail.com', '$2y$10$iwNpvzt8jkvnY3I2lMkfJuSBHBBtNP9xWXzvYBvvD6bUhFZepkja.', '2025-11-10 15:16:58'),
(6, 'Marjorie Elizabeth', 'Armijos Feijoo', '0705267383', 'Femenino', 'marjoriearmijos1989@hotmail.com', '$2y$10$DxOMkBkjOdu9Q/ik83Ech.Ap24Z7mQo2VoCgNN5zepS.FHwyUM3IK', '2025-11-10 15:19:59'),
(7, 'Anthony', 'Loayza', '0706151776', 'Masculino', 'anthonyloayza3@gmail.com', '$2y$10$zBgjd0hIW0E6tu38fS0a2OBO5Ua2qSBtaK4vAcljG/rPWSviXEQN6', '2025-11-10 16:08:39'),
(8, 'Gaby', 'Torres', '0705767044', 'Femenino', 'gabytc@gmail.com', '$2y$10$zIg3jrl7v.Wmr5P67ZSulu1UtocAIfD5SXoDQv/fkz/nlIh1pPFOG', '2025-11-12 03:19:57'),
(9, 'Freddy', 'Dutan', '0705245868', 'Masculino', 'freddydutan88@gmail.com', '$2y$10$5FknJUTHXk9nNg3GQDlHM.wCH.8kC0im8f5D3lK.qiq8b54Oy5932', '2025-11-12 17:03:28');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
