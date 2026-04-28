<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$error = "";

if(isset($_POST['guardar'])){

    $prov = trim($_POST['proveedor']);
    $desc = trim($_POST['descripcion']);
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $vence = $_POST['vencimiento'];

    if($prov == ""){
        $error = "El proveedor es obligatorio";
    } elseif($monto <= 0){
        $error = "El monto debe ser mayor a 0";
    } elseif($vence < $fecha){
        $error = "La fecha de vencimiento no puede ser menor a la fecha";
    } else {
        $conn->query("
            INSERT INTO pasivos(proveedor, descripcion, monto, fecha, fecha_vencimiento)
            VALUES('$prov','$desc','$monto','$fecha','$vence')
        ");

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Pasivo</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery UI (AUTOCOMPLETE) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 15px;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn {
            border-radius: 10px;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-lg">
                
                <div class="card-header bg-success text-white text-center">
                    <h4>➕ Registrar Pasivo</h4>
                </div>

                <div class="card-body">

                    <?php if($error != ""): ?>
                        <div class="alert alert-danger">❌ <?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Proveedor</label>
                            <input type="text" id="proveedor" name="proveedor" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Monto (COP)</label>
                            <input type="number" id="monto" name="monto" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="fecha" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha de vencimiento</label>
                            <input type="date" name="vencimiento" class="form-control" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button name="guardar" class="btn btn-success">
                                💾 Guardar Pasivo
                            </button>

                            <a href="index.php" class="btn btn-secondary">
                                ⬅️ Volver
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- 🔥 AUTOCOMPLETE SCRIPT -->
<script>
$(document).ready(function(){

    $("#proveedor").autocomplete({
        source: function(request, response){
            $.ajax({
                url: "buscar_pasivos.php",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data){
                    response(data);
                }
            });
        },
        minLength: 1,

        select: function(event, ui){
            $("#proveedor").val(ui.item.value);
            $("#descripcion").val(ui.item.descripcion);
            $("#monto").val(ui.item.monto);
            return false;
        }
    });

});
</script>

</body>
</html>