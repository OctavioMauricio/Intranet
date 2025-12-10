<?php
 error_reporting(E_ALL);
 ini_set('display_errors', '1');
	#es: Incluir el archivo de la libreria
	#en: Include class file
require_once('./compat_mysql.php');
require_once('./class/phpmydatagrid.class.php');
	
	#es: Crear el objeto contenedor
	#en: Create object container
	$objGrid = new datagrid('detalles_new.php','2');
	
	#es: Realizar la conexión con la base de datos
	#en: Connect with database
	$objGrid -> conectadb("localhost", "data_studio", "1Ngreso.,", "tnasolut_factibilidades");
	
	#es: Defnir la relacion con la base de datos maestra y obtener el valor del id por defecto para nuevos registros
	#en: Define the relation with the master nase and get the default id for new records
	$fac = $objGrid -> setMasterRelation("fac");

	$objGrid-> datarows(50);
	//$objGrid -> liquidTable = true;	
	//$objGrid -> width = "100%";
	//$objGrid -> ButtonWidth = '60';
	
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
	$objGrid-> orderby ("estado, tipo","asc");
	
	$titulo = "Detalle de Presupuestos x Proveedor ". $fac;
	
	#es: Definir un título para el grid
	#en: Define a Grid Title
	$objGrid-> tituloGrid($titulo);
$margen_mes = 0;
$margen_Unico = 0;
//////////////////////////////////////////////////////////////////////////
	#es: Especificar los campos a mostrar con sus respectivas caracter?sticas:
	$objGrid-> FormatColumn("fac","FAC", "40", "50", "2", "40", "left", "text", $fac); // Definir ID con valor de tabla maestra predeterminado // Define ID with default master value
	$objGrid-> FormatColumn("id","ID", "40", "50", "1", "40", "left", "text"); // Definir ID con valor de tabla maestra predeterminado // Define ID with default master value
	$objGrid -> FormatColumn("cot", "# COT", 15, 15, 0, "20", "center","text","0");
	$objGrid -> FormatColumn("estado", "Estado",  10, 10, 0, "100", "left", "select:En Consulta:Con Factibilidad:Sin Factibilidad:Factibilidad Aprobada:Factibilidad Rechazada:En Traslado/Reemplazado:Dado de Baja:Factibilidad Renovada","en consulta");
	$objGrid -> FormatColumn("tipo", "Tipo Conexion",  25, 25, 0, "100", "center", "select:Uso Boca de switch:Setup-Fee Boca de Switch:Equipo en Arriendo o Comodato:Enlace:Punto a Punto:Satelital Enlace:Satelital Itinerante:Satelital Kit:Satelital Adaptador Ethernet:Satelital Montaje Angular:Satelital Cable 45m:Satelital IP Publica Fija:Ultima Milla Capa 2:Ultima Milla Capa 3:Internet Pro:MPLS:ADSL:VLan:IP-Transit:Mb Internacional:XConnect de Terceros:Transporte Nacional:U Collocate:IP Extra:Fibra Oscura:la2lan:fusion Fibra:Enlace Covid-19:BAM:Enlace GSM:Enlace Radio Frecuencia:UPGrade:Consumo Interno", "enlace");
	$objGrid -> FormatColumn("mbps", "Mbps", 10, 10, 0, "40", "center", "number","10");
	$objGrid -> FormatColumn("proveedor", "Proveedor",  30, 30, 0, "80", "left", "select:Starlink:Movistar Empresa:Movistar Mayorista:Telxius:Claro:Ufinet:PIT Chile:Bynarya:TNA Solutions:GTD Teleductos:Entel:Level3:Wircom:IFX:Silica:Internexa:Austronet:Airpoint:Mi Internet:WMAx:Lidice II:Century Link:Edge Uno:Intercable Red Central","Movistar Mayorista");
	$objGrid -> FormatColumn("cod_serv", "Cod. Servicio", 200, 200, 0, "200", "left");

	$objGrid -> FormatColumn("plazo", "plazo", 6, 6, 0, "45", "center", "interger","36");
	$objGrid -> FormatColumn("inst_costo", "Costo Unico", 10, 10, 0, "45", "right", "2","number");
	$objGrid -> FormatColumn("mes_costo", "Costo Mes", 10, 10, 0, "45", "right", "2","number");
	$objGrid -> FormatColumn("inst_venta", "Venta Unico", 10, 10, 0, "45", "right", "2","number");
	$objGrid -> FormatColumn("mes_venta", "Venta Mes", 10, 10, 0, "45", "right", "2","number");
	$objGrid -> FormatColumn("margen_inst", "Margen Unico", 10, 10, 0, "45", "right", "2","number",$margen_Unico);
	$objGrid -> FormatColumn("margen_mensual", "Margen Mes", 10, 10, 0, "45", "right", "2","number",$margen_mes);


	$objGrid -> FormatColumn("datacenter", "Data Center",  30, 30, 0, "80", "left", "select:Badajoz:IFX:Magnus:Starlink:Claro:gtd lidice:Century Link","gtd lidice");


   // $objGrid -> FormatColumn("ip", "IP Asignada", 15, 15, 0, "50", "center","text","0");
//	$objGrid -> FormatColumn("vlan", "VLan Asignada", 15, 15, 0, "50", "center","text","0");

	$objGrid -> FormatColumn("comentarios", "Comentarios", 200, 200, 0, "500", "left");
	

	$objGrid -> addCellStyle ("estado", "['estado']=='con factibilidad'", "colorazul");
	$objGrid -> addCellStyle ("estado", "['estado']=='sin factibilidad'", "colorgris");
	$objGrid -> addCellStyle ("estado", "['estado']=='factibilidad aprobada'", "colorverde");
	$objGrid -> addCellStyle ("estado", "['estado']=='factibilidad renovada'", "colorverde");
	$objGrid -> addCellStyle ("estado", "['estado']=='factibilidad rechazada'", "colorrojo");
	$objGrid -> addRowStyle ("['estado']=='sin factibilidad'", "sinfactibilidad");
	$objGrid -> addRowStyle ("['estado']=='dado de baja'", "sinfactibilidad");	
	$objGrid -> addRowStyle ("['estado']=='factibilidad rechazada'", "sinfactibilidad");	
	$objGrid -> addRowStyle ("['estado']=='factibilidad aprobada'", "activedata");	
	$objGrid -> addRowStyle ("['estado']=='factibilidad renovada'", "activedata");	
	$objGrid -> addCellStyle ("fac", "['fac']<>'0'", "bold");
/////////////////////////////////////////////////////////////////////////
	$objGrid -> total('inst_costo, mes_costo, inst_venta, mes_venta, margen_inst, margen_mensual');
	#es: Instrucciones para el usuario
	#en: User instructions
	$objGrid-> fldComment("payment_date", "(YYYY-MM-DD)");
	
	#es: Crear la instruccion where
	#en: Create where statement
	$objGrid-> where("fac = '{$fac}'");
	
	#es: Pasar los parametros entre paginas
	#en: Pass parameters to inner pages
	$objGrid-> linkparam("fac=".$fac);
	
	$objGrid->processData = "valores_actuales";


	#es: Por ultimo, renderizar el Grid
	#en: Finally, render the grid.
	$objGrid-> grid();


function valores_actuales($arrData=array()){
	$arrTmpData = array();
	foreach($arrData as $key=>$row){		
		if($row['estado'] == "factibilidad aprobada" OR $row['estado'] == "con factibilidad") {
			$row['margen_inst'] =  $row['inst_venta'] - $row['inst_costo'];			
			$row['margen_mensual'] =  $row['mes_venta'] - $row['mes_costo'];			
		} else {
			$row['margen_inst'] =  0;			
			$row['margen_mensual'] =  0;			
		}
		$arrTmpData[$key] = $row;
		$query = "UPDATE `detalle` 
				 SET `margen_inst` = '{$row['margen_inst']}',
				     `margen_mensual` = '{$row['margen_mensual']}'	
					 WHERE `id` = {$row['id']}";		
		$tmp = actualiza_datos($query );
	}
	#es: Siempre se debe retornar un array con la nueva informacion
	return $arrTmpData;
}


function actualiza_datos($sql) {
	   $dbhost = 'localhost';
	   $dbuser = 'tnasolut_factibilidades';
	   $dbpass = 'P3rf3ct0.,';
	   $conn = new mysqli("localhost", "data_studio", "1Ngreso.,", "tnasolut_factibilidades");
		if (mysqli_connect_errno()) { die('Error de conexión: ' . $mysqli->connect_error); }   mysqli_set_charset($conn, 'utf8');
	   $retval = mysqli_query($conn, $sql);
	   if(! $retval ) { die('Error al actualizar Query: '.$sql."error Nº" . mysqli_error($conn));}
		mysqli_close($conn);
}






?>