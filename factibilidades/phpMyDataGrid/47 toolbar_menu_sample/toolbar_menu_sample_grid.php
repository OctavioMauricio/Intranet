<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('toolbar_menu_sample_grid.php','1');
	
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
	$objGrid-> saveaddnew = true;
	
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
	
	#es: Adicionar un separador a los botones
	#en: Add a separator for buttons
	$objGrid-> addSeparator();
	
	#Importante. Definir el uso de righclickmenu antes de definir las opciones
	$objGrid-> useRightClickMenu("class/phpMyMenu.inc.php");
	
	# Definir el menu
	$objGrid->objMenu->addmenu("add", 180, 22, 0, 1, 1, '#c0c0c0', '#fff', '#ddd', '', 'toolbar_menu_sample/bck-menuitems.gif' );
	
	# Definir las opciones del menu
	$objGrid->objMenu->additem("add", "Employee", "javascript:DG_addrow();","toolbar_menu_sample/user.png");
	$objGrid->objMenu->additem("add", "Payment", "javascript:AddPayments();","toolbar_menu_sample/money.png");
	$objGrid->objMenu->addSeparator("add");
	$objGrid->objMenu->additem("add", "Job", "javascript:AddJobs();","toolbar_menu_sample/tools.png");
	
	# Antes de adicionar el boton, asignarle el menu que se le creó
	$objGrid-> setButtonOption("add","", "left");
	
	#es: Definir un ID para identificar el nuevo botón
	#en: Define the ID for the new Button
	$objGrid-> idbtn = "add";
	
	#es: Adicionar un boton a la Barra de botones
	#en: Add a button to the toolbar
	$objGrid-> addButton("toolbar_menu_sample/add.gif", "", "New");
	
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
	$objGrid-> buttons(false,true,true,true,0, "Actions");
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	$objGrid-> FormatColumn("active","", "25", "0", "0","40","center","imagelink:favorites_sample_images/star%s.gif:favorite(%s\\,this),active");
	
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
			case 'favorite': 
				$objGrid->changeImage(); 
			break;
		}
	}
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>