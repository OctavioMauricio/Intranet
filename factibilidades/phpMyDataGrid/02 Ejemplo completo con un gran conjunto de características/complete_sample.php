<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>phpMyDataGrid Sample</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<?php 
	#es: esta es la forma RECOMENDADA de incluir los archivos auxiliares (CSS,JS)
	#en: this is the recommended way to include the auxiliary files (CSS,JS)
	include_once('class/phpmydatagrid.class.php'); 
	echo set_DG_Header("js/","css/", " /", "ruby");
	
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
			<h2>A full Sample</h2>
			<div id='descripcion'>
				Este ejemplo demuestra algunas de las opciones de phpMyDataGrid interactuando entre si
			</div>
			<div id='description'>
				This sample shows a full working Grid, featuring the use of some of the options in the grid
			</div>
			<div id='dg'> 
            	<!-- en: This is the recommended way to include a datagrid into your proyects, 2 files, one for the html template and other for the datagrid, if you're using templates, please check the "templates sample" -->
            	<!-- es: Esta es la forma recomendada de incluir un grid en sus proyectos, 2 archivos, 1 para la plantilla y otro para el datagrid, si esta utilizando "templates" por favor mirar el ejemplo de "plantillas" -->
				<?php include_once("complete_sample_grid.php"); ?>
			</div>
            <small><em> Tip: Hold shift key while click the ordering arrows to order by multiple columns<br />
            Sugerencia: Mantenga presionada la tecla Shift mientras hace clic en las flechas de ordenamiento para ordenar por múltiples columnas</em></small>
		    <input type="button" onclick='location.href="../samples.php"' style="float:right;" value="&lt;--" />
		</td>
	  </tr>
	</table>
	<script type="text/javascript" language="javascript">
        function citas(id){
            location.href='complete_sample_newpage.php?id='+id;
        }
    
        function favorite(FavStatus,obj){
			DG_SetFavorite(FavStatus,obj, "fa");
        }
    </script>
</body>
</html>
