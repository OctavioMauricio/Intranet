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
	<div id='ghead'>
    	<div id='glogo'>
			<a href="index.php">phpMyDataGrid Professional - Sample of use</a>
        </div>
    </div>
	<table border="0" id="bg">
	  <tr>
		<td id="content" colspan="2">
			<h2>Multiple Grids</h2>
			<div id='descripcion'>
				Este ejemplo enseña a incluir varios grids funcionales en una misma pagina
			</div>
			<div id='description'>
				This sample teach how to include several working datagrids in the same page
			</div>
		</td>            
	  </tr>
	  <tr>
		<td id="content2" style="width:50%; vertical-align:top; text-align:left">
			<div id='dg'> 
            	<!-- en: This is the recommended way to include a datagrid into your proyects, 2 files, one for the html template and other for the datagrid, if you're using templates, please check the "templates sample" -->
            	<!-- es: Esta es la forma recomendada de incluir un grid en sus proyectos, 2 archivos, 1 para la plantilla y otro para el datagrid, si esta utilizando "templates" por favor mirar el ejemplo de "plantillas" -->
				<?php include_once("multiple_grids_sample_employees.php"); ?>
			</div>
		</td>
   		<td style="width:50%; vertical-align:top; text-align:left">
            <strong>This is the second (totally independant) Grid:</strong><hr />
            <div style="margin-left:80px;">
				<?php include_once("multiple_grids_sample_publishers.php"); ?>
            </div>
		    <input type="button" onclick='location.href="../samples.php"' style="float:right;" value="&lt;--" />
		</td>
	  </tr>
	</table>
</body>
</html>