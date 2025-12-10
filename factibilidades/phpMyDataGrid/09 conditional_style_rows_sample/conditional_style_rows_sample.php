<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>phpMyDataGrid Sample</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<?php 
	#es: esta es la forma RECOMENDADA de incluir los archivos auxiliares (CSS,JS)
	#en: this is the recommended way to include the auxiliary files (CSS,JS)
	include_once('class/phpmydatagrid.class.php'); 
	echo set_DG_Header("js/","css/", " /", "bluesky");
?>
<style type="text/css">
	/* es: Definir las clases adicionales que se usarán en las condiciones, tambien las puede definir dentro de un archivo .CSS */
	/* en: Define the additional classes that will be used under conditions also can define them into a file. CSS */

	.activedata{
		background:#D3FFC9;
	}
	
	.inactivedata{
		background:#FCE5E8;
	}
	
	.miss_date{
		font-weight:bold;
		color:#F00;
	}
	
</style>
</head>

<body>
	<div id='ghead'>
    	<div id='glogo'>
			<a href="index.php">phpMyDataGrid Professional - Sample of use</a>
        </div>
    </div>
	<table border="0" id="bg">
	  <tr>
		<td id="content">
			<h2>Conditional Styling</h2>
			<div id='descripcion'>
				Este ejemplo enseña a dar formato a las filas basándose en una o más condicionantes
			</div>
			<div id='description'>
				This sample teach how to format rows, obtaning the result from one or more conditionals
			</div>
			<div id='dg'> 
            	<!-- en: This is the recommended way to include a datagrid into your proyects, 2 files, one for the html template and other for the datagrid, if you're using templates, please check the "templates sample" -->
            	<!-- es: Esta es la forma recomendada de incluir un grid en sus proyectos, 2 archivos, 1 para la plantilla y otro para el datagrid, si esta utilizando "templates" por favor mirar el ejemplo de "plantillas" -->
				<?php include_once("conditional_style_rows_sample_grid.php"); ?>
			</div>
		    <input type="button" onclick='location.href="../samples.php"' style="float:right;" value="&lt;--" />
		</td>
	  </tr>
	</table>
</body>
</html>