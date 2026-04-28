<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /sistema_financiero/auth/login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$id = $_GET['id'];
$g = $conn->query("SELECT * FROM gastos WHERE id=$id")->fetch_assoc();

if (isset($_POST['actualizar'])) {

    $descripcion = $_POST['descripcion'];
    $monto = $_POST['monto'];

    if ($descripcion == "" || $monto <= 0) {
        echo "<div class='alert alert-danger'>Datos inválidos</div>";
    } else {
        $conn->query("UPDATE gastos 
                      SET descripcion='$descripcion', monto='$monto'
                      WHERE id=$id");

        header("Location: index.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Gasto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-4">

<h2>✏️ Editar Gasto</h2>

<div class="card p-4 shadow">
<form method="POST">

    <div class="mb-3">
        <label>Descripción</label>
        <input type="text" name="descripcion" class="form-control"
               value="<?php echo $g['descripcion']; ?>">
    </div>

    <div class="mb-3">
        <label>Monto</label>
        <input type="number" name="monto" class="form-control"
               value="<?php echo $g['monto']; ?>">
    </div>

    <button name="actualizar" class="btn btn-warning">Actualizar</button>
    <a href="index.php" class="btn btn-secondary">Volver</a>

</form>
</div>

</body>
</html>