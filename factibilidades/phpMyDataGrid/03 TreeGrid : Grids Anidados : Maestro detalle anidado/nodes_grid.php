<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('nodes_grid.php', '1');
	
	#es: Después de definir el objeto, definir el enlace al grid de detalles
	#en: Just after define the object, then define the link to details grid
	$objGrid-> setDetailsGrid("nodes_details_grid.php", "otro_id");

	$objGrid -> friendlyHTML();

	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");

	#es: Decirle al datagrid que va a usar el calendario (datepicker)
	#en: Tell to phpMyDatagrid it must use the datepicker
	$objGrid-> useCalendar(true);

	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("otro_id");
	
	#es: Definir campo para ordenamiento
	#en: Define order field
	$objGrid-> orderby ("otro_id","desc");
	
	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(6);

	#es: Definir la barra de herramientas
	#en: Define toolbar
	$objGrid-> toolbar = true;
	
	#es: Al tener activa la barra de botones (toolbar) hacer que el cuadro de exportacion sea desplegado en la barra
	#en: As we have the toolbar active, allow that the export option to be linked to the toolbar
	$objGrid-> strExportInline = true;
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	
	#es: Instrucciones para el usuario
	#en: User instructions
	$objGrid-> fldComment("birth_date", "Write the employee bith date (YYYY-MM-DD)");
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>