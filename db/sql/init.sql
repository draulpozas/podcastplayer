-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 22-03-2020 a las 23:04:00
-- Versión del servidor: 5.7.26
-- Versión de PHP: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `podcasts`
--
CREATE DATABASE IF NOT EXISTS `podcasts` DEFAULT CHARACTER SET latin1 COLLATE latin1_spanish_ci;
USE `podcasts`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subscription`
--

DROP TABLE IF EXISTS `subscription`;
CREATE TABLE IF NOT EXISTS `subscription` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `feed` varchar(10000) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `subscription`
--

INSERT INTO `subscription` (`id`, `user_id`, `feed`) VALUES
(1, 1, 'http://www.ivoox.com/puzolana-f1_fg_f1669469_filtro_1.xml'),
(2, 1, 'http://www.ivoox.com/sinapsis_fg_f1582681_filtro_1.xml'),
(3, 1, 'https://www.relay.fm/cortex/feed'),
(4, 1, 'https://www.relay.fm/liftoff/feed'),
(5, 1, '../../testRssFiles/hellointernet.xml'),
(7, 1, 'https://www.ivoox.com/podcast-a-cola-del-peloton_fg_f1136710_filtro_1.xml'),
(8, 1, 'https://www.relay.fm/presentable/feed'),
(9, 1, 'https://www.relay.fm/upgrade/feed'),
(10, 2, ''),
(11, 2, 'https://www.relay.fm/testdrivers/feed'),
(12, 2, 'https://www.relay.fm/testdrivers/feed'),
(13, 2, 'https://www.relay.fm/testdrivers/feed'),
(14, 1, 'https://www.relay.fm/testdrivers/feed'),
(15, 1, 'jfdjdfjdfjdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) COLLATE latin1_spanish_ci NOT NULL,
  `passwd` varchar(256) COLLATE latin1_spanish_ci NOT NULL,
  `email` varchar(256) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `username`, `passwd`, `email`) VALUES
(1, 'diego', '$2y$10$SgNwfjsnOB41MTJOXUTA0ep6zy0gGjbkmqoNEg7s5Ykv3kyE3UoR2', 'dipo.9913@gmail.com'),
(2, 'diego2', '$2y$10$LUe9bjrHaRso1HyQyYMi8uAAG3hlKJvrNgGh19y20g9i2ZSPWU/Vy', 'dipo.9913@gmail.com');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `subscription`
--
ALTER TABLE `subscription`
  ADD CONSTRAINT `subscription_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
