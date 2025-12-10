<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('hide_fields_in_grid_but_editable_sample_grid.php','1');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("emp_id");
	
	#es: Mostrar Boton de "Grabar y Nuevo"
	#en: Display "Save & New" Button
	$objGrid-> saveaddnew=true;
	
	#es: Definir un título para el grid
	#en: Define a Grid Title
	$objGrid-> tituloGrid("Company ABC, employees");
	
	#es: Generar un código HTML amigable y legible
	#en: Generate an HTML code friendly and readable
	$objGrid-> friendlyHTML();

	#es: Visualizar la presentación con barra de herramientas
	#en: Enable toolbar look
	$objGrid-> toolbar=true;

	#es: No mostrar el texto "Mostrando registros x a y de z"
	#en: Do not display text: "Displaying rows x to y of z"
	$objGrid-> showToOf = false;

	#es: Presentar solo 10 registros por pagina
	#en: Show 10 records per page
	$objGrid-> datarows(10);

	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	
	#es: Definir campos Ocultos
	#en: Define Hidden fields
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 2, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "2", "105", "left", "date:ymd:-");
	
	#es: Definir estos campos Visibles al editar
	#en: Define the fields to be visibles when editing
	$objGrid-> chField("lname","E+");
	$objGrid-> chField("birth_date","E+");

	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");

	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>