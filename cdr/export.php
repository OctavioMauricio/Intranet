<?php
require_once __DIR__ . '/session_config.php';
// ... tu código de la página ...
require "../class/php-export-data.class.php";
$excel = new ExportDataExcel('browser');
$excel->filename = $_SESSION['filename'];
$data = $_SESSION['datos'];
// print_r($data);
// exit();
$excel->initialize();
foreach($data as $row) { $excel->addRow($row); }
$excel->finalize();
//exit();
?>
