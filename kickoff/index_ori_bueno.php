<?php
	session_start();
	$_SESSION['donde'] ="KickOff";
	// print_r($_SESSION);
	//	error_reporting(E_ALL);
	//	ini_set('display_errors', '1');
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
    <style type="text/css">
       table {
               border: none;
               font-size: 12px;
               border-collapse: collapse;
           }   
          th, td {
              padding: 2px;
              font-size: 14px;
         }
         th {
            background-color: midnightblue; 
            color: white;
         }
         body{
            margin:0;
            padding:0;
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
            font-size: 10px;
            background-color: #FFFFFF;
         }
        table tbody tr:nth-child(odd) {
            background: #F6F9FA;
        }
        table tbody tr:nth-child(even) {
            background: #FFFFFF;
        }
        table thead {
          background: #444;
          color: #fff;
          font-size: 18px;
        }
        table {
          border-collapse: collapse;
        }  
		/* unvisited link */
		a:link {
		  color: grey;
		}
		/* visited link */
		a:visited {
		  color: gray;
		}
		/* mouse over link */
		a:hover {
		  color: lightskyblue;
		  font-size: larger;
		  font-weight: bold;
		}
		/* selected link */
		a:active {
		  color: blue;
		}	
		.link { color: #FF0000; } /* CSS link color (red) */
		.link:hover { color: #00FF00; } /* CSS link hover (green) */		
		iframe {
			margin:0;
			padding:0;
			height:100%;
			margin-left: 0px;
			margin-top: 0px;
			margin-right: 0px;
			margin-bottom: 0px;
			display:block;
			width:100%;
			border:none; 
		}
		label {
		  display: block;
		  width: 130px;
		}
		button {cursor: pointer;}
 		#capa_casos {position:absolute; top:100px; left:10px; }		
	  	#capa_iconos {position:absolute; top:10%; left:10%; width: 80%; height: 50%;}		
	  	#capa_buscadores {position:absolute; top:10%; left:10%; width: 65%; height: 65%;}		
	</style>
		
   <!--meta http-equiv="refresh" content="300;url=https://intranet.icontel.cl/kickoff/"-->     
    <script language="JavaScript">
        var ptr=0   
       function autoSubmit() {
            var formObject = document.forms['form_select'];
            formObject.submit();
        }
		function capa(capa){
			var x=document.getElementById(capa);
			if(x.style.display === "none") {
				x.style.display = "block";
			/*	setTimeout(
					function(){
						x.style.transition = "opacity " + 3 + "s";
						x.style.opacity = 0;
						x.addEventListener("transitionend", function() {
							console.log("transition has ended, set display: none;");
							x.style.display='none';
							x.style.opacity = 1;
						 });
					}, 20000
				);
			*/
			} else {
				x.style.opacity = 1;
				x.style.display = "none";
			}
		}
	
		function carga_capas(){
			capa('capa_casos');
			capa('capa_iconos');
			capa('capa_buscadores');
		}
    </script>  
    </head>
    <body bgcolor="#FFFFFF" text="#1F1D3E" link="#E95300" onLoad="carga_capas()">
		<DIV hidden ID="capa_casos" style="background-color: darkblue; color: white" style="display: none" >
			<?PHP include_once("../casos/index.php"); ?>
		</DIV>	
		<DIV hidden ID="capa_iconos"  style="background-color: white; color: white"  style="display: none" >
			<iframe src="../app/menu.php" ></iframe>
		</DIV>	
		<DIV hidden ID="capa_buscadores"  style="background-color: white; color: white"  style="display: none" >
			<iframe src="./buscadores/index.php" ></iframe>
		</DIV>	
	<?PHP		
		$ventas = "/ Ventas / -..Ghislaine / -..MAO / -..Natalia / -..Raquel / -..Monica /";
		$operaciones = "/ -..Bryan / Operaciones / -..Alex /";
		$sac    = "/ Servicio al Cliente / -..Maria José / -..DAM /";
		$admin  = "/ -..MAM";
		$proveedores  = "/ -..MAO";
 		include_once("cm_header.php");
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
    </body>   
</html>

  