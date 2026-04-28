<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /sistema_financiero/auth/login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

// 🔔 NOTIFICACIONES
$stock_bajo = $conn->query("
    SELECT * FROM inventario
    WHERE cantidad <= 5 AND estado = 1
");
$total_stock_bajo = $stock_bajo->num_rows;

// 🔹 FILTRO
$desde = $_GET['desde'] ?? null;
$hasta = $_GET['hasta'] ?? null;

$filtro = "";
if ($desde && $hasta) {
    $filtro = "WHERE fecha BETWEEN '$desde' AND '$hasta'";
}

// 🔹 KPIs
$ingresos = $conn->query("SELECT SUM(total) as t FROM facturas $filtro")->fetch_assoc()['t'] ?? 0;
$gastos   = $conn->query("SELECT SUM(monto) as t FROM gastos $filtro")->fetch_assoc()['t'] ?? 0;
$ganancia = $ingresos - $gastos;
$color    = $ganancia >= 0 ? 'text-success' : 'text-danger';

// 🔹 TOP PRODUCTOS
$top = $conn->query("
    SELECT i.producto, SUM(d.cantidad) total_vendido
    FROM detalle_factura d
    JOIN inventario i ON d.producto_id = i.id
    JOIN facturas f ON d.factura_id = f.id
    WHERE i.estado = 1
    " . ($filtro ? "AND f.fecha BETWEEN '$desde' AND '$hasta'" : "") . "
    GROUP BY d.producto_id
    ORDER BY total_vendido DESC
    LIMIT 5
");

// 🔹 GRÁFICAS
$nombres = ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"];

// 🔹 GRÁFICA INGRESOS (CORREGIDO PRO)
$meses = $nombres; // Ene-Dic siempre
$totales = array_fill(0, 12, 0);

$q = $conn->query("
    SELECT MONTH(fecha) m, SUM(total) t 
    FROM facturas 
    " . ($filtro ? "WHERE fecha BETWEEN '$desde' AND '$hasta'" : "") . "
    GROUP BY m
");

while ($r = $q->fetch_assoc()) {
    if ($r['m'] >= 1 && $r['m'] <= 12) {
        $totales[$r['m'] - 1] = $r['t'];
    }
}

$meses_gastos = $totales_gastos = [];
$q = $conn->query("SELECT MONTH(fecha) m, SUM(monto) t FROM gastos $filtro GROUP BY m");
while ($r = $q->fetch_assoc()) {
    $meses_gastos[] = $nombres[$r['m']-1];
    $totales_gastos[] = $r['t'];
}

$nombres_productos = $totales_productos = [];
$q = $conn->query("
    SELECT i.producto, SUM(d.cantidad) t
    FROM detalle_factura d
    JOIN inventario i ON d.producto_id = i.id
    GROUP BY d.producto_id
    ORDER BY t DESC
    LIMIT 5
");
while ($r = $q->fetch_assoc()) {
    $nombres_productos[] = $r['producto'];
    $totales_productos[] = $r['t'];
}

// 🔴 TABLA STOCK
$stock_bajo_tabla = $conn->query("
    SELECT * FROM inventario
    WHERE cantidad <= 5 AND estado = 1
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 230px;
            min-height: 100vh;
        }

        .menu-item {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        .menu-item:hover {
            background-color: #495057;
            padding-left: 15px;
        }

        .content {
            flex: 1;
        }

        .card {
            border-radius: 12px;
        }
    </style>
</head>

<body>

<div class="d-flex">

    <!-- 🔷 SIDEBAR -->
    <div class="sidebar bg-dark text-white p-3">
        <h4 class="text-center mb-4">💼 Sistema</h4>

        <a href="/sistema_financiero/public/index.php" class="menu-item">🏠 Inicio</a>
        <a href="/sistema_financiero/public/dashboard.php" class="menu-item bg-secondary">📊 Datos</a>
        <a href="/sistema_financiero/modules/clientes/" class="menu-item">👤 Clientes</a>
        <a href="/sistema_financiero/modules/ingresos/" class="menu-item">💰 Ingresos</a>
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

<!-- CONTENIDO -->
<div class="content w-100 p-4">

<!-- NAVBAR -->
<!-- HEADER SUPERIOR -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <h3 class="mb-0">📊 Datos</h3>

    <div class="d-flex gap-3">

        <!-- 🔔 -->
        <div class="dropdown">
            <button class="btn btn-dark position-relative" data-bs-toggle="dropdown">
                🔔
                <?php if ($total_stock_bajo > 0): ?>
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                    <?php echo $total_stock_bajo; ?>
                </span>
                <?php endif; ?>
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                <?php if ($total_stock_bajo > 0): ?>
                    <?php while ($p = $stock_bajo->fetch_assoc()): ?>
                        <li class="dropdown-item small">
                            <strong><?php echo $p['producto']; ?></strong><br>
                            Stock: <?php echo $p['cantidad']; ?>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li class="dropdown-item text-center">Sin notificaciones</li>
                <?php endif; ?>
            </ul>
        </div>

        <a href="/sistema_financiero/auth/logout.php" class="btn btn-danger btn-sm">
            Cerrar sesión
        </a>

    </div>
</div>

<!-- 🔔 ALERTA DESCRIPTIVA -->
<div class="alert alert-primary shadow-sm mb-4">
    <strong>Módulo de Datos:</strong> 
    En este panel puedes ver un resumen general de cómo va tu negocio. Te muestra ingresos, gastos y ganancias, 
    con la opción de filtrar por fechas. También puedes identificar los productos más vendidos, detectar si hay poco 
    stock y apoyarte en gráficas para entender mejor la información y tomar decisiones más acertadas.
</div>

<!-- FILTRO -->
<form method="GET" class="row g-3 mb-3">
    <div class="col-md-4">
        <input type="date" name="desde" class="form-control" value="<?php echo $desde; ?>">
    </div>
    <div class="col-md-4">
        <input type="date" name="hasta" class="form-control" value="<?php echo $hasta; ?>">
    </div>
    <div class="col-md-4">
        <button class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>

<!-- BOTONES RÁPIDOS -->
<div class="mb-4">
    <a href="?desde=<?php echo date('Y'); ?>-01-01&hasta=<?php echo date('Y'); ?>-12-31" class="btn btn-outline-secondary btn-sm">Este año</a>
    <a href="?desde=<?php echo date('Y-m-01'); ?>&hasta=<?php echo date('Y-m-t'); ?>" class="btn btn-outline-secondary btn-sm">Este mes</a>
</div>

<!-- KPIs -->
<div class="row text-center">
<div class="col-md-4">
<div class="card p-4 shadow">
<h6>Ingresos</h6>
<h2 class="text-success">$<?php echo number_format($ingresos,0,',','.'); ?></h2>
</div>
</div>

<div class="col-md-4">
<div class="card p-4 shadow">
<h6>Gastos</h6>
<h2 class="text-danger">$<?php echo number_format($gastos,0,',','.'); ?></h2>
</div>
</div>

<div class="col-md-4">
<div class="card p-4 shadow">
<h6>Ganancia</h6>
<h2 class="<?php echo $color; ?>">
$<?php echo number_format($ganancia,0,',','.'); ?>
</h2>
</div>
</div>
</div>

<br>

<!-- BALANCE GENERAL -->
<div class="mb-3 text-end">
    <a href="exportar_excel.php?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>" 
       class="btn btn-success">
       📥 Exportar Balance General
    </a>
</div>

<!-- GRÁFICAS -->
<div class="row mt-4">
<div class="col-md-4"><canvas id="i" class="grafica"></canvas></div>
<div class="col-md-4"><canvas id="g" class="grafica"></canvas></div>
<div class="col-md-4"><canvas id="p" class="grafica"></canvas></div>
</div>

<!-- STOCK BAJO -->
<?php if ($stock_bajo_tabla->num_rows > 0): ?>
<div class="card mt-4 p-3 shadow">
<h5>⚠️ Productos con stock bajo</h5>
<table class="table">
<tr><th>Producto</th><th>Cantidad</th></tr>
<?php while ($p = $stock_bajo_tabla->fetch_assoc()): ?>
<tr>
<td><?php echo $p['producto']; ?></td>
<td class="text-danger"><?php echo $p['cantidad']; ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>
<?php endif; ?>

<!-- TOP PRODUCTOS -->
<div class="card mt-4 p-3 shadow">
<h5>📦 Top productos</h5>
<table class="table table-bordered text-center">
<tr><th>Producto</th><th>Vendidos</th></tr>
<?php while ($row = $top->fetch_assoc()): ?>
<tr>
<td><?php echo $row['producto']; ?></td>
<td><?php echo $row['total_vendido']; ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
new Chart(i,{type:'bar',data:{labels:<?php echo json_encode($meses); ?>,datasets:[{data:<?php echo json_encode($totales); ?>}]}})
new Chart(g,{type:'line',data:{labels:<?php echo json_encode($meses_gastos); ?>,datasets:[{data:<?php echo json_encode($totales_gastos); ?>}]}})
new Chart(p,{type:'pie',data:{labels:<?php echo json_encode($nombres_productos); ?>,datasets:[{data:<?php echo json_encode($totales_productos); ?>}]}})
</script>

</body>
</html>