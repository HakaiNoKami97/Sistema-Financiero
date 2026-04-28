<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$id = $_GET['id'];

$q = $conn->query("
SELECT i.producto, d.cantidad, d.subtotal
FROM detalle_factura d
JOIN inventario i ON d.producto_id = i.id
WHERE d.factura_id = $id
");

echo "<table class='table table-bordered text-center'>
<tr>
<th>Producto</th>
<th>Cantidad</th>
<th>Subtotal</th>
</tr>";

while($r = $q->fetch_assoc()){
    echo "<tr>
        <td>{$r['producto']}</td>
        <td>{$r['cantidad']}</td>
        <td>$ " . number_format($r['subtotal'],0,',','.') . "</td>
    </tr>";
}

echo "</table>";