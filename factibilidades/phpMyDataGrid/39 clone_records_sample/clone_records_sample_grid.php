<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('clone_records_sample_grid.php','1');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("emp_id");
	
	#es: Especificar ordenamiento
	#en: Define order field
	$objGrid-> orderby("emp_id", "DESC");
	
	#es: Mostrar Boton de "Grabar y Nuevo"
	#en: Display "Save & New" Button
	$objGrid-> saveaddnew=true;
	
	#es: Visualizar la presentación con barra de herramientas
	#en: Enable toolbar look
	$objGrid-> toolbar=true;

	#es: Presentar solo 5 registros por pagina
	#en: Show 5 records per page
	$objGrid-> datarows(5);

	#es: Definir campo(s) para búsquedas
	#en: Define search field(s)
	$objGrid-> searchby ("fname, lname");

	#es: Definir un título para el grid
	#en: Define a Grid Title
	$objGrid-> tituloGrid("Company ABC, employees");
	
	#es: Generar un código HTML amigable y legible
	#en: Generate an HTML code friendly and readable
	$objGrid-> friendlyHTML();
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("clone","Clone Record", "25", "0", "4","20","center","imagelink:clone_records_sample/clone.gif:clone(%s),emp_id");
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	
	#es: Interceptar el llamado AJAX 
	#en: Intercept the AJAX request 
	if ($objGrid->isAjaxRequest()){
		switch ($objGrid->getAjaxID()){
			#es: Validar si es "clone", y procesar 
			#en: Validate if the process is "clone" and process it
			case "clone": 
				#es: Actualizar los datos de la solicitud
				#en: Request Data
				$objGrid->requestData();
				#es: Obtener el ID del registro a procesar
				#es: Get record ID
				$rowID = $objGrid->dgrtd;
				#es: Obtener los datos del registro actual
				#es: Get record data
				$strSQL = sprintf("SELECT * FROM employee where emp_id=%s", magic_quote($rowID));
				$arrData = $objGrid->SQL_query($strSQL);
	
				if (empty($arrData[0]['birth_date'])) $arrData[0]['birth_date']='0000-00-00';
				
				#es: Crear el SQL para grabar el nuevo registro
				#es: Create the SQL query to save the new record
				$strSQL = sprintf("INSERT INTO employee (active, fname, minit, lname, birth_date) values (%s, %s, %s, %s, %s)", 
									magic_quote($arrData[0]['active']),
									magic_quote($arrData[0]['fname']),
									magic_quote($arrData[0]['minit']),
									magic_quote($arrData[0]['lname']),
									magic_quote($arrData[0]['birth_date'])
									);
				$arrData = $objGrid->SQL_query($strSQL);
			break;
		}
	}

	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");

	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>