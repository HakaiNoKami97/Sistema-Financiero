<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /sistema_financiero/auth/login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

// Obtener clientes
$clientes = $conn->query("SELECT * FROM clientes");

// Cliente seleccionado
$cliente_id = $_GET['cliente_id'] ?? null;

// Datos resumen
$total_compras = 0;
$total_facturas = 0;
$ultima_compra = null;
?>

<!DOCTYPE html>
<html>
<head>
<title>Historial de Clientes</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
    <a href="/sistema_financiero/modules/ingresos/" class="menu-item">💰 Ingresos</a>
    <a href="/sistema_financiero/modules/gastos/" class="menu-item">💸 Gastos</a>
    <a href="/sistema_financiero/modules/pasivos/" class="menu-item">💳 Pasivos</a>
    <a href="/sistema_financiero/modules/inventario/" class="menu-item">📦 Inventario</a>
    <a href="/sistema_financiero/modules/facturas/" class="menu-item">🧾 Facturas</a>
    <a href="/sistema_financiero/modules/historial/" class="menu-item active">🕒 Historial</a>

    <hr>

    <a href="/sistema_financiero/auth/logout.php" class="menu-item text-danger">
        🚪 Cerrar sesión
    </a>
</div>

<!-- 🔹 CONTENIDO -->
<div class="content">

<h2 class="mb-4">📊 Historial de Compras por Cliente</h2>

<!-- 🔥 DESCRIPCIÓN DEL MÓDULO -->
<div class="alert alert-primary shadow-sm">
    <strong>Módulo de Historial:</strong> En esta sección puedes revisar el historial de compras de cada cliente. 
    Te permite ver cuánto ha comprado, cuántas facturas tiene y cuándo fue su última compra. Es útil para entender 
    mejor a tus clientes, identificar los más frecuentes y tomar decisiones más acertadas en tu negocio.
</div>

<!-- 🔎 FILTRO -->
<div class="card p-4 shadow mb-4">
<form method="GET" class="row g-3">

<div class="col-md-8">
<label>Seleccionar cliente</label>
<select name="cliente_id" class="form-select" required>
<option value="">Seleccione cliente</option>

<?php while($c = $clientes->fetch_assoc()): ?>
<option value="<?= $c['id'] ?>" <?= ($cliente_id == $c['id']) ? 'selected' : '' ?>>
    <?= $c['nombre'] ?>
</option>
<?php endwhile; ?>

</select>
</div>

<div class="col-md-4 d-flex align-items-end">
<button class="btn btn-primary w-100">🔍 Ver historial</button>
</div>

</form>
</div>

<?php if($cliente_id): ?>

<?php
$q = $conn->query("
SELECT * FROM facturas 
WHERE cliente_id = $cliente_id
ORDER BY fecha DESC
");

$total_facturas = $q->num_rows;

$sum = $conn->query("
SELECT SUM(total) t, MAX(fecha) f 
FROM facturas 
WHERE cliente_id = $cliente_id
")->fetch_assoc();

$total_compras = $sum['t'] ?? 0;
$ultima_compra = $sum['f'] ?? "N/A";
?>

<!-- 📊 RESUMEN -->
<div class="row mb-4">

<div class="col-md-4">
<div class="card p-3 text-center shadow">
<h5>💰 Total Comprado</h5>
<h4 class="text-success">$ <?= number_format($total_compras,0,',','.') ?></h4>
</div>
</div>

<div class="col-md-4">
<div class="card p-3 text-center shadow">
<h5>🧾 Facturas</h5>
<h4><?= $total_facturas ?></h4>
</div>
</div>

<div class="col-md-4">
<div class="card p-3 text-center shadow">
<h5>📅 Última compra</h5>
<h4><?= $ultima_compra ?></h4>
</div>
</div>

</div>

<!-- 📄 LISTADO -->
<div class="card shadow p-4">

<h5>🧾 Detalle de compras</h5>

<table class="table table-bordered text-center">

<thead class="table-dark">
<tr>
<th>Factura</th>
<th>Fecha</th>
<th>Total</th>
<th>Ver</th>
</tr>
</thead>

<tbody>

<?php while($f = $q->fetch_assoc()): ?>
<tr>
<td>#<?= $f['id'] ?></td>
<td><?= $f['fecha'] ?></td>
<td class="text-success fw-bold">
$ <?= number_format($f['total'],0,',','.') ?>
</td>
<td>
<button class="btn btn-info btn-sm" 
onclick="verDetalle(<?= $f['id'] ?>)">
👁 Ver
</button>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>

</div>

<?php endif; ?>

</div>

<!-- 🔍 MODAL -->
<div class="modal fade" id="modalDetalle" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5>Detalle de factura</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body" id="detalleContenido">
Cargando...
</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function verDetalle(id){

    fetch("detalle_factura.php?id="+id)
    .then(res => res.text())
    .then(data => {
        document.getElementById("detalleContenido").innerHTML = data;
        new bootstrap.Modal(document.getElementById('modalDetalle')).show();
    });

}
</script>

</body>
</html>