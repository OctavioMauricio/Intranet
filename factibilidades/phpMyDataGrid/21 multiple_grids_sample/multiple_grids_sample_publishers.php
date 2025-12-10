<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('multiple_grids_sample_publishers.php','2');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("publishers");
	
	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(3);

	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("pub_id");
	
	#es: Definir campo para ordenamiento
	#en: Define order field
	$objGrid-> orderby ("pub_id","desc");

	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("pub_id","ID", "40", "50", "0", "93", "left", "text");
	$objGrid-> FormatColumn("pub_name","Name", "13", "40", 0, "95","left");
	$objGrid-> FormatColumn("city","City", "5", "20", 0, "95", "left");
	$objGrid-> FormatColumn("state","State", "5", "2", 0, "95", "left");
	$objGrid-> FormatColumn("country","Country", "5", "30", 0, "95", "left");
	
	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;
	
	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");
	
	#es: Permitir Edición AJAX online
	#en: Allow AJAX online edition
	$objGrid-> ajax('silent');
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>