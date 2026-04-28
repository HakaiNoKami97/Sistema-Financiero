<?php
require_once $_SERVER['DOCUMENT_ROOT']."/sistema_financiero/vendor/dompdf/autoload.inc.php";

use Dompdf\Dompdf;

include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$id = intval($_GET['id']);

// 🔹 Datos
$f = $conn->query("SELECT * FROM facturas WHERE id=$id")->fetch_assoc();
$c = $conn->query("SELECT * FROM clientes WHERE id={$f['cliente_id']}")->fetch_assoc();

$detalle = $conn->query("
    SELECT d.*, i.producto 
    FROM detalle_factura d
    JOIN inventario i ON d.producto_id = i.id
    WHERE d.factura_id=$id
");

// 🔹 Función formato COP
function cop($n) {
    return '$ ' . number_format($n, 0, ',', '.');
}

// 🔹 Variables
$subtotal = 0;
$descuento = $f['descuento'] ?? 0;
$metodo_pago = $f['metodo_pago'] ?? 'No registrado';
$banco = $f['banco'] ?? '';

// 🔹 HTML
$html = "
<style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #333; }
    .header-table { width: 100%; }
    .empresa { font-size: 16px; font-weight: bold; }
    .factura-box { text-align: right; }
    .factura-titulo { font-size: 18px; font-weight: bold; border: 1px solid #000; padding: 5px; }
    .info { margin-top: 15px; margin-bottom: 15px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { background: #2c3e50; color: white; padding: 8px; font-size: 12px; }
    td { border: 1px solid #ddd; padding: 6px; font-size: 11px; }
    .text-right { text-align: right; }
    .totales { margin-top: 15px; width: 40%; float: right; }
    .totales td { border: none; padding: 5px; }
    .total-final { font-size: 14px; font-weight: bold; border-top: 2px solid #000; }
    .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #777; }
</style>

<div class='container'>

<!-- 🔹 ENCABEZADO -->
<table class='header-table'>
<tr>
<td>
    <div class='empresa'>SISTEMA FINANCIERO S.A.S</div>
    NIT: 900.123.456-7<br>
    Dirección: Calle 123 #45-67<br>
    Tel: 300 123 4567
</td>
<td class='factura-box'>
    <div class='factura-titulo'>FACTURA</div>
    No: {$f['id']}<br>
    Fecha: {$f['fecha']}
</td>
</tr>
</table>

<!-- 🔹 CLIENTE Y PAGO -->
<div class='info'>
    <strong>Cliente:</strong> {$c['nombre']}<br>
    <strong>Método de pago:</strong> {$metodo_pago}<br>
";

// 🔥 Mostrar banco solo si es electrónico
if ($metodo_pago == 'electronico' && !empty($banco)) {
    $html .= "<strong>Banco:</strong> {$banco}<br>";
}

$html .= "
</div>

<!-- 🔹 TABLA -->
<table>
<tr>
<th>Producto</th>
<th>Cantidad</th>
<th>Precio Unitario</th>
<th>Subtotal</th>
</tr>
";

// 🔹 PRODUCTOS
while ($row = $detalle->fetch_assoc()) {

    $sub = $row['subtotal'];
    $subtotal += $sub;

    $html .= "
    <tr>
        <td>{$row['producto']}</td>
        <td class='text-right'>{$row['cantidad']}</td>
        <td class='text-right'>" . cop($row['precio']) . "</td>
        <td class='text-right'>" . cop($sub) . "</td>
    </tr>
    ";
}

// 🔹 TOTAL FINAL
$total_final = $f['total'];

// 🔹 TOTALES
$html .= "
</table>

<table class='totales'>
<tr>
<td><strong>Subtotal:</strong></td>
<td class='text-right'>" . cop($subtotal) . "</td>
</tr>
";

// 🔥 Mostrar descuento por puntos
if ($descuento > 0) {
    $html .= "
    <tr>
        <td><strong>Descuento (puntos):</strong></td>
        <td class='text-right'>- " . cop($descuento) . "</td>
    </tr>
    ";
}

$html .= "
<tr>
<td><strong>Total:</strong></td>
<td class='text-right total-final'>" . cop($total_final) . "</td>
</tr>
</table>

<div style='clear: both;'></div>

<!-- 🔹 FOOTER -->
<div class='footer'>
    Esta es una representación gráfica de la factura.<br>
    ¡Gracias por su compra!
</div>

</div>
";

// 🔹 DOMPDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("factura_$id.pdf", ["Attachment" => false]);