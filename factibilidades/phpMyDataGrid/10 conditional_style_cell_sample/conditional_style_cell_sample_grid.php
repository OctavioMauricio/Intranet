<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('conditional_style_cell_sample_grid.php','1');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");

	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(10);

	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("emp_id");
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,2);
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	
	#es: Definir la condicion o condiciones y la clase CSS a usar si esta condición se cumple
	#en: Define the condition or conditions and the CSS class to use if the result is true
	$objGrid ->addCellStyle ("birth_date", "empty(['birth_date']) or ['birth_date']=='0000-00-00'", "miss_date");
		// Nota: Los nombres de los campos deben estar encerrados entre [''] para su funcionamiento, por ejemplo, el campo nombre se debe representar como ['nombre']
		// Note: Field names must be enclosed between [''] to work properly, for example, field name must be writed as ['name']
	
	#es: Puede definir varias condiciones puede ser para el mismo campo, o combinando los resultados con otros campos
	#en: You may define several conditions even for the same field or combining resulted styles with other fields
	$objGrid ->addCellStyle ("birth_date", "!empty(['birth_date'])", "bold_date");
	
	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>