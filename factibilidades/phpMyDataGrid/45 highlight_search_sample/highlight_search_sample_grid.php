<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('highlight_search_sample_grid.php','1');
	
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

	#es: Presentar solo 5 registros por pagina
	#en: Show 5 records per page
	$objGrid-> datarows(5);

	#es: Definir campo(s) para búsquedas
	#en: Define search field(s)
	$objGrid-> searchby ("fname, lname");

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
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:mdy:-");

	#es: Definir el nombre de la función para pre-procesar la información de la tabla antes de ser mostrada -
	#    Tenga en cuenta que esta función se debe usar principalmente para VISUALIZAR informacion y es poco 
	#    recomendado usarla en procesos de mantenimiento
	#en: Define the function name for pre-processing information from the table before being displayed
	# 	 Note that this function should be used mainly to display information and is NOT recommended for use 
	# 	 with maintenance processes
	$objGrid->processData = "setHighLights";

	#es: renderizar el Grid 
	#en: render the grid 
	$objGrid-> grid();

	#es: Obtener los datos del Request (POST o GET)
	#en: Obtain Request data (GET or POST)
	$objGrid->requestData();

	#es: Crear la funcion que procesara la informacion, siempre debe recibir $arrData=array() como parámetro
	#en: This is the function which will process the table info, always MUST receive $arrData=array() as parameter
	function setHighLights($arrData=array()){
		global $objGrid;
		foreach($arrData as $key=>$row){
			if (!empty($objGrid->ss) and $objGrid->getAjaxID() != DG_IsAdding){
				#es: Preparar el nuevo valor del campo, buscando el texto y reemplazandolo con el dato resaltado
				#en: Prepare the new value of the field, searching for the text and replacing it with the highlighted data 
				if ((float)phpversion()<5)
					$row[$objGrid->ss] =  str_replace($objGrid->schrstr, "<span style='background:#060; color:#FFF'>" . $objGrid->schrstr . "</span>", $row[$objGrid->ss]);
				else
					$row[$objGrid->ss] =  str_ireplace($objGrid->schrstr, "<span style='background:#060; color:#FFF'>" . $objGrid->schrstr . "</span>", $row[$objGrid->ss]);
			};
			#es: Almacenar los datos del registro en un array temporal
			#en: Store row data in a temporary array
			$arrTmpData[$key] = $row;
		};
		#es: Siempre se debe retornar un array con la nueva informacion
		#en: It is necesary to return the new processed array
		return $arrTmpData;
	}
?>