<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('master_detail_sample_payments.php','2');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("payment_history");
	
	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(10);
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);
	
	#es: Decirle al datagrid que va a usar el calendario (datepicker)
	#en: Tell to phpMyDatagrid it must use the datepicker
	$objGrid-> useCalendar(true);
	
	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(15);
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("id");
	
	#es: Definir campo para ordenamiento
	#en: Define order field
	$objGrid-> orderby ("payment_date");
	
	#es: Capturar el id del empleado
	#en: Obtain employee id
	$emp_id = (isset($_GET['emp_id'])?$_GET['emp_id']:(isset($_POST['emp_id'])?$_POST['emp_id']:''));
	$emp_id = (isset($_GET['e_id'])?$_GET['e_id']:(isset($_POST['e_id'])?$_POST['e_id']:$emp_id));
	
	#es: Buscar los datos del empleado
	#en: Locate employee data
	if (!empty($emp_id)){
		$strSQL = "SELECT * FROM employee where emp_id=" . magic_quote($emp_id);
		$arrData = $objGrid->SQL_query($strSQL);
		$name = $arrData[0]['fname'] . " " . $arrData[0]['lname'];
	}else{
		$name = "No employee selected!";
	}
	
	#es: Definir un título para el grid
	#en: Define a Grid Title
	$objGrid-> tituloGrid("Employee: " . $name);
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "2", "93", "left", "text", $emp_id); // Definir ID con valor de tabla maestra predeterminado // Define ID with default master value
	$objGrid-> FormatColumn("payment_date","Payment Date", "5", "10", "0", "105", "left", "date:ymd:-");
	$objGrid-> FormatColumn("amount","Amount", "13", "20", 0, "125","right", "money:$");

	#es: Instrucciones para el usuario
	#en: User instructions
	$objGrid-> fldComment("payment_date", "(YYYY-MM-DD)");
	
	#es: Crear la instruccion where
	#en: Create where statement
	$objGrid-> where("emp_id = '{$emp_id}'");
	
	#es: Pasar los parametros entre paginas
	#en: Pass parameters to inner pages
	$objGrid-> linkparam("emp_id=".$emp_id);
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>