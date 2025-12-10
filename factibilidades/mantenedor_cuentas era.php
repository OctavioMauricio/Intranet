<?php
	error_reporting(E_ALL);

	#es: Incluir el archivo de la libreria
	require_once('class/phpmydatagrid.class.php');
	#es: Crear el objeto contenedor
	$objGrid = new datagrid('cabecera.php','1');
	#es: Realizar la conexión con la base de datos
	$objGrid -> conectadb("localhost", "tnasolut_data_studio", "P3rf3ct0.,", "tnasolut_sms");
	#es: Decirle al datagrid que va a usar el calendario (datepicker)
	$objGrid-> tabla ("usuariossmstna");
	#es: titulo
	$objGrid-> tituloGrid("Mantenedor de Usuarios de SMS");
	#es: Definir campo(s) para búsquedas
	$objGrid-> searchby("estado, fac, contacto, contrato, direccion, interno, final, comentario");
	#es: Definir campo llave
	$objGrid-> keyfield ("fac");
	#es: Definir campo para ordenamiento
	$objGrid-> orderby ("fac","desc");
	#es: Definir la cantidad de registros a mostrar por pagina
	$objGrid-> datarows(1000);
	#es: Definir una altura fija para el DataGrid
	#en: Define a fixed height for the DataGrid
	$objGrid-> height="500px";
	
    if (isset($retCode)) $objGrid->retcode = $retCode;
	#es: Al tener activa la barra de botones (toolbar) hacer que el cuadro de exportacion sea desplegado en la barra
	$objGrid-> strExportInline = true;
	#es: Calcula próximo numero de factibilidad
	$next_fact = fnext_fac();
	#es: Toma la fecha de hoy
	$hoy = fhoy_str();
	
//////////////////////////////////////////////////////////////////////////
	#es: Especificar los campos a mostrar con sus respectivas caracter?sticas:
	$objGrid-> FormatColumn("id","ID", "40", "50", "2", "93", "right", "text");
	$objGrid -> FormatColumn("nombre", "Nombre", 100, 200, 0,"300", "left");
	$objGrid -> FormatColumn("apellido", "Apellido", 100, 200, 0,"300", "left");
	$objGrid -> FormatColumn("email", "Email", 100, 200, 0,"300", "left");
	$objGrid -> FormatColumn("empresa", "Empresa", 100, 200, 0,"300", "left");
	$objGrid -> FormatColumn("username", "User Name", 100, 200, 0,"300", "left");
	$objGrid -> FormatColumn("password", "Password", 100, 200, 0,"300", "left");
	$objGrid -> FormatColumn("token", "Token", 100, 200, 0,"300", "left");
	$objGrid -> FormatColumn("perfil", "Perfil",  30, 30, 0, "110", "left", "select:1_ Usuario :2_ Administrador","1");
	$objGrid -> FormatColumn("proveedor", "Proveedor",  30, 30, 0, "110", "left", "select:DISINTAR DWG2000F_DWG1000F:LYRICS_Lyrics","LYRICS");
	$objGrid -> FormatColumn("channel_bank", "Channel Bank",  30, 30, 0, "110", "left", "select:1_1:2_2:3_3:4_4:5_5:6_6:7_7","6");


	#es:agregamos variables_generales.php
	require_once('variables_generales.php');
	
	#es: Por ultimo, renderizar el Grid
	$objGrid-> grid();

	#es: Crear la funcion que procesara la informacion, siempre debe recibir $arrData=array() como parámetro
	

?>	
