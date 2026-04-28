<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /sistema_financiero/auth/login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM clientes WHERE id=$id");
$cliente = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Cliente</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }
        .card {
            border-radius: 12px;
        }
    </style>
</head>

<body class="container d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width: 400px;">

    <h4 class="mb-3 text-center">✏️ Editar Cliente</h4>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control"
                   value="<?php echo $cliente['nombre']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control"
                   value="<?php echo $cliente['telefono']; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="email" class="form-control"
                   value="<?php echo $cliente['email']; ?>">
        </div>

        <div class="d-grid gap-2">
            <button name="actualizar" class="btn btn-warning">
                🔄 Actualizar
            </button>

            <a href="index.php" class="btn btn-secondary">
                ⬅ Volver
            </a>
        </div>

    </form>

</div>

<?php
if (isset($_POST['actualizar'])) {

    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    $conn->query("UPDATE clientes 
                  SET nombre='$nombre', telefono='$telefono', email='$email'
                  WHERE id=$id");

    echo "<div class='alert alert-success text-center mt-3'>
            ✅ Cliente actualizado correctamente
          </div>";

    header("Refresh:1; url=index.php");
}
?>

</body>
</html>