<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$email = "admin@admin.com";
$password = password_hash("123456", PASSWORD_DEFAULT);

$conn->query("INSERT INTO usuarios (email, password)
              VALUES('$email', '$password')");

echo "Usuario creado correctamente";
?>