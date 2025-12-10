<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('intercept_data_sample_grid.php','1');
	
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
	$objGrid-> datarows(8);
	
	#es: Permitir selección de varios registros simultáneamente mediante el uso de cajas de chequeo (checkboxes)
	#en: Allow selection of multiple records simultaneously through the use of checkboxes 
	$objGrid-> checkable();
	
	#es: Activar la barra de botones del datagrid
	#en: Activate the toolbar
	$objGrid-> toolbar = true;
	
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
	
	#es: Generar un código HTML amigable y legible
	#en: Generate an HTML code friendly and readable
	$objGrid-> friendlyHTML();
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0, "Actions");
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	
	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");
	
	#es: Interceptar el llamado AJAX 
	#en: Intercept the AJAX request 
	if ($objGrid->isAjaxRequest()){
		switch ($objGrid->getAjaxID()){
			case DG_IsDelete: // case 3:	// Delete Rows / Borrar Registro
				#es: Actualizar los datos de la solicitud
				#en: Request Data
				$objGrid->requestData();
				#es: Obtener el ID del registro a procesar
				#es: Get record ID
				$row = $objGrid->dgrtd;
				echo "<small>The record ID {$row} has been deleted, here you can do other actions, like delete all related records in other table</small>";
				#es: Ejemplo de otros procesos:
				#en: Other actions sample:
				$strSQL = sprintf("delete from payment_history where emp_id=%s", magic_quote($row));
				echo "<br /><small>For example: {$strSQL}</small>";
				# $objGrid -> SQL_query($strSQL);
				#es: Elimine el comentario de la linea anterior para ejecutar la consulta SQL
				#en: Remove comment from above to execute the query
			break;
		}
	}
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>