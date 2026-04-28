<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$term = $_GET['term'] ?? '';

$q = $conn->query("
    SELECT * FROM clientes 
    WHERE nombre LIKE '%$term%' 
    LIMIT 10
");

$data = [];

while($row = $q->fetch_assoc()){
    $data[] = [
        "label" => $row['nombre'],
        "value" => $row['nombre'],
        "telefono" => $row['telefono'],
        "email" => $row['email']
    ];
}

echo json_encode($data);