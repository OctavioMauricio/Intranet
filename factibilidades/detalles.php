<?php
	#es: Incluir el archivo de la libreria
	#en: Include class file
	require_once('class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('detalles_new.php','2');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid -> conectadb("localhost", "tnasolut_dstudio", "P3rf3ct0.,", "tnasolut_factibilidades");
	
	#es: Defnir la relacion con la base de datos maestra y obtener el valor del id por defecto para nuevos registros
	#en: Define the relation with the master nase and get the default id for new records
	$emp_id = $objGrid -> setMasterRelation("fac");
	$objGrid-> datarows(50);
	$objGrid -> liquidTable = true;	
	$objGrid -> width = "100%";
	$objGrid -> ButtonWidth = '60';
	
	#es: Especificar la tabla de trabajo
	#en: Define Tablename
	$objGrid-> tabla ("detalle");

	#es: definir la codificación de caracteres para mostrar la página
	$objGrid -> charset = 'UTF-8';
	#es:Seleccionar set de caracteres para mysql
	$objGrid -> sqlcharset = "utf8";

	#es: Permitir Edición AJAX online
	$objGrid-> ajax('silent');
	
	#es: Definir acciones permitidas
	#en: Define allowed actions
	$objGrid-> buttons(true,true,true,true,0);
	
	#es: Decirle al datagrid que va a usar el calendario (datepicker)
	#en: Tell to phpMyDatagrid it must use the datepicker
	$objGrid-> useCalendar(true);
	
	#es: Definir la cantidad de registros a mostrar por pagina
	#en: Define amount of records to display per page
	$objGrid-> datarows(15);
	
	#es: Definir campo llave
	#en: Define keyfield
	$objGrid-> keyfield ("id");
	
	#es: Definir campo para ordenamiento
	#en: Define order field
	$objGrid-> orderby ("tipo");
	
	$titulo = "Detalle de Factibilidad";
	
	#es: Definir un título para el grid
	#en: Define a Grid Title
	$objGrid-> tituloGrid($titulo);
	
//////////////////////////////////////////////////////////////////////////
	#es: Especificar los campos a mostrar con sus respectivas caracter?sticas:
	$objGrid-> FormatColumn("fac","FAC", "40", "50", "0", "40", "left", "text", $fac); // Definir ID con valor de tabla maestra predeterminado // Define ID with default master value
	$objGrid-> FormatColumn("id","ID", "40", "50", "1", "40", "left", "text"); // Definir ID con valor de tabla maestra predeterminado // Define ID with default master value
	$objGrid -> FormatColumn("estado", "Estado",  10, 10, 0, "150", "left", "select:En Consulta:Con Factibilidad:Sin Factibilidad:Factibilidad Aprobada:Factibilidad Rechazada:En Traslado.Reemplazado","en consulta");
	$objGrid -> FormatColumn("tipo", "Tipo Conexion",  25, 25, 0, "100", "center", "select:Uso Boca de switch:Setup-Fee Boca de Switch:Enlace:Punto a Punto:Ultima Milla:Internet Pro:ADSL:VLan:IP Transit:Mb Internacional:XConnect de Terceros:U Collocate:IP Extra:Fibra Oscura", "enlace");
	$objGrid -> FormatColumn("mbps", "Mbps", 10, 10, 0, "40", "center", "number","10");
	$objGrid -> FormatColumn("proveedor", "Proveedor",  30, 30, 0, "80", "left", "select:Movistar Empresa:Movistar Mayorista:Claro:PIT Chile:Bynarya:TNA Solutions:GTD Teleductos:Entel:Level3:Cmet:IFX:Silica:Internexa:Austronet:Mi Internet","Movistar");
	//$objGrid -> FormatColumn("cod_serv", "Cod Servicio", 50, 50, 0, "500", "left");
	$objGrid -> FormatColumn("cot", "# COT", 6, 6, 0, "20", "center");
	//$objGrid -> FormatColumn("proyecto", "Proyecto Servicio",  20, 20, 0, "100", "right", "text");
	$objGrid -> FormatColumn("plazo", "plazo", 6, 6, 0, "45", "center", "interger","36");
	$objGrid -> FormatColumn("inst_costo", "Inst Costo UF", 10, 10, 0, "45", "right", "2","number");
	$objGrid -> FormatColumn("mes_costo", "Mens Costo UF", 10, 10, 0, "45", "right", "2","number");
	$objGrid -> FormatColumn("inst_venta", "Inst Venta UF", 10, 10, 0, "45", "right", "2","number");
	$objGrid -> FormatColumn("mes_venta", "Mens Venta UF", 10, 10, 0, "45", "right", "2","number");
	//$objGrid -> FormatColumn("vlan", "VLan", 20, 20, 0, "50", "right");
	//$objGrid -> FormatColumn("entronque_ip_pit_chile", "IP Entronque", 20, 20, 0, "100", "right");
	//$objGrid -> FormatColumn("entronque_ip_movistar", "IP Movistar", 20, 20, 0, "100", "right");
	//$objGrid -> FormatColumn("interred_ip", "IP Interred", 20, 20, 0, "100", "right");
	//$objGrid -> FormatColumn("interred_pit_chile", "Interred PIT Chile WAN Cliente", 20, 20, 0, "100", "right");
	//$objGrid -> FormatColumn("interred_Movistar", "Interred Movistar", 20, 20, 0, "100", "right");
	//$objGrid -> FormatColumn("interred_ip_lan", "IP LAN Interred", 20, 20, 0, "100", "right");
	$objGrid -> FormatColumn("comentarios", "Comentarios", 200, 200, 0, "500", "left");
	#es: Definir la condicion o condiciones y la clase CSS a usar si esta condición se cumple
	$objGrid -> addCellStyle ("estado", "['estado']=='con factibilidad'", "colorazul");
	$objGrid -> addCellStyle ("estado", "['estado']=='sin factibilidad'", "colorgris");
	$objGrid -> addCellStyle ("estado", "['estado']=='factibilidad aprobada'", "colorverde");
	$objGrid -> addCellStyle ("estado", "['estado']=='factibilidad rechazada'", "colorrojo");
	$objGrid -> addRowStyle ("['estado']=='sin factibilidad'", "sinfactibilidad");	
	$objGrid -> addRowStyle ("['estado']=='factibilidad rechazada'", "sinfactibilidad");	
	$objGrid -> addRowStyle ("['estado']=='factibilidad aprobada'", "activedata");	
	$objGrid -> addCellStyle ("fac", "['fac']<>'0'", "bold");

	
/////////////////////////////////////////////////////////////////////////

	#es: Instrucciones para el usuario
	#en: User instructions
	$objGrid-> fldComment("payment_date", "(YYYY-MM-DD)");
	
	#es: Crear la instruccion where
	#en: Create where statement
	$objGrid-> where("fac = '{$fac}'");
	
	#es: Pasar los parametros entre paginas
	#en: Pass parameters to inner pages
	$objGrid-> linkparam("fac=".$fac);
	
	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();
?>
