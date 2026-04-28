<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /sistema_financiero/auth/login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$id = $_GET['id'];

$conn->query("DELETE FROM gastos WHERE id=$id");

header("Location: index.php");