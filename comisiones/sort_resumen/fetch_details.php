<?php
	session_start();
	include "config.php";
	$columnName = $_POST['columnName'];
	$sort = $_POST['sort'];
	$query =  $_SESSION['query'] . $_SESSION['agrupar'] . " ORDER BY ".$columnName." ".$sort." ";;
	include_once("tabla_datos.php");
?>

	
