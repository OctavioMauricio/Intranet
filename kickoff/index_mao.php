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
 	$sweet 					= "https://sweet.icontel.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3D";
	$url_nuevo_lead 		= $sweet . "Leads%26action%3DEditView%26return_module%3DLeads%26return_action%3DDetailView";
	$url_nuevo_caso 		= $sweet . "Cases%26action%3DEditView%26return_module%3DCases%26return_action%3DDetailView";
	$url_nueva_oportunidad 	= $sweet . "Opportunities%26action%3DEditView%26return_module%3DOpportunities%26return_action%3DDetailView";
	$url_nueva_tarea 		= $sweet . "Tasks%26action%3DEditView%26return_module%3DTasks%26return_action%3DDetailView";
	$url_nueva_cotizacion	= $sweet . "AOS_Quotes%26action%3DEditView%26return_module%3DAOS_Quotes%26return_action%3DDetailView";
	$url_nuevo_contacto 	= $sweet . "Contacts%26action%3DEditView%26return_module%3DContacts%26return_action%3Dindex";
	$style_header			= 'style="color: white;background-color: midnightblue;"';
	$style_titulo			= 'style="color: white;background-color: #255154;"';
    $tmp      				= Valor_Diario_Moneda(6);  // Dolar = 2, EURO=3, UF=6
	$UF    	 				= $tmp[2];
    $UF_Fecha 				= date("d-m-Y", strtotime($tmp[1]));
	$tmp      				= Valor_Diario_Moneda(2);  // Dolar = 2, EURO=3, UF=6
	$USD   	  				= $tmp[2];
	$USD_Fecha				= date("d-m-Y", strtotime($tmp[1]));
	$db_sweet   			= "tnasolut_sweet";    
    $db_monedas  			= "icontel_monedas";
	$db_clientes 			= "icontel_clientes";
    date_default_timezone_set("America/Santiago");
    setlocale(LC_ALL, 'es_CL');
    $hoy 					= date("d-m-Y H:i:s");
function DbConnect($dbname){ // se conecta a base de datos y devuelve $conn
    $server   = "localhost";
    $user     = "data_studio";
    $password = "1Ngr3s0.,";
    $conn = new mysqli($server, $user, $password);
    if ($conn->connect_error) { die("No me pude conectar a servidor localhost: " . $conn->connect_error); }
    $dummy = mysqli_set_charset ($conn, "utf8");    
    $bd_seleccionada = mysqli_select_db($conn, $dbname);
    if (!$bd_seleccionada) { die ('No se puede usar '.$dbname.' : ' . mysql_error()); }
    return($conn);
}
function DbConnect_icontel($dbname){ // se conecta a base de datos y devuelve $conn
    $server   = "localhost";
    $user     = "data_studio";
    $password = "1Ngr3s0.,";
    $conn = new mysqli($server, $user, $password);
    if ($conn->connect_error) { die("No me pude conectar a servidor localhost: " . $conn->connect_error); }
    $dummy = mysqli_set_charset ($conn, "utf8");    
    $bd_seleccionada = mysqli_select_db($conn, $dbname);
    if (!$bd_seleccionada) { die ('No se puede usar '.$dbname.' : ' . mysql_error()); }
    return($conn);
}
function get_aos_product_quotes($id) { // devuelve datos del aos_product_quotes buscado por id
    $conn = DbConnect("tnasolut_sweet");
    $sql = "CALL aos_products_quotes_by_id('$id')";       
    $result = $conn->query($sql);
    if($result->num_rows > 0)  { 
        $row = mysqli_fetch_array($result); 
        $datos =  array('nombre'=>trim($row['producto']), 'proveedor'=>trim($row['proveedor']), 'codigo_servicio'=> trim($row['service_code']) );
    } else $datos = "";
    $conn->close(); 
    return($datos);
}
function contactos_cuenta($id) { // devuelve select con los contactos de la cuenta
    $conn = DbConnect("tnasolut_sweet");
    $sql = "CALL account_contacts_by_account_id('$id')";  
    $select = "<select style='background-color: lightgray; vertical-align: bottom;' size='5' name='contacto_id' id='contacto_id'>\n";
    $result = $conn->query($sql);
    if($result->num_rows > 0)  { 
        while($row = $result->fetch_assoc()) {
          $select .= "<option value='".$row['contact_id']."'>".$row['contact_nombre']." ".$row['contact_apellido']."\n";    
        }
    $select .= "<option value = ' '> </option>\n<select>\n";    
    } 
    $conn->close(); 
    return($select);
}
function Valor_Diario_Moneda($cual){  // Dolar = 2, EURO=3, UF=6  
	$moneda = array();
    $conn = DbConnect("tnasolut_sweet");
	$sql = "CALL tnasolut_sweet.moneda_ultimo_valor({$cual})";                    
	$result = $conn->query($sql);
	$ptr=0;
	if($result->num_rows > 0)  { 
		while($row = $result->fetch_assoc()) {
		  $ptr ++; 
		  switch ($cual){    
		  case "2":
			$moneda[0] = "USD";
			break;
		  case "3":
			 $moneda[0] = "EURO";
			break;
		  case "6":
			 $moneda[0] = "UF";
			break;
		   default:
			 $moneda[0] = "Error Moneda desconocida.";                     
		  }  
		  $moneda[1] = $row["fecha"];				  
		  $moneda[2] = $row["valor"];
		}
	} 
	$conn->close();
	unset($result);
	unset($conn);
	return $moneda;
} 
function busca_columna($sql){
    $datos = Array();
    $conn = DbConnect("tnasolut_sweet");
    $result = $conn->query($sql);
    if ($result->num_rows > 0)   {  
         while($row = $result->fetch_assoc()) {
            array_push($datos, $row['dato']); 
        } 
    } 
    $conn->close(); 
    return($datos);
}
function crea_select_sin_titulo($datos, $name){   
    ?><table>
        <tr>
            <td><select style="width: 146px; background-color: lightgray; color: gray;" name='<?php echo $name;?>[]' multiple size=6>
                <?php for ($i = 0; $i < count($datos); $i++) { ?>
                     <option value = '<?php echo $datos[$i]; ?>'><?php echo $datos[$i]; ?></option>
                <?php  } ?>   
            </select></td>
        </tr>
    </table><?php
}		
    $conn = DbConnect($db_sweet);
    $sql = "CALL `security_groups`()";                        
    $result = $conn->query($sql);
    $ptr=0;
    $grupos = array();
    if($result->num_rows > 0)  { 
        $select = '<select name="sg" onChange="autoSubmit();"><br>';
        $select .= '<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seleccione</option><br>';           
        
        while($row = $result->fetch_assoc()) {
            $ptr ++; 
            $grupos[$ptr]['name'] = $row["name"];            
            $grupos[$ptr]['id']   = $row["id"];
            $select .= '<option value="'.$row["id"].'">'.$row["name"].'</option><br>';           
        }
        $select .= '</select><br>';
    }
    $conn->close();
    unset($result);
    unset($conn);   
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

  