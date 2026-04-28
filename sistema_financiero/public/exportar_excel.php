<?php
session_start();
require $_SERVER['DOCUMENT_ROOT']."/sistema_financiero/vendor/autoload.php";
include($_SERVER['DOCUMENT_ROOT']."/sistema_financiero/config/conexion.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Style\Font;

// ================= EXCEL =================
$excel = new Spreadsheet();

/**
 * =========================
 * FUNCIONES DE ESTILO PRO
 * =========================
 */

function setFont($sheet){
    $sheet->getParent()->getDefaultStyle()->getFont()->setName('Century Gothic');
}

function autoSize($sheet){
    foreach (range('A','T') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
}

function headerStyle($sheet, $range){
    $sheet->getStyle($range)->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 12,
            'color' => ['rgb' => 'FFFFFF']
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '2F5597']
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN
            ]
        ]
    ]);
}

function tableStyle($sheet, $range){
    $sheet->getStyle($range)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN
            ]
        ],
        'alignment' => [
            'vertical' => Alignment::VERTICAL_CENTER
        ]
    ]);
}

/**
 * =========================
 * DATOS BASE
 * =========================
 */
$ingresos = $conn->query("SELECT SUM(total) t FROM facturas")->fetch_assoc()['t'] ?? 0;
$gastos   = $conn->query("SELECT SUM(monto) t FROM gastos")->fetch_assoc()['t'] ?? 0;

$inventario = $conn->query("SELECT SUM(costo * cantidad) t FROM inventario WHERE estado=1")->fetch_assoc()['t'] ?? 0;
$pasivos = $conn->query("SELECT SUM(monto) t FROM pasivos WHERE estado=1")->fetch_assoc()['t'] ?? 0;

$caja = $ingresos - $gastos;
$activos = $caja + $inventario;
$patrimonio = $activos - $pasivos;

/**
 * =========================
 * HOJA 1: DASHBOARD
 * =========================
 */
$dashboard = $excel->getActiveSheet();
$dashboard->setTitle("Dashboard");
setFont($dashboard);

/** KPI */
$dashboard->setCellValue("B2","Ingresos");
$dashboard->setCellValue("D2","Gastos");
$dashboard->setCellValue("F2","Activos");
$dashboard->setCellValue("H2","Patrimonio");

$dashboard->setCellValue("B3",$ingresos);
$dashboard->setCellValue("D3",$gastos);
$dashboard->setCellValue("F3",$activos);
$dashboard->setCellValue("H3",$patrimonio);

headerStyle($dashboard,"B2:H2");

/**
 * =========================
 * HOJA 2: MOVIMIENTOS
 * =========================
 */
$mov = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($excel, "Movimientos");
$excel->addSheet($mov);
setFont($mov);

$meses = ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"];
$ingMes = array_fill(0,12,0);
$gasMes = array_fill(0,12,0);

$q = $conn->query("SELECT MONTH(fecha)m,SUM(total)t FROM facturas GROUP BY m");
while($r=$q->fetch_assoc()){
    $ingMes[$r['m']-1]=$r['t'];
}

$q = $conn->query("SELECT MONTH(fecha)m,SUM(monto)t FROM gastos GROUP BY m");
while($r=$q->fetch_assoc()){
    $gasMes[$r['m']-1]=$r['t'];
}

// Encabezados
$mov->setCellValue("A1","Mes");
$mov->setCellValue("B1","Ingresos");
$mov->setCellValue("C1","Gastos");
headerStyle($mov,"A1:C1");

$fila=2;
foreach($meses as $i=>$m){
    $mov->setCellValue("A$fila",$m);
    $mov->setCellValue("B$fila",$ingMes[$i]);
    $mov->setCellValue("C$fila",$gasMes[$i]);
    $fila++;
}

tableStyle($mov,"A1:C13");
autoSize($mov);

/**
 * =========================
 * HOJA 3: BALANCE
 * =========================
 */
$bal = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($excel, "Balance");
$excel->addSheet($bal);
setFont($bal);

$bal->setCellValue("A1","Concepto");
$bal->setCellValue("B1","Valor");

$bal->setCellValue("A2","Activos");
$bal->setCellValue("B2",$activos);

$bal->setCellValue("A3","Pasivos");
$bal->setCellValue("B3",$pasivos);

$bal->setCellValue("A4","Patrimonio");
$bal->setCellValue("B4",$patrimonio);

headerStyle($bal,"A1:B1");
tableStyle($bal,"A1:B4");
autoSize($bal);

/**
 * =========================
 * HOJA 4: PRODUCTOS
 * =========================
 */
$prod = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($excel, "Productos");
$excel->addSheet($prod);
setFont($prod);

$prod->setCellValue("A1","Producto");
$prod->setCellValue("B1","Ventas");
headerStyle($prod,"A1:B1");

$q=$conn->query("
SELECT i.producto,SUM(d.cantidad)t
FROM detalle_factura d
JOIN inventario i ON d.producto_id=i.id
GROUP BY d.producto_id
ORDER BY t DESC LIMIT 10
");

$fila=2;
while($r=$q->fetch_assoc()){
    $prod->setCellValue("A$fila",$r['producto']);
    $prod->setCellValue("B$fila",$r['t']);
    $fila++;
}

tableStyle($prod,"A1:B$fila");
autoSize($prod);

/**
 * =========================
 * HOJA 5: UTILIDADES
 * =========================
 */
$util = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($excel, "Utilidades");
$excel->addSheet($util);
setFont($util);

// ENCABEZADOS
$util->setCellValue("A1","Concepto");
$util->setCellValue("B1","Valor");

headerStyle($util,"A1:B1");

// ================= RESUMEN =================

// calcular costos reales
$costos = $conn->query("
    SELECT SUM(i.costo * d.cantidad) t
    FROM detalle_factura d
    JOIN inventario i ON d.producto_id = i.id
")->fetch_assoc()['t'] ?? 0;

$util_bruta = $ingresos - $costos;
$util_neta = $util_bruta - $gastos;

// datos resumen
$util->setCellValue("A2","Ingresos");
$util->setCellValue("B2",$ingresos);

$util->setCellValue("A3","Costos");
$util->setCellValue("B3",$costos);

$util->setCellValue("A4","Utilidad Bruta");
$util->setCellValue("B4",$util_bruta);

$util->setCellValue("A5","Gastos");
$util->setCellValue("B5",$gastos);

$util->setCellValue("A6","Utilidad Neta");
$util->setCellValue("B6",$util_neta);

// formato moneda
$util->getStyle("B2:B6")->getNumberFormat()->setFormatCode('"$"#,##0');

tableStyle($util,"A1:B6");

// ================= DETALLE POR PRODUCTO =================

$util->setCellValue("A9","Producto");
$util->setCellValue("B9","Ventas");
$util->setCellValue("C9","Costo");
$util->setCellValue("D9","Utilidad");

headerStyle($util,"A9:D9");

$q = $conn->query("
    SELECT i.producto,
           SUM(d.subtotal) ventas,
           SUM(i.costo * d.cantidad) costo
    FROM detalle_factura d
    JOIN inventario i ON d.producto_id = i.id
    GROUP BY d.producto_id
");

$fila = 10;

while($r = $q->fetch_assoc()){
    $u = $r['ventas'] - $r['costo'];

    $util->setCellValue("A$fila",$r['producto']);
    $util->setCellValue("B$fila",$r['ventas']);
    $util->setCellValue("C$fila",$r['costo']);
    $util->setCellValue("D$fila",$u);

    $fila++;
}

// formato dinero
$util->getStyle("B10:D$fila")->getNumberFormat()->setFormatCode('"$"#,##0');

tableStyle($util,"A9:D$fila");

autoSize($util);

/**
 * =========================
 * EXPORTAR
 * =========================
 */
$excel->setActiveSheetIndex(0);

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
date_default_timezone_set('America/Bogota');

$fecha = date("dmY_His");
header("Content-Disposition: attachment; filename=Reporte_Financiero_$fecha.xlsx");

$writer = new Xlsx($excel);
$writer->setIncludeCharts(true);
$writer->save("php://output");
exit;