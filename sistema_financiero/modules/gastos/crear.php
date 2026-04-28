<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /sistema_financiero/auth/login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

if (isset($_POST['guardar'])) {

    $descripcion = $_POST['descripcion'];
    $monto = $_POST['monto'];

    if ($descripcion == "") {
        echo "<div class='alert alert-danger'>Descripción obligatoria</div>";
    } elseif ($monto <= 0) {
        echo "<div class='alert alert-danger'>Monto inválido</div>";
    } else {
        $conn->query("INSERT INTO gastos(descripcion, monto, fecha)
                      VALUES('$descripcion','$monto', NOW())");

        header("Location: index.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Gasto</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery + Autocomplete -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <style>
        .ui-autocomplete {
            z-index: 9999;
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
</head>

<body class="container py-4">

<h2>➕ Nuevo Gasto</h2>

<div class="card p-4 shadow">
<form method="POST">

    <!-- DESCRIPCION -->
    <div class="mb-3">
        <label>Descripción</label>
        <input type="text" id="descripcion" name="descripcion" class="form-control" required>
    </div>

    <!-- MONTO -->
    <div class="mb-3">
        <label>Monto</label>
        <input type="number" id="monto" name="monto" class="form-control" required>
    </div>

    <button name="guardar" class="btn btn-success">Guardar</button>
    <a href="index.php" class="btn btn-secondary">Volver</a>

</form>
</div>

<!-- ================= AUTOCOMPLETE ================= -->
<script>
$(function(){

    $("#descripcion").autocomplete({
        source: "buscar_gastos.php",
        minLength: 2,

        select: function(event, ui){
            $("#monto").val(ui.item.monto); // autocompleta monto promedio
        }
    });

});
</script>

</body>
</html>