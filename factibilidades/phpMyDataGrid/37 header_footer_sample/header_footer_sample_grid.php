<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('header_footer_sample_grid.php','1');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("emp_id");
	
	#es: Visualizar la presentación con barra de herramientas
	#en: Enable toolbar look
	$objGrid-> toolbar=true;
	
	#es: Definir un título para el grid
	#en: Define a Grid Title
	$objGrid-> tituloGrid("Company ABC, employees");
	
	#es: Definir un pie de página para el grid
	#en: Define a Grid footer
	$objGrid-> FooterGrid("This is a footer for the entire Grid");
	
	#es: Generar un código HTML amigable y legible
	#en: Generate an HTML code friendly and readable
	$objGrid-> friendlyHTML();
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);

	#es: Presentar solo 5 registros por pagina
	#en: Show 5 records per page
	$objGrid-> datarows(5);

	#es: Definir campo(s) para búsquedas
	#en: Define search field(s)
	$objGrid-> searchby ("fname, lname");

	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");

	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	
	#es: Definir un encabezado para el proceso de Adicion
	#en: Define a header for the process of addition
	$objGrid ->actHeader["add"]= "This is a header in ADD option";
	
	#es: Definir un pie de página para el proceso de Adicion
	#en: Define a footer for the process of addition
	$objGrid ->actFooter["add"]= "This is a footer in ADD option";
	
	#es: Definir un encabezado para el proceso de editar
	#en: Define a header for the process of edition
	$objGrid ->actHeader["edit"]= "This is a header in EDIT option";
	
	#es: Definir un pie de página para el proceso de editar
	#en: Define a footer for the process of edition
	$objGrid ->actFooter["edit"]= "This is a footer in EDIT option";
	
	#es: Definir un encabezado para el proceso de Ver
	#en: Define a header for the process of view
	$objGrid ->actHeader["view"]= "This is a header in VIEW option";
	
	#es: Definir un pie de página para el proceso de Ver
	#en: Define a footer for the process of view
	$objGrid ->actFooter["view"]= "This is a footer in VIEW option";
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>