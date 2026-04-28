<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /sistema_financiero/auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema Financiero</title>

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Fuente moderna -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
        }

        .card {
            border-radius: 15px;
            transition: 0.3s;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 10px 25px rgba(0,0,0,0.1);
        }

        .icon {
            font-size: 40px;
        }
    </style>
</head>

<body>

<!-- 🔷 NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">💼 Sistema Financiero</span>

        <a href="/sistema_financiero/auth/logout.php" class="btn btn-danger btn-sm">
            Cerrar sesión
        </a>
    </div>
</nav>

<div class="container mt-5">

    <h2 class="mb-4 text-center">🏠 Panel Principal</h2>

    <!-- 🔹 FILA 1 -->
    <div class="row g-4">

        <div class="col-md-3">
            <a href="/sistema_financiero/modules/clientes/" class="text-decoration-none text-dark">
                <div class="card shadow p-4 text-center">
                    <div class="icon">👤</div>
                    <h5 class="mt-3">Clientes</h5>
                    <p class="text-muted">Gestión de clientes</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/sistema_financiero/modules/ingresos/" class="text-decoration-none text-dark">
                <div class="card shadow p-4 text-center">
                    <div class="icon">💰</div>
                    <h5 class="mt-3">Ingresos</h5>
                    <p class="text-muted">Control de ingresos</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/sistema_financiero/modules/gastos/" class="text-decoration-none text-dark">
                <div class="card shadow p-4 text-center">
                    <div class="icon">💸</div>
                    <h5 class="mt-3">Gastos</h5>
                    <p class="text-muted">Registro de gastos</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/sistema_financiero/modules/pasivos/" class="text-decoration-none text-dark">
                <div class="card shadow p-4 text-center">
                    <div class="icon">💳</div>
                    <h5 class="mt-3">Pasivos</h5>
                    <p class="text-muted">Registro de deudas</p>
                </div>
            </a>
        </div>

    </div>

    <!-- 🔹 FILA 2 -->
    <div class="row g-4 mt-1">

        <div class="col-md-3">
            <a href="/sistema_financiero/modules/inventario/" class="text-decoration-none text-dark">
                <div class="card shadow p-4 text-center">
                    <div class="icon">📦</div>
                    <h5 class="mt-3">Inventario</h5>
                    <p class="text-muted">Control de productos</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/sistema_financiero/modules/facturas/" class="text-decoration-none text-dark">
                <div class="card shadow p-4 text-center">
                    <div class="icon">🧾</div>
                    <h5 class="mt-3">Facturas</h5>
                    <p class="text-muted">Gestión de ventas</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/sistema_financiero/public/dashboard.php" class="text-decoration-none text-dark">
                <div class="card shadow p-4 text-center">
                    <div class="icon">📊</div>
                    <h5 class="mt-3">Datos</h5>
                    <p class="text-muted">Resumen financiero</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/sistema_financiero/modules/historial/" class="text-decoration-none text-dark">
                <div class="card shadow p-4 text-center">
                    <div class="icon">🕒</div>
                    <h5 class="mt-3">Historial</h5>
                    <p class="text-muted">Historial de compras</p>
                </div>
            </a>
        </div>

    </div>

</div>

</body>
</html>