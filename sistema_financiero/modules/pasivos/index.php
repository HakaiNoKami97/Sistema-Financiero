<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /sistema_financiero/auth/login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$result = $conn->query("SELECT * FROM pasivos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pasivos</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fuente -->
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

        .table th {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>

<body>

<div class="d-flex">

    <!-- 🔷 SIDEBAR -->
    <div class="sidebar bg-dark text-white p-3">
        <h4 class="text-center mb-4">💼 Sistema</h4>

        <a href="/sistema_financiero/public/index.php" class="menu-item">🏠 Inicio</a>
        <a href="/sistema_financiero/public/dashboard.php" class="menu-item">📊 Datos</a>
        <a href="/sistema_financiero/modules/clientes/" class="menu-item">👤 Clientes</a>
        <a href="/sistema_financiero/modules/ingresos/" class="menu-item">💰 Ingresos</a>
        <a href="/sistema_financiero/modules/gastos/" class="menu-item">💸 Gastos</a>
        <a href="/sistema_financiero/modules/pasivos/" class="menu-item bg-secondary">💳 Pasivos</a>
        <a href="/sistema_financiero/modules/inventario/" class="menu-item">📦 Inventario</a>
        <a href="/sistema_financiero/modules/facturas/" class="menu-item">🧾 Facturas</a>
        <a href="/sistema_financiero/modules/historial/" class="menu-item">🕒 Historial</a>        

        <hr>

        <a href="/sistema_financiero/auth/logout.php" class="menu-item text-danger">
            🚪 Cerrar sesión
        </a>
    </div>

    <!-- 🔹 CONTENIDO -->
    <div class="content p-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2>💳 Gestión de Pasivos</h2>

            <a href="crear.php" class="btn btn-success">
                ➕ Nuevo pasivo
            </a>
        </div>

        <!-- 🔔 ALERTA DESCRIPTIVA -->
        <div class="alert alert-primary shadow-sm mb-4">
            <strong>Módulo de Pasivos:</strong> 
            En esta sección puedes registrar y llevar el control de tus deudas con proveedores, 
            incluyendo montos y fechas de vencimiento. Te permite ver cuáles están pendientes, marcar 
            las que ya pagaste y recibir alertas cuando una deuda está por vencer o ya se venció, ayudándote 
            a mantener tus finanzas organizadas.
        </div>

        <!-- CARD -->
        <div class="card shadow p-4">

            <h5 class="mb-3">📄 Lista de deudas</h5>

            <table class="table table-striped table-hover text-center align-middle">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Proveedor</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th>Vencimiento</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php while($row = $result->fetch_assoc()): ?>

                <?php
                // 🔴 Estado
                if ($row['estado']) {
                    $estado = "<span class='badge bg-danger'>Pendiente</span>";
                } else {
                    $estado = "<span class='badge bg-success'>Pagado</span>";
                }

                // ⏰ ALERTA DE VENCIMIENTO
                $hoy = date('Y-m-d');

                if ($row['estado'] == 1 && $row['fecha_vencimiento'] < $hoy) {
                    $vencimiento = "<span class='text-danger fw-bold'>".$row['fecha_vencimiento']." ⚠️</span>";
                } elseif ($row['estado'] == 1 && $row['fecha_vencimiento'] <= date('Y-m-d', strtotime('+3 days'))) {
                    $vencimiento = "<span class='text-warning fw-bold'>".$row['fecha_vencimiento']."</span>";
                } else {
                    $vencimiento = $row['fecha_vencimiento'];
                }
                ?>

                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['proveedor'] ?></td>
                    <td><?= $row['descripcion'] ?></td>

                    <td class="text-danger fw-bold">
                        $ <?= number_format($row['monto'],0,',','.') ?>
                    </td>

                    <td><?= $vencimiento ?></td>

                    <td><?= $estado ?></td>

                    <td>
                        <?php if ($row['estado'] == 1): ?>
                            <a href="pagar.php?id=<?= $row['id'] ?>"
                               class="btn btn-success btn-sm"
                               onclick="return confirm('¿Marcar como pagado?')">
                               💰
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>
</div>

</body>
</html>