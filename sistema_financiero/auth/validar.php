<?php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$email = $_POST['email'];
$password_ingresada = $_POST['password'];

// Buscar solo por email
$sql = "SELECT * FROM usuarios WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();
    $hash = $user['password'];

    // ✅ VERIFICAR CONTRASEÑA
    if (password_verify($password_ingresada, $hash)) {

        $_SESSION['usuario'] = $user['email'];

        header("Location: /sistema_financiero/public/index.php");

    } else {
        echo "❌ Contraseña incorrecta";
    }

} else {
    echo "❌ Usuario no existe";
}
?>