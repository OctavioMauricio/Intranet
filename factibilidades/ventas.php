<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Factibilidades TNA Solutions</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<?php 
	#es: esta es la forma RECOMENDADA de incluir los archivos auxiliares (CSS,JS)
	#en: this is the recommended way to include the auxiliary files (CSS,JS)
	include_once('class/phpmydatagrid.class.php'); 
	echo set_DG_Header("js/","css/", " /", "bluesky");	
	date_default_timezone_set("America/Santiago");
//	echo set_DG_Header();
?>
<meta http-equiv="refresh" content="1800">
<script type="text/javascript"> // Timer's de actualización
	var i=1800;
	var timer = setInterval(timer,1000);
	function timer(){
		var m = parseInt(i/60);
		var s = i - (m*60);
		if(m<10) m = '0'+m;
		if(s<10) s = '0'+s; 
		var t = m + ":"+s;
		document.getElementById('timer').textContent = " Refresh: " + t;
		document.getElementById ('fecha_hora').firstChild.data = new Date().toLocaleString();	
		i--;
		if(i<=0){
			location.reload();
		} else if(i<10){
			i = '0'+i;
		}
	}	
</script>

</head>

<body>
	<table border="0" id="bg">
	  <tr>
		<td id="content2" style="width:50%; vertical-align:top; text-align:left">
			<div id='dg'> 
				<?php include_once("fac_ventas.php"); ?>
			</div>
		</td>
   		<td style="width:50%; vertical-align:top; text-align:left">
            <div style="margin-left:2px;">
				<?php include_once("fac_ventas1.php"); ?>
            </div>
		</td>
	  </tr>
	</table>
</body>
</html>