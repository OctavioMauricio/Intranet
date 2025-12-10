<?php
session_start();
$_SESSION['donde'] = "KickOff";
// Verificar si existe la variable de sesión (puedes adaptarla al nombre que uses, por ejemplo 'usuario' o 'id')
if (!isset($_SESSION['usuario'])) {
    header("Location: https://www.intranet.cl");
    exit();
}
?>
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$ventas       = "/ Ventas / -..Ghislaine / -..MAO / -..Natalia / -..Monica / -..Raquel";
$operaciones  = "/ -..Bryan / Operaciones / -..Alex /";
$sac          = "/ Servicio al Cliente / -..Maria José / -..DAM /";
$admin        = "/ -..MAM";
$proveedores  = "/ -..MAO";
$mao_mam 	  = "/ -..MAO / -..MAM";

include_once("config.php"); 
include_once("security_groups.php"); 	

if (isset($_POST['sg'])) {
	$sg_id = $_POST['sg'];
	$_SESSION['sg_id'] = $sg_id;
}
if (isset($_GET['sg'])) {
	$sg_id = $_GET['sg'];
	$_SESSION['sg_id'] = $sg_id;
}      
if (!isset($sg_id)) {
	if (isset($_SESSION['sg_id'])) {
		$sg_id = $_SESSION['sg_id'];
	} else {
		$sg_id = "a03a40e8-bda8-0f1b-b447-58dcfb6f5c19"; // soporte
		$sg_name = "Soporte Soporte tecnico";
		$_SESSION['sg_id'] = $sg_id;                   
	}
} 

// Buscar sg_name
$i = 1;
$cuantos = count($grupos);  
while ($i <= $cuantos) {
	if ($grupos[$i]['id'] == $sg_id) {
		$sg_name = $grupos[$i]['name'];
	}
	$i++;
}

// Variables JS
echo "<script>
		var sg_id = '$sg_id';
		var sg_name = '$sg_name';
	  </script>";
?>

<!DOCTYPE html>
<html lang="es">
<head>
	 <meta charset="UTF-8">
	<title>Cuadro de Mando</title>
	<script src="js/kickoff.js"></script>  
	<link rel="stylesheet" href="css/kickoff.css" />
	<link rel="stylesheet" href="css/rebote.css" />
	<meta http-equiv="refresh" content="300">
</head>

<body>
	<div id="page">
		
		<!-- 🔷 HEADER -->
		<header id="header"><?php include_once("cm_header.php"); ?></header>

		<!-- 🔶 CONTENIDO CON SCROLL -->
    <div class="cargando"><span class="texto">iContel</span></div>
		<main id="content">

			<!-- Capas ocultas -->
			<div hidden id="capa_casos"><?php include_once("../casos/index.php"); ?></div>
			<div hidden id="capa_iconos"><iframe src="../app/menu.php"></iframe></div>
			<div hidden id="capa_buscadores"><iframe src="./buscadores/index.php"></iframe></div>

			<!-- Contenido dinámico por grupo -->
			<?php
				if (strpos($proveedores, $sg_name)) include_once("cm_casos_abiertos_sujeto_a_cobro.php");
				if (strpos($mao_mam, $sg_name)) include_once("cm_traslados_y_bajas.php");
				if (strpos($admin, $sg_name)) include_once("cm_casos_abiertos_debaja.php");

				include_once("cm_casos_abiertos.php");

				if (strpos($ventas, $sg_name) || strpos($admin, $sg_name)) include_once("cm_cobranza_comercial.php");
				if (strpos($ventas, $sg_name)) include_once("cm_clientes_potenciales.php");
				include_once("cm_tareas_pendientes.php");

				if ($sg_name != "Soporte tecnico") include_once("cm_oportunidades_abiertas.php");
				if (strpos($ventas . $operaciones, $sg_name)) include_once("cm_oportunidades_en_Demo.php");
				if (strpos($ventas, $sg_name) && $sg_name != "-..MAO") include_once("cm_oportunidades_Archivadas.php");

				if (strpos($sac, $sg_name)) {
					include_once("cm_casos_abiertos_seguimiento.php");
					include_once("cm_casos_abiertos_congelados.php");
				}

				if (strpos($admin, $sg_name)) include_once("cm_ordenes_de_compra_pendientes.php");
				if (strpos($sac, $sg_name)) include_once("cm_cobranza_comercial.php");
			?>
		</main>

		<!-- 🔻 FOOTER FIJO -->
		<footer>
			&#9786; &#169;&#174;&#8482; Copyright <span id="Year"></span>
			<b> iConTel </b> - &#9742; +56 2 2840 9988 -
			&#9993; <a href="mailto:contacto@icontel.cl">contacto@icontel.cl</a> -
			&#x1F3E0; Badajoz 45, piso 17, Las Condes, Santiago, Chile.
		</footer>

	</div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
          const year = document.getElementById('Year');
          if (year) year.textContent = new Date().getFullYear();

          const cargando = document.querySelector('.cargando');
          if (cargando) cargando.classList.add('ocultar');
        });
    </script>

</body>
</html>
