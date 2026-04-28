<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// obtener datos del pasivo
$p = $conn->query("SELECT * FROM pasivos WHERE id=$id")->fetch_assoc();

if (!$p) {
    header("Location: index.php");
    exit;
}

// actualizar estado
$conn->query("UPDATE pasivos SET estado = 0 WHERE id = $id");

// registrar gasto automático
$conn->query("
    INSERT INTO gastos(descripcion, monto, fecha)
    VALUES('Pago deuda: ".$p['proveedor']."', '".$p['monto']."', NOW())
");

header("Location: index.php");
exit;