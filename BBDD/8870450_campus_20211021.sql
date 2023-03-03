-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: PMYSQL148.dns-servicio.com:3306
-- Tiempo de generación: 21-10-2021 a las 11:21:27
-- Versión del servidor: 5.7.34
-- Versión de PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `8870450_campus`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabAdministracion`
--

CREATE TABLE `tabAdministracion` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tabAdministracion`
--

INSERT INTO `tabAdministracion` (`id`, `nombre`) VALUES
(1, 'AGE - Adm. General Estado'),
(2, 'CCAA - Comunidad Autonoma'),
(3, 'Municipal / Local'),
(4, 'Servicios Salud Autonomicos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabClasificacion`
--

CREATE TABLE `tabClasificacion` (
  `id` int(11) NOT NULL,
  `tema` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tabClasificacion`
--

INSERT INTO `tabClasificacion` (`id`, `tema`) VALUES
(1, 'Constitucion Española'),
(2, 'Sede Electronica'),
(3, 'Procedimientos de Contratación'),
(4, 'Procedimiento Administrativo Común');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabExamen`
--

CREATE TABLE `tabExamen` (
  `id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `idOferta` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tabExamen`
--

INSERT INTO `tabExamen` (`id`, `descripcion`, `idOferta`) VALUES
(1, 'Auxiliar Administrativo - INAP', 1),
(2, 'Auxiliar Administrativo CARM', 2),
(3, 'Auxiliar Administrativo Ayto. Murcia', 3),
(4, 'Auxiliar Administrativo SMS', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabOferta`
--

CREATE TABLE `tabOferta` (
  `id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `idAdministracion` int(11) NOT NULL,
  `modalidad` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tabOferta`
--

INSERT INTO `tabOferta` (`id`, `descripcion`, `idAdministracion`, `modalidad`) VALUES
(1, 'Auxiliar Administrativo - INAP', 1, 'LIBRE'),
(2, 'Auxiliar Administrativo - CARM', 2, 'LIBRE'),
(3, 'Auxiliar Administrativo - Ayto. Murcia', 3, 'LIBRE'),
(4, 'Auxiliar Administrativo - SMS', 4, 'LIBRE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabPreguntas`
--

CREATE TABLE `tabPreguntas` (
  `id` int(11) NOT NULL,
  `idExamen` int(11) NOT NULL,
  `texto` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tabPreguntas`
--

INSERT INTO `tabPreguntas` (`id`, `idExamen`, `texto`) VALUES
(1, 2, '¿Cuál de las siguientes actuaciones no podría calificarse como acto administrativo en sentido estricto?'),
(2, 2, 'La orden de convocatoria de un concurso-oposición es:'),
(3, 2, 'Por medio de la delegación se traslada:'),
(4, 2, 'Si un particular sufre un accidente en la travesía de una población y presenta una reclamación de responsabilidad patrimonial ante el Ayuntamiento, creyéndolo titular de la vía y, en consecuencia, competente para su resolución, cuando en realidad lo es la Comunidad Autónoma,'),
(5, 2, '¿Puede la Consejera de Hacienda y Administración Pública delegar en el Secretario Autonómico competente en materia de Función Pública la aprobación del Reglamento General de Provisión de Puestos de Trabajo en la Administración regional?');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabPreguntasClasificacion`
--

CREATE TABLE `tabPreguntasClasificacion` (
  `id` int(11) NOT NULL,
  `idPregunta` int(11) NOT NULL,
  `idClasificacion` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabRespuestas`
--

CREATE TABLE `tabRespuestas` (
  `id` int(11) NOT NULL,
  `idPregunta` int(11) NOT NULL,
  `texto` text NOT NULL,
  `correcta` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tabRespuestas`
--

INSERT INTO `tabRespuestas` (`id`, `idPregunta`, `texto`, `correcta`) VALUES
(1, 1, 'La suspensión de un juez para el ejercicio de sus funciones, impuesta por el Consejo General del Poder Judicial.', 0),
(2, 1, 'La adjudicación de un contrato de suministro de material quirúrgico para el Hospital Reina Sofía.', 0),
(3, 1, 'La decisión del Jefe de Servicio de Cardiología del mismo Hospital de efectuar un by-pass a un paciente, que muere tras la realización de la intervención.', 1),
(4, 2, 'Un reglamento, porque se dirige a una multitud de personas.', 0),
(5, 2, 'Un acto administrativo, porque se integra en el ordenamiento jurídico y tiene vocación de permanencia.', 0),
(6, 2, 'Un acto administrativo con destinatario plural y que se agota con su cumplimiento o ejecución.', 1),
(7, 3, 'La titularidad de la competencia.', 0),
(8, 3, 'El ejercicio de la competencia.', 1),
(9, 3, 'Sólo la facultad de firmar los documentos en que se plasman los actos administrativos.', 0),
(10, 4, 'El Ayuntamiento debe trasladar inmediatamente la reclamación al Consejero competente en materia de carreteras, por así disponerlo el artículo 20 LPAC.', 0),
(11, 4, 'El Ayuntamiento puede avocar para sí la competencia de la Consejería.', 0),
(12, 4, 'El Ayuntamiento puede declararse incompetente, por falta de titularidad sobre la vía, por lo que el particular habrá de interponer una nueva reclamación ante la Comunidad Autónoma, si quiere ver satisfecha su pretensión.', 1),
(13, 5, 'No, porque el SMS no depende de esa Consejería.', 0),
(14, 5, 'Sí, porque no es necesario que exista una dependencia jerárquica entre delegante y delegado.', 1),
(15, 5, 'No, porque la LPAC prohíbe la delegación de competencias de carácter gestor.', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabRespuestasUsuario`
--

CREATE TABLE `tabRespuestasUsuario` (
  `id` int(11) NOT NULL,
  `idPregunta` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `acierto` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabUsuario`
--

CREATE TABLE `tabUsuario` (
  `id` int(11) DEFAULT NULL,
  `DNI` varchar(12) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `activo` varchar(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tabUsuario`
--

INSERT INTO `tabUsuario` (`id`, `DNI`, `username`, `password`, `activo`) VALUES
(1, '34829708A', 'jillansa', 'P@$s=947533', 'S');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tabAdministracion`
--
ALTER TABLE `tabAdministracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tabClasificacion`
--
ALTER TABLE `tabClasificacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tabExamen`
--
ALTER TABLE `tabExamen`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tabOferta`
--
ALTER TABLE `tabOferta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tabPreguntas`
--
ALTER TABLE `tabPreguntas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tabPreguntasClasificacion`
--
ALTER TABLE `tabPreguntasClasificacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tabRespuestas`
--
ALTER TABLE `tabRespuestas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tabRespuestasUsuario`
--
ALTER TABLE `tabRespuestasUsuario`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
