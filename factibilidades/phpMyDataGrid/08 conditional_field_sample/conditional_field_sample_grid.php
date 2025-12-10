<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('conditional_field_sample_grid.php','1');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("emp_id");
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,1);
	
	#es: Presentar solo 5 registros por pagina
	#en: Show 5 records per page
	$objGrid-> datarows(8);

	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;

	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	
	#es: Definir todas las condiciones posibles, una por cada elemento del array
	#en: Define all possible conditions, one for each element of the array
	$arrCondiciones = array(
		"['job_id']==1" => "<b style='color:#F00'>This is the No 1 Job</b>",
		"['job_id']==2" => "<i style='color:#060'>Description No 2</i>",
		"['job_id']==3" => "<u style='color:#00F'>This is the 3rd</u>",
		"['job_id']==4" => "<u style='color:#660'><b>Job 4</b></u>",
		"['job_id']==5" => "<b style='color:#606'>Finally the 5th</b>",
		"empty(['job_id'])" => "<b><u style='color:#066'><i>This man has no job</i></u></b>"
		);

	#es: Si el tipo de campo es un array, se entiende que es un campo multicondicional
	#en: If the typefield is an array, it is understood that it is a multiconditional field
	$objGrid-> FormatColumn("job_id","Job ID", "5", "30", 0, "95", "left", $arrCondiciones);

	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");
	
	#es: Permitir Edición AJAX online
	#en: Allow AJAX online edition
	$objGrid-> ajax("silent");

	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>