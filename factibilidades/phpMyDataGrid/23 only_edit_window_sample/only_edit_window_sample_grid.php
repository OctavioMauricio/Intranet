<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('only_edit_window_sample_grid.php','1');
	
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
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactive:Active");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	
	#es: Definir la accion a realizar al hacer clic sobre el boton "Cerrar / Cancelar"
	#en: Define the action to perform when you click on the "Close / Cancel" button 
	$objGrid->actionCloseDiv= "location.href=\"../samples.php\"";
	
	#es: Si la variable ID no está definida, obtenerla del GET o del POST
	#en: if ID var is not defined, read it from GET or POST
	if (!isset($id)) $id = $objGrid->REQUEST("id");
	
	#es: Definir que es un proceso de "SOLO EDICION" se puede incluir como parametro el ID de un registro a modificar, 
	#    o se puede dejar en blanco para ADICIONAR un nuevo registro
	#en: Define which is a process of "EDITION ONLY" you can include the ID as parameter to define a record to modify, 
	#    or can be left blank to add a new record
	$objGrid -> edit($id);		// Editar Registro ID  / Edit Record ID 
	
	#es: Pasar los parametros
	#en: Pass the parameters
	$objGrid -> linkparam("id=$id");
	
	#es: Definir propiedades para edicion sin capas
	#en: Define no Layer edition
	$objGrid->nowindow=true;
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>