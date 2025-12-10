<?php
require_once __DIR__ . '/session_config.php';
include "config.php";
$columnName = $_POST['columnName'];
$sort = $_POST['sort'];
$query = $_SESSION["query_contactos"]." order by ".$columnName." ".$sort." ";
include_once("tabla_datos.php");
?>

	
