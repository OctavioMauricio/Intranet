<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('validation_sample_grid.php','1');
	
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
	$objGrid-> buttons(true,true,true,true,0);
	
	#es: Decirle al datagrid que va a usar el calendario (datepicker)
	#en: Tell to phpMyDatagrid it must use the datepicker
	$objGrid-> useCalendar(true);
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	$objGrid-> FormatColumn("salary","Salary", "5", "30", 0, "95", "right", "2");

	#es: Cambiar las propiedades del campo
	#en: Change Field Properties
	$objGrid-> chField("emp_id","N-E-");

	#es: Validaremos que el nombre del empleado tenga mas de dos caracteres
	#en: Validate employee name to be length than 2 characters
	$objGrid-> jsValidate("salary", "IsNumeric(this.value)", "Only numbers please", "Please write the employee salary");
	
	#es: Validaremos que el nombre del empleado tenga mas de dos caracteres
	#en: Validate employee name to be length than 2 characters
	$objGrid-> jsValidate("fname", "this.value.length>=2", "Employee name must be longer than 2 characters", "Please write the employee name (3 chars Min)");
	
	#es: Validaremos el apellido del empleado, esta vez usando una funcion
	#en: Validate employee last name, this time by using a function call
	$objGrid-> jsValidate("lname", "valida_lname(this.value)", "Please write a valid last name for the employee", "Write the employee last name");
	
	#es: Si no queremos validar la entrada de datos del usuario, pero queremos mostrar un mensaje indicador al usuario
	#en: If we do not want to validate the user input, but want to display an indicator message to user
	$objGrid-> fldComment("birth_date", "Write the employee bith date or pick one from calendar");
	
	#es: Permitir Edición AJAX online
	#en: Allow AJAX online edition
	$objGrid-> ajax('silent');

	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(5);

	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");

	#es: Activar la barra de botones del datagrid
	#en: Activate the toolbar
	$objGrid-> toolbar = true;
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>