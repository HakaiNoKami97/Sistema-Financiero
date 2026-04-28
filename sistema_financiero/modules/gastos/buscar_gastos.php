<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$term = $_GET['term'] ?? '';

$q = $conn->query("
    SELECT descripcion, AVG(monto) as monto
    FROM gastos
    WHERE descripcion LIKE '%$term%'
    GROUP BY descripcion
    LIMIT 10
");

$data = [];

while($row = $q->fetch_assoc()){
    $data[] = [
        "label" => $row['descripcion'],
        "value" => $row['descripcion'],
        "monto" => round($row['monto'])
    ];
}

echo json_encode($data);