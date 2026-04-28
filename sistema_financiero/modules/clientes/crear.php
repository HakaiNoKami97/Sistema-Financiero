<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Cliente</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery + Autocomplete -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <style>
        body {
            background-color: #f4f6f9;
        }
        .card {
            border-radius: 12px;
        }
        .ui-autocomplete {
            z-index: 9999;
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
</head>

<body class="container d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width: 400px;">

    <h4 class="mb-3 text-center">➕ Nuevo Cliente</h4>

    <form method="POST">

        <!-- NOMBRE -->
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>

        <!-- TELEFONO -->
        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" id="telefono" name="telefono" class="form-control">
        </div>

        <!-- EMAIL -->
        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" id="email" name="email" class="form-control">
        </div>

        <div class="d-grid gap-2">
            <button name="guardar" class="btn btn-success">
                💾 Guardar
            </button>

            <a href="index.php" class="btn btn-secondary">
                ⬅ Volver
            </a>
        </div>

    </form>

</div>

<!-- ================= AUTOCOMPLETE ================= -->
<script>
$(function(){

    $("#nombre").autocomplete({
        source: "buscar_clientes.php",
        minLength: 2,

        select: function(event, ui){
            $("#telefono").val(ui.item.telefono);
            $("#email").val(ui.item.email);
        }
    });

});
</script>

<?php
if (isset($_POST['guardar'])) {

    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    $conn->query("INSERT INTO clientes(nombre, telefono, email)
                  VALUES('$nombre','$telefono','$email')");

    echo "<div class='alert alert-success mt-3 text-center'>
            ✅ Cliente guardado correctamente
          </div>";

    header("Refresh:1; url=index.php");
}
?>

</body>
</html>