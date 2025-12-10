<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('join_sample_grid.php','1');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Especificar campo clave para edición AJAX
	#en: Define key field to allow AJAX edition
	$objGrid-> keyfield("emp_id");
	
	#es: El manejo de tablas JOINed no acepta mantenimientos, por lo cual tenemos todos los botones disabled
	#en: JOINed tables do not allow maintenance, for that reason, all butons must be disabled
	$objGrid-> buttons(false,false,false,false);

	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(5);

	#es: Preparar el SQL personalizado, teniendo en cuenta de no incluir comandos como Where, Order o group, 
	#    ya que estos deben ser definidos desde su propio metodo.
	#en: Prepare the custom SQL query, having in mind to not to include statements like Where, Order or group, 
	#    because those statement must be included by their respective methods
	//$objGrid-> sqlstatement ("select emp_id, active, fname, minit, lname, birth_date, job_desc, e.job_id from employee e inner join jobs j on e.job_id=j.job_id");
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "0", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	$objGrid-> FormatColumn("job_desc","Job Description", "5", "30", 0, "155", "left");
	$objGrid-> FormatColumn("job_id","", "5", "30", 2, "95", "left");

	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;
	
	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");

	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>