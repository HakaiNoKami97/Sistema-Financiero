<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /sistema_financiero/auth/login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$factura_generada = false;
$factura_id = null;

if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
    header("Location: index.php");
    exit;
}

if (isset($_GET['eliminar'])) {
    $index = intval($_GET['eliminar']);

    if (isset($_SESSION['carrito'][$index])) {
        unset($_SESSION['carrito'][$index]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }

    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Facturación</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    <a href="/sistema_financiero/modules/ingresos/" class="menu-item">💰 Ingresos</a>
    <a href="/sistema_financiero/modules/gastos/" class="menu-item">💸 Gastos</a>
    <a href="/sistema_financiero/modules/pasivos/" class="menu-item">💳 Pasivos</a>
    <a href="/sistema_financiero/modules/inventario/" class="menu-item">📦 Inventario</a>
    <a href="/sistema_financiero/modules/facturas/" class="menu-item active">🧾 Facturas</a>
    <a href="/sistema_financiero/modules/historial/" class="menu-item">🕒 Historial</a>

    <hr>

    <a href="/sistema_financiero/auth/logout.php" class="menu-item text-danger">
        🚪 Cerrar sesión
    </a>
</div>

<div class="content">

<h2>🧾 Facturación</h2>

<!-- 🔥 ALERTA DESCRIPTIVA -->
<div class="alert alert-info shadow-sm">
    <strong>¿Para qué sirve este módulo?</strong><br>
    Aquí puedes registrar tus ventas de forma organizada, agregando productos a un carrito y 
    asignando cada factura a un cliente. El sistema también actualiza automáticamente el inventario, 
    aplica descuentos usando puntos, permite elegir el método de pago y genera comprobantes, ayudándote 
    a llevar un mejor control de todas las ventas.
</div>

<?php if (!$factura_generada): ?>

<!-- AGREGAR PRODUCTO -->
<div class="card p-4 mb-4">
<form method="POST" class="row g-3">
<div class="col-md-5">
<select name="producto_id" id="producto_id" class="form-select" required>
    <option value="" disabled selected hidden>Seleccione producto</option>

    <?php
        $productos = $conn->query("SELECT * FROM inventario WHERE estado = 1");

        while ($p = $productos->fetch_assoc()) {

            $stock = $p['cantidad'];

            // 🔥 Texto visible
            $texto_stock = $stock > 0 ? "Stock: $stock" : "Sin stock";

            // 🔒 Deshabilitar si no hay stock
            $disabled = $stock <= 0 ? "disabled" : "";

            // 🎨 Color gris si no hay stock
            $style = $stock <= 0 ? "style='color:#999;'" : "";

            // ✅ AQUÍ VA TODO JUNTO
            echo "<option value='{$p['id']}' $disabled $style>
                    {$p['producto']} ($texto_stock)
                </option>";
        }
    ?>
</select>
</div>

<div class="col-md-3">
<input type="number" name="cantidad" class="form-control" required min="1">
</div>

<div class="col-md-4">
<button name="agregar" class="btn btn-primary w-100">Agregar</button>
</div>
</form>
</div>

<?php
if (isset($_POST['agregar'])) {
    $producto_id = intval($_POST['producto_id']);
    $cantidad = intval($_POST['cantidad']);

    $res = $conn->query("SELECT * FROM inventario WHERE id=$producto_id");
    $producto = $res->fetch_assoc();

    if ($cantidad > 0 && $cantidad <= $producto['cantidad']) {
        $_SESSION['carrito'][] = [
            'producto_id'=>$producto_id,
            'nombre'=>$producto['producto'],
            'cantidad'=>$cantidad,
            'precio'=>$producto['precio'],
            'subtotal'=>$producto['precio']*$cantidad
        ];
    } else {
        echo "<div class='alert alert-danger'>Cantidad inválida o sin stock</div>";
    }
}
?>

<!-- CARRITO -->
<div class="card p-4 mb-4">
<h5>🛒 Carrito</h5>

<table class="table text-center">
<tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th><th></th></tr>

<?php
$total = 0;
foreach ($_SESSION['carrito'] as $i => $item) {
    $total += $item['subtotal'];

    echo "<tr>
    <td>{$item['nombre']}</td>
    <td>{$item['cantidad']}</td>
    <td>$ ".number_format($item['precio'],0,',','.')."</td>
    <td>$ ".number_format($item['subtotal'],0,',','.')."</td>
    <td><a href='?eliminar=$i' class='btn btn-danger btn-sm'>❌</a></td>
    </tr>";
}
?>
</table>

<strong>Total: $ <?php echo number_format($total,0,',','.'); ?></strong>

<?php if ($total > 0): ?>
<a href="?vaciar=1" class="btn btn-warning ms-3">🗑️ Vaciar</a>
<?php endif; ?>
</div>

<!-- FACTURAR -->
<div class="card p-4">
<form method="POST">

<div class="row align-items-center">

    <!-- 🔹 SELECT CLIENTE -->
    <div class="col-md-8">
        <select name="cliente_id" id="cliente_id" class="form-select" required>
            <option value="" disabled selected hidden>Seleccione cliente</option>
            <?php
            $clientes = $conn->query("SELECT * FROM clientes");

            while ($c = $clientes->fetch_assoc()) {

                $puntos = $c['puntos'];
                $texto_puntos = $puntos > 0 ? "⭐ $puntos pts" : "Sin puntos";

                echo "<option value='{$c['id']}'>
                        {$c['nombre']} ($texto_puntos)
                      </option>";
            }
            ?>
        </select>
    </div>

<!-- 🔥 MOSTRAR PUNTOS -->
<div id="puntosCliente" class="alert alert-info mt-3" style="display:none;"></div>

<!-- MÉTODO DE PAGO + PUNTOS -->
<div class="d-flex align-items-center gap-4 flex-wrap mt-3">

    <!-- EFECTIVO -->
    <div class="form-check">
        <input class="form-check-input" type="radio" 
               name="metodo_pago" value="efectivo" id="efectivo">

        <label class="form-check-label" for="efectivo">
            Efectivo
        </label>
    </div>

    <!-- ELECTRÓNICO -->
    <div class="form-check">
        <input class="form-check-input" type="radio" 
               name="metodo_pago" value="electronico" id="electronico">

        <label class="form-check-label" for="electronico">
            Electrónico
        </label>
    </div>

    <!-- USAR PUNTOS -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" 
               name="usar_puntos" id="usar_puntos">

        <label class="form-check-label" for="usar_puntos">
            Usar todos los puntos
        </label>
    </div>

</div>

<!-- SELECT BANCOS -->
<div id="bancoContainer" style="display:none;" class="mt-3">
    <select name="banco" id="banco" class="form-select">
        <option value="" disabled selected hidden>
            Seleccione banco
        </option>

        <option>Bancamía</option>
        <option>Banco Agrario de Colombia</option>
        <option>Banco AV Villas</option>
        <option>Banco Caja Social</option>
        <option>Banco de Bogotá</option>
        <option>Banco de Occidente</option>
        <option>Banco Falabella</option>
        <option>Banco GNB Sudameris</option>
        <option>Banco Pichincha</option>
        <option>Banco Popular</option>
        <option>Banco W</option>
        <option>Bancolombia</option>
        <option>BBVA Colombia</option>
        <option>Davivienda</option>
        <option>Itaú Corpbanca Colombia</option>
        <option>Lulo Bank</option>
        <option>Nequi</option>
        <option>RappiPay (DaviPlata)</option>
        <option>Scotiabank Colpatria</option>
        <option>Western Unión</option>
    </select>
</div>

<br>

<div class="mt-4">
    <button name="facturar" id="btnFinalizar" class="btn btn-success px-4" disabled>
        Finalizar
    </button>
</div>

</form>
</div>

<?php endif; ?>

<?php
if (isset($_POST['facturar']) && count($_SESSION['carrito']) > 0) {

    $cliente_id = $_POST['cliente_id'];
    $usar = isset($_POST['usar_puntos']);
    $metodo = $_POST['metodo_pago'];
    $banco = $_POST['banco'] ?? null;

    $res = $conn->query("SELECT puntos FROM clientes WHERE id=$cliente_id");
    $cli = $res->fetch_assoc();

    $puntos = $cli['puntos'];
    $descuento = 0;

    if ($usar && $puntos > 0) {
        $descuento = $puntos * 50;
        $puntos = 0;
    }

    $total = array_sum(array_column($_SESSION['carrito'], 'subtotal'));
    $total_final = max(0, $total - $descuento);

    $conn->query("INSERT INTO facturas (cliente_id,total,metodo_pago,banco,descuento)
    VALUES ($cliente_id,$total_final,'$metodo','$banco',$descuento)");

    $factura_id = $conn->insert_id;

    foreach ($_SESSION['carrito'] as $item) {
        $conn->query("INSERT INTO detalle_factura 
        (factura_id, producto_id, cantidad, precio, subtotal)
        VALUES ($factura_id, {$item['producto_id']}, {$item['cantidad']}, {$item['precio']}, {$item['subtotal']})");

        $conn->query("UPDATE inventario 
        SET cantidad = cantidad - {$item['cantidad']} 
        WHERE id = {$item['producto_id']}");
    }

    // 🎯 CALCULAR PUNTOS GANADOS (1 punto por cada 1000)
    $puntos_ganados = floor($total_final / 1000);

    // 🔄 SUMAR PUNTOS NUEVOS
    $puntos_finales = $puntos + $puntos_ganados;

    // 💾 ACTUALIZAR CLIENTE
    $conn->query("UPDATE clientes SET puntos=$puntos_finales WHERE id=$cliente_id");

    echo "<div class='alert alert-success mt-4'>
        ✅ Factura generada<br>
        💰 Total: $ ".number_format($total,0,',','.')."<br>
        🎯 Descuento: $ ".number_format($descuento,0,',','.')."<br>
        💵 Total final: $ ".number_format($total_final,0,',','.')."<br>
        ⭐ Puntos ganados: $puntos_ganados <br>
        📊 Puntos actuales: $puntos_finales
        </div>";

    echo "<a href='pdf.php?id=$factura_id' target='_blank' class='btn btn-primary mt-3'>
    📄 Generar factura
    </a>";

    $_SESSION['carrito'] = [];
}
?>

</div>

<script>
const cliente = document.getElementById('cliente_id');
const banco = document.getElementById('banco');
const bancoContainer = document.getElementById('bancoContainer');
const electronico = document.getElementById('electronico');
const btn = document.getElementById('btnFinalizar');

function validar() {
    const tieneProductos = <?php echo count($_SESSION['carrito']); ?> > 0;
    const clienteOk = cliente.value !== "";
    const metodo = document.querySelector('input[name="metodo_pago"]:checked');
    const bancoOk = !electronico.checked || banco.value !== "";

    btn.disabled = !(tieneProductos && clienteOk && metodo && bancoOk);
}

cliente.addEventListener('change', validar);

document.querySelectorAll('input[name="metodo_pago"]').forEach(r => {
    r.addEventListener('change', () => {
        bancoContainer.style.display = (r.value === 'electronico') ? 'block' : 'none';
        validar();
    });
});

banco.addEventListener('change', validar);
window.onload = validar;
</script>
<script>
$(document).ready(function() {
    $('#banco').select2({
        placeholder: "Seleccione banco",
        width: '100%',
        dropdownAutoWidth: true
    });
});
</script>
<script>
$(document).ready(function() {

    // 🔹 Productos
    $('#producto_id').select2({
        placeholder: "Buscar producto...",
        width: '100%'
    });

    // 🔹 Clientes
    $('#cliente_id').select2({
        placeholder: "Buscar cliente...",
        width: '100%'
    });

    // 🔹 Bancos
    $('#banco').select2({
        placeholder: "Seleccione banco",
        width: '100%'
    });

});
</script>

</body>
</html>
