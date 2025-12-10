<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ar" lang="ar" dir="rtl">
<head>
<title>phpMyDataGrid Sample</title>
<!-- Set page content as utf-8 -->
<!-- Definir que el contenido de la pagina es utf-8 -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type='text/css' rel='stylesheet' href='style.css' />
<?php 
	#es: esta es la forma RECOMENDADA de incluir los archivos auxiliares (CSS,JS)
	#en: this is the recommended way to include the auxiliary files (CSS,JS)
	include_once('class/phpmydatagrid.class.php'); 
	echo set_DG_Header();
?>
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
			<h2>Toolbar popup Menu Sample</h2>
			<div id='descripcion'>
				Este ejemplo muestra la integración de phpMyDataGrid con lenguajes RTL
			</div>
			<div id='description'>
				This example shows the integration of phpMyDataGrid with RTL languages
			</div>

			<div id='dg'> 
            	<!-- en: This is the recommended way to include a datagrid into your proyects, 2 files, one for the html template and other for the datagrid, if you're using templates, please check the "templates sample" -->
            	<!-- es: Esta es la forma recomendada de incluir un grid en sus proyectos, 2 archivos, 1 para la plantilla y otro para el datagrid, si esta utilizando "templates" por favor mirar el ejemplo de "plantillas" -->
				<?php include_once("base_sample_rtl_grid.php"); ?>
			</div>
		    <input type="button" onclick='location.href="../samples.php"' style="float:right;" value="&lt;--" />
		</td>
	  </tr>
	</table>
</body>
</html>