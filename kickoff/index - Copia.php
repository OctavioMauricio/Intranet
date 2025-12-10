<?php
	$_SESSION['donde'] ="KickOff";
	// print_r($_SESSION);
	//	error_reporting(E_ALL);
	//	ini_set('display_errors', '1');
	session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
		<?PHP  	
			 include_once("config.php"); 
			 include_once("security_groups.php"); 	
				//if(isset($_SESSION['sg_id'])) echo "Session = {$_SESSION['sg_id']}";
				if (isset($_POST['sg']))   { 
					$sg_id   = $_POST['sg'];
					$_SESSION['sg_id'] = $sg_id;
				}
				if (isset( $_GET['sg']))   { 
					$sg_id   =  $_GET['sg']; 
					$_SESSION['sg_id'] = $sg_id;
				}      
				if(!isset($sg_id)){
					if(isset($_SESSION['sg_id'])) {
						$sg_id = $_SESSION['sg_id'];
					} else {
						$sg_id   = "a03a40e8-bda8-0f1b-b447-58dcfb6f5c19"; // soporte
						$sg_name = "Soporte Soporte tecnico";
						$_SESSION['sg_id'] = $sg_id;                   
					}
				} 
				$i = 1;
				$cuantos = count($grupos);            
				while ($i <= $cuantos) {
					if($grupos[$i]['id'] == $sg_id) {
						$sg_name=$grupos[$i]['name'];
					}
					$i++;
				}		
			echo "<script>
					var sg_id = '$sg_id';
					var sg_name = '$sg_name';
				  </script>";

			include_once("/meta_data/meta_data.html"); 
		?>   
    	<title>Cuadro de Mando</title>
		<script type="text/javascript" src="js/kickoff.js"></script>  
		<link rel="stylesheet" href="css/kickoff.css" />
		<link href="./css/rebote.css" rel="stylesheet" type="text/css" />		
		<meta http-equiv="refresh" content="300">	
	</head>
    <body bgcolor="#FFFFFF" text="#1F1D3E" link="#E95300" onload="BodyOnLoad()">
		<div id="page">
			<div id="header">
				<?PHP		
					$ventas = "/ Ventas / -..Ghislaine / -..MAO / -..Natalia / -..Monica / -..Raquel";
					$operaciones = "/ -..Bryan / Operaciones / -..Alex /";
					$sac    = "/ Servicio al Cliente / -..Maria José / -..DAM /";
					$admin  = "/ -..MAM";
					$proveedores  = "/ -..MAO";
					$mao_mam 	  = "/ -..MAO / -..MAM";
					include_once("cm_header.php");
				?>
			</div>
			<div id="content"> 
              <!-- Imagen de espera -->
            <div class="cargando">
               <span class="texto">iContel</span>
            </div>
				
				<DIV hidden ID="capa_casos" style="background-color: darkblue; color: white;" >
					<?PHP include_once("../casos/index.php"); ?>
				</DIV>	
				<DIV hidden ID="capa_iconos"  style="background-color: white; color: white;" >
					<iframe src="../app/menu.php" ></iframe>
				</DIV>	
				<DIV hidden ID="capa_buscadores"  style="background-color: white; color: white;" >
					<iframe src="./buscadores/index.php" ></iframe>
				</DIV>	
				<?PHP
					if(strpos($proveedores, $sg_name)) { include_once("cm_casos_abiertos_sujeto_a_cobro.php"); }	
					if(strpos($mao_mam, $sg_name)) { include_once("cm_traslados_y_bajas.php"); }	
					if(strpos($admin, $sg_name)){include_once("cm_casos_abiertos_debaja.php"); }
					include_once("cm_casos_abiertos.php"); 
					if(strpos($proveedores, $sg_name)) include_once("cm_casos_abiertos_sujeto_a_cobro.php");	
					if(strpos($ventas, $sg_name) or strpos($admin, $sg_name) )  include_once("cm_cobranza_comercial.php");	
					if(strpos($ventas, $sg_name)) include_once("cm_clientes_potenciales.php");
					include_once("cm_tareas_pendientes.php");
					if($sg_name != "Soporte tecnico") include_once("cm_oportunidades_abiertas.php"); 
					if(strpos($ventas.$operaciones, $sg_name) ) include_once("cm_oportunidades_en_Demo.php");		
					if(strpos($ventas, $sg_name) && $sg_name != "-..MAO" ) include_once("cm_oportunidades_Archivadas.php");		
					if(strpos($sac, $sg_name) ) include_once("cm_cobranza_comercial.php");				
					if(strpos($sac, $sg_name)){
						include_once("cm_casos_abiertos_seguimiento.php");			
						include_once("cm_casos_abiertos_congelados.php");			
					}
					if(strpos($admin, $sg_name)){include_once("cm_ordenes_de_compra_pendientes.php"); }
				?>
				<br><br>	
			</div>
		</div>
        <!-- Script para ocultar la imagen de espera cuando la página esté cargada -->
        <script>
          // Oculta la imagen de espera y muestra el contenido
          document.addEventListener('DOMContentLoaded', function() {
          document.querySelector('.cargando').classList.add('ocultar');
          document.querySelector('.contenido').style.display = 'block';
        });
        </script>	
    </body>   
</html>

  