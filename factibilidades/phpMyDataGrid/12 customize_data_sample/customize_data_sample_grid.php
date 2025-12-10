<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('customize_data_sample_grid.php','1');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	
	$tableName = "employee";
	$objGrid-> tabla ($tableName);
	
	#es: Definir campo llave
	#en: Define key field
	$objGrid-> keyfield("emp_id");
	
	#es: Permitir Addicionar/Editar/Ver
	#en: Allow Add/Edit/View
	$objGrid-> buttons(true,true,true);

	#es: Activar boton de exportar
	#en: Allow export
	$objGrid-> export(true,true,false,true,false);

	#es: Definir el orden en que se mostrarán los botones
	#en: Define order to display the buttons
	$objGrid-> btnOrder	 = "[D][V][E]";	

	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(10);

	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	
	#es: Probablemente usted desee usar el formato "date:ymd:-" para su campo, tenga en cuenta que NO se puede 
	#    ya que el grid intentaría dar formato a la marca de tiempo
	#en: Probably you want to use the format "date:ymd:-" for the field, note that NO can be given that because 
	#    the grid will try to format the timestamp
	$objGrid-> FormatColumn("afiliation_date","Affiliation Date", "10", "10", "0", "105", "left");
	
	#es: Definir el nombre de la función para pre-procesar la información de la tabla antes de ser mostrada
	#en: Define the function name for pre-processing information from the table before being displayed
	$objGrid->processData = "setDates";
	
	#es: Capturar la informacion antes de ser grabada para procesarla
	#en: Capture input data before being saved in order to process it
	if ($objGrid->isAjaxRequest()){
		$objGrid->requestData();
		switch ($objGrid->getAjaxID()){ 
			case "4" :	// Inline Edition - Edición en línea
				$arrData = $objGrid->getEditedData();
				$rowid   = $arrData['id'];
				$fname   = $arrData['fieldname'];
				$fldData = $arrData['data'];
				if ($fname == 'lname'){		// if the edited field is 'lname' // si el campo que se esta editando es 'lname'
					if (substr($fldData,0,2)!='XX')
						$objGrid->setNewInlineData("XX".$fldData);
					#es: La anterior definición es solo un ejemplo de como alterar el valor de un campo al editar online, en este caso
					#    si los dos primeros caracteres son diferentes a XX, adicionarle XX al campo
					#en: Line above is just a sample about how to modify the data in a field by using inline edition, in this case
					#    if the first 2 characters are others than XX then add the XX to the field.
				}
				if ($fname == 'afiliation_date'){		// if the edited field is 'afiliation_date' // si el campo que se esta editando es 'afiliation_date' //
					$newValue = strtotime($fldData); // Convert the typed date into a timestamp // convertir la fecha digitada en una marca de tiempo //
	
					#es: para una correcta visualizacion del campo, basta con  guardar el timestamp e indicarle al grid que no debe ser el quien guarde
					#en: for a right process, we must store the field and tell to the grid to not to store.
					echo 
					$strSQL = sprintf("update {$tableName} set afiliation_date=%s where emp_id=%s", 
										$objGrid->magic_quote($newValue),
										$objGrid->magic_quote($rowid)
									);
					$objGrid->SQL_query($strSQL);
					
					die ($fldData);
				}
			break;
			case "6" : 	// Add/Edit - Adicionar / Editar
				#es: Como el formulario esta trabajando con 'POST' obtenemos el valor del campo
				#en: As form method is 'POST' we obtain and modify data for field
				#es: Todos los campos enviados por phpMyDataGrid tienen el prefijo dfFld
				#en: All fields sent by phpMyDataGrid has the prefix dgFld
				$fldData = $_POST['dgFldafiliation_date'];
				$arrData = explode("-",$fldData);
				$newValue= mktime(0, 0, 0, $arrData[1], $arrData[2], $arrData[0] );
				$_POST['dgFldafiliation_date'] = $newValue;
			break;
		}
	}
	
	#es: Definimos que el grid sea AJAX editable
	#en: Define grid as AJAX online editable
	$objGrid-> ajax('silent');
	
	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;
	
	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
	
	#es: Crear la funcion que procesara la informacion, siempre debe recibir $arrData=array() como parámetro
	#en: This is the function which will process the table info, always MUST receive $arrData=array() as parameter
	function setDates($arrData=array()){
		$arrTmpData = array();
		foreach($arrData as $key=>$row){
			#es: Obtener los datos del campo o campos que se modificará(n)
			#en: Obtain the info for field or fields which will be processed
			$arrDate = getdate($row['afiliation_date']);
			
			#es: Definir el nuevo valor del campo 
			#en: Define new value for field 
			$month=(strlen($arrDate['mon'])==1)?'0'.$arrDate['mon']:$arrDate['mon'];	# Generate 01, 02, 03, 04, etc.
			$day=(strlen($arrDate['mday'])==1)?'0'.$arrDate['mday']:$arrDate['mday'];	# Generate 01, 02, 03, 04, etc.
			$row['afiliation_date'] = $arrDate['year']."-".$month."-".$day;				# Display date format 2008-07-02
	
			#es: Almacenar los datos del registro en un array temporal
			#en: Store row data in a temporary array
	
	$arrTmpData[$key] = $row;
		};
		#es: Siempre se debe retornar un array con la nueva informacion
		#en: It is necesary to return the new processed array
		return $arrTmpData;
	}
?>