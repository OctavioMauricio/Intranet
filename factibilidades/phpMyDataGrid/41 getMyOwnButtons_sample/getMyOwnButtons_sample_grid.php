<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('getMyOwnButtons_sample_grid.php','1');
	
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

	#es: Visualizar la presentación con barra de herramientas
	#en: Enable toolbar look
	#$objGrid-> toolbar = true;

	#es: Presentar solo 5 registros por pagina
	#en: Show 5 records per page
	$objGrid-> datarows(5);

	#es: Generar un código HTML amigable y legible
	#en: Generate an HTML code friendly and readable
	$objGrid-> friendlyHTML();
	
	#es: Definir opciones de exportacion
	#en: Define exporting options
	$objGrid-> export(true,true,true,true,false);
	
	#es: Definir campo(s) para búsquedas
	#en: Define search field(s)
	$objGrid-> searchby("fname,lname");
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	
	#es: Definir que se obtendrán los botones directamente desde el código del DataGrid
	#en: Define that buttons will be obtained directly from DataGrid Code
	$objGrid -> retcode = true;
	$objGrid -> getMyOwnButtons = true;
	
	#es: renderizar el Grid en una variable
	#en: render the grid into a variable
	$strGrid = $objGrid-> grid();
	
	#es: Obtener los datos de los botones
	#en: Get Buttons Data
	$btnAdd = $objGrid -> strAddBtn;
	$btnSearch = $objGrid -> strSearchBtn;
	$btnExport = $objGrid -> strExportBtn;

	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");

	#es: Definir la nueva presentación de los botones
	#en: Define new look for buttons
	$buttons = "<br style='clear:both'>
				<table border='0'>
					<tr>
						<td>{$btnAdd}</td>
						<td>{$btnSearch}</td>
						<td>{$btnExport}</td>
					</tr>
				</table>";
	
	
	#es: Mostrar lso botones
	#en: Display buttons
	echo $buttons;
	
	#es: Imprimir el grid 
	#en: Imprimir el grid
	echo $strGrid;
	
	#es: Mostrar lso botones de nuevo
	#en: Display buttons again
	echo $buttons;
?>