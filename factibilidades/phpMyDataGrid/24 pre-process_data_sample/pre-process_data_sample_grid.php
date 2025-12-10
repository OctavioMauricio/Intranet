<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('pre-process_data_sample_grid.php','1');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Definir campo llave
	#en: Define key field
	$objGrid-> keyfield("emp_id");
	
	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(5);

	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "0", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Full Name", "13", "20", 0, "195","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "2", 2, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 2, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left");
	
	#es: Definir el nombre de la función para pre-procesar la información de la tabla antes de ser mostrada -
	#    Tenga en cuenta que esta función se debe usar principalmente para VISUALIZAR informacion y es poco 
	#    recomendado usarla en procesos de mantenimiento
	#en: Define the function name for pre-processing information from the table before being displayed
	# 	 Note that this function should be used mainly to display information and is NOT recommended for use 
	# 	 with maintenance processes
	$objGrid->processData = "setFullNames";

	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;

	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");

	#es: Definir campo(s) para búsquedas
	#en: Define search field(s)
	$objGrid-> searchby("fname, lname");

	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
	
	#es: Crear la funcion que procesara la informacion, siempre debe recibir $arrData=array() como parámetro
	#en: This is the function which will process the table info, always MUST receive $arrData=array() as parameter
	function setFullNames($arrData=array()){
		foreach($arrData as $key=>$row){
	
			#es: Preparar el nuevo valor del campo
			#en: Prepare new field value
			$fullName = $row['fname'] . " " . $row['minit'] . " " . $row['lname'];
			
			#es: Guardar el nuevo valor en el campo
			#en: Store the new value in the field
			$row['fname'] = $fullName;
	
			#es: Para el ejemplo, dejaremos el campo lname en blanco
			#en: for this sample, let's set as empty the value for lname field
			$row['lname'] = "";
	
			#es: Almacenar los datos del registro en un array temporal
			#en: Store row data in a temporary array
			$arrTmpData[$key] = $row;
		};
		#es: Siempre se debe retornar un array con la nueva informacion
		#en: It is necesary to return the new processed array
		return $arrTmpData;
	}
	
	#es: Usted puede estar pensando: "pero vamos, eso lo puedo hacer concatenando los campos en un SQL personalizado", 
	#	 y está en  lo cierto, pero queríamos mostrarle la forma en la que puede "modificar" la información antes de mostrarla
	
	#en: You may be thinking, "oh! come on, I can do that concatenating the fields in a custom SQL", and you are right,
	#    but we wanted to show you the way to "modify" the grid information before displaying it.

?>