<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /sistema_financiero/auth/login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");
?>

<!DOCTYPE html>
<html>
<head>
<title>Ingresos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<style>
        body {
            background-color: #f4f6f9;
            margin: 0;
            font-family: Arial;
        }

        /* 🔷 SIDEBAR */
        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
        }

        /* 🔹 CONTENIDO */
        .content {
            margin-left: 240px;
            padding: 20px;
        }

        .card {
            border-radius: 12px;
        }

        .menu-item {
            display: block;
            color: #ccc;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        .menu-item:hover {
            background: #0d6efd;
            color: white;
        }

        /* 🔥 ACTIVO */
        .menu-item.active {
            background: #0d6efd;
            color: white;
        }
    </style>
</head>

<body>

<!-- 🔷 SIDEBAR -->
<div class="sidebar bg-dark text-white p-3">
    <h4 class="text-center mb-4">💼 Sistema</h4>

    <a href="/sistema_financiero/public/index.php" class="menu-item">🏠 Inicio</a>
    <a href="/sistema_financiero/public/dashboard.php" class="menu-item">📊 Datos</a>
    <a href="/sistema_financiero/modules/clientes/" class="menu-item">👤 Clientes</a>
    <a href="/sistema_financiero/modules/ingresos/" class="menu-item active">💰 Ingresos</a>
    <a href="/sistema_financiero/modules/gastos/" class="menu-item">💸 Gastos</a>
    <a href="/sistema_financiero/modules/pasivos/" class="menu-item">💳 Pasivos</a>
    <a href="/sistema_financiero/modules/inventario/" class="menu-item">📦 Inventario</a>
    <a href="/sistema_financiero/modules/facturas/" class="menu-item">🧾 Facturas</a>
    <a href="/sistema_financiero/modules/historial/" class="menu-item">🕒 Historial</a>

    <hr>

    <a href="/sistema_financiero/auth/logout.php" class="menu-item text-danger">
        🚪 Cerrar sesión
    </a>
</div>

<div class="content p-4">

<!-- ================= INGRESOS MANUALES ================= -->
<h2 class="mb-4">💰 Ingresos manuales</h2>

<div class="alert alert-success shadow-sm">
    <strong>Módulo de Ingresos:</strong> 
    En esta sección puedes registrar todo el dinero que entra al negocio, ya sea por ventas, servicios u otros 
    ingresos. También te permite aumentar el stock del inventario, dejando registrado cada movimiento para que 
    tengas un control más ordenado y completo de tus finanzas.
</div>

<div class="card shadow p-4 mb-4">
<h5>➕ Registrar ingreso manual de dinero</h5>

<form method="POST" class="row g-3">

<div class="col-md-6">
<label>Descripción</label>
<input type="text" id="descripcion" name="descripcion" class="form-control" required>
</div>

<div class="col-md-3">
<label>Monto</label>
<input type="number" name="monto" step="0.01" min="1" class="form-control" required>
</div>

<div class="col-md-3">
<label>Tipo</label>
<select name="tipo" class="form-select" required>
<option value="extra">Ingreso extra</option>
<option value="servicio">Servicio</option>
<option value="ajuste">Ajuste</option>
</select>
</div>

<div class="col-md-12">
<button name="guardar" class="btn btn-success w-100">
💾 Guardar ingreso
</button>
</div>

</form>
</div>

<?php
// GUARDAR INGRESO MANUAL
if (isset($_POST['guardar'])) {

    $desc = trim($_POST['descripcion']);
    $monto = floatval($_POST['monto']);
    $tipo = $_POST['tipo'];

    if ($desc == "") {
        echo "<div class='alert alert-danger'>❌ La descripción es obligatoria</div>";
    } elseif ($monto <= 0) {
        echo "<div class='alert alert-danger'>❌ Monto inválido</div>";
    } else {

        $conn->query("INSERT INTO ingresos (descripcion, monto, tipo, fecha)
                      VALUES ('$desc', $monto, '$tipo', NOW())");

        echo "<div class='alert alert-success'>✅ Ingreso registrado correctamente</div>";
    }
}
?>

<!-- ================= ALIMENTAR INVENTARIO ================= -->
<div class="card shadow p-4 mb-4">
<h5>📦 Alimentar inventario (aumentar stock)</h5>

<form method="POST" class="row g-3">

<div class="col-md-6">
<label>Producto</label>

<select name="producto_id" id="producto_id" class="form-select select2" required>
<option value="" disabled selected>Seleccione producto</option>

<?php
$productos = $conn->query("SELECT * FROM inventario WHERE estado = 1");

while ($p = $productos->fetch_assoc()) {

    echo "<option value='{$p['id']}'>
            {$p['producto']} (Stock: {$p['cantidad']})
          </option>";
}
?>
</select>

</div>

<div class="col-md-3">
<label>Cantidad a agregar</label>
<input type="number" name="cantidad" min="1" class="form-control" required>
</div>

<div class="col-md-3 d-flex align-items-end">
<button name="agregar_stock" class="btn btn-primary w-100">
➕ Agregar stock
</button>
</div>

</form>
</div>

<?php
// AUMENTAR INVENTARIO
if (isset($_POST['agregar_stock'])) {

    $producto_id = intval($_POST['producto_id']);
    $cantidad = intval($_POST['cantidad']);

    if ($cantidad <= 0) {
        echo "<div class='alert alert-danger'>❌ Cantidad inválida</div>";
    } else {

        // 🔍 Obtener nombre del producto
        $res = $conn->query("SELECT producto FROM inventario WHERE id = $producto_id");
        $prod = $res->fetch_assoc();
        $nombre_producto = $prod['producto'];

        // 📦 Actualizar stock
        $conn->query("UPDATE inventario 
                      SET cantidad = cantidad + $cantidad 
                      WHERE id = $producto_id");

        // 📝 Registrar movimiento en ingresos
        $descripcion = "Ingreso de stock: $nombre_producto (+$cantidad unidades)";

        $conn->query("INSERT INTO ingresos (descripcion, monto, tipo, fecha)
                      VALUES ('$descripcion', 0, 'stock', NOW())");

        echo "<div class='alert alert-success'>
        ✅ Stock actualizado correctamente (+$cantidad unidades)
        </div>";
    }
}
?>

<!-- ================= LISTADO ================= -->
<div class="card shadow p-4">
<h5>📄 Historial de ingresos</h5>

<table class="table table-striped text-center">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Descripción</th>
<th>Tipo</th>
<th>Monto</th>
<th>Fecha</th>
<th></th>
</tr>
</thead>

<tbody>

<?php
$ingresos = $conn->query("SELECT * FROM ingresos ORDER BY fecha DESC");

while ($row = $ingresos->fetch_assoc()) {

    // 🎨 Definir badge según tipo
    if ($row['tipo'] == 'stock') {
        $badge = "<span class='badge bg-primary'>Stock</span>";
    } elseif ($row['tipo'] == 'extra') {
        $badge = "<span class='badge bg-success'>Extra</span>";
    } elseif ($row['tipo'] == 'servicio') {
        $badge = "<span class='badge bg-warning text-dark'>Servicio</span>";
    } else {
        $badge = "<span class='badge bg-secondary'>Ajuste</span>";
    }

    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['descripcion']}</td>
        <td>$badge</td>
        <td class='text-success fw-bold'>$ " . number_format($row['monto'],0,',','.') . "</td>
        <td>{$row['fecha']}</td>
        <td>
            <a href='editar.php?id={$row['id']}' class='btn btn-warning btn-sm'>✏️</a>
            <a href='eliminar.php?id={$row['id']}' class='btn btn-danger btn-sm'
               onclick=\"return confirm('¿Eliminar este ingreso?')\">🗑️</a>
        </td>
    </tr>";
}
?>

</tbody>
</table>

</div>

</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Seleccione producto",
        allowClear: true,
        width: '100%'
    });
});
</script>
<script>
$(document).ready(function(){

    $("#descripcion").autocomplete({
        source: function(request, response){
            $.ajax({
                url: "buscar_ingresos.php",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data){
                    response(data);
                }
            });
        },
        minLength: 1
    });

});
</script>
</body>
</html>