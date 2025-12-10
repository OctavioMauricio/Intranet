<?php 
// ==========================================================
// /intranet/oportunidades/sort/tabla.php
// Resultado de la búsqueda de oportunidades
// Autor: Mauricio Araneda
// Fecha: 2025-11-18
// Codificación: UTF-8 sin BOM
// ==========================================================

// ⚠️ IMPORTANTE: nada de HTML antes de esto
session_name('icontel_intranet_sess');
session_start();

// Cabeceras
header('Content-Type: text/html; charset=utf-8');
require_once "./includes/functions.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?PHP include_once("../meta_data/meta_data.html"); ?>   
<title><?php //echo $server; ?> CDR Recargas iConTel</title>
<LINK href="css/cdr.css" rel="stylesheet" type="text/css">
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
<style type="text/css">
	.revisar {
		font-weight: bold;
		color: red ;
	}
	.normal {
		font-weight: normal  ;
		color: grey;
	}
</style>	
</head>
<body>
<?php
	if(isset($_POST['desde'])) {
		$_SESSION['desde'] = $_POST['desde'];
	}
	if(isset($_POST['hasta'])) {
		$_SESSION['hasta'] = $_POST['hasta']; 
	}
	if(!isset($_SESSION['desde'])) {
		$_SESSION['desde'] = primer_dia_mes();
	} 
	if(!isset($_SESSION['hasta'])) {
		$_SESSION['hasta'] = ultimo_dia_mes();
	}
	if(isset($_POST['cliente'])) $_SESSION['cliente'] = $_POST['cliente'];

	
	
	$_SESSION['todos'] = 0;
	if($_POST['todos']) $_SESSION['todos'] = 1;
	$estemes_desde = primer_dia_mes();
	$estemes_hasta = ultimo_dia_mes();
	$mesanterior_desde = primer_dia_mes_anterior();	
	$mesanterior_hasta = ultimo_dia_mes_anterior();	

	function ultimo_dia_mes() { 
	  $month = date('m');
	  $year = date('Y');
	  $day = date("d", mktime(0,0,0, $month+1, 0, $year));
	  return date('Y-m-d H:i:s', mktime(23,59,59, $month, $day, $year));
	}
	function primer_dia_mes() {
	  $month = date('m');
	  $year = date('Y');
 	  return date('Y-m-d H:i:s', mktime(0,0,0, $month, 1, $year));
	}	
	function ultimo_dia_mes_anterior() { 
	  $month = date('m');
	  $year = date('Y');
	  $day = date("d", mktime(0,0,0, $month, 0, $year));
	  return date('Y-m-d H:i:s', mktime(23,59,59, $month-1, $day, $year));
	}
	function primer_dia_mes_anterior() {
	  $month = date('m');
	  $year = date('Y');
	  return date('Y-m-d H:i:s', mktime(0,0,0, $month-1, 1, $year));
	}	
	$db_host="cdr.tnasolutions.cl";
	$port=3306;
	$socket="";
	$db_user="cdr";
	$db_pwd="Pq63_10ad";
	$db_name="tnasolutions";
	$con = mysqli_connect($db_host, $db_user, $db_pwd);
	if (!$con) die("No se conecta a servidor");
	if (!mysqli_select_db($con, $db_name)) die("No selecciona base de datos");
	mysqli_set_charset($con, 'utf8');
	$sql = "SELECT id_client, razon_social FROM clientes where slm ORDER BY razon_social ASC";
	$query = mysqli_query($con, $sql);
	$select = "<select name='cliente' onchange='this.form.submit()'>";
	$ids = array();
	while($row = mysqli_fetch_row($query)) { array_push($ids,$row[0]); }
    $totalids = count($ids);

	?>
	<form name="formulario" id="formulario" method="post" action="" class='no-print' >
	 <table border="0">
	   <tr>
		 <td width="30">Desde:</td>
		 <td width="80"><input type="text" name="desde" id="desde" alt="fecha inicio"  title="Y-m-d" placeholder="YYYY-MM-DD" value="<?php echo $_SESSION['desde']; ?>"></td>
		 <td>Hasta:</td>
		 <td><input type="text" name="hasta" id="hasta" alt="fecha Fin" class="IP_calendar" title="Y-m-d" placeholder="YYYY-MM-DD" value="<?php echo $_SESSION['hasta']; ?>">
		 <td><input type="submit" value="Buscar"></td>
		 <td>
		 <?php if($_SESSION['todos']){ ?>
			  <b><input type="radio" name="todos" value="0"  onchange='this.form.submit()'> Solo Recargas</br> 
			 <input type="radio" name="todos"  value="1" checked onchange='this.form.submit()'> Todos </b>	

		 <?php	} else { ?>
			  <b><input type="radio" name="todos" value="0" checked  onchange='this.form.submit()'> Solo Recargas</br> 
			 <input type="radio" name="todos"  value="1" onchange='this.form.submit()'> Todos </b>	
		 <?php	} ?>
		 </td>
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
		<tr align="center" bgcolor="orange">
			<td style='width: 20px'>#</td>							
			<td style='width: 620px'>
				<div align="left" style="align-content: left; text-align: left">Razon Social</div></td>				
			<td>RUT Cliente</td>
			<td>Fecha<br>Desde</td>
			<td>Fecha<br>Hasta</td>
			<td>Llamadas</td>
			<td>Duración</td>
			<td>Valor $</td>
			<td>Plan Min </td>
			<td>Plan $</td>
			<td>Recargas</td>
			<td>Recargas $</td>
		</tr>
	</thead>
	<tbody>	
	<?php	
	if(isset($_POST['desde']) & isset($_POST['hasta'])){
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$totalrecargas = 0;
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
			$query = "Select 
				razon_social as cliente, 
				rut as rut, 
				minutos_plan as plan,
				slm as val_min,
				minrecarga as recarga
			from clientes where id_client = {$id} ";
			$result = mysqli_query($con, $query);
			if (!$result) { die("Error para mostrar los datos."); }
			$row     = mysqli_fetch_row($result);
			$cliente = $row['0'] . " ({$id})";
			$rut	 = $row['1'];
		    $plan	 = $row['2'];
			$slm	 = $row['3'];
			$recarga = $row['4'];
            $query = "SELECT 
                   count(id_client) as total_lamadas,
                   sum(cdr.duration/60) as total_minutos,
                   sum(cdr.cost) as total_pesos
                   FROM tnasolutions.cdr cdr  
				   USE INDEX (IX_CallsIDClientCallStart)
                   where cdr.id_client = ".$id." AND (call_start BETWEEN '{$desde}' AND '{$hasta}')
                    ; ";
            $result = mysqli_query($con, $query);
            while ($fila = mysqli_fetch_array($result)) {
                 $llamadas = $fila[0];
                 $consumo  = $fila[1];
                 $total    = $fila[2];
            }
			if ($plan > 0) $class = "normal"; else $class = "revisar"; 
			$factorrecarga = 1.20;			
			$planvalor = $plan*$slm;
			if($plan > 500) $recarga = 1000; 
            else {
                if($plan >100) $recarga = 500; else $recarga = 100;
            }
			$valorrecarga = $recarga * $slm;
			$recargas     =  ($planvalor-$total)/$valorrecarga;
			if($recargas  > 0) $recargas = 0; else $recargas = ceil($recargas *(-1));
			$valorrecargas = $recargas*$recarga*$slm*$factorrecarga;
			if($slm > 0) {
				if($_SESSION['todos'])  {require('includes/recargas_acumulado.php');} 
				else if($recargas > 0 or ($plan == 0 and $llamadas > 0)) {require('includes/recargas_acumulado.php');}
			}
		}	
		//  Oculta progresbar 
		echo '<script type="text/javascript">toggle_visible("progress")</script>';		
		mysqli_free_result($result);
		mysqli_close($con);
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