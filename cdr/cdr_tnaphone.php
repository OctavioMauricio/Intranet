<?php
require_once __DIR__ . '/session_config.php';
// ... tu código de la página ...
	if(!$_SESSION['loggedin']) {
		$_SESSION['url_origen'] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		header("location: http://intranet.tnasolutions.cl/login/");
		echo "UPs, ha ocurrido un error. La página que busca no se encuentra.";
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?PHP include_once("../meta_data/meta_data.html"); ?>   
<title>CDR Recargas iConTel</title>
<LINK href="../css/cdr.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../js/IP_generalLib.js"></script>
<script type="text/javascript">
    function toggle_visible(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
</script>
</head>
<body>
<?php
	if(isset($_POST['desde'])) $_SESSION['desde'] = $_POST['desde'];
	if(isset($_POST['hasta'])) $_SESSION['hasta'] = $_POST['hasta']; 
	if(!isset($_SESSION['desde'])) $_SESSION['desde'] = primer_dia_mes();
	if(!isset($_SESSION['hasta'])) $_SESSION['hasta'] = ultimo_dia_mes();
	if(isset($_POST['cliente'])) $_SESSION['cliente'] = $_POST['cliente'];
	$_SESSION['todos'] = 1;
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
	// $db_host="170.79.233.7";
	$db_host="cdr.tnasolutions.cl";
	$port=3306;
	$socket="";
	$db_user="cdr";
	$db_pwd="Pq63_10ad";
	$db_name="tnasolutions";
	$con = mysql_connect($db_host, $db_user, $db_pwd);
	if (!$con) die("No se conecta a servidor");
	if (!mysql_select_db($db_name)) die("No selecciona base de datos");
	mysql_set_charset('utf8',$con);
	$sql = "SELECT clid, nombre FROM tnaphone.clientes ORDER BY nombre ASC";
	$query = mysql_query($sql);
	$select = "<select name='cliente' onchange='this.form.submit()'>";
	$ids = array();
	while($row = mysql_fetch_row($query)) { array_push($ids,$row[0]); }
	?>
	<form name="formulario" id="formulario" method="post" action="" class='no-print' >
	 <table>
	   <tr>
		 <td>Fecha Desde:</td>
		 <td><input type="text" name="desde" id="desde" alt="fecha inicio" class="IP_calendar" title="Y-m-d" placeholder="YYYY-MM-DD" value="<?php echo $_SESSION['desde']; ?>"></td>
		 <td>Fecha Hasta:</td>
		 <td><input type="text" name="hasta" id="hasta" alt="fecha Fin" class="IP_calendar" title="Y-m-d" placeholder="YYYY-MM-DD" value="<?php echo $_SESSION['hasta']; ?>">
		 <td><input type="submit" value="Buscar"></td>
		 <td><input type="button" value="este Mes" onclick="estemes()"></td>						
		 <td><input type="button" value="Mes anterior" onclick="mesanterior()"></td>	
		 <td><a href="index.php"><input type="button" value="CDR"></a></td>	
	 </table>
	<div id="progress" align="center" style="width:100%;border:1px solid;  background: orange; align-content: center"></div> 	 	 
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
	<table class="fixed_header">
	<thead>
		<tr align="center">
			<th style='width: 20px'>#</th>							
			<th style='width: 600px'><div align="left" style="align-content: left; text-align: left">Razon Social</div></th>				
			<th>RUT</th>
			<th>Fecha Desde</th>
			<th>Fecha Hasta</th>
			<th>Llamadas</th>
			<th>Duración</th>
			<th>Valor $</th>
			<th>Plan Min </th>
			<th>Plan $</th>
			<th>Recargas</th>
			<th>Recargas $</th>
		</tr>
	</thead>
	<tbody>	
	<?php	
	if(isset($_POST['desde']) & isset($_POST['hasta'])){
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$totalrecargas = 0;
		$totalids = count($ids);
		$contador = 0;
		// . Muestra progresbar 
		echo '<script type="text/javascript">toggle_visible("progress")</script>';			
		foreach($ids as $id) {
			$contador ++;
			// Progress BAR
				$percent = intval($contador/$totalids * 100)."%";  
				echo '<script language="javascript">
				document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">'.number_format($contador,0).' Clientes procesados.</div>";
				</script>'; 
			// FIN Progress BAR		
			$query = "Select nombre as cliente from tnaphone.clientes where clid = {$id}";
			$result = mysql_query($query);
			if (!$result) { die("Error para mostrar los datos."); }
			$row     = mysql_fetch_row($result);
			$cliente = $row['0'];
			$rut	 = $row['1'];
			$plan	 = $row['2'];
			$slm	 = $row['3'];
			$recarga = $row['4'];
			$query = "
			SELECT 
			   cdr.calldate as Inicio,
			   cdr.src as Origen,
			   cdr.dst as Destino,
			   cdr.billsec/60 as Duracion,
			   (cdr.billsec/60) * cdr.costo as Valor,
			   cdr.dstname as Tipo,
			   cdr.costo as Tarifa
			FROM tnaphone.cdr cdr 
			FORCE INDEX (clid_calldate)
			INNER JOIN tnaphone.clientes cli ON cdr.clid=cli.clid 
			where cli.clid = ".$id." && cdr.calldate >= '".$desde."' && cdr.calldate <= '".$hasta."' 
		    ;";
			$result = mysql_query($query);
			$llamadas = mysql_num_rows($result);
			if (!$result) { die("Error para mostrar los datos."); }
			$fields_num = mysql_num_fields($result);
			if ($plan > 0) $color = "grey"; else $color = "red";
			$color = "grey";
			$consumo =0;
			$total =0;
			$linea = 0;	
			while($row = mysql_fetch_row($result)) {
				$linea ++;
				$ptr = 0;
				foreach($row as $cell) {
					$ptr ++;
					switch ($ptr) {
						case 4: // Duración en min
							$consumo += $cell;
							break;
						case 5: // Valor en $
							$total += $cell;
							break;
						default:
					}
				}
			}
			{require('includes/recargas_acumulado.php');} 
			//else if($recargas > 0) {require('includes/recargas_acumulado.php');}
		}	
		//  Oculta progresbar 
		echo '<script type="text/javascript">toggle_visible("progress")</script>';		
		mysql_free_result($result);
		mysql_close($con);
		echo "
		<tr>
			<td colspan=\"5\" align=\"center\"> </b></td>				
			<td align=\"right\"><b>".number_format($totalllamadas, 0)."</b></td>
			<td align=\"right\"><b>".number_format($totalminutos, 0)."</b></td>
			<td align=\"right\"><b>$ ".number_format($totalvalor, 0)."</b></td>				
			<td align=\"right\"><b>".number_format($totalplanmin, 0)."</b></td>				
			<td align=\"right\"><b>$ ".number_format($totalplanval, 0)."</b></td>
			<td align=\"right\"><b>".number_format($cantrecargas, 0)."</b></td>
			<td align=\"right\"><b>$ ".number_format($totalrecargas, 0)."</b></td>
		 </tr>";
	}
	?>
	</tbody>
	</table>
  </body>
</html>