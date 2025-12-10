<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
//echo "entre:";
    #es: Incluir el archivo de la libreria
    require_once('./compat_mysql.php');
    require_once('./class/phpmydatagrid.class.php');
//	require_once('class/phpmydatagrid_php81.class.php');
	#es: Crear el objeto contenedor
	$objGrid = new datagrid('cabecera_new.php','1');
	#es: Realizar la conexión con la base de datos
    //	$objGrid -> conectadb("localhost", "data_studio", "1Ngr3s0.,", "tnasolut_factibilidades");
    $objGrid -> conectadb("172.16.10.15", "data_studio", "1Ngr3s0.,", "tnasolut_factibilidades");
    $objGrid-> setDetailsGrid("detalles_new.php", "fac");
	$objGrid -> friendlyHTML();
	
    
//	$objGrid -> liquidTable = true;	
//	$objGrid -> width = "100%";
	$objGrid -> ButtonWidth = '30';
	
	#es: Decirle al datagrid que va a usar el calendario (datepicker)
	$objGrid-> tabla ("cabecera");
	#es: titulo
	$objGrid-> tituloGrid("Mantenedor de Factibilidades de TNA Solutions");
	#es: Definir campo(s) para búsquedas
	$objGrid-> searchby("tipo, estado, ejecutivo, fac, ancho_banda, nombre, contacto, contrato, direccion, comentario");
	#es: definir la codificación de caracteres para mostrar la página
	$objGrid -> charset = 'UTF-8';
	#es:Seleccionar set de caracteres para mysql
	$objGrid -> sqlcharset = "utf8";
	#es: Definir campo llave
	$objGrid-> keyfield ("fac");
	#es: Definir campo para ordenamiento
	$objGrid-> orderby ("fac","desc");
	#es: Definir la cantidad de registros a mostrar por pagina
	$objGrid-> datarows(25);
	#es: Definir una altura fija para el DataGrid
	#en: Define a fixed height for the DataGrid
	//$objGrid-> height="500px";
	#es: Al tener activa la barra de botones (toolbar) hacer que el cuadro de exportacion sea desplegado en la barra
	$objGrid-> strExportInline = true;
	#es: Define una altura fija para el Grid sin importar cuantos registros contenga
	//$objGrid -> height = '570';

	#es: Calcula próximo numero de factibilidad
	$next_fact = fnext_fac();
	#es: Toma la fecha de hoy
	$hoy = fhoy_str();
	$dias = 1;
	// llena $fac como parametro pasado por la url
	$fac = $_GET["fac"];
	// Se evalúa si $fac está definida y se cambia la query
	if (isset($fac)) {
		$sql = "SELECT * FROM `cabecera` WHERE `fac` LIKE '%".$fac."%' ";
		$objGrid-> sqlstatement ($sql);
	}

//////////////////////////////////////////////////////////////////////////
	#es: Especificar los campos a mostrar con sus respectivas caracter?sticas:
//	$objGrid-> FormatColumn("id","ID", "40", "50", "2", "93", "right", "text");
	$objGrid -> FormatColumn("fac", "FAC", 6, 6, 0, "20", "center", "number",$next_fact);
	$objGrid -> FormatColumn("sweet_op", "OP Sweet", 40, 40, 0, "40", "center");	
	$objGrid -> FormatColumn("tipo", "Tipo Servicio",  40, 30, 0, "80", "center", "select:Enlace: Upgrade: Satelital","enlace");
    $objGrid -> FormatColumn("ejecutivo", "Ejecutiv@", 40, 40, 0, "40", "center", "select:VTA:DAM:MAM:MAO:GRC:MRB:NDB:RMT:SWM","GRC");
	//$objGrid -> FormatColumn("sam", "# REF", 40, 40, 0, "40", "left");
    $objGrid -> FormatColumn("estado", "Estado",  40, 30, 0, "150", "left", "select:00 sin solicitar:01 solicitado:02 cotizado proveedor:02 cotizado cliente:03 en seguimiento:03 En espera por cliente:03 En Formalizacion:03 aprobado por cliente:04 solicitar instalacion:05 instalacion:05 Inst espera Cliente:05 Renovacion:06 en provision:07 en produccion:08 dado de baja:09 sin factibilidad:10 rechazada cliente:11 cliente rechazado:12 trasladado:12 Reemplazada","00 sin solicitar");
	$objGrid -> FormatColumn("rut", "R.U.T.", 150, 150, 0,"100", "left");	
	$objGrid -> FormatColumn("nombre", "Nombre Completo", 150, 150, 0, "150", "left");
	$objGrid -> FormatColumn("direccion", "Direccion", 80, 80, 0,"400", "left");
	$objGrid -> FormatColumn("contacto", "Contacto", 100, 200, 0,"300", "left");	
	$objGrid -> FormatColumn("ancho_banda", "Ancho de Banda", 100, 200, 0,"100", "right");
	$objGrid -> FormatColumn("solicitud", "Fecha solicitud",  10, 10, 0, "80", "center", "date:dmyy:-",$hoy);
	$objGrid -> FormatColumn("aprobacion", "Fecha aprobacion",  10, 10, 0, "80", "center", "date:dmyy:-");
	$objGrid -> FormatColumn("compromiso_cliente", "Fecha Comprometida",  10, 10, 0, "80", "center", "date:dmyy:-");
	$objGrid -> FormatColumn("instalacion", "Fecha Instalacion",  10, 10, 0, "80", "center", "date:dmyy:-");
	$objGrid -> FormatColumn("dias","Dias Habiles", "13", "20", 0, "30","center",$dias);
	$objGrid -> FormatColumn("comentario", "Comentarios", 200, 200, 0, "300", "left");
	#es: Definir la condicion o condiciones y la clase CSS a usar si esta condición se cumple
	//$objGrid -> addCellStyle ("fac", "['fac']<>'0'", "bold");
	$objGrid -> addCellStyle ("estado", "['estado']=='00 sin solicitar'", "bold");
	$objGrid -> addCellStyle ("estado", "['estado']=='01 solicitado'", "colornegro");
	$objGrid -> addCellStyle ("estado", "['estado']=='02 cotizado cliente'", "colorazul");
	$objGrid -> addCellStyle ("estado", "['estado']=='03 aprobado por cliente'", "bold");
	$objGrid -> addCellStyle ("estado", "['estado']=='04 solicitar instalacion'", "bold");
	$objGrid -> addCellStyle ("estado", "['estado']=='05 instalacion'", "colornaranjo");	
	$objGrid -> addCellStyle ("estado", "['estado']=='05 instalacion'", "bold");
	$objGrid -> addCellStyle ("estado", "['estado']=='05 renovacion'", "colornaranjo");	
	$objGrid -> addCellStyle ("estado", "['estado']=='05 renovacion'", "bold");
	$objGrid -> addCellStyle ("estado", "['estado']=='05 Inst espera Cliente'", "colorazul");	
    $objGrid -> addCellStyle ("estado", "['estado']=='05 Inst espera Cliente'", "bold");
	$objGrid -> addCellStyle ("estado", "['estado']=='06 en provision'", "colormagenta");
	$objGrid -> addCellStyle ("estado", "['estado']=='07 en produccion'", "colorverde");
	$objGrid -> addCellStyle ("estado", "['estado']=='09 sin factibilidad'", "colorgris");
	$objGrid -> addCellStyle ("estado", "['estado']=='10 rechazada cliente'", "colorgris");
	$objGrid -> addRowStyle ("['estado']=='00 sin solicitar'", "bold");	
	$objGrid -> addRowStyle ("['estado']=='02 cotizado cliente'", "activedata");	
	$objGrid -> addRowStyle ("['estado']=='03 aprobado por cliente'", "colorrojo");	
	$objGrid -> addRowStyle ("['estado']=='04 solicitar instalacion'", "colorrojo");	
	$objGrid -> addRowStyle ("['estado']=='05 instalacion'", "colornaranjo");		
	$objGrid -> addRowStyle ("['estado']=='05 renovacion'", "colornaranjo");		
	//$objGrid -> addRowStyle ("['estado']=='05 Inst espera Cliente'", "colornazul");	
	//$objGrid -> addRowStyle ("['estado']=='05 instalacion'", "bold");	
	$objGrid -> addRowStyle ("['estado']=='02 cotizado proveedor'", "cotizadoproveedor");	
	$objGrid -> addRowStyle ("['estado']=='03 en espera por cliente'", "esperaxcliente");	
	$objGrid -> addRowStyle ("['estado']=='03 aprobado por cliente'", "aprobadoporcliente");	
	$objGrid -> addRowStyle ("['estado']=='05 inst espera cliente'", "instalacionenespera");	
	$objGrid -> addRowStyle ("['estado']=='10 rechazada cliente'", "sinfactibilidad");	
	$objGrid -> addRowStyle ("['estado']=='09 sin factibilidad'", "sinfactibilidad");	
	$objGrid -> addRowStyle ("['estado']=='09 sin factibilidad'", "inactivedata");	
	$objGrid -> addCellStyle ("dias", "['dias']<='30'", "colorverde");
	$objGrid -> addCellStyle ("dias", "['dias']>'30'", "colorrojo");
	$objGrid -> addCellStyle ("dias_inst", "['dias_inst']<='30'", "colorverde");
	$objGrid -> addCellStyle ("dias_inst", "['dias_inst']>'30'", "colorrojo");
/////////////////////////////////////////////////////////////////////////
	#es: Definir el nombre de la función para pre-procesar la información de la tabla antes de ser mostrada -
	#    Tenga en cuenta que esta función se debe usar principalmente para VISUALIZAR informacion y es poco 
	#    recomendado usarla en procesos de mantenimiento
	#en: Define the function name for pre-processing information from the table before being displayed
	# 	 Note that this function should be used mainly to display information and is NOT recommended for use 
	# 	 with maintenance processes
	$objGrid->processData = "fDias";

	#es:agregamos variables_generales.php
	require_once('variables_generales.php');
	
	#es: Por ultimo, renderizar el Grid
	$objGrid-> grid();

	#es: Crear la funcion que procesara la informacion, siempre debe recibir $arrData=array() como parámetro
	function fDias($arrData=array()){
		
		$hoy = fhoy_str();

		foreach($arrData as $key=>$row){
			$row['dias']      = "";
			$row['dias_inst'] = "";
			$status = $row['estado'];
			switch ($status) {
			case (($status > 4) && ($status <7)) :	//en instalación y provision
				#es: Preparar el nuevo valor del campo
				list($year,$mes,$dia) = explode("-",$row['aprobacion']);
				$fecha_inicial = $dia."-".$mes."-".$year;
				$fdias =  Evalua(DiasHabiles($fecha_inicial, $hoy));
				if ($fdias < 1) $fdias ="";
				#es: Guardar el nuevo valor en el campo
				$row['dias'] = $fdias;
				#es: Almacenar los datos del registro en un array temporal
				break;
			case ($status =7):    // en producción
				#es: Preparar el nuevo valor del campo
				list($year,$mes,$dia) = explode("-",$row['aprobacion']);
				$fecha_inicial = $dia."-".$mes."-".$year;
				list($year,$mes,$dia) = explode("-",$row['instalacion']);
				$fecha_final = $dia."-".$mes."-".$year;
				$fdias =  Evalua(DiasHabiles($fecha_inicial,$fecha_final));
				if ($fdias < 1) $fdias="";
				#es: Guardar el nuevo valor en el campo
				$row['dias'] = $fdias;
				#es: Almacenar los datos del registro en un array temporal
				break;
			}
		$arrTmpData[$key] = $row;
		}
		#es: Siempre se debe retornar un array con la nueva informacion
		return $arrTmpData;
	}
	
//1.- Pasar la fecha inicial y final a maketime y obtener un arreglo con todas los días intermedios. 
function DiasHabiles($fecha_inicial, $fecha_final) {
    // Explota las fechas inicial y final
    list($dia, $mes, $year) = explode("-", $fecha_inicial);
    $ini = mktime(0, 0, 0, (int)$mes, (int)$dia, (int)$year);

    list($diaf, $mesf, $yearf) = explode("-", $fecha_final);
    $fin = mktime(0, 0, 0, (int)$mesf, (int)$diaf, (int)$yearf);

    if ($ini > mktime(0, 0, 0, 1, 1, 2016) && $fin > mktime(0, 0, 0, 1, 1, 2016)) {
        $r = 0;
        $newArray = [];

        while ($ini <= $fin) {
            $newArray[] = $ini;
            // Suma un día (en segundos)
            $ini = mktime(0, 0, 0, (int)$mes, (int)($dia + ++$r), (int)$year);
        }
        return $newArray;
    } else {
        return "0";
    }
}
function fhoy_str() { // convierte hoy en str
		$date=new DateTime(); //fecha hora actual
		$fecha_str = $date->format('d-m-Y');
		$tmp = explode('-',$fecha_str);
		$fecha_str = implode("-",$tmp);
	    return $fecha_str;	
	} 
//2.- Una función que evalué el arreglo de fechas obtenido, que contenga los feriados nacionales que correspondan (restando) y que reste los sábados y domingos. 
	function Evalua($arreglo){
		$feriados        = array(
		'1-1',  //  Año Nuevo (irrenunciable)  
		'10-4',  //  Viernes Santo (feriado religioso)  
		'11-4',  //  Sábado Santo (feriado religioso)  
		'1-5',  //  Día Nacional del Trabajo (irrenunciable)  
		'21-5',  //  Día de las Glorias Navales  
		'27-6',  //  San Pedro y San Pablo (feriado religioso)  
		'16-7',  //  Virgen del Carmen (feriado religioso)  
		'15-8',  //  Asunción de la Virgen (feriado religioso)  
		'18-9',  //  Día de la Independencia (irrenunciable)  
		'19-9',  //  Día de las Glorias del Ejército  
		'12-10',  //  Aniversario del Descubrimiento de América  
		'31-10',  //  Día Nacional de las Iglesias Evangélicas y Protestantes (feriado religioso)  
		'1-11',  //  Día de Todos los Santos (feriado religioso)  
		'2-11',  //  Día de Todos los Santos (feriado religioso )  
		'8-12',  //  Inmaculada Concepción de la Virgen (feriado religioso)  
		'25-12',  //  Natividad del Señor (feriado religioso) (irrenunciable)  
		);
		$j= count($arreglo);
		for($i=0;$i<=$j;$i++){
			$dia     = $arreglo[$i];
            if (is_numeric($dia)) {
                $fecha = getdate((int)$dia);
            } else {
                continue; // o manejo alternativo
            }
			$feriado = $fecha['mday']."-".$fecha['mon'];
			if($fecha["wday"]==0 or $fecha["wday"]==6){
				$dia_ ++;
			} elseif(in_array($feriado,$feriados)){
				$dia_++;
			}
		}
		$rlt = $j - $dia_;
		return $rlt;
	}
	function fnext_fac() { // recupera la última fac utilizada y le suma 1
		// Crea conexion a mlysq

//		$conn = new mysqli("localhost", "data_studio", "1Ngr3s0.,", "tnasolut_factibilidades");
		$conn = new mysqli("172.16.10.15", "data_studio", "1Ngr3s0.,", "tnasolut_factibilidades");
		// Checkea conexion
		if ($conn->connect_error) die("Error de Conexion: " . $conn->connect_error);
		$sql = "SELECT * FROM `cabecera` ORDER BY `fac` DESC LIMIT 1";
		$result = mysqli_query($conn, $sql);
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$fnext_fac = $row['fac'];
			}
		}
		mysqli_free_result($result);
		$conn->close();	
		return ($fnext_fac + 1);
	}
	
	function f_estado() {

		#es: Realizar la conexión con la base de datos
		#en: Connect with database
		$tmpObj -> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
		$strSQL = "select job_id, job_desc from jobs order by job_desc";
		$arrData = $tmpObj -> SQL_query($strSQL);
		echo "<select id='job_id' name='job_id' style='width:150px'>";
		echo "<option value=''>(Select job to search)</option>";
		foreach ($arrData as $jobs){
			echo "<option value='" . $jobs['job_id'] . "'>" . $jobs['job_desc'] . "</option>";
		}
		echo "</select>";
		unset($tmpObj);		
	}
	
	

?>	
