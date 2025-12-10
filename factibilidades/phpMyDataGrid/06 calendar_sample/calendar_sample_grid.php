 <?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('calendar_sample_grid.php','1');
	
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
	
	#es: Decirle al datagrid que va a usar el calendario (datepicker)
	#en: Tell to phpMyDatagrid it must use the datepicker
	$objGrid-> useCalendar(true);
	
	#es: Presentar solo 5 registros por pagina
	#en: Show 5 records per page
	$objGrid-> datarows(5);
	
	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);

	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;

	#es: Definir color para campos modificados en línea
	#en: Define color for inline edited fields
	$objGrid-> dgAjaxChanged = "#F00";

	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "135","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "135", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	
	#es: Permitir Edición AJAX online
	#en: Allow AJAX online edition
	$objGrid-> ajax('silent');
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>