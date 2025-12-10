<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('toolbar_sample_grid.php','1');
	
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
	
	#es: Mostrar Boton de "Grabar y Nuevo"
	#en: Display "Save & New" Button
	$objGrid-> saveaddnew=true;
	
	#es: Definir un título para el grid
	#en: Define a Grid Title
	$objGrid-> tituloGrid("Company ABC, employees");
	
	#es: Definir campo(s) para búsquedas
	#en: Define search field(s)
	$objGrid-> searchby("fname,lname");
	
	#es: Definir opciones de exportacion
	#en: Define exporting options
	$objGrid-> export(true,true,true,true,false);
	
	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(5);
	
	#es: Permitir selección de varios registros simultáneamente mediante el uso de cajas de chequeo (checkboxes)
	#en: Allow selection of multiple records simultaneously through the use of checkboxes 
	$objGrid-> checkable();
	
	#es: Activar la barra de botones del datagrid
	#en: Activate the toolbar
	$objGrid-> toolbar = true;
	
	#es: Adicionar un boton a la Barra de botones
	#en: Add a button to the toolbar
	$objGrid-> addButton("favorites_sample_images/star1.gif", "add_favorites()", "Add to Favorites");
	
	#es: Adicionar un separador a los botones
	#en: Add a separator for buttons
	$objGrid-> addSeparator();
	
	#es: Definir las opciones de la lista desplegable
	#en: Define items for the combo list
	$arrData = array (
		"active" => "Set as Favorite",
		"inactive" => "Set as Normal"
	);
	
	#es: Adicionar un conjunto de opciones en una lista desplegable
	#en: Add a set of options in a combo list
	$objGrid-> addSelect($arrData, "check_selected(this.value)", 'Choose an option');
	
	#es: Adicionar un separador a los botones
	#en: Add a separator for buttons
	$objGrid-> addSeparator();
	
	#es: Activar el boton que permite Eliminar varios registros slmultaneamente
	#en: Activate button to delete several records at time
	$objGrid-> delchkbtn = true;
	
	#es: Opciones de exportar en la barra de herramientas en vez de una ventana flotante
	#en: Export options in the toolbar instead of a floating window
	$objGrid-> strExportInline = true;
	
	#es: Opciones de búsqueda en la barra de herramientas en vez de una ventana flotante
	#en: Export options in the toolbar instead of a floating window
	$objGrid-> strSearchInline = true;
	
	#es: Activar icono de refrescar el DataGrid
	#en: Activate refresh DataGrid icon 
	$objGrid-> reload = true;
	
	#es: Definir el orden de presentación de los botones de registro
	#en: Define order for buttons in record presentation
	$objGrid-> btnOrder="[D][V][E]";
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0, "Actions");
	
	$array_opciones = array("1"=>"Option number one",
							"2"=>"Option number two",
							"3"=>"Option number three",
							"4"=>"Option number four",
							"5"=>"Option number five"
							);
	
	$objGrid->addExportOption("subopt1", "Checkbox", "Suboption1");
	$objGrid->addExportOption("subopt2", "Checkbox", "Suboption2");
	$objGrid->addExportOption("", "separator");
	$objGrid->addExportOption("subopt3", "Checkbox", "Suboption3");
	$objGrid->addExportOption("ordenadopor", "Select", "Order By...", $array_opciones);
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	$objGrid-> FormatColumn("active","", "25", "0", "3","40","center","imagelink:favorites_sample_images/star%s.gif:favorite(%s\\,this),active", 0);
	
	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");
	
	#es: Eliminar las flechas de ordenamiento del campo active
	#en: Remove orderin arrows for "active" field
	$objGrid-> chField("active","R");
	
	#es: Interceptar el llamado AJAX 
	#en: Intercept the AJAX request 
	if ($objGrid->isAjaxRequest()){
		switch ($objGrid->getAjaxID()){
			#es: Validar si es "fa" (tal como se definio en el script), y procesar el campo de imagen
			#en: Validate if the value is "fa" (as defined in the script), and process the image field
			case 'fa': 
				$objGrid->changeImage(); 
			break;
			
			#es: Validar si es "multi_favorites" (tal como se definio en el script), y procesar las filas seleccionadas
			#en: Validate if the value is "multi_favorites" (as defined in the script), and process the selected rows
			case 'multi_favorites':
				$arrRows = $objGrid->getCheckedBoxes();
				foreach ($arrRows as $row){
					$sqlQuery = "update employee set active='1' where emp_id=".magic_quote($row);
					$objGrid-> SQL_query($sqlQuery);
				}
			break;
			
			#es: Validar si es "check_selected" (tal como se definio en el script), y procesar las filas de acuerdo a la condicion
			#en: Validate if the value is "check_selected" (as defined in the script), and process the selected rows based in the paramater
			case "check_selected":
				$objGrid->requestData();
				$arrRows = $objGrid->getCheckedBoxes();
				switch ($objGrid->dgrtd){
					case "active":
						foreach ($arrRows as $row){
							$sqlQuery = "update employee set active='1' where emp_id=".magic_quote($row);
							$objGrid-> SQL_query($sqlQuery);
						}
					break;
					case "inactive":
						foreach ($arrRows as $row){
							$sqlQuery = "update employee set active='0' where emp_id=".magic_quote($row);
							$objGrid-> SQL_query($sqlQuery);
						}
					break;
				}
			break;
		}
	}
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>