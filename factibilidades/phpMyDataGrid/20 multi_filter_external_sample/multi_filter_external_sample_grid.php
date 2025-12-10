<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('multi_filter_external_sample_grid.php',1);
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid-> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("employee");
	
	/*********************************** Set filter based on requested parameters ***********************************/
	/********************************* Crear el filtro basandose en los parametros **********************************/
	
	#es: Obtener los datos del formulario
	#en: Obtain data from FORM 
	$fname = getvar("fname", "fnameajax");  // function getvar is found balow... 
	$lname = getvar("lname", "lnameajax");
	$bdate = getvar("bdate", "bdateajax");
	$job_id = getvar("job_id", "jobidajax");
	
	#es: Definir cada filtro y almacenarlo en un elemento de un array
	#en: Define each filter and store it in an array element
	$conditions = array();

	if (!empty($fname)) $conditions[] = sprintf(" (fname = %s)", $objGrid -> magic_quote($fname));
	if (!empty($lname)) $conditions[] = sprintf(" (lname = %s)", $objGrid -> magic_quote($lname));
	if (!empty($bdate)) $conditions[] = sprintf(" (birth_date = %s)", $objGrid -> magic_quote($bdate));
	if (!empty($job_id)) $conditions[] = sprintf(" (job_id = %s)", $objGrid -> magic_quote($job_id));

	#es: Unir todas las condiciones usando el conector AND
	#en: Join all conditions using AND
	$condition = implode(" AND ", $conditions);

	#es: Adicionar la condicion al Where
	#en: Add condition to grid WHERE
	$objGrid -> where($condition);
	
	#es: Crear los parametros en el grid para que esten disponibles en los llamados AJAX
	#en: Set parameters to be available for AJAX requests
	$objGrid -> linkparam("&fnameajax={$fname}&lnameajax={$lname}&jobidajax={$job_id}");
	
	/****************************** Fin del ejemplo *********************************/
    /********************************* End sample ***********************************/
 
    #es: Función a invocar cuando se pulse el botón buscar
    #en: Function to invoke when search button is pressed
    $objGrid -> srconClic = "activateSearchBox()";  

    #es: Habilitar la presentación con barra de herramientas
    #en: Enable toolbar
	$objGrid -> toolbar=true;
    
    #es: Habilitar el botón para recargar el grid
    #en: enable button to reload grid 
	$objGrid-> reload = true;
    
    #es: Definir los campos por los cuales se realizará la búsqueda (en este caso solo es necesario para que aparezca el botón de búsqueda)
    #en: Define search fields (In this case we need this just to make the search button visible)
	$objGrid-> searchby("emp_id");
    
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid -> keyfield('emp_id');
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid -> buttons(true, true, true, true, -1);
	
	#es: Definir opciones de exportacion
	#en: Define exporting options
	$objGrid -> export(true, true, true, true, false);
	
	#es: Especificar los campos a mostrar con sus respectivas características:
	#en: Specify each field to display with their own properties
	$objGrid-> FormatColumn("emp_id","ID", "40", "50", "1", "33", "left", "text");
	$objGrid-> FormatColumn("active","Activo", "5", "12", 0, "60", "left", "check:Active:Inactive");
	$objGrid-> FormatColumn("fname","First name", "13", "255", 0, "180","left");
	$objGrid-> FormatColumn("lname","Last Name", "13", "255", 0, "180","left");
	$objGrid-> FormatColumn("birth_date","Birth Date", "10", "255", 0, "130","left");
	$objGrid-> FormatColumn("job_id","Job Name", "5", "30", 1, "195", "left", "related:select job_desc from jobs where job_id=%s", "2");
	
	#es: Permitir Edición AJAX online
	#en: Allow AJAX online edition
	$objGrid-> ajax('silent');
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();

	# es: Funcion auxiliar para obtener la informacion para el multifiltro
	# en: Helper function to obtain the data for multifilter
	function getvar($var1, $var2, $default=""){
		if ((isset($_GET['top_search'])?$_GET['top_search']:(isset($_POST['top_search'])?$_POST['top_search']:$default))==1){
			return addslashes((isset($_POST[$var1]) and !empty($_POST[$var1]))?$_POST[$var1]:((isset($_GET[$var1]) and !empty($_GET[$var1]))?$_GET[$var1]:$default));
		}else{
			return addslashes((isset($_POST[$var2]) and !empty($_POST[$var2]))?$_POST[$var2]:((isset($_GET[$var2]) and !empty($_GET[$var2]))?$_GET[$var2]:$default));
		}
	}

?>