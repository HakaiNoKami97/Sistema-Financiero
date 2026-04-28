<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$id = $_GET['id'];

$conn->query("DELETE FROM ingresos WHERE id=$id");

header("Location: index.php");