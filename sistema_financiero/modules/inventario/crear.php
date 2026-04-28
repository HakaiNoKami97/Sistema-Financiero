<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- 🔥 AUTOCOMPLETE -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h4>➕ Agregar Nuevo Producto</h4>
        </div>

        <div class="card-body">

            <?php
            $error = "";

            if (isset($_POST['guardar'])) {
                $producto = trim($_POST['producto']);
                $cantidad = $_POST['cantidad'];
                $precio   = $_POST['precio'];
                $costo    = $_POST['costo'];

                if ($producto == "") {
                    $error = "El nombre del producto es obligatorio";
                } elseif ($cantidad < 0) {
                    $error = "La cantidad no puede ser negativa";
                } elseif ($precio < 0) {
                    $error = "El precio no puede ser negativo";
                } elseif ($costo < 0) {
                    $error = "El costo no puede ser negativo";
                } elseif ($costo > $precio) {
                    $error = "⚠️ El costo no debería ser mayor al precio de venta";
                } else {

                    $conn->query("
                        INSERT INTO inventario (producto, cantidad, precio, costo, estado)
                        VALUES ('$producto', '$cantidad', '$precio', '$costo', 1)
                    ");

                    header("Location: index.php");
                    exit;
                }
            }

            if ($error != "") {
                echo "<div class='alert alert-danger'>❌ $error</div>";
            }
            ?>

            <form method="POST">
                
                <div class="mb-3">
                    <label class="form-label">Nombre del producto</label>
                    <input type="text" id="producto" name="producto" class="form-control" placeholder="Ej: Labial rojo" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control" placeholder="Ej: 10" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Precio de venta (COP)</label>
                    <input type="number" step="0.01" id="precio" name="precio" class="form-control" placeholder="Ej: 25000" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Costo (COP)</label>
                    <input type="number" step="0.01" id="costo" name="costo" class="form-control" placeholder="Ej: 15000" required>
                </div>

                <div class="alert alert-info">
                    💡 El costo permite calcular ganancias reales en reportes.
                </div>

                <div class="d-grid gap-2 mt-3">

                    <button type="submit" name="guardar" class="btn btn-success">
                        💾 Guardar Producto
                    </button>

                    <a href="/sistema_financiero/modules/inventario/index.php" 
                    class="btn btn-outline-secondary">
                        ⬅ Volver al inventario
                    </a>

                </div>

            </form>

        </div>
    </div>
</div>

<!-- 🔥 SCRIPT AUTOCOMPLETE -->
<script>
$(document).ready(function(){

    $("#producto").autocomplete({
        source: function(request, response){
            $.ajax({
                url: "buscar_productos.php",
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
            $("#producto").val(ui.item.value);
            $("#precio").val(ui.item.precio);
            $("#costo").val(ui.item.costo);
            return false;
        }
    });

});
</script>

</body>
</html>