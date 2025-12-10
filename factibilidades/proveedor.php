<?php
	#es: Incluir el archivo de la libreria
	require_once('class/phpmydatagrid.class.php');
	#es: Crear el objeto contenedor
	$objGrid = new datagrid('proveedor.php','1');
	#es: Realizar la conexión con la base de datos
	$objGrid -> conectadb("localhost", "pitchi_script", "P3rf3ct0.,", "pitchi_enlaces");
	#es: Especificar la tabla de trabajo
	$objGrid-> tabla ("proveedor");
	#es: Hacer que phpMyDataGrid Genere los formularios necesarios
	$objGrid-> Form("proveedor", true);
	#es: Definir campo llave
	$objGrid-> keyfield ("id");
	#es: Definir un título para el grid
	$objGrid-> tituloGrid("Mantenedor de Provedores");
	#es: Definir campo(s) para búsquedas
	$objGrid-> searchby("proveedor, activo");
	#es: Totalizar columnas
	//$objGrid-> total("mbps,costo,venta");
	#es:Seleccionar idioma
	#es: Definir la cantidad de registros a mostrar por pagina
	$objGrid-> datarows(20);
//////////////////////////////////////////////////////////////////////////
	#es: Especificar los campos a mostrar con sus respectivas caracter?sticas:
	$objGrid-> FormatColumn("id","ID", "40", "50", "1", "100", "right", "text");
	$objGrid-> FormatColumn("proveedor","Proveedor", "13", "20", 0, "200","left");
	$objGrid-> FormatColumn("activo","Activo", "13", "1", 0, "100", "left", "check:Inactivo:Activo");
/////////////////////////////////////////////////////////////////////////
	#es:agregamos variables_generales.php
	require_once('variables_generales.php');
	#es: Por ultimo, renderizar el Grid
	$objGrid-> grid();
?>