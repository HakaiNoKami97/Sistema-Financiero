-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-04-2026 a las 00:13:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_financiero`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `puntos` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `telefono`, `email`, `puntos`) VALUES
(2, 'Guillermo', '3004969886', 'guillermo971013@hotmail.com', 36);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `id` int(11) NOT NULL,
  `factura_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_factura`
--

INSERT INTO `detalle_factura` (`id`, `factura_id`, `producto_id`, `cantidad`, `precio`, `subtotal`) VALUES
(1, 1, 2, 1, 3500.00, 3500.00),
(2, 3, 1, 1, 3000.00, 3000.00),
(3, 6, 3, 1, 35000.00, 35000.00),
(4, 6, 4, 1, 18000.00, 18000.00),
(5, 6, 11, 1, 12000.00, 12000.00),
(6, 6, 15, 1, 27000.00, 27000.00),
(7, 7, 3, 1, 35000.00, 35000.00),
(8, 8, 3, 1, 35000.00, 35000.00),
(9, 8, 4, 1, 18000.00, 18000.00),
(10, 8, 5, 1, 28000.00, 28000.00),
(11, 8, 6, 1, 22000.00, 22000.00),
(12, 8, 7, 1, 30000.00, 30000.00),
(13, 8, 8, 1, 32000.00, 32000.00),
(14, 8, 9, 1, 45000.00, 45000.00),
(15, 8, 10, 1, 15000.00, 15000.00),
(16, 8, 11, 1, 12000.00, 12000.00),
(17, 9, 3, 1, 35000.00, 35000.00),
(18, 10, 4, 5, 18000.00, 90000.00),
(19, 10, 6, 5, 22000.00, 110000.00),
(20, 10, 11, 10, 12000.00, 120000.00),
(21, 11, 7, 2, 30000.00, 60000.00),
(22, 11, 8, 1, 32000.00, 32000.00),
(23, 12, 4, 1, 18000.00, 18000.00),
(24, 13, 13, 10, 14000.00, 140000.00),
(25, 14, 21, 5, 17000.00, 85000.00),
(26, 15, 3, 1, 35000.00, 35000.00),
(27, 15, 4, 1, 18000.00, 18000.00),
(28, 15, 5, 1, 28000.00, 28000.00),
(29, 15, 6, 1, 22000.00, 22000.00),
(30, 15, 8, 1, 32000.00, 32000.00),
(31, 15, 7, 1, 30000.00, 30000.00),
(32, 15, 9, 1, 45000.00, 45000.00),
(33, 15, 12, 1, 20000.00, 20000.00),
(34, 21, 4, 1, 18000.00, 18000.00),
(35, 23, 17, 10, 6000.00, 60000.00),
(36, 26, 18, 2, 38000.00, 76000.00),
(37, 27, 11, 3, 12000.00, 36000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT 0.00,
  `descuento` decimal(10,2) DEFAULT 0.00,
  `metodo_pago` varchar(20) DEFAULT NULL,
  `banco` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `cliente_id`, `total`, `fecha`, `subtotal`, `descuento`, `metodo_pago`, `banco`) VALUES
(1, 2, 3500.00, '2026-04-08', 0.00, 0.00, NULL, NULL),
(2, 2, 0.00, '2026-04-08', 0.00, 0.00, NULL, NULL),
(3, 2, 3000.00, '2026-04-08', 0.00, 0.00, NULL, NULL),
(4, 2, 0.00, '2026-04-08', 0.00, 0.00, NULL, NULL),
(5, 2, 0.00, '2026-04-08', 0.00, 0.00, NULL, NULL),
(6, 2, 92000.00, '2026-04-10', 0.00, 0.00, NULL, NULL),
(7, 2, 35000.00, '2026-04-10', 0.00, 0.00, NULL, NULL),
(8, 2, 235800.00, '2026-04-10', 0.00, 0.00, NULL, NULL),
(9, 2, 35000.00, '2026-04-10', 0.00, 0.00, NULL, NULL),
(10, 2, 320000.00, '2026-04-10', 0.00, 0.00, NULL, NULL),
(11, 2, 89700.00, '2026-04-10', 0.00, 0.00, NULL, NULL),
(12, 2, 17200.00, '2026-04-10', 18000.00, 800.00, NULL, NULL),
(13, 2, 140000.00, '2026-04-10', 140000.00, 0.00, NULL, NULL),
(14, 2, 85000.00, '2026-04-10', 85000.00, 0.00, NULL, NULL),
(15, 2, 227700.00, '2026-04-16', 230000.00, 2300.00, NULL, NULL),
(16, 2, 0.00, NULL, 0.00, 900.00, 'electronico', 'Bancolombia'),
(17, 2, 18000.00, NULL, 0.00, 0.00, 'electronico', 'Nequi'),
(18, 2, 0.00, NULL, 0.00, 0.00, 'electronico', 'Nequi'),
(19, 2, 0.00, NULL, 0.00, 0.00, 'electronico', 'Nequi'),
(20, 2, 0.00, NULL, 0.00, 0.00, 'electronico', 'Nequi'),
(21, 2, 18000.00, NULL, 0.00, 0.00, 'electronico', 'Nequi'),
(22, 2, 0.00, NULL, 0.00, 0.00, 'electronico', 'Nequi'),
(23, 2, 60000.00, NULL, 0.00, 0.00, 'efectivo', ''),
(24, 2, 0.00, NULL, 0.00, 0.00, 'efectivo', ''),
(25, 2, 0.00, NULL, 0.00, 0.00, 'efectivo', ''),
(26, 2, 76000.00, NULL, 0.00, 0.00, '', ''),
(27, 2, 36000.00, NULL, 0.00, 0.00, 'electronico', 'Nequi');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id`, `descripcion`, `monto`, `fecha`) VALUES
(2, 'Celebracion de cumpleaños a empleado x', 70000.00, '2026-04-10'),
(4, 'Pago deuda: Distribuidora El Sol', 850000.00, '2026-04-28'),
(5, 'Pago deuda: Proveedor Tech SAS', 1250000.00, '2026-04-28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `tipo` varchar(50) NOT NULL DEFAULT 'extra'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id`, `descripcion`, `monto`, `fecha`, `tipo`) VALUES
(5, 'Ingreso de stock: Iluminador (+2 unidades)', 0.00, '2026-04-28', 'stock');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` int(11) NOT NULL,
  `producto` varchar(100) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `costo` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `producto`, `cantidad`, `precio`, `estado`, `costo`) VALUES
(1, 'Botella de agua', 0, 3000.00, 0, 1500.00),
(2, 'Cerveza', 0, 3500.00, 0, 2000.00),
(3, 'Base líquida', 20, 35000.00, 1, 20000.00),
(4, 'Corrector', 20, 18000.00, 1, 10000.00),
(5, 'Polvo compacto', 18, 28000.00, 1, 16000.00),
(6, 'Rubor', 10, 22000.00, 1, 12000.00),
(7, 'Iluminador', 10, 30000.00, 1, 18000.00),
(8, 'Contorno en crema', 7, 32000.00, 1, 18000.00),
(9, 'Paleta de sombras', 16, 45000.00, 1, 28000.00),
(10, 'Delineador líquido', 24, 15000.00, 1, 8000.00),
(11, 'Lápiz de cejas', 20, 12000.00, 1, 6000.00),
(12, 'Máscara de pestañas', 27, 20000.00, 1, 11000.00),
(13, 'Labial mate', 30, 14000.00, 1, 7000.00),
(14, 'Labial gloss', 30, 13000.00, 1, 7000.00),
(15, 'Primer facial', 13, 27000.00, 1, 15000.00),
(16, 'Fijador de maquillaje', 16, 26000.00, 1, 14000.00),
(17, 'Esponjas de maquillaje', 40, 6000.00, 1, 3000.00),
(18, 'Brochas (kit básico)', 18, 38000.00, 1, 22000.00),
(19, 'Desmaquillante', 18, 19000.00, 1, 10000.00),
(20, 'Toallitas desmaquillantes', 22, 10000.00, 1, 5000.00),
(21, 'Gel para cejas', 10, 17000.00, 1, 9000.00),
(22, 'Glitter facial', 10, 11000.00, 1, 6000.00),
(23, 'Delineador AVON (Pestañas muñeca)', 20, 32000.00, 0, 20000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pasivos`
--

CREATE TABLE `pasivos` (
  `id` int(11) NOT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pasivos`
--

INSERT INTO `pasivos` (`id`, `proveedor`, `descripcion`, `monto`, `fecha`, `fecha_vencimiento`, `estado`, `created_at`) VALUES
(1, 'Distribuidora El Sol', 'Compra de mercancía (lotes de productos varios)', 850000.00, '2026-04-20', '2026-05-02', 0, '2026-04-28 20:37:31'),
(2, 'Proveedor Tech SAS', 'Compra de equipos (teclados y mouse)', 1250000.00, '2026-03-15', '2026-04-10', 0, '2026-04-28 20:38:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`) VALUES
(2, 'admin@admin.com', '$2y$10$wUtluTjiYsjS10/dedp8suR/IoCPePAhmo.zwdYngxwf1mFAqzmVi');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura_id` (`factura_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pasivos`
--
ALTER TABLE `pasivos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `pasivos`
--
ALTER TABLE `pasivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `detalle_factura_ibfk_1` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`),
  ADD CONSTRAINT `detalle_factura_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `inventario` (`id`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
