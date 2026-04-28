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
    <title>Clientes</title>

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
    <a href="/sistema_financiero/modules/clientes/" class="menu-item active">👤 Clientes</a>
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

<!-- 🔹 CONTENIDO -->
<div class="content">

    <!-- 🔹 HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>👤 Gestión de Clientes</h2>

        <a href="crear.php" class="btn btn-primary">
            ➕ Nuevo cliente
        </a>
    </div>

    <!-- 🔥 ALERTA DESCRIPTIVA -->
    <div class="alert alert-info shadow-sm">
        <strong>¿Para qué sirve este módulo?</strong><br>
        En esta sección puedes registrar y administrar la información de tus clientes, 
        como sus datos de contacto. También te permite relacionarlos con las facturas, 
        revisar su historial de compras y tener una mejor idea de su comportamiento para 
        tomar decisiones más acertadas en tu negocio.
    </div>

    <!-- 🔹 TABLA -->
    <div class="card shadow p-3">

        <table class="table table-hover table-striped text-center align-middle">

            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            <?php
            $result = $conn->query("SELECT * FROM clientes");

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['telefono']}</td>
                    <td>{$row['email']}</td>
                    <td>

                        <a href='editar.php?id={$row['id']}' 
                           class='btn btn-sm btn-warning me-1'>
                           ✏️
                        </a>

                        <a href='eliminar.php?id={$row['id']}' 
                           class='btn btn-sm btn-danger'
                           onclick=\"return confirm('¿Eliminar este cliente?')\">
                           🗑️
                        </a>

                    </td>
                </tr>";
            }
            ?>
            </tbody>

        </table>

    </div>

</div>

</body>
</html>