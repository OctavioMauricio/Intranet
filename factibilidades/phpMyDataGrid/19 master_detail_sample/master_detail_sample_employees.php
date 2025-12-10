<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('master_detail_sample_employees.php','1');
	
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
	$objGrid-> keyfield ("emp_id");
	
	#es: Definir campo para ordenamiento
	#en: Define order field
	$objGrid-> orderby ("emp_id","desc");
	
	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(6);
	
	// Inicialmente le decimos que queremos que la exportación HTML interactue con otro archivo y a la vez definimos el archivo que procesara la salida de datos.
//	$objGrid->exportMagma = "_magma_exporta.php";
	
	// Definimos los datos de cada una de las opciones de detalle a imprimir, se debe adicionar un array marcado por cada opcion, el array debe contener
	// los sigguientes parametros:
	
		// sql: este key contiene el SQL necesario para mostrar la salida detalle, puede tener varios parametros de opcion, por ejemplo si se relaciona
				// con id_intereses, este campo deberia ir representado como ['id_intereses']  para que el grid lo reconozca y lo reemplace con el valor
				// que trae desde el maestro.
	
		// parameters: la lista de parametros usados anteriormente de la tabla maestra, separados por coma, ejemplo: "id_intereses,codigo_interes"
	
		// menu: Define el valor que mostrará en el menu desplegable al usuario a la hora de elegir que tabla de detalles desea incluir
	$objGrid->exportDetails['pagos'] = array("sql"=>"SELECT `emp_id`,`payment_date`,`amount` FROM `payment_history` WHERE emp_id = '['emp_id']' ORDER BY `payment_date` ASC",
										"parameters"=>"emp_id",
										"menu"=>"pagos");
	
	/* ---- Puede tener varias tablas para generar la lista de detalle, basta con poner un subindice diferente al array exportDetails 
	$objGrid->exportDetails['lineas'] = array("sql"=>"SELECT id_productos, id_stocks, nombre_producto, referencia_producto, cantidad FROM `lineas_intereses` where id_intereses='['id_intereses']' ORDER BY nombre_producto",
										"parameters"=>"id_intereses".
										"menu"=>"L&iacute;neas de inter&eacute;s");
	*/
	
	/* Por ultimo realizamos un pequeño Hack para lograr que al momento de exportar el grid no nos genere código, en cambio nos devuelva los datos en arrays */
	if (isset($retCode)) $objGrid->retcode = $retCode;
	
	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;
	
	#es: Definir opciones de exportacion
	#en: Define exporting options
	$objGrid-> export(true,true,true,true,false);
	
	#es: Al tener activa la barra de botones (toolbar) hacer que el cuadro de exportacion sea desplegado en la barra
	#en: As we have the toolbar active, allow that the export option to be linked to the toolbar
	$objGrid-> strExportInline = true;
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("dummy_field_1","View Payments", "5", "30", 4, "95", "center", "imagelink:images/selected_rows.gif:viewDetails(%s),emp_id");
	
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