<?PHP
require_once __DIR__ . '/session_config.php';
// ... tu código de la página ...
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<link rel="apple-touch-icon" sizes="57x57" href="../favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="../favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="../favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="../favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="../favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="../favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="../favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="../favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="../favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="../favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<META NAME="author" CONTENT="TNA Solutions">
<META NAME="subject" CONTENT="TNA SOlutions, Transportes">
<META NAME="Description" CONTENT="TNA SOlutions, Diseño, Seguridad Informatica, Desarrollo de Sistemas, Redes, Aplicaciones Web">
<META NAME="Classification" CONTENT="TNA Solutions, Diseño, Seguridad Informatica, Desarrollo de Sistemas, Redes, Aplicaciones Web">
<META NAME="Keywords" CONTENT="TNA Solutions, Diseño, Seguridad, Informatica, Desarrollo, Sistemas, Redes, Aplicaciones, Web, servidor, computacion, email">
<META NAME="Geography" CONTENT="Chile">
<META NAME="Language" CONTENT="Spanish">
<META HTTP-EQUIV="Expires" CONTENT="never">
<META NAME="Copyright" CONTENT="TNA Solutions">
<META NAME="Designer" CONTENT="TNA Solutions">
<META NAME="Publisher" CONTENT="TNA Solutions">
<META NAME="Revisit-After" CONTENT="7 days">
<META NAME="distribution" CONTENT="Global">
<META NAME="Robots" CONTENT="INDEX,FOLLOW">
<META NAME="city" CONTENT="Santiago">
<META NAME="country" CONTENT="Chile">
<meta http-equiv="refresh" content="1800"> 
<title>CDR TNA Solutions</title>
<LINK href="../css/cdr.css" rel="stylesheet" type="text/css">
<style>

	.fixed_header tbody{
	  height: 300px;
	}
	div.redondo {
		border-radius: 13px; 
		border-spacing: 0;
		overflow: auto;
		border: 1px solid #666;
		background-color: #ccc;
		padding: 5px;
	}
	div.logo {
		border-radius: 13px; 
		border-spacing: 0;
		overflow: auto;
		border: 0px solid #666;
		background-color: #ccc;
		padding: 5px;
		background: #D9D9D9;
	}
	th.logo {
	
	  background-color: #D9D9D9;
	  color: white;	
	  padding: 4px;
	  font-weight: bold;
	  font-size: 14px;
	  background: #D9D9D9;

	}
	th.left {
	  background-color: orange;
	  color: white;	
	  padding: 4px;
	  font-weight: bold;
	  font-size: 14px;
	  align-content: left;
	  text-align: left;	
		
	}

</style>
<script type="text/javascript" src="../js/IP_generalLib.js"></script>
</head>

<body>
<?php
	if(!$_SESSION['loggedin']) {
		echo "UPs, ha ocurrido un error. La página que busca no se encuentra.";
		exit();
	}
	if(isset($_POST['desde'])) $_SESSION['desde'] = $_POST['desde'];
	if(isset($_POST['hasta'])) $_SESSION['hasta'] = $_POST['hasta']; 
	if(!isset($_SESSION['desde'])) $_SESSION['desde'] = primer_dia_mes();
	if(!isset($_SESSION['hasta'])) $_SESSION['hasta'] = ultimo_dia_mes();
	if(isset($_POST['cliente'])) $_SESSION['cliente'] = $_POST['cliente'];
	$estemes_desde = primer_dia_mes();
	$estemes_hasta = ultimo_dia_mes();
	$mesanterior_desde = primer_dia_mes_anterior();	
	$mesanterior_hasta = ultimo_dia_mes_anterior();	

	function ultimo_dia_mes() { 
	  $month = date('m');
	  $year = date('Y');
	  $day = date("d", mktime(0,0,0, $month+1, 0, $year));
	  return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
	}
	function primer_dia_mes() {
	  $month = date('m');
	  $year = date('Y');
	  return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
	}	
	
	function ultimo_dia_mes_anterior() { 
	  $month = date('m');
	  $year = date('Y');
	  $day = date("d", mktime(0,0,0, $month, 0, $year));
	  return date('Y-m-d', mktime(0,0,0, $month-1, $day, $year));
	}
	function primer_dia_mes_anterior() {
	  $month = date('m');
	  $year = date('Y');
	  return date('Y-m-d', mktime(0,0,0, $month-1, 1, $year));
	}	
	
	$db_host="170.79.233.7";
	$port=3306;
	$socket="";
	$db_user="cdr";
	$db_pwd="Pq63_10ad";
	$db_name="tnasolutions";
	$con = mysql_connect($db_host, $db_user, $db_pwd);
	if (!$con) die("No se conecta a servidor");
	if (!mysql_select_db($db_name)) die("No selecciona base de datos");
	mysql_set_charset('utf8',$con);
	$sql = "SELECT id_client, razon_social FROM clientes ORDER BY razon_social ASC";
	$query = mysql_query($sql);
	$select = "<select name='cliente' style=\"width: 170px\" onchange='this.form.submit()'>";
	while($row = mysql_fetch_row($query)) { 
		$select = $select.'<option value="'.$row[0].'"';
		if($row[0]==$_SESSION['cliente']) $select = $select." selected";
		$select = $select.'>'.$row[1].'</option>';
	}
	$texto = $texto . '</select>';
	?>
	<form name="formulario" id="formulario" method="post" action="" class='no-print' >
	 <table>
	   <tr>
		   <td><div align="left" style="width: 60px">
			 <?php 
				if($_SESSION['TNA']) {
					echo $select; 
				} else { 
					echo $_SESSION['cliente']; 
					$_POST['cliente'] = $_SESSION['id'];
				} 
			 ?>
		 </div></td>
		 <td>Fecha Desde:</td>
		 <td><input type="text" style="width: 70px" name="desde" id="desde" alt="fecha inicio" class="IP_calendar" title="Y-m-d" placeholder="YYYY-MM-DD" value="<?php echo $_SESSION['desde']; ?>"></td>
		 <td>Fecha Hasta:</td>
		 <td><input type="text" style="width: 70px" name="hasta" id="hasta" alt="fecha Fin" class="IP_calendar" title="Y-m-d" placeholder="YYYY-MM-DD" value="<?php echo $_SESSION['hasta']; ?>"></td>
		 <td><input type="submit" value="Buscar" /></td>
  		 <td><input type="button" value="este Mes" onclick="estemes()"></td>					
		 <td><input type="button" value="Mes anterior" onclick="mesanterior()"></td>	
		 <td><a href="recargas.php"><input type="button" value="Recargas"></a></td>				
		 <td><a href="Exportar.php"><input type="button" value="Exportar"></a></td>	
	 </table>
	</form>	
	<script type="text/javascript">
	function mesanterior(){ 
		document.getElementById("desde").value="<?php echo $mesanterior_desde; ?> ";
		document.getElementById("hasta").value="<?php echo $mesanterior_hasta; ?> ";
		document.getElementById("formulario").submit();	
	}

	function estemes(){ 
		document.getElementById("desde").value="<?php echo $estemes_desde; ?> ";
		document.getElementById("hasta").value="<?php echo $estemes_hasta; ?> ";
		document.getElementById("formulario").submit();	
	}
	</script>
	<?php	
	if(isset($_POST['cliente']) & isset($_POST['desde']) & isset($_POST['hasta'])){
		$id = $_POST['cliente'];
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];	
		$_POST = array();
		$query = "Select 
					razon_social as cliente, 
					rut as rut, 
					minutos_plan as plan,
					slm as val_min
				from clientes where id_client = {$id}";
		$result = mysql_query($query);
		if (!$result) { die("Error para mostrar los datos."); }
		$row     = mysql_fetch_row($result);
		$cliente = $row['0'];
		$rut	 = $row['1'];
		$plan	 = $row['2'];
		$slm	 = $row['3'];
		$query = "SELECT 
			   cdr.call_start as Inicio,
			   cdr.caller_id as Origen,
			   cdr.called_number as Destino,
			   cdr.duration/60 as Duracion,
			   cdr.cost as Valor,
			   cdr.tariffdesc as Tipo,
			   cdr.call_rate as Tarifa
		FROM tnasolutions.cdr cdr  
		INNER JOIN tnasolutions.clientes cli ON cdr.id_client=cli.id_client 
		INNER JOIN tnasolutions.rutas ruta ON cdr.id_route=ruta.id_route 
		where cli.id_client = ".$id." && call_start >= '".$desde."' && call_start <= '".$hasta."' 
		order by cdr.call_start DESC";
		$result = mysql_query($query);
		$llamadas = mysql_num_rows($result);
		if (!$result) { die("Error para mostrar los datos."); }
		$fields_num = mysql_num_fields($result);
		if ($plan > 0) $color = "grey"; else $color = "red";
		echo "<h2 align='center' style='color: {$color}'>CDR de {$cliente}, RUT: {$rut}</h2>";
		?>
		<div class="redondo">
		<table border="1">
			<tr align="center">
				<!--th>Razon Social</th>				
				<th>RUT</th-->
				<th>Fecha Desde</th>
				<th>Fecha Hasta</th>
				<th>Llamadas</th>
				<th>Duración</th>
				<th>Valor $</th>
				<th>Plan Min </th>
				<th>Plan $</th>
				<?php if($_SESSION['TNA']) { ?>	
			  <th>Recargas</th>
			  <th>Recargas $</th>
				<?php }	?>		
		  </tr>
			<tr align="right">
				<?php
					//echo "<td><div id='cliente'></div></td>";				
					//echo "<td><div id=\"rut\"></div></td>";				
					echo "<td><div id=\"desdef\"></div></td>";				
					echo "<td><div id=\"hastaf\"></div></td>";				
					echo "<td><div id=\"cantidad\"></div></td>";				
					echo "<td><div id=\"duracion\"></div></td>";				
					echo "<td><div id=\"valor\"></div></td>";	
					echo "<td><div id=\"planmin\"></div></td>";	
					echo "<td><div id=\"planvalor\"></div></td>";	
					echo "<td><div id=\"cantrecargas\"></div></td>";	
					echo "<td><div id=\"valrecargas\"></div></td>";	

				?>
			</tr>		
		</table></div>
		<div class="logo"><table border="0">
			<tr>
				<th class="logo" align="left"><img src="../images/logo.png" width="717px" height="100px" alt=""></th>
			</tr>
		</table></div>
		<div >
		<table border="1" class="fixed_header">
		<thead>
			<tr style='color: white'>
			  <th class='no-print' align="left" style="width: 20px">#</th>					
				<th>Fecha</th>
			    <th>Origen</th>	
				<th>Destino</th>
				<th>Mins</th>
				<th>Valor</th>
				<th>Tipo Llamada</th>
				<th>Tarifa</th>
			</tr>
			</thead>
			<tbody>
		<?PHP
		$consumo =0;
		$total =0;
		$linea = 0;	
		$_SESSION['filename'] = $cliente."_".$desde."_".$hasta.".xlm";
		$datos = array();
		$cabeceras = array("Fecha Inicio"," Numero Origen","Numero Destino","Minutos Duracion","valor en $", "Tipo Llamada","Tarifa x minuto");
		array_push($datos,$cabeceras);
		while($row = mysql_fetch_row($result)) {
			$linea ++;
			echo "<tr>";
			$ptr = 0;
			$tmp = array();
			echo "<td align='right' style='width: 20px' class='no-print'>".$linea."</td>";	
			foreach($row as $cell) {
				$ptr ++;
				switch ($ptr) {
					case 1: // Fecha hora
						$cell = date('d-m-Y  H:i:s', strtotime($cell));
						echo "<td align='left'>".$cell."</td>";
						array_push($tmp,$cell);
						break;
					case 3: //Nº destino
						$cell = preg_replace("/[^0-9.]/", "", $cell);
						echo "<td align='right'>".$cell."</td>";
						array_push($tmp,$cell);
						break;
					case 4: // Duración en min
						$cell = number_format($cell, 2);
						echo "<td align='right'>".$cell."</td>";
						$consumo += $cell;
						array_push($tmp,$cell);
						break;
					case 5: // Valor en $
						$cell = number_format($cell, 0);
						echo "<td align='right'>".$cell."</td>";
						$total += $cell;
						array_push($tmp,$cell);
						break;
					case 7: // Tarifa
						$cell = number_format($cell, 0);
						echo "<td align='right'>".$cell."</td>";
						array_push($tmp,$cell);
						break;
					case 8:

						break;
					default:
						array_push($tmp,$cell);
						echo "<td>$cell</td>";
				}
			}
			echo "</tr>\n";
			array_push($datos,$tmp);			
		}
		echo "<tr><td colspan='8'><b><div align=\"center\" style=\"align-content: center; text-align: center\">";	
		echo number_format($llamadas, 0)." llamadas efectuadas, con un total de ".number_format($consumo, 2)." minutos consumidos y un valor total de $ ".number_format($total, 0).".</b></div></td></tr></tbody></table><div>";
		$planvalor = $plan*$slm;
		if($plan > 500) $recarga = 1000; else $recarga = 500;
		$valorrecarga = $recarga * $slm;
		$recargas  =  round(($planvalor-$total)/$valorrecarga,0);
		if($recargas > 0) $recargas = 0; else $recargas = $recargas *(-1);
		$valorrecargas = $recargas*$recarga*$slm;
		echo "<script>
				document.getElementById('desdef').innerHTML = '".date('d/m/Y', strtotime($desde))."';
				document.getElementById('hastaf').innerHTML = '".date('d/m/Y',strtotime($hasta))."';
				document.getElementById('cantidad').innerHTML = '".number_format($llamadas, 0)."';
				document.getElementById('duracion').innerHTML = '".number_format($consumo, 2)."';
				document.getElementById('valor').innerHTML = '".number_format($total, 0)."';
				document.getElementById('planmin').innerHTML = '".number_format($plan, 0)."';
				document.getElementById('planvalor').innerHTML = '".number_format($planvalor, 0)."';
				document.getElementById('cantrecargas').innerHTML = '".number_format($recargas, 0)."';
				document.getElementById('valrecargas').innerHTML = '".number_format($valorrecargas, 0)."';
		</script>";
		$_SESSION['datos']	= $datos;
		mysql_free_result($result);
		mysql_close($con);
	}
	?>
</body></html>