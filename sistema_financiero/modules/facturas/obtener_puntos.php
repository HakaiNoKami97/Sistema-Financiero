<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$id = intval($_GET['id']);
$res = $conn->query("SELECT puntos FROM clientes WHERE id=$id");
$row = $res->fetch_assoc();

echo $row['puntos'];