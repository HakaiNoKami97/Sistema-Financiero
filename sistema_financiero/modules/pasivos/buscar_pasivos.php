<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$term = $_GET['term'] ?? '';

$result = $conn->query("
    SELECT proveedor, descripcion, monto
    FROM pasivos
    WHERE proveedor LIKE '%$term%'
    GROUP BY proveedor
    LIMIT 10
");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = [
        "label" => $row['proveedor'],
        "value" => $row['proveedor'],
        "descripcion" => $row['descripcion'],
        "monto" => $row['monto']
    ];
}

echo json_encode($data);