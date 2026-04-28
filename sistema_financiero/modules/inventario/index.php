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
    <title>Inventario</title>

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
        <a href="/sistema_financiero/modules/pasivos/" class="menu-item">💳 Pasivos</a>
        <a href="/sistema_financiero/modules/inventario/" class="menu-item bg-secondary">📦 Inventario</a>
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>📦 Gestión de Inventario</h2>

            <a href="crear.php" class="btn btn-success">
                ➕ Nuevo producto
            </a>
        </div>

        <!-- 🔥 ALERTA (FUERA DEL FLEX) -->
        <div class="alert alert-primary shadow-sm mb-4">
            <strong>Módulo de Inventario:</strong> 
            En esta sección puedes administrar tus productos, ver cuántos tienes disponibles, definir precios y costos. 
            El sistema también calcula la ganancia de cada producto y te muestra alertas según el nivel de stock, para que 
            puedas manejar mejor tus compras y ventas.
        </div>

        <!-- TABLA -->
        <div class="card shadow p-4">

            <h5 class="mb-3">📄 Lista de productos</h5>

            <table class="table table-striped table-hover text-center align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Costo</th>
                        <th>Utilidad</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                $result = $conn->query("SELECT * FROM inventario");

                while ($row = $result->fetch_assoc()) {

                    // 🔴 Estado de stock
                    if ($row['cantidad'] <= 5) {
                        $stock = "<span class='badge bg-danger'>Bajo</span>";
                    } elseif ($row['cantidad'] <= 10) {
                        $stock = "<span class='badge bg-warning text-dark'>Medio</span>";
                    } else {
                        $stock = "<span class='badge bg-success'>Alto</span>";
                    }

                    // 🟢 Estado activo/inactivo
                    $estado = $row['estado'] 
                        ? "<span class='badge bg-success'>Activo</span>" 
                        : "<span class='badge bg-secondary'>Inactivo</span>";

                    // 💰 CALCULAR UTILIDAD
                    $costo = $row['costo'] ?? 0;
                    $utilidad = $row['precio'] - $costo;

                    // 🎨 COLOR UTILIDAD
                    $colorUtilidad = $utilidad >= 0 ? 'text-success' : 'text-danger';

                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['producto']}</td>
                        <td>{$row['cantidad']}</td>

                        <td class='text-success fw-bold'>$ " . number_format($row['precio'], 0, ',', '.') . "</td>

                        <td class='text-primary fw-bold'>$ " . number_format($costo, 0, ',', '.') . "</td>

                        <td class='$colorUtilidad fw-bold'>
                            $ " . number_format($utilidad, 0, ',', '.') . "
                        </td>
                        <td>$stock</td>
                        <td>$estado</td>
                        <td>
                            <a href='editar.php?id={$row['id']}' class='btn btn-warning btn-sm'>✏️</a>";

                    // 🔥 BOTÓN DINÁMICO
                    if ($row['estado'] == 1) {
                        echo "
                            <a href='desactivar.php?id={$row['id']}'
                               class='btn btn-danger btn-sm'
                               onclick=\"return confirm('¿Desactivar este producto?')\">
                               🚫
                            </a>";
                    } else {
                        echo "
                            <a href='activar.php?id={$row['id']}'
                               class='btn btn-success btn-sm'
                               onclick=\"return confirm('¿Activar este producto?')\">
                               ✅
                            </a>";
                    }

                    echo "</td></tr>";
                }
                ?>

                </tbody>

            </table>

        </div>

    </div>
</div>

</body>
</html>