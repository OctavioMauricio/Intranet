<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('upload_images_sample_grid.php','1');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid -> keyfield('emp_id');

	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(5);

	#es: Definir campos del grid
	#en: Define grid fields
	$objGrid-> FormatColumn("emp_id","ID Empleado", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("active","Status", "13", "1", 0, "60", "left", "check:Inactivo:Activo");
	$objGrid-> FormatColumn("fname","Nombre", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Segundo Nombre", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Apellidos", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("photo","Photo", "25", "0","0","150","center","image:upload_images_folder/%s");	
	
	#es: Definir un tamaño standar para mostrar las imagenes en el campo "photo".
	#en: Define an standar size for images in "photo" field
	$objGrid-> setImageSize("photo",95,127);
	
    #es: Definir directorio para cargar imagenes
    #en: Define folder to upload files
	$objGrid-> uploadDirectory = 'upload_images_folder/';
    
    #es: Definir extensiones aceptadas
    #en: Define valid extensions
	$objGrid-> validImgExtensions = array("gif","jpg","jpeg","png"); /* Allowed img extensions to upload */

	#es: Definir el campo como campo de imagen que permite subir imagenes
	#en: Define field as upload type field
	$objGrid-> chField("photo","RUM");  
	
	#es: Definir Directorio de subida de imagenes
	#en: Define folder to upload images
	$objGrid-> uploadDirectory = 'upload_images_folder/';
	
	#es: Hacer que phpMyDataGrid Genere los formularios necesarios
	#en: Allow phpMyDataGrid to use and generate its own forms 
	$objGrid-> Form('employees', true);

	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");

	#es: Activar la barra de botones del datagrid
	#en: Activate the toolbar
	$objGrid-> toolbar = true;
	
	#es: Permitir mantenimiento de tablas (Adicionar/Editar/Borrar/Ver)
	#en: Allow table maintenance (Add/Edit/Delete/view)
	$objGrid-> buttons(true, true, true, true, 0);
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>