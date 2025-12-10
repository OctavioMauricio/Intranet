<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('complete_sample_grid.php','1');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	#es: Especificar ordenamiento
	#en: Define order field
	$objGrid-> orderby("fname");
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("emp_id");
	
	#es: Definir campo(s) para búsquedas
	#en: Define search field(s)
	$objGrid-> searchby("fname, lname");

	#es: Decirle al datagrid que va a usar el calendario (datepicker)
	#en: Tell to phpMyDatagrid it must use the datepicker
	$objGrid-> useCalendar(true);
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);
	
	#es: Presentar solo 5 registros por pagina
	#en: Show 5 records per page
	$objGrid-> datarows(5);
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("image_1","View dates", "25", "0", "4","20","center","imagelink:images/calendar.gif:citas(%s),emp_id");
	$objGrid-> FormatColumn("active","", "25", "0", "3","40","center","imagelink:favorites_sample_images/star%s.gif:favorite(%s\\,this),active");
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "93", "left", "text");
	$objGrid-> FormatColumn("fname","Name", "13", "20", 0, "95","left");
	$objGrid-> FormatColumn("minit","Middle Name", "5", "1", 0, "80", "left");
	$objGrid-> FormatColumn("lname","Last Name", "5", "30", 0, "95", "left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "5", "10", "0", "105", "left", "date:ymd:-");
	$objGrid-> FormatColumn("job_id","Job Name", "5", "30", 1, "195", "left", "related:select job_desc from jobs where job_id=%s", "2");
	$objGrid-> FormatColumn("hire_date","Hire Date", "5", "19", "2", "195", "left", "datetime:mdy:-:His,:");
	$objGrid-> FormatColumn("salary","Salary", "5", "30", 0, "95", "right", "2");
	$objGrid-> FormatColumn("days","Days", "5", "30", 0, "95", "right", "integer");
	$objGrid-> FormatColumn("total_salary","Value Days", "10", "10", "4","120","right","scalc:((salary/30)*days)//money:$");		
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
	$objGrid -> chField("photo","RUM");  
	
	#es: Indicarle al datagrid que usará la característica de menú contextual, indicando la ruta hacia la classe
	#en: Tell to phpMyDataGrid that contextual menus will be available, we must indicate the class path
	$objGrid-> useRightClickMenu("class/phpMyMenu.inc.php");
	
	#es: Definir un pie de página para el grid
	#en: Define a dataGrid Footer
	$objGrid-> FooterGrid("You can right click over a record for options - This function is not available in Opera Browser");
	
	#es: Totalizar columnas
	#en: Totalize columns
	$objGrid-> total("salary,days,total_salary");

	#es: Eliminar las flechas de ordenamiento del campo active
	#en: Remove ordering arrows for "active" field
	$objGrid-> chField("active","R");
	
	#es: Definir el campo como campo de imagen que permite subir imagenes
	#en: Define field as upload type field
	$objGrid-> chField("photo","RUM");  
	
	#es: Definir que el campo sea exportable
	#en: Define field to be exported
	$objGrid-> chField("hire_date","X+");  
	
	#es: Interceptar el llamado AJAX y validar si es "fa" (tal como se definio en el script), si es asi, procesar el campo de imagen
	#en: Intercept the AJAX request and validate if the value is "fa" (as defined in the script), if so, process the image field
	if ($objGrid->isAjaxRequest() and $objGrid->getAjaxID()=='fa'){
		$objGrid->changeImage();
	}
	
	#es: Definir Directorio de subida de imagenes
	#en: Define folder to upload images
	$objGrid-> uploadDirectory = 'upload_images_folder/';
	
	#es: Hacer que phpMyDataGrid Genere los formularios necesarios
	#en: Allow phpMyDataGrid to use and generate its own forms 
	$objGrid-> Form('employees', true);
	
	#es: Validaremos que el nombre del empleado tenga mas de dos caracteres
	#en: Validate employee name to be length than 2 characters
	$objGrid-> jsValidate("fname", "this.value.length>=2", "Employee name must be longer than 2 characters", "Please write the employee name (3 chars Min)");
	
	#es: Si no queremos validar la entrada de datos del usuario, pero queremos mostrar un mensaje indicador al usuario
	#en: If we do not want to validate the user input, but want to display an indicator message to user
	$objGrid-> fldComment("birth_date", "Write the employee bith date or pick one from calendar");
	
	#es: Definir opciones de exportacion
	#en: Define exporting options
	$objGrid-> export(true,true,true,true,false);
	
	#es: Definir el tipo de paginacion
	#en: Define pagination mode
	$objGrid-> paginationmode ("input");
	
	#es: Generar una barra de botones
	#en: Add a toolbar
	$objGrid-> toolbar = true;
	
	#es: Al tener activa la barra de botones (toolbar) hacer que el cuadro de búsqueda y de exportación sea desplegado en la barra
	#en: As we have the toolbar active, allow that the search and export options be linked to the toolbar
	$objGrid-> strSearchInline = true;
	$objGrid-> strExportInline = true;
	
	#es: Adicionar un boton de Actualización en la barra de herramientas
	#en: Add a reload button to the toolbar
	$objGrid-> reload = true;
	
	#es: Permitir Edición AJAX online
	#en: Allow AJAX online edition
	$objGrid-> ajax('silent');
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>