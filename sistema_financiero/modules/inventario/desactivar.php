<?php
session_start();

// 🔒 Validar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: /sistema_financiero/auth/login.php");
    exit;
}

// 🔌 Conexión
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

// 🔹 Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// 🔹 Verificar que exista
$res = $conn->query("SELECT id FROM inventario WHERE id = $id");

if ($res->num_rows == 0) {
    header("Location: index.php");
    exit;
}

// 🔴 Desactivar producto
$conn->query("UPDATE inventario SET estado = 0 WHERE id = $id");

// 🔁 Redirigir
header("Location: index.php");
exit;