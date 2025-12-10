 <?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('export_details_grid.php','1');
	
	#es: Generar un código HTML amigable y legible
	#en: Generate an HTML code friendly and readable
	$objGrid-> friendlyHTML();
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("emp_id");
	
	#es: Decirle al datagrid que va a usar el calendario (datepicker)
	#en: Tell to phpMyDatagrid it must use the datepicker
	$objGrid-> useCalendar(true);
	
	// Definimos los datos de cada una de las opciones de detalle a imprimir, se debe adicionar un array marcado por cada opcion, el array debe contener
	// los siguientes parametros:

	#es: Definir la relacion entre el maestro y el detalle
	#en: Define relation between master and detail
	$objGrid->exportDetails['pagos'] = array("sql"=>"SELECT `emp_id`,`payment_date`,`amount` FROM `payment_history` WHERE emp_id = '['emp_id']' ORDER BY `payment_date` ASC",
										"parameters"=>"emp_id",
										"menu"=>"Payments");
		// sql: este key contiene el SQL necesario para mostrar la salida detalle, puede tener varios parametros de opcion, por ejemplo si se relaciona
				// con id_intereses, este campo deberia ir representado como ['id_intereses']  para que el grid lo reconozca y lo reemplace con el valor
				// que trae desde el maestro.
	
		// parameters: la lista de parametros usados anteriormente de la tabla maestra, separados por coma, ejemplo: "id_intereses,codigo_interes"
	
		// menu: Define el valor que mostrará en el menu desplegable al usuario a la hora de elegir que tabla de detalles desea incluir


	/* ---- Puede tener varias tablas para generar la lista de detalle, basta con poner un subindice diferente al array exportDetails 
	$objGrid->exportDetails['lineas'] = array("sql"=>"SELECT id_productos, id_stocks, nombre_producto, referencia_producto, cantidad FROM `lineas_intereses` where id_intereses='['id_intereses']' ORDER BY nombre_producto",
										"parameters"=>"id_intereses".
										"menu"=>"L&iacute;neas de inter&eacute;s");
	*/
	#es: Presentar solo 5 registros por pagina
	#en: Show 5 records per page
	$objGrid-> datarows(5);
	
	#es: Definir campo(s) para búsquedas
	#en: Define search field(s)
	$objGrid-> searchby("fname, lname");
	
	#es: Definir opciones de exportacion
	#en: Define exporting options
	$objGrid-> export(true,true,true,true,false);
	
	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;
	
	#es: Opciones de exportar en la barra de herramientas en vez de una ventana flotante
	#en: Export options in the toolbar instead of a floating window
	$objGrid-> strExportInline = true;
	
	#es: Opciones de búsqueda en la barra de herramientas en vez de una ventana flotante
	#en: Export options in the toolbar instead of a floating window
	$objGrid-> strSearchInline = true;
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);
	
	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "135","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "135", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	
	#es: Permitir Edición AJAX online
	#en: Allow AJAX online edition
	$objGrid-> ajax('silent');
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>