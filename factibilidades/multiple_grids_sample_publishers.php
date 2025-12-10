<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('multiple_grids_sample_publishers.php','2');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid -> conectadb("localhost", "tnasolut_data_studio", "P3rf3ct0.,", "tnasolut_factibilidades");	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("cabecera");
    $sql = "SELECT * FROM `cabecera` WHERE SUBSTRING(`estado`,1,2) = 2 ";
  	$objGrid-> sqlstatement ($sql);
		$objGrid-> tituloGrid("Factibilidades Cotizadas o por Cotizar");
	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(1000);
	$objGrid-> height="950px";
	#es: Definir acciones permitidas
	#en: Define allowed actions
	//$objGrid-> buttons(true,true,true,true,0);
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("pub_id");
	
	#es: Definir campo para ordenamiento
	#en: Define order field
	$objGrid-> searchby("estado, fac, nombre, contacto, contrato, direccion, comentario");
	#es: definir la codificación de caracteres para mostrar la página
	$objGrid -> charset = 'UTF-8';
	#es:Seleccionar set de caracteres para mysql
	$objGrid -> sqlcharset = "utf8";
	#es: Definir campo llave
	$objGrid-> keyfield ("fac");
  	$objGrid-> sqlstatement ($sql);
	#es: Definir campo para ordenamiento
	$objGrid-> orderby ("estado","asc");


//////////////////////////////////////////////////////////////////////////
	#es: Especificar los campos a mostrar con sus respectivas caracter?sticas:
//	$objGrid-> FormatColumn("id","ID", "40", "50", "2", "93", "right", "text");
	$objGrid -> FormatColumn("fac", "FAC", 6, 6, 0, "20", "center", "number",$next_fact);
	$objGrid -> FormatColumn("estado", "Estado",  40, 30, 0, "100", "left", "select:0 sin solicitar:1 solicitado:2 cotizado proveedor:2 cotizado cliente:3 aprobado por cliente:4 solicitar instalacion:5 instalacion:6 en provision:7 en produccion:8 dado de baja:9 sin factibilidad:10 rechazada cliente:11 cliente rechazado:12 trasladado","0 sin solicitar");
	$objGrid -> FormatColumn("nombre", "Nombre Completo", 150, 150, 0, "180", "left");
	$objGrid -> FormatColumn("direccion", "Direccion", 80, 80, 0,"180", "left");
	$objGrid -> FormatColumn("ancho_banda", "Ancho de Banda", 100, 200, 0,"100", "right");
	$objGrid -> FormatColumn("solicitud", "Fecha solicitud",  10, 10, 0, "80", "center", "date:dmyy:-",$hoy);
	$objGrid -> FormatColumn("dias","Dias Habiles", "13", "20", 0, "30","center");
	$objGrid -> addRowStyle ("['estado']=='0 sin solicitar'", "fondoazul");	
	$objGrid -> addRowStyle ("['estado']=='2 cotizado cliente'", "activedata");	
	$objGrid -> addRowStyle ("['estado']=='5 instalacion'", "miss_date");	
	$objGrid -> addRowStyle ("['estado']=='10 rechazada cliente'", "sinfactibilidad");	
	$objGrid -> addRowStyle ("['estado']=='9 sin factibilidad'", "sinfactibilidad");	
	$objGrid -> addRowStyle ("['estado']=='9 sin factibilidad'", "inactivedata");	
	$objGrid -> addCellStyle ("dias", "['dias']<='30'", "colorverde");
	$objGrid -> addCellStyle ("dias", "['dias']>'30'", "colorrojo");
	$objGrid -> addCellStyle ("dias_inst", "['dias_inst']<='30'", "colorverde");
	$objGrid -> addCellStyle ("dias_inst", "['dias_inst']>'30'", "colorrojo");
/////////////////////////////////////////////////////////////////////////

	
	#es: Generar una barra de botones
	#en: Add a toolbar
	//$objGrid-> toolbar = true;
	
	#es: Definir el tipo de paginacion
	#en: Define pagination mode
//	$objGrid-> paginationmode ("input");
	
	#es: Permitir Edición AJAX online
	#en: Allow AJAX online edition
	$objGrid-> ajax('silent');
	$objGrid->processData = "fDias";
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();


?>