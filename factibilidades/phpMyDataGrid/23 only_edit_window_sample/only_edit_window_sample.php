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
			<h2>No Grid, only Edit</h2>
			<div id='descripcion'>
				Este ejemplo enseña la forma de mostrar la pantalla de edición de datos sin mostrar el DataGrid
			</div>
			<div id='description'>
				This sample teach how display the edit data form without displaying the DataGrid
			</div>
			<div id='dg'> 
            	<!-- en: This is the recommended way to include a datagrid into your proyects, 2 files, one for the html template and other for the datagrid, if you're using templates, please check the "templates sample" -->
            	<!-- es: Esta es la forma recomendada de incluir un grid en sus proyectos, 2 archivos, 1 para la plantilla y otro para el datagrid, si esta utilizando "templates" por favor mirar el ejemplo de "plantillas" -->
				<?php 
					#en: Process data by using the id, in this case, just to select some data in the table
					#es: Realizar las acciones necesarias, en este caso solo para elegir algunos datos de la tabla
					$link = mysql_connect("127.0.0.1", "user", "password");
					mysql_select_db("guru_sample_a");
					
					$objData = mysql_query("select emp_id from employee order by emp_id limit 15");
					echo "Select an ID to edit / Seleccione un ID para editar:<br>";
					while($row = mysql_fetch_array($objData)){
						echo "<a href='only_edit_window_sample.php?id=" . $row['emp_id'] . "'>" . $row['emp_id'] . "</a>&nbsp; ";
					}
					if (isset($_GET['id']) and !empty($_GET['id'])){
						$id = $_GET['id'];
						include_once("only_edit_window_sample_grid.php"); 
					}
				?>
			</div>
		</td>
	  </tr>
	</table>
</body>
</html>
