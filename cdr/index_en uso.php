<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once("../session.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?PHP include_once("../meta_data/meta_data.html"); ?>   
 <title><?php //echo $server; ?> CDR iConTel</title>
<LINK href="../css/cdr.css" rel="stylesheet" type="text/css">
<style>
html, body {
  height: 100%;
  margin: 0;
}
.wrapper {
  height: 100%;
}
.header {
  height: 220Upx;
  background: silver;
	
}
.footer {
  height: 80;	
  position: fixed;
  bottom: 0px;
  overflow: hide;
  width: 100%;
  background: silver;	
}	
.content {
  overflow: auto;
  height: calc(100% - 220px-80px);
  background: silver;
}
.contentxxxx {
  margin-left: auto;
  margin-right: auto;
  margin-bottom: 100px;

  overflow: auto;
  width: 100%;	
  height: calc(100% - 220px-80px);
  overflow: auto;
  background: pink;
}
#invisible {display:none}
@media print {
  #invisible {display:block}
}
.footerxxxxx {	
	background:url('../images/footer.png') repeat-x center top;
	height:80px;
	width:717px;
	clear:both;
	text-align:center;
	position: relative;
	z-index: 1001;
  overflow: hide;
  width: 100%;
	margin-bottom: 10px;
	border: 0px;
	}
	
	.titulo{
	align-content: center;	
	background: white;
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
		background-color: #D9D9D9;
		padding: 5px;
		background: #D9D9D9;
	}
	div.logosinbordes {
		border-radius: 13px; 
		border-spacing: 0;
		overflow: auto;
		border: 1px solid white;
		background-color: white;
		padding: 5px;
		background: white;
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

body {
	margin-left: 10px;
	margin-right: 10px;
}
</style>
<script type="text/javascript">
    function toggle_visible(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
</script>
<script type="text/javascript" src="../js/IP_generalLib.js"></script>
</head>
<body>
	<div class="wrapper"> <!-- comienzo de wrapper -->
		<form name="formulario" id="formulario" method="post" action="" class='no-print' >
		 <table>
		   <tr>
			<td><div align="left" style="width: 60px">
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
				//$con = mysql_connect($db_host, $db_user, $db_pwd);
				$con = new mysqli($db_host, $db_user, $db_pwd);
				if (!$con) die("No se conecta a servidor");
				mysqli_select_db($con,$db_name);
				mysqli_set_charset($con, 'utf8');
				$_SESSION['TNA'] = 1; // para activar cdr total hasta modificar control de clientes.

				if($_SESSION['TNA']) {
					$sql = "SELECT id_client, razon_social FROM clientes where slm ORDER BY razon_social ASC";
					$query = mysqli_query($con,$sql);
					$select = "<select name='cliente' style=\"width: 170px\" onchange='this.form.submit()'>";
					while($row = mysqli_fetch_row($query)) { 
						
						$select = $select.'<option value="'.$row[0].'"';
						if($row[0]==$_SESSION['cliente']) $select = $select." selected";
						$select = $select.'>'.$row[1].' ('.$row[0].')</option>';
					}
					$texto = $texto . '</select>';
					echo $select; 
				} else { 
					echo $_SESSION['cliente']; 
					$_POST['cliente'] = $_SESSION['id'];
				} 

			?>
		 </div></td>
		 <td>Fecha Desde:</td>
		 <td><input type="text" style="width: 100px" name="desde" id="desde" alt="fecha inicio" class="IP_calendar" title="Y-m-d" placeholder="YYYY-MM-DD" value="<?php echo $_SESSION['desde']; ?>"></td>
		 <td>Fecha Hasta:</td>
		 <td><input type="text" style="width: 100px" name="hasta" id="hasta" alt="fecha Fin" class="IP_calendar" title="Y-m-d" placeholder="YYYY-MM-DD" value="<?php echo $_SESSION['hasta']; ?>"></td>
 		 <td><input type="submit" value="Buscar" /></td>
  		 <td><input type="button" value="este Mes" onclick="estemes()"></td>					
		 <td><input type="button" value="Mes anterior" onclick="mesanterior()"></td>
		 <?php if($_SESSION['TNA']) { ?>
			<td><a href="did800.php"><input type="button" value="DID800"></a></td>							
			<td><a href="recargas.php"><input type="button" value="Recargas"></a></td>				
			<td><a href="cdr_tnaphone.php"><input type="button" value="TNAPhone"></a></td>				
		 <?php }	?>
		 <td><a href="export.php"><input type="button" value="Exportar"></a></td>	
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
		$result = mysqli_query($con,$query);
		if (!$result) { die("Error para mostrar los datos."); }
		$row     = mysqli_fetch_row($result);
		$cliente = $row['0'];
		$rut	 = $row['1'];
		$plan	 = $row['2'];
		$slm	 = $row['3'];
		//$query = "call consulta_cdr($id, $desde, $hasta )";
		$query = "SELECT 
			   cdr.call_start as Inicio,
			   cdr.caller_id as Origen,
			   cdr.called_number as Destino,
			   cdr.duration/60 as Duracion,
			   cdr.cost as Valor,
			   cdr.tariffdesc as Tipo,
			   cdr.call_rate as Tarifa
		FROM tnasolutions.cdr cdr  
		USE INDEX (IX_CallsIDClientCallStart)
		where cdr.id_client = ".$id." AND (call_start BETWEEN '{$desde}' AND '{$hasta}')
		;";
		$result = mysqli_query($con,$query);
		$llamadas = mysqli_num_rows($result);
		if (!$result) { die("Error para mostrar los datos."); }
		$fields_num = mysqli_num_fields($result);
		if ($plan > 0) $color = "grey"; else $color = "red";
?> 		<div class="header">
		<div class="titulo" align="center" style="color: <?php echo $color; ?>">
			<?php echo "CDR de {$cliente}, RUT: {$rut}"; ?>
		</div>
		<div class="redondo">
		<table border="1">
			<tr align="center">
				<!--th>Razon Social</th>				
				<th>RUT</th-->
				<th>Fecha Desde</th>
				<th>Fecha Hasta</th>
				<th>Llamadas</th>
				<th>Minutos</th>				
				<th>Consumo $</th>
				<th>Plan $</th>
				<th>Consumo Extra $</th>
				<?php if($_SESSION['TNA']) { 
				  //<th class='no-print' >Cant. Recargas</th>
				  //<th class='no-print' >Valor Recarga $</th>
				?>	
				  <th>Cant. Recargas</th>
				  <th>Valor Recarga $</th>
				<?php }	?>		
		  </tr>
			<tr align="right">
				<?php
					//echo "<td><div id='cliente'></div></td>";				
					//echo "<td><div id=\"rut\"></div></td>";				
					echo "<td><div id=\"desdef\"></div></td>";				
					echo "<td><div id=\"hastaf\"></div></td>";				
					echo "<td><div id=\"cantidad\"></div></td>";				
					echo "<td><div id=\"minutos\"></div></td>";				
					echo "<td><div id=\"valor\"></div></td>";	
					echo "<td><div id=\"planvalor\"></div></td>";					
					echo "<td><div id=\"consumoextra\"></div></td>";
					if($_SESSION['TNA']) {	
						echo "<td><div id=\"cantrecargas\"></div></td>";	
						echo "<td><div id=\"valorrecarga\"></div></td>";	
					}			
				?>
			</tr>		
		</table></div>
		<div id="invisible" class="logo"><table border="0">
			<tr>
				<!--th class="logo" align="center" style="background-color: #D9D9D9D9"><img src="../images/logo.png" width="100%" alt=""></th!-->
				<th class="logo" align="center" style="background-color: #D9D9D9D9"><img src="../images/footer.png" width="100%" alt=""></th>
			</tr>
		</table></div>
		<div class="redondo">
		<table border="1">
			<tr style='color: white'>
				<!--th class='no-print' align="left" style="width: 2%">#</th-->									
				<th align="left" style="width: 2%">#</th>					
				<th style='width: 15%'>Fecha</th>
				<th style='width: 15%'>Origen</th>	
				<th style='width: 10%'>Destino</th>
				<th style='width: 10%'>Minutos</th>
				<th style='width: 10%x'>Valor $</th>
				<th style='width: 28%'>Tipo Llamada</th>
				<th style='width: 10%'>Tarifa $</th>
			</tr>
		</table></div></div>
<div class="content"> <!-- comienzo div content con scroll-->
		<div class="redondo"> <!-- comienzo de class redondo para tabla contenidos -->
			<table id="contenidos">	<!-- Com ienzo tabla contenidos -->
			<?PHP
			$consumo =0;
			$total =0;
			$linea = 0;	
			$_SESSION['filename'] = $cliente."_".$desde."_".$hasta.".xlm";
			$datos = array();
			$cabeceras = array("Fecha Inicio"," Numero Origen","Numero Destino","Minutos Duracion","valor en $", "Tipo Llamada","Tarifa x minuto");
			array_push($datos,$cabeceras);
			// . Muestra progresbar 
			echo '<script type="text/javascript">toggle_visible("progress")</script>';
			while($row = mysqli_fetch_row($result)) {
				$linea ++;
				// Progress BAR
					$percent = intval($linea/$llamadas * 100)."%";  
					echo '<script language="javascript">
					document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">'.number_format($linea,0).' Llamadas procesadas.</div>";
					</script>'; 
				// FIN Progress BAR				
				echo "<tr>";
				$ptr = 0;
				$tmp = array();
				echo "<td align='right' style='width: 2%'>".$linea."</td>";	
				foreach($row as $cell) {
					$ptr ++;
					switch ($ptr) {
					case 1: // Fecha hora
						$cell = date('d-m-Y  H:i:s', strtotime($cell)); ?>
						<td style='width: 15%' align='left'><?php echo $cell; ?></td>
						<?php array_push($tmp,$cell);
						break;
					case 2: //Nº origen
						$cell = preg_replace("/[^0-9.]/", "", $cell); ?>
						<td style='width: 15%' align='right'><?php echo $cell; ?></td>
						<?php array_push($tmp,$cell);
						break;
					case 3: //Nº destino
						$cell = preg_replace("/[^0-9.]/", "", $cell); ?>
						<td style='width: 10%' align='right'><?php echo $cell; ?></td>
						<?php array_push($tmp,$cell);
						break;
					case 4: // Duración en min
						$cell = number_format($cell, 2); ?>
						<td style='width: 10%' align='right'><?php echo $cell; ?></td>
						<?php $consumo += $cell;
						array_push($tmp,$cell);
						break;
					case 5: // Valor en $
						$cell = number_format($cell, 0); ?>
						<td style='width: 10%' align='right'><?php echo $cell; ?></td>
						<?php $total += $cell;
						array_push($tmp,$cell);
						break;
					case 6: // tipo de llamada ?>
						<td width='28%' style='width: 28%, align-content: right' align='right'><?php echo $cell; ?></td>
						<?php array_push($tmp,$cell);
						break;
					case 7: // Tarifa 
						$cell = number_format($cell, 0); ?>
						<td width='10%' style='width: 10%, align-content: right' align='right'><?php echo $cell; ?></td>
						<?php array_push($tmp,$cell);
						break;
					case 8:
						break;
					default:
						array_push($tmp,$cell);
						echo "<td>$cell</td>";
					} // fin de switch
				} // fin de foreach
				echo "</tr>\n";
				array_push($datos,$tmp);			
			} // fin de while
			//  Oculta progresbar 
			echo '<script type="text/javascript">toggle_visible("progress")</script>';
        // extrae totales
		$query = "SELECT 
               count(id_client) as total_lamadas,
               sum(cdr.duration/60) as total_minutos,
               sum(cdr.cost) as total_pesos
            FROM tnasolutions.cdr cdr  
			USE INDEX (IX_CallsIDClientCallStart)
            where cdr.id_client = ".$id." && (call_start >= '".$desde." 00:00:00' && call_start <= '".$hasta." 23:59:59') ";
            $result = mysqli_query($con,$query);
            while ($fila = mysqli_fetch_array($result)) {
				  $llamadas = $fila[0];
                  $consumo  = $fila[1];
                  $total    = $fila[2];
            }
			?> 
				<tr>
					<td colspan='8'><b><div align="center" style="align-content: center; text-align: center"> 
						<?php echo number_format($llamadas, 0); ?>  llamadas efectuadas, con un total de <?php echo number_format($consumo, 0); ?> minutos consumidos y un valor total de $ <?php  echo  number_format($total, 0); ?> </b></div>
					</td>
				</tr>
			</table> <!-- fin tabla de contenidos -->
		</div> 
		<?php
		$factorrecarga = 1.20;
		$planvalor = $plan*$slm;
        if($plan > 500) $recarga = 1000; 
        else {
            if($plan >100) $recarga = 500; else $recarga = 100;
        }
		$valorrecarga = $recarga * $slm;
		$recargas  =  ($planvalor-$total)/$valorrecarga;
		if($recargas > 0) $recargas = 0; else $recargas = ceil($recargas *(-1));
	 	$valorrecargas = $recargas*$recarga*$slm;
		if ($total > $planvalor) $consumoextra = $total - $planvalor; else $consumoextra = 0; 
		$valorrecarga = $valorrecarga * $factorrecarga;
		$tmp = "<script>
				document.getElementById('desdef').innerHTML = '".date('d/m/Y', strtotime($desde))."';
				document.getElementById('hastaf').innerHTML = '".date('d/m/Y',strtotime($hasta))."';
				document.getElementById('cantidad').innerHTML = '".number_format($llamadas, 0)."';
				document.getElementById('minutos').innerHTML = '".number_format($consumo, 0)."';
				document.getElementById('valor').innerHTML = '".number_format($total, 0)."';
				document.getElementById('planvalor').innerHTML = '".number_format($planvalor, 0)."'";

		if($recargas >0) {		
				$tmp .= "
				document.getElementById('consumoextra').innerHTML = '".number_format($consumoextra, 0)."';				
				document.getElementById('cantrecargas').innerHTML = '".number_format($recargas, 0)."';
				document.getElementById('valorrecarga').innerHTML = '".number_format($valorrecarga, 0)."'";
		}
		$tmp .= "</script>";
		echo $tmp;
		$_SESSION['datos']	= $datos;
		mysqli_free_result($result);
		mysqli_close($con);
	}
	?>
</div> <!-- fin div content -->
 <?php /*
  <div class="footer" id="invisible"> <!-- comienzo de footer -->
		<div class="logosinbordes"> <!-- comienzo de logos sin borde para footer -->
		<table border="0">
			<tr>
				<th class="logo" align="center"><img src="../images/footer.png" alt="" width="717" height="75"></th>
			</tr>
		</table>
		</div> <!-- fin logos sin bor¡de para footer -->
  </div> <!-- fin de footer  -->
*/
?>
</div>	<!-- fin de wrapper  -->
</body></html>