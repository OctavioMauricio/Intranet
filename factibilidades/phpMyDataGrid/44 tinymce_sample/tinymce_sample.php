<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>phpMyDataGrid Sample</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<?php 
	#########################################################################################
	#########################################################################################
	#########################################################################################
	######                                                                             ######
	###### This sample DO NOT INCLUDE TinyMCE files, you must download the library     ######
	###### Please download TinyMCE Library from http://tinymce.moxiecode.com/          ######
	######                                                                             ######
	###### Este ejemplo NO INCLUYE los archivos de la librería TinyMCE                 ######
	###### Por favor descargue la libreria TinyMCE desde http://tinymce.moxiecode.com/ ######
	######                                                                             ######
	#########################################################################################
	#########################################################################################
	#########################################################################################


	#es: esta es la forma RECOMENDADA de incluir los archivos auxiliares (CSS,JS)
	#en: this is the recommended way to include the auxiliary files (CSS,JS)
	include_once('class/phpmydatagrid.class.php'); 
	echo set_DG_Header();
	
#es: se debe incluir la libreria tiny_mce - Leer nota al principio del ejemplo
#en: tiny_mce must be included - Read note at the top of this sample
?>
<script type='text/javascript' src='tinymce/jscripts/tiny_mce/tiny_mce.js'></script>
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
			<h2>TinyMCE Sample</h2>
			<div id='descripcion'>
				Este ejemplo enseña a incluir el editor WYSIWYG TinyMCE para los textareas
			</div>
			<div id='description'>
				This sample teach how to work with TinyMCE WYSIWYG Editor for textareas
			</div>
			<div id='dg'> 
            	<!-- en: This is the recommended way to include a datagrid into your proyects, 2 files, one for the html template and other for the datagrid, if you're using templates, please check the "templates sample" -->
            	<!-- es: Esta es la forma recomendada de incluir un grid en sus proyectos, 2 archivos, 1 para la plantilla y otro para el datagrid, si esta utilizando "templates" por favor mirar el ejemplo de "plantillas" -->
				<?php include_once("tinymce_sample_grid.php"); ?>
			</div>
		    <input type="button" onclick='location.href="../samples.php"' style="float:right;" value="&lt;--" />
		</td>
	  </tr>
	</table>
</body>
</html>