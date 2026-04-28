<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$term = $_GET['term'] ?? '';

$result = $conn->query("
    SELECT producto, precio, costo 
    FROM inventario 
    WHERE producto LIKE '%$term%' 
    LIMIT 10
");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = [
        "label" => $row['producto'],
        "value" => $row['producto'],
        "precio" => $row['precio'],
        "costo" => $row['costo']
    ];
}

echo json_encode($data);