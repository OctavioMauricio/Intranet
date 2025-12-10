<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('calc_sample_grid.php','1232');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Presentar solo 5 registros por pagina
	#en: Show 5 records per page
	$objGrid-> datarows(5);

	#es: Permitir seleccionar múltiples registros
	#en: Allow to select several records
	$objGrid-> checkable();

	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Especificar campo clave para edición AJAX
	#en: Define key field to allow AJAX edition
	$objGrid-> keyfield("emp_id");
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "0", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:mdy:/");
	$objGrid-> FormatColumn("salary","Salary", "5", "30", 0, "95", "right", "2");
	$objGrid-> FormatColumn("days","Days", "5", "30", 0, "95", "right", "integer");
	$objGrid-> FormatColumn("total_salary","Value Days", "10", "10", "4","120","right","scalc:((salary/30)*days)//money:$");		
	#es: Importante: Usted puede utilizar cualquier campo que EXISTA y que sea VISIBLE en el datagrid, y puede usar los operadores matemáticos básicos de suma, resta, multiplicación y división
	#en: Important: You may use any field DEFINED and VISIBLE in the grid, as well as basic math operators, addition, substraction, multiplication and divition
	
	#es: Totalizar columnas
	#en: Totalize columns
	$objGrid-> total("salary,days,total_salary");
	
	#es: Definir opciones de exportacion
	#en: Define exporting options
	$objGrid-> export(true,true,true,true,false);
	
	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");
	
	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;
	
	#es: Permitir Edición AJAX online
	#en: Allow AJAX online edition
	$objGrid-> ajax("silent");
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>