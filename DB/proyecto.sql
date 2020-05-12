-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-12-2018 a las 20:49:20
-- Versión del servidor: 10.1.30-MariaDB
-- Versión de PHP: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
-- Estructura de tabla para la tabla `actas`
--

CREATE TABLE `actas` (
  `id` int(10) UNSIGNED NOT NULL,
  `consecutivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `fecha_de_reunion` date NOT NULL,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `actas`
--

INSERT INTO `actas` (`id`, `consecutivo`, `titulo`, `descripcion`, `fecha_de_reunion`, `archivo`, `id_user`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Marzo 2018 asamblea', 'Nos reunimos', '2018-03-01', '1534600492.pdf', 2, 1, '2018-08-18 23:54:52', '2018-08-18 23:54:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activos_fijos`
--

CREATE TABLE `activos_fijos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `valor` bigint(20) NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_acta` int(10) UNSIGNED DEFAULT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alquileres`
--

CREATE TABLE `alquileres` (
  `id` int(10) UNSIGNED NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `costo` bigint(20) NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_zona` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptos_retencion`
--

CREATE TABLE `conceptos_retencion` (
  `id` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `porcentaje` decimal(8,2) NOT NULL,
  `resolucion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conceptos_retencion`
--

INSERT INTO `conceptos_retencion` (`id`, `descripcion`, `porcentaje`, `resolucion`, `archivo`, `created_at`, `updated_at`) VALUES
(1, 'Cualquier cosa', '10.10', NULL, NULL, '2018-09-01 06:58:19', '2018-09-01 06:58:19'),
(2, 'Por ventas', '12.12', NULL, NULL, '2018-09-02 02:33:31', '2018-09-02 02:33:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conjuntos`
--

CREATE TABLE `conjuntos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barrio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel_cel` bigint(20) NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_inicio_interes` bigint(20) DEFAULT NULL,
  `id_tipo_propiedad` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conjuntos`
--

INSERT INTO `conjuntos` (`id`, `nit`, `nombre`, `ciudad`, `direccion`, `barrio`, `tel_cel`, `logo`, `fecha_inicio_interes`, `id_tipo_propiedad`, `created_at`, `updated_at`) VALUES
(1, '456456456-10', 'Conjunto La estrella', 'Manizales / Caldas', 'Calle 6ta', 'Estrella', 8812172, '1533650120.png', 17, 3, '2018-06-19 11:00:00', '2018-10-09 06:07:56'),
(2, '789789789', 'Conjunto Portal de la 50', 'Manizales', 'Calle 50A', 'Versalles bajo', 3215478989, NULL, 29, 3, '2018-07-11 20:13:46', '2018-10-09 06:08:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consecutivos`
--

CREATE TABLE `consecutivos` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_consecutivo` bigint(20) NOT NULL,
  `tipo_consecutivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefijo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_conjunto` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `consecutivos`
--

INSERT INTO `consecutivos` (`id`, `id_consecutivo`, `tipo_consecutivo`, `prefijo`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 202020, 'ingreso', 'ell', 1, '2018-11-14 07:45:12', '2018-11-14 07:45:12'),
(2, 203040, 'gestion_recaudo', 'ell', 1, '2018-11-14 07:46:04', '2018-11-14 07:46:04'),
(3, 2323, 'egreso', 'ell', 1, '2018-11-24 09:04:43', '2018-11-24 09:04:43'),
(4, 2324, 'egreso', 'ell', 1, '2018-11-24 09:08:33', '2018-11-24 09:08:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `id` int(10) UNSIGNED NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuota_admon`
--

CREATE TABLE `cuota_admon` (
  `id` int(10) UNSIGNED NOT NULL,
  `saldo_vigente_real` bigint(20) DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_factura` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `periodo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_tipo_unidad` int(10) UNSIGNED DEFAULT NULL,
  `id_cuota_adm_ord` int(10) UNSIGNED DEFAULT NULL,
  `id_tabla_intereses` int(10) UNSIGNED DEFAULT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuota_admon`
--

INSERT INTO `cuota_admon` (`id`, `saldo_vigente_real`, `estado`, `estado_factura`, `periodo`, `id_tipo_unidad`, `id_cuota_adm_ord`, `id_tabla_intereses`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 95000, 'En deuda', NULL, '2018 - Agosto', 1, 7, 2, 1, '2018-11-23 07:07:21', '2018-11-23 07:29:12'),
(2, 114000, 'En deuda', NULL, '2018 - Agosto', 3, 8, 2, 1, '2018-11-23 07:07:21', '2018-11-23 07:07:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuota_adm_ex_ord`
--

CREATE TABLE `cuota_adm_ex_ord` (
  `id` int(10) UNSIGNED NOT NULL,
  `costo` bigint(20) NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_cuota_extraordinaria` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_factura` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_tipo_unidad` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `id_acta` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuota_adm_ex_ord`
--

INSERT INTO `cuota_adm_ex_ord` (`id`, `costo`, `fecha_vencimiento`, `descripcion`, `tipo_cuota_extraordinaria`, `estado`, `estado_factura`, `id_tipo_unidad`, `id_conjunto`, `id_acta`, `created_at`, `updated_at`) VALUES
(1, 100000, '2018-11-22', 'Se daño una teja may', 'Daños', 'Pagado', 'En proceso de Factura', 1, 1, NULL, '2018-11-23 07:08:01', '2018-11-24 07:40:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuota_adm_ord`
--

CREATE TABLE `cuota_adm_ord` (
  `id` int(10) UNSIGNED NOT NULL,
  `costo` bigint(20) NOT NULL,
  `fecha_vigencia_inicio` date NOT NULL,
  `fecha_vigencia_fin` date NOT NULL,
  `rango` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_tipo_unidad` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `id_acta` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuota_adm_ord`
--

INSERT INTO `cuota_adm_ord` (`id`, `costo`, `fecha_vigencia_inicio`, `fecha_vigencia_fin`, `rango`, `id_tipo_unidad`, `id_conjunto`, `id_acta`, `created_at`, `updated_at`) VALUES
(4, 100000, '2018-01-01', '2018-12-31', '2018-01-01 - 2018-12-31', 1, 1, NULL, '2018-08-19 00:02:06', '2018-08-19 00:02:06'),
(5, 120000, '2018-01-01', '2018-12-31', '2018-01-01 - 2018-12-31', 3, 1, 1, '2018-08-19 00:03:36', '2018-08-19 00:03:36'),
(6, 100000, '2018-01-01', '2018-11-06', '2018-01-01 - 2018-11-06', 1, 1, 1, '2018-08-19 00:04:25', '2018-08-19 00:04:25'),
(7, 95000, '2019-01-01', '2019-12-31', '2019-01-01 - 2019-12-31', 1, 1, NULL, '2018-08-19 00:07:57', '2018-08-19 00:07:57'),
(8, 114000, '2019-01-01', '2019-12-31', '2019-01-01 - 2019-12-31', 3, 1, 1, '2018-08-19 00:07:57', '2018-08-19 00:07:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `divisiones`
--

CREATE TABLE `divisiones` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo_division` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_letra` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_archivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `privacidad` enum('privado','publico') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publico',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos`
--

CREATE TABLE `egresos` (
  `id` int(10) UNSIGNED NOT NULL,
  `concepto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `documento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aiu` bigint(20) DEFAULT NULL,
  `id_consecutivo_egresos` int(10) UNSIGNED NOT NULL,
  `id_proveedor` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `egresos`
--

INSERT INTO `egresos` (`id`, `concepto`, `fecha`, `documento`, `aiu`, `id_consecutivo_egresos`, `id_proveedor`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 'asdf', '2018-11-23', '234', NULL, 4, 7, 1, '2018-11-24 09:08:33', '2018-11-24 09:08:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos_detalles`
--

CREATE TABLE `egresos_detalles` (
  `id` int(10) UNSIGNED NOT NULL,
  `sub_valor_antes_iva` bigint(20) NOT NULL,
  `iva` decimal(8,2) NOT NULL,
  `sub_valor_con_iva` bigint(20) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor_retencion` decimal(8,2) DEFAULT NULL,
  `id_egresos` int(10) UNSIGNED NOT NULL,
  `id_presup_individual` int(10) UNSIGNED NOT NULL,
  `id_conceptos_retencion` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejecucion_presupuestal_individual`
--

CREATE TABLE `ejecucion_presupuestal_individual` (
  `id` int(10) UNSIGNED NOT NULL,
  `porcentaje_total` double NOT NULL,
  `porcentaje_ejecutado` double DEFAULT NULL,
  `total` bigint(20) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `tipo` enum('ingreso','egreso') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_tipo_ejecucion` int(10) UNSIGNED NOT NULL,
  `id_ejecucion_pre_total` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ejecucion_presupuestal_individual`
--

INSERT INTO `ejecucion_presupuestal_individual` (`id`, `porcentaje_total`, `porcentaje_ejecutado`, `total`, `fecha_inicio`, `fecha_fin`, `tipo`, `id_tipo_ejecucion`, `id_ejecucion_pre_total`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 10.1, NULL, 20200000000, '2018-09-04', '2018-09-04', 'egreso', 1, 1, 1, '2018-09-05 09:45:00', '2018-09-05 09:45:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejecucion_presupuestal_total`
--

CREATE TABLE `ejecucion_presupuestal_total` (
  `id` int(10) UNSIGNED NOT NULL,
  `valor_total` bigint(20) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `tipo` enum('ingreso','egreso') COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ejecucion_presupuestal_total`
--

INSERT INTO `ejecucion_presupuestal_total` (`id`, `valor_total`, `fecha_inicio`, `fecha_fin`, `tipo`, `archivo`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 200000000000, '2018-09-04', '2018-09-18', 'egreso', NULL, 1, '2018-09-05 09:44:13', '2018-09-05 09:44:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encomientas`
--

CREATE TABLE `encomientas` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `id_tipo_unidad` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `encomientas`
--

INSERT INTO `encomientas` (`id`, `titulo`, `descripcion`, `id_conjunto`, `id_tipo_unidad`, `created_at`, `updated_at`) VALUES
(1, 'Facturas', 'Mes agosto', 1, 1, '2018-08-18 23:36:03', '2018-08-18 23:36:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `events`
--

CREATE TABLE `events` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime NOT NULL,
  `start_hour` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_date` datetime NOT NULL,
  `end_hour` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_user` int(10) UNSIGNED DEFAULT NULL,
  `id_zona_comun` int(10) UNSIGNED DEFAULT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `events`
--

INSERT INTO `events` (`id`, `title`, `start_date`, `start_hour`, `end_date`, `end_hour`, `color`, `id_user`, `id_zona_comun`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 'Zona Común: Piscina - Usuario que reservó: Edisson Bedoya', '2018-08-21 13:00:00', '13:00', '2018-08-21 15:00:00', '15:00', '#0080ff', 3, 1, 1, '2018-08-22 01:56:21', '2018-08-22 01:56:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha_couta_adm`
--

CREATE TABLE `fecha_couta_adm` (
  `id` int(10) UNSIGNED NOT NULL,
  `rango` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `fecha_couta_adm`
--

INSERT INTO `fecha_couta_adm` (`id`, `rango`, `id_conjunto`) VALUES
(1, '2018-01-01 - 2018-12-31', 1),
(2, '2018-01-01 - 2018-11-06', 1),
(3, '2019-01-01 - 2019-12-31', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestion_cobros`
--

CREATE TABLE `gestion_cobros` (
  `id` int(10) UNSIGNED NOT NULL,
  `saldo_total` bigint(20) NOT NULL,
  `saldo_total_operativo` bigint(20) DEFAULT NULL,
  `fecha_actual` date NOT NULL,
  `id_tbl_consecutivo` int(10) UNSIGNED NOT NULL,
  `id_tipo_unidad` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `gestion_cobros`
--

INSERT INTO `gestion_cobros` (`id`, `saldo_total`, `saldo_total_operativo`, `fecha_actual`, `id_tbl_consecutivo`, `id_tipo_unidad`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 300000, 300000, '2018-11-24', 2, 1, 1, '2018-11-24 06:54:13', '2018-11-24 07:40:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestion_cobros_detalles`
--

CREATE TABLE `gestion_cobros_detalles` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `costo` bigint(20) NOT NULL,
  `id_gestion_cobros` int(10) UNSIGNED NOT NULL,
  `id_tipo_unidad` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `gestion_cobros_detalles`
--

INSERT INTO `gestion_cobros_detalles` (`id`, `tipo`, `descripcion`, `costo`, `id_gestion_cobros`, `id_tipo_unidad`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 'Cuota Extraordinaria', 'Se daño una teja may', 100000, 1, 1, 1, '2018-11-24 07:40:26', '2018-11-24 07:40:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id` int(10) UNSIGNED NOT NULL,
  `valor` bigint(20) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `persona_pago` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `persona_recibe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_apto` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id`, `valor`, `descripcion`, `persona_pago`, `persona_recibe`, `id_apto`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(3, 200000, 'No pudo pagar, le cerraron el banco', 'William Henao', 'Edisson Bedoya', 2, 2, '2018-09-02 02:16:30', '2018-09-02 02:16:30'),
(5, 100000, 'esto es una descripcion', 'bird', 'nelson', 1, 1, '2018-09-07 07:10:35', '2018-09-07 07:10:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_zonas_comunes`
--

CREATE TABLE `inventario_zonas_comunes` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `valor_uso` bigint(20) DEFAULT NULL,
  `id_zona_comun` int(10) UNSIGNED DEFAULT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventario_zonas_comunes`
--

INSERT INTO `inventario_zonas_comunes` (`id`, `nombre`, `descripcion`, `valor_uso`, `id_zona_comun`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 'Bose', 'asdfasdfasdf', NULL, 1, 1, '2018-08-26 02:58:10', '2018-08-26 02:58:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

CREATE TABLE `mascotas` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_dueno` int(10) UNSIGNED NOT NULL,
  `id_tipo_unidad` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`id`, `tipo`, `nombre`, `descripcion`, `foto`, `id_dueno`, `id_tipo_unidad`, `created_at`, `updated_at`) VALUES
(3, 'Perro', 'rodolfo', 'rodolfete', 'default_img.jpg', 3, 1, '2018-12-26 00:39:42', '2018-12-26 00:39:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2012_05_01_105748_create_tipo_conjunto_table', 1),
(3, '2013_05_02_143321_create_roles_table', 1),
(5, '2014_10_12_100000_create_password_resets_table', 1),
(6, '2018_05_02_120227_create_parqueaderos_table', 1),
(7, '2018_05_02_133618_create_divisiones_table', 1),
(10, '2018_05_02_152115_create_tipo_documentos_table', 1),
(11, '2018_05_02_152530_create_documentos_table', 1),
(12, '2018_05_02_153513_create_noticias_table', 1),
(13, '2018_05_02_154504_create_reglamento_table', 1),
(14, '2018_05_02_160054_create_alquileres_table', 1),
(16, '2018_05_31_124657_create_notas_table', 1),
(17, '2018_06_05_184629_create_quejas_reclamos_table', 1),
(18, '2018_06_05_205443_create_mascotas_table', 1),
(19, '2018_06_12_131221_create_actas_table', 1),
(20, '2018_06_12_162547_create_contacto_table', 1),
(21, '2018_06_21_211927_create_events_table', 1),
(22, '2018_07_05_142630_create_respuesta_peticiones_table', 1),
(23, '2018_07_06_204509_create_encomientas_table', 1),
(24, '2018_07_18_133403_create_tabla_intereses_table', 1),
(31, '2018_09_02_161152_create_cuota_adm_ord_table', 5),
(49, '2018_05_02_151353_create_zonas_comunes_table', 13),
(51, '2018_10_21_142707_create_inventario_zonas_comunes_table', 13),
(53, '2018_08_28_141943_create_activos_fijos_table', 15),
(54, '2014_10_12_000000_create_users_table', 16),
(55, '2018_08_28_200509_create_ingresos_table', 17),
(58, '2018_08_30_213556_create_conceptos_retencion_table', 18),
(74, '2018_11_30_152338_create_egresos_table', 19),
(75, '2018_11_30_205030_create_egresos_detalles_table', 19),
(76, '2018_10_16_135300_create_ejecucion_presupuestal_total_table', 20),
(77, '2018_10_16_143951_create_tipo_ejecucion_pre_table', 20),
(78, '2018_10_17_135350_create_ejecucion_presupuestal_individual_table', 20),
(80, '2018_11_30_304556_create_saldo_favor_table', 21),
(84, '2013_05_02_114029_create_conjuntos_table', 24),
(85, '2018_1_30_413840_create_registros_csv_table', 25),
(88, '2018_10_30_233653_create_consecutivos_table', 27),
(89, '2018_11_13_132829_create_gestion_cobros_table', 27),
(90, '2018_11_15_032426_create_gestion_cobros_detalles_table', 28),
(91, '2018_10_12_160501_create_multas_table', 29),
(93, '2018_09_02_161506_create_cuota_adm_ex_ord_table', 31),
(94, '2018_10_08_170520_create_fecha_couta_adm_table', 31),
(95, '2018_11_30_614424_create_cuota_admon_table', 31),
(96, '2018_08_21_143459_create_otros_cobros_table', 32),
(97, '2018_05_02_134747_create_tipo_unidad_table', 33),
(98, '2018_12_18_011050_create_residentes_table', 33);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `multas`
--

CREATE TABLE `multas` (
  `id` int(10) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `costo` bigint(20) NOT NULL,
  `resolucion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_factura` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_tipo_unidad` int(10) UNSIGNED NOT NULL,
  `id_acta` int(10) UNSIGNED DEFAULT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `multas`
--

INSERT INTO `multas` (`id`, `fecha`, `descripcion`, `costo`, `resolucion`, `estado`, `estado_factura`, `id_user`, `id_tipo_unidad`, `id_acta`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, '2018-08-14', 'asdsadasd', 12222, NULL, 'En deuda', NULL, 2, 1, NULL, 1, '2018-08-14 15:23:49', '2018-11-21 07:06:00'),
(2, '2018-08-22', 'se daño', 200, '1212', 'En deuda', NULL, 2, 1, NULL, 1, '2018-08-14 15:24:33', '2018-11-07 12:15:49'),
(3, '2018-08-21', 'dfasdfafsd', 122222, '34545', 'En deuda', NULL, 3, 1, 1, 1, '2018-08-22 11:37:38', '2018-11-07 12:15:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_creador` int(10) UNSIGNED NOT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_receptor` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `otros_cobros`
--

CREATE TABLE `otros_cobros` (
  `id` int(10) UNSIGNED NOT NULL,
  `costo` bigint(20) NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_factura` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_tipo_unidad` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `otros_cobros`
--

INSERT INTO `otros_cobros` (`id`, `costo`, `descripcion`, `estado`, `estado_factura`, `id_tipo_unidad`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 50000, 'Alquiler de la piscina', 'En deuda', NULL, 1, 1, '2018-11-23 07:08:40', '2018-11-23 07:08:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parqueaderos`
--

CREATE TABLE `parqueaderos` (
  `id` int(10) UNSIGNED NOT NULL,
  `numero_letra` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `privacidad` enum('Privado','Común') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Común',
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('ebedoya18@misena.edu.co', '$2y$10$NBPaE1vBx4ItW371T65F1OV7/PomXbb5qdC7B0eQqEOegchPViv1C', '2018-12-13 06:34:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quejas_reclamos`
--

CREATE TABLE `quejas_reclamos` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_limite` date NOT NULL,
  `dias_restantes` bigint(20) NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_proveedor` int(10) UNSIGNED DEFAULT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `quejas_reclamos`
--

INSERT INTO `quejas_reclamos` (`id`, `tipo`, `titulo`, `descripcion`, `estado`, `fecha_solicitud`, `fecha_limite`, `dias_restantes`, `id_user`, `id_proveedor`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 'Petición', 'titulo 1', 'descripcion 1', 'Cerrado', '2018-08-01', '2018-08-16', 15, 3, NULL, 1, '2018-08-02 05:37:21', '2018-08-04 02:45:34'),
(2, 'Petición', 'titulo 2', 'dasdasdasd', 'Pendiente', '2018-08-01', '2018-08-16', 6, 3, NULL, 1, '2018-08-02 05:37:41', '2018-08-02 05:37:41'),
(3, 'Sugerencia', 'titulo 3', 'dessdnfjsdf 3', 'Pendiente', '2018-08-01', '2018-08-16', 15, 3, NULL, 1, '2018-08-02 05:38:08', '2018-08-02 05:38:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_csv`
--

CREATE TABLE `registros_csv` (
  `id` int(10) UNSIGNED NOT NULL,
  `valor` bigint(20) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `referencia` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cod_unico` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_tipo_unidad` int(10) UNSIGNED DEFAULT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reglamento`
--

CREATE TABLE `reglamento` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo_reglamento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `residentes`
--

CREATE TABLE `residentes` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo_residente` enum('inquilino','familiar') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `documento` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generar_carta` enum('Si','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `fecha_ingreso` date NOT NULL,
  `fecha_salida` date DEFAULT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Activo',
  `id_tipo_unidad` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `residentes`
--

INSERT INTO `residentes` (`id`, `tipo_residente`, `nombre`, `apellido`, `genero`, `tipo_documento`, `documento`, `generar_carta`, `fecha_ingreso`, `fecha_salida`, `estado`, `id_tipo_unidad`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 'familiar', 'Wisin', 'Morera', 'Masculino', 'Cedula de Ciudadanía', '234353', 'No', '2018-12-25', NULL, 'Activo', 1, 1, '2018-12-25 22:56:43', '2018-12-25 22:56:43'),
(2, 'familiar', 'Matando', 'la liga', 'Indefinido', 'Tarjeta de Identidad', '346456456', 'No', '2018-12-06', NULL, 'Activo', 1, 1, '2018-12-25 22:57:39', '2018-12-25 22:57:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta_peticiones`
--

CREATE TABLE `respuesta_peticiones` (
  `id` int(10) UNSIGNED NOT NULL,
  `respuesta` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_peticion` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `respuesta_peticiones`
--

INSERT INTO `respuesta_peticiones` (`id`, `respuesta`, `id_peticion`, `created_at`, `updated_at`) VALUES
(1, 'sadasd', 1, '2018-08-04 01:46:45', '2018-08-04 02:45:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `tipo`, `created_at`, `updated_at`) VALUES
(1, 'Owner', '2018-05-04 19:00:00', '2018-05-04 19:00:00'),
(2, 'Admin', '2018-05-04 19:00:00', '2018-05-04 19:00:00'),
(3, 'Dueno', '2018-05-04 19:00:00', '2018-05-04 19:00:00'),
(4, 'Alquilado', '2018-05-04 19:00:00', '2018-05-04 19:00:00'),
(5, 'Empleado', '2018-05-04 19:00:00', '2018-05-04 19:00:00'),
(6, 'Proveedor', '2018-05-04 19:00:00', '2018-05-04 19:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `saldo_favor`
--

CREATE TABLE `saldo_favor` (
  `id` int(10) UNSIGNED NOT NULL,
  `saldo` bigint(20) NOT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_tipo_unidad` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `saldo_favor`
--

INSERT INTO `saldo_favor` (`id`, `saldo`, `estado`, `id_tipo_unidad`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 200000, 'Activo', 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabla_intereses`
--

CREATE TABLE `tabla_intereses` (
  `id` int(10) UNSIGNED NOT NULL,
  `periodo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_vigencia_inicio` date NOT NULL,
  `fecha_vigencia_fin` date NOT NULL,
  `numero_resolucion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tasa_efectiva_anual` decimal(8,2) NOT NULL,
  `tasa_efectiva_anual_mora` decimal(8,2) NOT NULL,
  `tasa_mora_nominal_anual` double DEFAULT NULL,
  `tasa_mora_nominal_mensual` double DEFAULT NULL,
  `tasa_diaria` double(10,9) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tabla_intereses`
--

INSERT INTO `tabla_intereses` (`id`, `periodo`, `fecha_vigencia_inicio`, `fecha_vigencia_fin`, `numero_resolucion`, `tasa_efectiva_anual`, `tasa_efectiva_anual_mora`, `tasa_mora_nominal_anual`, `tasa_mora_nominal_mensual`, `tasa_diaria`, `created_at`, `updated_at`) VALUES
(1, 'Enero 2018', '2018-01-01', '2018-02-28', '100-100', '20.20', '30.30', 26.76, 2.23, 0.000743333, '2018-08-18 23:49:31', '2018-08-18 23:49:31'),
(2, 'Febrero 2018', '2018-02-28', '2018-03-31', '200-200', '19.10', '28.65', 25.46, 2.122, 0.000707333, '2018-08-18 23:50:26', '2018-08-18 23:50:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_conjunto`
--

CREATE TABLE `tipo_conjunto` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_conjunto`
--

INSERT INTO `tipo_conjunto` (`id`, `tipo`, `created_at`, `updated_at`) VALUES
(1, 'Condominio', '2018-06-20 02:49:03', '2018-06-20 02:49:03'),
(2, 'Conjunto Residencial', '2018-06-20 02:49:17', '2018-06-20 02:49:17'),
(3, 'Conjunto Cerrado', '2018-06-20 02:49:24', '2018-06-20 02:49:24'),
(4, 'Edificio', '2018-06-20 02:49:29', '2018-06-20 02:49:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documentos`
--

CREATE TABLE `tipo_documentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_ejecucion_pre`
--

CREATE TABLE `tipo_ejecucion_pre` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_ejecucion_pre`
--

INSERT INTO `tipo_ejecucion_pre` (`id`, `tipo`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Vigilancia', 'asdasdas', '2018-09-05 09:44:37', '2018-09-05 09:44:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_unidad`
--

CREATE TABLE `tipo_unidad` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo_unidad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_letra` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_habitaciones` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coeficiente` decimal(8,2) DEFAULT NULL,
  `referencia_bancaria` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_division` int(10) UNSIGNED DEFAULT NULL,
  `id_parqueadero` int(10) UNSIGNED DEFAULT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `id_dueno_apto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_unidad`
--

INSERT INTO `tipo_unidad` (`id`, `tipo_unidad`, `numero_letra`, `numero_habitaciones`, `coeficiente`, `referencia_bancaria`, `id_division`, `id_parqueadero`, `id_conjunto`, `id_dueno_apto`, `created_at`, `updated_at`) VALUES
(1, 'Apartamento', '101', '4', '20.20', 'NNAP101', NULL, NULL, 1, 3, '2018-08-01 14:29:01', '2018-08-01 14:31:26'),
(2, 'Oficina', '10', '2', NULL, 'NNOFI10', NULL, NULL, 2, 5, '2018-08-01 18:18:03', '2018-08-01 18:18:03'),
(3, 'Oficina', '20ABC', '2', '23.00', 'NNOFI2', NULL, NULL, 1, 5, '2018-08-10 18:23:27', '2018-08-10 18:23:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre_completo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_cedula` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_cedula` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `edad` bigint(20) NOT NULL,
  `genero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_cuenta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_cuenta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  `celular` bigint(20) NOT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Activo',
  `habeas_data` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sin Aceptar',
  `id_rol` int(10) UNSIGNED NOT NULL,
  `id_conjunto` int(10) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre_completo`, `tipo_cedula`, `numero_cedula`, `email`, `password`, `edad`, `genero`, `tipo_cuenta`, `numero_cuenta`, `telefono`, `celular`, `estado`, `habeas_data`, `id_rol`, `id_conjunto`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Edisson Bedoya', 'Cedula de Ciudadanía', '1053841381', 'ebedoya18@misena.edu.co', '$2y$10$LN3EnQGUoG1jf/jYvbgzpuWt8I9gEFz8ovuSdH4.XNQpNOCnlCoqa', 23, 'Masculino', 'Corriente', '$2y$10$gBCGTsFux3fpQLnKLx/uAONjOXfSA/.ERwlQU2K5xOzD8p3QhHLxW', NULL, 3186168519, 'Activo', 'Acepto', 1, NULL, 'gHxuZMrDY5YFDcC6Yuseckl1d9pylJEFYHd6JIl2SeQ1lrd2oDC0Np2J7yKv', '2018-05-06 02:28:47', '2018-06-01 19:06:21'),
(2, 'Gerardo', 'Cedula de Ciudadanía', '10238799', 'titobedoya01@hotmail.com', '$2y$10$Qraa2igMSsX9.6VoOFOURuvzmkExVUnCWXOkwnNQy4VUSUQX7pNGu', 56, 'Masculino', NULL, NULL, NULL, 3146641013, 'Activo', 'Acepto', 2, 1, 'EoKqJ13Frtxow7q3VqRO9WpgtoGmgmI5utxMzgQvvTQ4VhYyQS3x15i7ZAm5', '2018-06-20 17:18:22', '2018-09-02 02:12:18'),
(3, 'Edisson Bedoya', 'Cedula de Ciudadanía', '1053841382', 'ebg-0315@hotmail.com', '$2y$10$vhUqKgAl8uZnYwN.VI8nFe4Qlm754mlvPPMqVjx4GmiSU9gtxcyNm', 23, 'Masculino', NULL, '$2y$10$rOljsphGqwcldNwV8TawKu4dxRHxZiYCgjvz.4oQfQ/R89ZR2Xp/K', NULL, 3216253401, 'Activo', 'Acepto', 3, 1, 'C6IYEE2sHUkrKAvc9phnL9PGG1iB0p4m0fk1fKabhDL4S61xBcnhkyZaP3ow', '2018-06-20 13:09:18', '2018-08-29 03:03:01'),
(4, 'William Cardona', 'Cedula de Ciudadanía', '10538478989', 'guilian@yahoo.es', '$2y$10$/pMzcnWXkuCKXzMlKqzIB.xN6wUveBN2GwrkyEUsTv/NsiWe5UtUK', 19, 'Masculino', NULL, NULL, 8812172, 3116027191, 'Activo', 'Acepto', 2, 2, 'Fbuykx2dhTO47U1ZmARlVNWdys0gkZawOLzOVA5abmqurUF5tfyGVRQNIw9k', '2018-07-12 01:16:10', '2018-08-23 07:51:15'),
(5, 'Alejandro Grajales', 'Cedula de Ciudadanía', '4567457', 'alejo@hotmail.com', '$2y$10$4De7fG7ATyuHK79V.lHzj.vYKXRij3/qtjstuxe9K6.9k15dT58ky', 23, 'Masculino', NULL, NULL, NULL, 87878787, 'Activo', 'Acepto', 3, NULL, 'wvfV24tzF8BXVUyC2Ibkq9EkL5CfcN7gFhGS2iPwY42QkRSq8kwJgEI9meds', '2018-07-12 01:37:30', '2018-08-23 07:52:07'),
(6, 'Juan carlos', 'Cedula de Ciudadanía', '23523452435', 'carlos@hotmail.com', '$2y$10$9/5DUsBHedK1CZ8/tS6fGukHhWOwBESUZxRxf3NGlwmNNOg3wgDAq', 23, 'Masculino', NULL, NULL, NULL, 568567, 'Activo', 'Acepto', 3, NULL, '6xoMQy3sYwPXZTtkO5clcEyJhWFMBHgwnbjCdXyy8Ti2m8PjMN8J9uIe1FLv', '2018-07-13 07:08:50', '2018-08-23 07:52:46'),
(7, 'Arcangel', 'Cedula de Ciudadanía', '43634634', 'arca@hotmail.com', '$2y$12$CJx0YNDV5IqEE1hBDUK4O.j/Nm9SPCB/1Ts7GvWcuB5LWHvFGen9S', 99, 'No aplica', NULL, NULL, NULL, 3216253401, 'Activo', 'Acepto', 6, NULL, '96o3h6Ioqzt2fP0H6VcCpJxAUoHPxlBY9X1Qvvm670WReBunoPxWTsZbUTp0', '2018-07-14 03:32:03', '2018-08-29 03:10:39'),
(8, 'Andres sierra', 'Cedula de Ciudadanía', '232323', 'sierra@hotmail.com', '$2y$10$XNzVSi47w8Ml2/kKXq1nku.kDFdmd5270L1sNzzoW4VCKBmQ6RPuS', 32, 'Masculino', NULL, NULL, NULL, 8812121, 'Activo', 'Acepto', 5, 1, 'RSPGW6ZfQUlXg20AXG7fZXNl6jtAmoZhBBQ5nbgXtu7ZXlwzRJtNbv44MwdX', '2018-08-25 05:27:33', '2018-08-25 05:27:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zonas_comunes`
--

CREATE TABLE `zonas_comunes` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_propiedad` enum('Privado','Común') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Común',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor_uso` bigint(20) DEFAULT NULL,
  `id_conjunto` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `zonas_comunes`
--

INSERT INTO `zonas_comunes` (`id`, `nombre`, `tipo_propiedad`, `color`, `valor_uso`, `id_conjunto`, `created_at`, `updated_at`) VALUES
(1, 'Piscina', 'Común', '#0080ff', 100000, 1, '2018-08-22 01:48:14', '2018-08-22 01:53:57');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actas`
--
ALTER TABLE `actas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actas_id_user_foreign` (`id_user`),
  ADD KEY `actas_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `activos_fijos`
--
ALTER TABLE `activos_fijos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activos_fijos_id_acta_foreign` (`id_acta`),
  ADD KEY `activos_fijos_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `alquileres`
--
ALTER TABLE `alquileres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alquileres_id_user_foreign` (`id_user`),
  ADD KEY `alquileres_id_zona_foreign` (`id_zona`);

--
-- Indices de la tabla `conceptos_retencion`
--
ALTER TABLE `conceptos_retencion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `conjuntos`
--
ALTER TABLE `conjuntos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conjuntos_id_tipo_propiedad_foreign` (`id_tipo_propiedad`);

--
-- Indices de la tabla `consecutivos`
--
ALTER TABLE `consecutivos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consecutivos_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuota_admon`
--
ALTER TABLE `cuota_admon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuota_admon_id_tipo_unidad_foreign` (`id_tipo_unidad`),
  ADD KEY `cuota_admon_id_cuota_adm_ord_foreign` (`id_cuota_adm_ord`),
  ADD KEY `cuota_admon_id_tabla_intereses_foreign` (`id_tabla_intereses`),
  ADD KEY `cuota_admon_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `cuota_adm_ex_ord`
--
ALTER TABLE `cuota_adm_ex_ord`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuota_adm_ex_ord_id_tipo_unidad_foreign` (`id_tipo_unidad`),
  ADD KEY `cuota_adm_ex_ord_id_conjunto_foreign` (`id_conjunto`),
  ADD KEY `cuota_adm_ex_ord_id_acta_foreign` (`id_acta`);

--
-- Indices de la tabla `cuota_adm_ord`
--
ALTER TABLE `cuota_adm_ord`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuota_adm_ord_id_tipo_unidad_foreign` (`id_tipo_unidad`),
  ADD KEY `cuota_adm_ord_id_conjunto_foreign` (`id_conjunto`),
  ADD KEY `cuota_adm_ord_id_acta_foreign` (`id_acta`);

--
-- Indices de la tabla `divisiones`
--
ALTER TABLE `divisiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `divisiones_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `egresos_id_consecutivo_egresos_foreign` (`id_consecutivo_egresos`),
  ADD KEY `egresos_id_proveedor_foreign` (`id_proveedor`),
  ADD KEY `egresos_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `egresos_detalles`
--
ALTER TABLE `egresos_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `egresos_detalles_id_egresos_foreign` (`id_egresos`),
  ADD KEY `egresos_detalles_id_presup_individual_foreign` (`id_presup_individual`),
  ADD KEY `egresos_detalles_id_conceptos_retencion_foreign` (`id_conceptos_retencion`);

--
-- Indices de la tabla `ejecucion_presupuestal_individual`
--
ALTER TABLE `ejecucion_presupuestal_individual`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ejecucion_presupuestal_individual_id_tipo_ejecucion_foreign` (`id_tipo_ejecucion`),
  ADD KEY `ejecucion_presupuestal_individual_id_ejecucion_pre_total_foreign` (`id_ejecucion_pre_total`),
  ADD KEY `ejecucion_presupuestal_individual_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `ejecucion_presupuestal_total`
--
ALTER TABLE `ejecucion_presupuestal_total`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ejecucion_presupuestal_total_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `encomientas`
--
ALTER TABLE `encomientas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `encomientas_id_conjunto_foreign` (`id_conjunto`),
  ADD KEY `encomientas_id_tipo_unidad_foreign` (`id_tipo_unidad`);

--
-- Indices de la tabla `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_id_user_foreign` (`id_user`),
  ADD KEY `events_id_zona_comun_foreign` (`id_zona_comun`),
  ADD KEY `events_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `fecha_couta_adm`
--
ALTER TABLE `fecha_couta_adm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fecha_couta_adm_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `gestion_cobros`
--
ALTER TABLE `gestion_cobros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gestion_cobros_id_tbl_consecutivo_foreign` (`id_tbl_consecutivo`),
  ADD KEY `gestion_cobros_id_tipo_unidad_foreign` (`id_tipo_unidad`),
  ADD KEY `gestion_cobros_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `gestion_cobros_detalles`
--
ALTER TABLE `gestion_cobros_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gestion_cobros_detalles_id_gestion_cobros_foreign` (`id_gestion_cobros`),
  ADD KEY `gestion_cobros_detalles_id_tipo_unidad_foreign` (`id_tipo_unidad`),
  ADD KEY `gestion_cobros_detalles_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingresos_id_apto_foreign` (`id_apto`),
  ADD KEY `ingresos_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `inventario_zonas_comunes`
--
ALTER TABLE `inventario_zonas_comunes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventario_zonas_comunes_id_zona_comun_foreign` (`id_zona_comun`),
  ADD KEY `inventario_zonas_comunes_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mascotas_id_dueno_foreign` (`id_dueno`),
  ADD KEY `mascotas_id_tipo_unidad_foreign` (`id_tipo_unidad`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `multas`
--
ALTER TABLE `multas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `multas_id_user_foreign` (`id_user`),
  ADD KEY `multas_id_tipo_unidad_foreign` (`id_tipo_unidad`),
  ADD KEY `multas_id_acta_foreign` (`id_acta`),
  ADD KEY `multas_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notas_id_creador_foreign` (`id_creador`),
  ADD KEY `notas_id_receptor_foreign` (`id_receptor`),
  ADD KEY `notas_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `noticias_id_user_foreign` (`id_user`),
  ADD KEY `noticias_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `otros_cobros`
--
ALTER TABLE `otros_cobros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otros_cobros_id_tipo_unidad_foreign` (`id_tipo_unidad`),
  ADD KEY `otros_cobros_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `parqueaderos`
--
ALTER TABLE `parqueaderos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parqueaderos_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `quejas_reclamos`
--
ALTER TABLE `quejas_reclamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quejas_reclamos_id_user_foreign` (`id_user`),
  ADD KEY `quejas_reclamos_id_proveedor_foreign` (`id_proveedor`),
  ADD KEY `quejas_reclamos_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `registros_csv`
--
ALTER TABLE `registros_csv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registros_csv_id_tipo_unidad_foreign` (`id_tipo_unidad`),
  ADD KEY `registros_csv_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `reglamento`
--
ALTER TABLE `reglamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reglamento_id_user_foreign` (`id_user`),
  ADD KEY `reglamento_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `residentes`
--
ALTER TABLE `residentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `residentes_documento_unique` (`documento`),
  ADD KEY `residentes_id_tipo_unidad_foreign` (`id_tipo_unidad`),
  ADD KEY `residentes_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `respuesta_peticiones`
--
ALTER TABLE `respuesta_peticiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `respuesta_peticiones_id_peticion_foreign` (`id_peticion`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `saldo_favor`
--
ALTER TABLE `saldo_favor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `saldo_favor_id_tipo_unidad_foreign` (`id_tipo_unidad`),
  ADD KEY `saldo_favor_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `tabla_intereses`
--
ALTER TABLE `tabla_intereses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_conjunto`
--
ALTER TABLE `tipo_conjunto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_documentos`
--
ALTER TABLE `tipo_documentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_ejecucion_pre`
--
ALTER TABLE `tipo_ejecucion_pre`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_unidad`
--
ALTER TABLE `tipo_unidad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_unidad_id_division_foreign` (`id_division`),
  ADD KEY `tipo_unidad_id_parqueadero_foreign` (`id_parqueadero`),
  ADD KEY `tipo_unidad_id_conjunto_foreign` (`id_conjunto`),
  ADD KEY `tipo_unidad_id_dueno_apto_foreign` (`id_dueno_apto`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_numero_cedula_unique` (`numero_cedula`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_id_rol_foreign` (`id_rol`),
  ADD KEY `users_id_conjunto_foreign` (`id_conjunto`);

--
-- Indices de la tabla `zonas_comunes`
--
ALTER TABLE `zonas_comunes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zonas_comunes_id_conjunto_foreign` (`id_conjunto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actas`
--
ALTER TABLE `actas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `activos_fijos`
--
ALTER TABLE `activos_fijos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alquileres`
--
ALTER TABLE `alquileres`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conceptos_retencion`
--
ALTER TABLE `conceptos_retencion`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `conjuntos`
--
ALTER TABLE `conjuntos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `consecutivos`
--
ALTER TABLE `consecutivos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuota_admon`
--
ALTER TABLE `cuota_admon`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cuota_adm_ex_ord`
--
ALTER TABLE `cuota_adm_ex_ord`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cuota_adm_ord`
--
ALTER TABLE `cuota_adm_ord`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `divisiones`
--
ALTER TABLE `divisiones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `egresos`
--
ALTER TABLE `egresos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `egresos_detalles`
--
ALTER TABLE `egresos_detalles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ejecucion_presupuestal_individual`
--
ALTER TABLE `ejecucion_presupuestal_individual`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ejecucion_presupuestal_total`
--
ALTER TABLE `ejecucion_presupuestal_total`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `encomientas`
--
ALTER TABLE `encomientas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `fecha_couta_adm`
--
ALTER TABLE `fecha_couta_adm`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `gestion_cobros`
--
ALTER TABLE `gestion_cobros`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `gestion_cobros_detalles`
--
ALTER TABLE `gestion_cobros_detalles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `inventario_zonas_comunes`
--
ALTER TABLE `inventario_zonas_comunes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT de la tabla `multas`
--
ALTER TABLE `multas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `otros_cobros`
--
ALTER TABLE `otros_cobros`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `parqueaderos`
--
ALTER TABLE `parqueaderos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quejas_reclamos`
--
ALTER TABLE `quejas_reclamos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `registros_csv`
--
ALTER TABLE `registros_csv`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reglamento`
--
ALTER TABLE `reglamento`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `residentes`
--
ALTER TABLE `residentes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `respuesta_peticiones`
--
ALTER TABLE `respuesta_peticiones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `saldo_favor`
--
ALTER TABLE `saldo_favor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tabla_intereses`
--
ALTER TABLE `tabla_intereses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_conjunto`
--
ALTER TABLE `tipo_conjunto`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_documentos`
--
ALTER TABLE `tipo_documentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_ejecucion_pre`
--
ALTER TABLE `tipo_ejecucion_pre`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipo_unidad`
--
ALTER TABLE `tipo_unidad`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `zonas_comunes`
--
ALTER TABLE `zonas_comunes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actas`
--
ALTER TABLE `actas`
  ADD CONSTRAINT `actas_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `actas_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `activos_fijos`
--
ALTER TABLE `activos_fijos`
  ADD CONSTRAINT `activos_fijos_id_acta_foreign` FOREIGN KEY (`id_acta`) REFERENCES `actas` (`id`),
  ADD CONSTRAINT `activos_fijos_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `alquileres`
--
ALTER TABLE `alquileres`
  ADD CONSTRAINT `alquileres_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alquileres_id_zona_foreign` FOREIGN KEY (`id_zona`) REFERENCES `zonas_comunes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `conjuntos`
--
ALTER TABLE `conjuntos`
  ADD CONSTRAINT `conjuntos_id_tipo_propiedad_foreign` FOREIGN KEY (`id_tipo_propiedad`) REFERENCES `tipo_conjunto` (`id`);

--
-- Filtros para la tabla `consecutivos`
--
ALTER TABLE `consecutivos`
  ADD CONSTRAINT `consecutivos_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cuota_admon`
--
ALTER TABLE `cuota_admon`
  ADD CONSTRAINT `cuota_admon_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cuota_admon_id_cuota_adm_ord_foreign` FOREIGN KEY (`id_cuota_adm_ord`) REFERENCES `cuota_adm_ord` (`id`),
  ADD CONSTRAINT `cuota_admon_id_tabla_intereses_foreign` FOREIGN KEY (`id_tabla_intereses`) REFERENCES `tabla_intereses` (`id`),
  ADD CONSTRAINT `cuota_admon_id_tipo_unidad_foreign` FOREIGN KEY (`id_tipo_unidad`) REFERENCES `tipo_unidad` (`id`);

--
-- Filtros para la tabla `cuota_adm_ex_ord`
--
ALTER TABLE `cuota_adm_ex_ord`
  ADD CONSTRAINT `cuota_adm_ex_ord_id_acta_foreign` FOREIGN KEY (`id_acta`) REFERENCES `actas` (`id`),
  ADD CONSTRAINT `cuota_adm_ex_ord_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cuota_adm_ex_ord_id_tipo_unidad_foreign` FOREIGN KEY (`id_tipo_unidad`) REFERENCES `tipo_unidad` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cuota_adm_ord`
--
ALTER TABLE `cuota_adm_ord`
  ADD CONSTRAINT `cuota_adm_ord_id_acta_foreign` FOREIGN KEY (`id_acta`) REFERENCES `actas` (`id`),
  ADD CONSTRAINT `cuota_adm_ord_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cuota_adm_ord_id_tipo_unidad_foreign` FOREIGN KEY (`id_tipo_unidad`) REFERENCES `tipo_unidad` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `divisiones`
--
ALTER TABLE `divisiones`
  ADD CONSTRAINT `divisiones_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD CONSTRAINT `egresos_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `egresos_id_consecutivo_egresos_foreign` FOREIGN KEY (`id_consecutivo_egresos`) REFERENCES `consecutivos` (`id`),
  ADD CONSTRAINT `egresos_id_proveedor_foreign` FOREIGN KEY (`id_proveedor`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `egresos_detalles`
--
ALTER TABLE `egresos_detalles`
  ADD CONSTRAINT `egresos_detalles_id_conceptos_retencion_foreign` FOREIGN KEY (`id_conceptos_retencion`) REFERENCES `conceptos_retencion` (`id`),
  ADD CONSTRAINT `egresos_detalles_id_egresos_foreign` FOREIGN KEY (`id_egresos`) REFERENCES `egresos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `egresos_detalles_id_presup_individual_foreign` FOREIGN KEY (`id_presup_individual`) REFERENCES `ejecucion_presupuestal_individual` (`id`);

--
-- Filtros para la tabla `ejecucion_presupuestal_individual`
--
ALTER TABLE `ejecucion_presupuestal_individual`
  ADD CONSTRAINT `ejecucion_presupuestal_individual_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ejecucion_presupuestal_individual_id_ejecucion_pre_total_foreign` FOREIGN KEY (`id_ejecucion_pre_total`) REFERENCES `ejecucion_presupuestal_total` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ejecucion_presupuestal_individual_id_tipo_ejecucion_foreign` FOREIGN KEY (`id_tipo_ejecucion`) REFERENCES `tipo_ejecucion_pre` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ejecucion_presupuestal_total`
--
ALTER TABLE `ejecucion_presupuestal_total`
  ADD CONSTRAINT `ejecucion_presupuestal_total_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `encomientas`
--
ALTER TABLE `encomientas`
  ADD CONSTRAINT `encomientas_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `encomientas_id_tipo_unidad_foreign` FOREIGN KEY (`id_tipo_unidad`) REFERENCES `tipo_unidad` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_id_zona_comun_foreign` FOREIGN KEY (`id_zona_comun`) REFERENCES `zonas_comunes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `fecha_couta_adm`
--
ALTER TABLE `fecha_couta_adm`
  ADD CONSTRAINT `fecha_couta_adm_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `gestion_cobros`
--
ALTER TABLE `gestion_cobros`
  ADD CONSTRAINT `gestion_cobros_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gestion_cobros_id_tbl_consecutivo_foreign` FOREIGN KEY (`id_tbl_consecutivo`) REFERENCES `consecutivos` (`id`),
  ADD CONSTRAINT `gestion_cobros_id_tipo_unidad_foreign` FOREIGN KEY (`id_tipo_unidad`) REFERENCES `tipo_unidad` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `gestion_cobros_detalles`
--
ALTER TABLE `gestion_cobros_detalles`
  ADD CONSTRAINT `gestion_cobros_detalles_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gestion_cobros_detalles_id_gestion_cobros_foreign` FOREIGN KEY (`id_gestion_cobros`) REFERENCES `gestion_cobros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gestion_cobros_detalles_id_tipo_unidad_foreign` FOREIGN KEY (`id_tipo_unidad`) REFERENCES `tipo_unidad` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `ingresos_id_apto_foreign` FOREIGN KEY (`id_apto`) REFERENCES `tipo_unidad` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ingresos_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventario_zonas_comunes`
--
ALTER TABLE `inventario_zonas_comunes`
  ADD CONSTRAINT `inventario_zonas_comunes_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventario_zonas_comunes_id_zona_comun_foreign` FOREIGN KEY (`id_zona_comun`) REFERENCES `zonas_comunes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD CONSTRAINT `mascotas_id_dueno_foreign` FOREIGN KEY (`id_dueno`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `mascotas_id_tipo_unidad_foreign` FOREIGN KEY (`id_tipo_unidad`) REFERENCES `tipo_unidad` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `multas`
--
ALTER TABLE `multas`
  ADD CONSTRAINT `multas_id_acta_foreign` FOREIGN KEY (`id_acta`) REFERENCES `actas` (`id`),
  ADD CONSTRAINT `multas_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `multas_id_tipo_unidad_foreign` FOREIGN KEY (`id_tipo_unidad`) REFERENCES `tipo_unidad` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `multas_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notas_id_creador_foreign` FOREIGN KEY (`id_creador`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notas_id_receptor_foreign` FOREIGN KEY (`id_receptor`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `noticias_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `noticias_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `otros_cobros`
--
ALTER TABLE `otros_cobros`
  ADD CONSTRAINT `otros_cobros_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `otros_cobros_id_tipo_unidad_foreign` FOREIGN KEY (`id_tipo_unidad`) REFERENCES `tipo_unidad` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `parqueaderos`
--
ALTER TABLE `parqueaderos`
  ADD CONSTRAINT `parqueaderos_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `quejas_reclamos`
--
ALTER TABLE `quejas_reclamos`
  ADD CONSTRAINT `quejas_reclamos_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quejas_reclamos_id_proveedor_foreign` FOREIGN KEY (`id_proveedor`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `quejas_reclamos_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `registros_csv`
--
ALTER TABLE `registros_csv`
  ADD CONSTRAINT `registros_csv_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registros_csv_id_tipo_unidad_foreign` FOREIGN KEY (`id_tipo_unidad`) REFERENCES `tipo_unidad` (`id`);

--
-- Filtros para la tabla `reglamento`
--
ALTER TABLE `reglamento`
  ADD CONSTRAINT `reglamento_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reglamento_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `residentes`
--
ALTER TABLE `residentes`
  ADD CONSTRAINT `residentes_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `residentes_id_tipo_unidad_foreign` FOREIGN KEY (`id_tipo_unidad`) REFERENCES `tipo_unidad` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `respuesta_peticiones`
--
ALTER TABLE `respuesta_peticiones`
  ADD CONSTRAINT `respuesta_peticiones_id_peticion_foreign` FOREIGN KEY (`id_peticion`) REFERENCES `quejas_reclamos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `saldo_favor`
--
ALTER TABLE `saldo_favor`
  ADD CONSTRAINT `saldo_favor_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saldo_favor_id_tipo_unidad_foreign` FOREIGN KEY (`id_tipo_unidad`) REFERENCES `tipo_unidad` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tipo_unidad`
--
ALTER TABLE `tipo_unidad`
  ADD CONSTRAINT `tipo_unidad_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tipo_unidad_id_division_foreign` FOREIGN KEY (`id_division`) REFERENCES `divisiones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tipo_unidad_id_dueno_apto_foreign` FOREIGN KEY (`id_dueno_apto`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tipo_unidad_id_parqueadero_foreign` FOREIGN KEY (`id_parqueadero`) REFERENCES `parqueaderos` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `zonas_comunes`
--
ALTER TABLE `zonas_comunes`
  ADD CONSTRAINT `zonas_comunes_id_conjunto_foreign` FOREIGN KEY (`id_conjunto`) REFERENCES `conjuntos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
