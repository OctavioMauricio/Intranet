<?php
	session_start();
	include "config.php";
	$columnName = $_POST['columnName'];
	$sort = $_POST['sort'];
    $query =  $_SESSION['query'] . $_SESSION['agrupar'] . $_SESSION['orden'];
	include_once("tabla_datos.php");
?>

	
