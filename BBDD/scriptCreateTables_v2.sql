-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: PMYSQL148.dns-servicio.com:3306
-- Tiempo de generación: 09-12-2021 a las 09:26:14
-- Versión del servidor: 5.7.36
-- Versión de PHP: 7.4.24

--
-- Base de datos: `8870450_campus`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargaDatos`
--

CREATE TABLE `cargaDatos` (
  `oferta` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `modalidad` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `administracion` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `examen` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `fecha_examen` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `pregunta` varchar(1000) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `numRespuesta` varchar(10) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `textoRespuesta` varchar(1000) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `correcta` varchar(10) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabAdministracion`
--

CREATE TABLE `tabAdministracion` (
  `id` int(11) NOT NULL,
  `nombre` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabClasificacion`
--

CREATE TABLE `tabClasificacion` (
  `id` int(11) NOT NULL,
  `tema` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabExamen`
--

CREATE TABLE `tabExamen` (
  `id` int(11) NOT NULL,
  `descripcion` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `idOferta` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabOferta`
--

CREATE TABLE `tabOferta` (
  `id` int(11) NOT NULL,
  `descripcion` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `idAdministracion` int(11) NOT NULL,
  `modalidad` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabPreguntas`
--

CREATE TABLE `tabPreguntas` (
  `id` int(11) NOT NULL,
  `idExamen` int(11) NOT NULL,
  `texto` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `nivel` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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
  `texto` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `correcta` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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
  `id` int(11) NOT NULL,
  `DNI` text CHARACTER SET utf8 COLLATE utf8_spanish_ci,
  `username` text CHARACTER SET utf8 COLLATE utf8_spanish_ci,
  `password` varchar(2000) CHARACTER SET utf8 DEFAULT NULL,
  `active` varchar(1) CHARACTER SET utf8 DEFAULT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_spanish_ci,
  `surname` text CHARACTER SET utf8 COLLATE utf8_spanish_ci,
  `email` text CHARACTER SET utf8 COLLATE utf8_spanish_ci,
  `date_register` date DEFAULT NULL,
  `email_validado` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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

--
-- Indices de la tabla `tabUsuario`
--
ALTER TABLE `tabUsuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--
--
ALTER TABLE `tabAdministracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
  
ALTER TABLE `tabClasificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


  
ALTER TABLE `tabOferta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
  
  
ALTER TABLE `tabCuerpo`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
  
COMMIT;
