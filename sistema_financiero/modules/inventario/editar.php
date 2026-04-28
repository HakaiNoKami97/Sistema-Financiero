<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM inventario WHERE id=$id");
$producto = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        
        <div class="card-header bg-warning text-dark text-center">
            <h4>✏️ Editar Producto</h4>
        </div>

        <div class="card-body">

            <?php
            $error = "";

            if (isset($_POST['actualizar'])) {

                $p  = trim($_POST['producto']);
                $c  = intval($_POST['cantidad']);
                $pr = floatval($_POST['precio']);
                $co = floatval($_POST['costo']);

                // VALIDACIONES
                if ($p == "") {
                    $error = "El nombre del producto es obligatorio";
                } elseif ($c < 0) {
                    $error = "La cantidad no puede ser negativa";
                } elseif ($pr < 0) {
                    $error = "El precio no puede ser negativo";
                } elseif ($co < 0) {
                    $error = "El costo no puede ser negativo";
                } elseif ($co > $pr) {
                    $error = "El costo no puede ser mayor al precio de venta";
                } else {

                    $conn->query("UPDATE inventario 
                                  SET producto='$p', 
                                      cantidad='$c', 
                                      precio='$pr',
                                      costo='$co'
                                  WHERE id=$id");

                    header("Location: index.php");
                    exit;
                }
            }

            if ($error != "") {
                echo "<div class='alert alert-danger'>❌ $error</div>";
            }
            ?>

            <form method="POST">
                
                <!-- PRODUCTO -->
                <div class="mb-3">
                    <label class="form-label">Nombre del producto</label>
                    <input 
                        type="text" 
                        name="producto" 
                        class="form-control"
                        value="<?php echo $producto['producto']; ?>" 
                        required
                    >
                </div>

                <!-- CANTIDAD -->
                <div class="mb-3">
                    <label class="form-label">Cantidad</label>
                    <input 
                        type="number" 
                        name="cantidad" 
                        class="form-control"
                        value="<?php echo $producto['cantidad']; ?>" 
                        required
                    >
                </div>

                <!-- PRECIO -->
                <div class="mb-3">
                    <label class="form-label">Precio de venta (COP)</label>
                    <input 
                        type="number" 
                        step="0.01" 
                        name="precio" 
                        class="form-control"
                        value="<?php echo $producto['precio']; ?>" 
                        required
                    >
                </div>

                <!-- COSTO (NUEVO) -->
                <div class="mb-3">
                    <label class="form-label">Costo (COP)</label>
                    <input 
                        type="number" 
                        step="0.01" 
                        name="costo" 
                        class="form-control"
                        value="<?php echo $producto['costo'] ?? 0; ?>" 
                        required
                    >
                    <small class="text-muted">
                        💡 Este valor se usa para calcular utilidad y reportes contables
                    </small>
                </div>

                <!-- BOTONES -->
                <div class="d-grid gap-2">
                    <button type="submit" name="actualizar" class="btn btn-warning">
                        🔄 Actualizar Producto
                    </button>

                    <a href="index.php" class="btn btn-secondary">
                        ⬅️ Volver
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>