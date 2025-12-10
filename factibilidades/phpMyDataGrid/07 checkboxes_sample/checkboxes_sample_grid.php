<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('checkboxes_sample_grid.php','1');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("emp_id");
	
	#es: Permitir selección de varios registros simultáneamente mediante el uso de cajas de chequeo (checkboxes)
	#en: Allow selection of multiple records simultaneously through the use of checkboxes 
	$objGrid-> checkable();
	
	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(5);
	
	#es: Generar un código HTML amigable y legible
	#en: Generate an HTML code friendly and readable
	$objGrid-> friendlyHTML();
	
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
	
	
	#es: Verificar si se trata de un llamado AJAX, y verificar el ID de dicho llamado
	#en: check whether is an AJAX call, and verify the AJAX ID
	if ($objGrid->isAjaxRequest()){
		if ($objGrid->getAjaxID()=='ve'){
			$selectedRows = $objGrid->getCheckedBoxes();
			foreach ($selectedRows as $row){
				#es: Puede realizar cualquier proceso al tener disponible la id
				#en: You may do any kind of internal process with the id
				echo "<br>Row ID: {$row} is checked"; 
			}
		}
	}

	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;

	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");

	#es: Definir las cantidades para visualizacion
	#en: Define amounts for visualization
	$objGrid -> arrRows = array(1,2,3,4,5,10,20,50,100);

	#es: Permitir Edición AJAX online
	#en: Allow AJAX online edition
	$objGrid-> ajax("default");

	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>