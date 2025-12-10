<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('related_field_sample_grid.php','1');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("emp_id");

	#es: Definir campos para busquedas
	#en: Define search fields
	$objGrid-> searchby("fname,lname");

	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(8);

	#es: Definir opciones de exportacion
	#en: Define exporting options
	$objGrid-> export(true,true,true,true,false);
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	$objGrid-> FormatColumn("job_id","Job Name", "5", "30", 0, "195", "left", "related:select job_desc from jobs where job_id=%s", "2");

	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");
	
	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;
	
	#es: Al tener activa la barra de botones (toolbar) hacer que el cuadro de busqueda y de exportacion sea desplegado en la barra
	#en: As we have the toolbar active, allow that the search and export options be linked to the toolbar
	$objGrid-> strSearchInline = true;
	$objGrid-> strExportInline = true;
	
	#es: Adicionar un boton de Actualizacion en la barra de herramientas
	#en: Add a reload button to the toolbar
	$objGrid-> reload = true;

	#es: Permitir Edición AJAX online
	#en: Allow AJAX online edition
	$objGrid-> ajax("silent");

	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>