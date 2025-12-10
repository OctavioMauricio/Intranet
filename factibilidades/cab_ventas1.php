<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>phpMyDataGrid Sample</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<?php 
	#es: esta es la forma RECOMENDADA de incluir los archivos auxiliares (CSS,JS)
	#en: this is the recommended way to include the auxiliary files (CSS,JS)
	include_once('class/phpmydatagrid.class.php'); 
	echo set_DG_Header();
?>
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
            <div style="margin-left:80px;">
				<?php include_once("fac_ventas1.php"); ?>
            </div>
		</td>
	  </tr>
	</table>
</body>
</html>