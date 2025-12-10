<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	include_once('class/phpmydatagrid.class.php'); 
		
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('nodes_details_grid.php','2');
	
	#es: Después de definir el objeto, definir el enlace al grid de detalles
	#en: Just after define the object, then define the link to details grid
//	$objGrid-> setDetailsGrid("nodes_payment_details_grid.php", "otro_id");
	
	#es: Defnir la relacion con la base de datos maestra y obtener el valor del id por defecto para nuevos registros
	#en: Define the relation with the master nase and get the default id for new records
	$emp_id = $objGrid -> setMasterRelation("otro_id");
	
	#es: Tabla liquida con ancho del 95%
	#en: Liquid table, 95% width
	$objGrid -> liquidTable = true;
	$objGrid -> width = "95%";
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("payment_history");
	
	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(10);
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);
	
	#es: Decirle al datagrid que va a usar el calendario (datepicker)
	#en: Tell to phpMyDatagrid it must use the datepicker
	$objGrid-> useCalendar(true);
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("id");
	
	#es: Definir campo para ordenamiento
	#en: Define order field
	$objGrid-> orderby ("payment_date");
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "2", "93", "left", "text", $emp_id); // Definir ID con valor de tabla maestra predeterminado // Define ID with default master value
	$objGrid-> FormatColumn("payment_date","Payment Date", "5", "10", "0", "105", "left", "date:ymd:-");
	$objGrid-> FormatColumn("amount","Amount", "13", "20", 0, "125","right", "money:$");
	
	#es: Instrucciones para el usuario
	#en: User instructions
	$objGrid-> fldComment("payment_date", "(YYYY-MM-DD)");
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>