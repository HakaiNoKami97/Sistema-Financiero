<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM ingresos WHERE id=$id");
$ingreso = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar ingreso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-4">

<h2>✏️ Editar ingreso</h2>

<div class="card p-4 shadow">

<form method="POST">

    <div class="mb-3">
        <label>Descripción</label>
        <input type="text" name="descripcion" class="form-control"
               value="<?php echo $ingreso['descripcion']; ?>" required>
    </div>

    <div class="mb-3">
        <label>Monto</label>
        <input type="number" step="0.01" name="monto" class="form-control"
               value="<?php echo $ingreso['monto']; ?>" required>
    </div>

    <button name="actualizar" class="btn btn-success">Actualizar</button>
    <a href="index.php" class="btn btn-secondary">Volver</a>

</form>

</div>

<?php
if (isset($_POST['actualizar'])) {

    $desc = $_POST['descripcion'];
    $monto = $_POST['monto'];

    if ($monto <= 0) {
        echo "<div class='alert alert-danger mt-3'>❌ Monto inválido</div>";
        exit;
    }

    $conn->query("UPDATE ingresos 
                  SET descripcion='$desc', monto='$monto'
                  WHERE id=$id");

    header("Location: index.php");
}
?>

</body>
</html>