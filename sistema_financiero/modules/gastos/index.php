<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /sistema_financiero/auth/login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$gastos = $conn->query("SELECT * FROM gastos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gastos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
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

        .table th {
            background-color: #2c3e50;
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
    <a href="/sistema_financiero/modules/gastos/" class="menu-item active">💸 Gastos</a>
    <a href="/sistema_financiero/modules/pasivos/" class="menu-item">💳 Pasivos</a>
    <a href="/sistema_financiero/modules/inventario/" class="menu-item">📦 Inventario</a>
    <a href="/sistema_financiero/modules/facturas/" class="menu-item">🧾 Facturas</a>
    <a href="/sistema_financiero/modules/historial/" class="menu-item">🕒 Historial</a>

    <hr>

    <a href="/sistema_financiero/auth/logout.php" class="menu-item text-danger">
        🚪 Cerrar sesión
    </a>
</div>

<!-- 🔹 CONTENIDO -->
<div class="content">

    <!-- 🔹 HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>💸 Gestión de Gastos</h2>

        <a href="crear.php" class="btn btn-primary">
            ➕ Nuevo gasto
        </a>
    </div>

    <!-- 🔥 DESCRIPCIÓN DEL MÓDULO -->
    <div class="alert alert-info shadow-sm">
        <strong>Módulo de Gastos:</strong> En esta sección puedes registrar y consultar 
        todos los gastos del negocio. Te ayuda a llevar un control claro del dinero que sale, 
        ver en qué se está gastando y tener una mejor base para analizar tus finanzas.
    </div>

    <!-- 🔹 TABLA -->
    <div class="card p-3 shadow">

        <table class="table table-hover table-striped text-center align-middle">

            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            <?php while ($g = $gastos->fetch_assoc()): ?>
            <tr>
                <td><?php echo $g['descripcion']; ?></td>
                <td class="text-danger fw-bold">
                    $<?php echo number_format($g['monto'], 0, ',', '.'); ?>
                </td>
                <td><?php echo date('d/m/Y', strtotime($g['fecha'])); ?></td>
                <td>
                    <a href="editar.php?id=<?php echo $g['id']; ?>" 
                       class="btn btn-warning btn-sm me-1">✏️</a>

                    <a href="eliminar.php?id=<?php echo $g['id']; ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Eliminar gasto?')">🗑️</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>

        </table>

    </div>

</div>

</body>
</html>