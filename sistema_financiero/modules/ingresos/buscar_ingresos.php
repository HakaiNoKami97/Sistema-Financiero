<?php
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

$term = $_GET['term'] ?? '';

$result = $conn->query("
    SELECT DISTINCT descripcion 
    FROM ingresos 
    WHERE descripcion LIKE '%$term%' 
    LIMIT 10
");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row['descripcion'];
}

echo json_encode($data);