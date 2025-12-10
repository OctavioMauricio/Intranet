<?php
	error_reporting(0);

	#es: Incluir el archivo de la libreria
	require_once('class/phpmydatagrid.class.php');
	#es: Crear el objeto contenedor
	$objGrid = new datagrid('cabecera.php','1');
	#es: Realizar la conexión con la base de datos
	$objGrid -> conectadb("localhost", "tnasolut_data_studio", "P3rf3ct0.,", "tnasolut_factibilidades");
	#es: Decirle al datagrid que va a usar el calendario (datepicker)
	$objGrid-> tabla ("cabecera");
	#es: titulo
	$titulo = "<span id='titulo'>
				Factibilidades Solicitadas <span id='fecha_hora'>".date("d/m/Y h:i:s")."  Refresh: ".
		      " <span id='timer' style='color:blue'30:00</span></span></span>";
	$objGrid-> tituloGrid($titulo);
	#es: Definir campo(s) para búsquedas
	$objGrid-> searchby("estado, fac, nombre, contacto, contrato, direccion, comentario");
	#es: definir la codificación de caracteres para mostrar la página
	$objGrid -> charset = 'UTF-8';
	#es:Seleccionar set de caracteres para mysql
	$objGrid -> sqlcharset = "utf8";
	#es: Definir campo llave
	$objGrid-> keyfield ("fac");
    $sql = "SELECT * FROM `cabecera` WHERE SUBSTRING(`estado`,1,2) < 2 ";
  	$objGrid-> sqlstatement ($sql);
	#es: Definir campo para ordenamiento
	$objGrid-> orderby ("estado","asc");
	#es: Definir la cantidad de registros a mostrar por pagina
	$objGrid-> datarows(1000);
	#es: Definir una altura fija para el DataGrid
	#en: Define a fixed height for the DataGrid
	$objGrid-> height="950px";
	
	// Inicialmente le decimos que queremos que la exportación HTML interactue con otro archivo y a la vez definimos el archivo que procesara la salida de datos.
//	$objGrid->exportMagma = "_magma_exporta.php";
	
	// Definimos los datos de cada una de las opciones de detalle a imprimir
	// los sigguientes parametros:
	// sql: este key contiene el SQL necesario para mostrar la salida detalle,
	// parameters: la lista de parametros usados anteriormente de la tabla maestra, separados por coma
	// menu: Define el valor que mostrará en el menu desplegable al usuario
	$objGrid->exportDetails['detalles'] = array("sql"=>"SELECT * FROM `detalle` WHERE fac = '['fac']' ORDER BY `tipo` ASC",
										"parameters"=>"fac",
										"menu"=>"detalles");	
	/* Por ultimo realizamos un pequeño Hack para lograr que al momento de exportar el grid no nos genere código, en cambio nos devuelva los datos en arrays */
    if (isset($retCode)) $objGrid->retcode = $retCode;
	#es: Al tener activa la barra de botones (toolbar) hacer que el cuadro de exportacion sea desplegado en la barra
	$objGrid-> strExportInline = true;
	#es: Calcula próximo numero de factibilidad
	$next_fact = fnext_fac();
	#es: Toma la fecha de hoy
	$hoy = fhoy_str();
	
//////////////////////////////////////////////////////////////////////////
	#es: Especificar los campos a mostrar con sus respectivas caracter?sticas:
//	$objGrid-> FormatColumn("id","ID", "40", "50", "2", "93", "right", "text");
	$objGrid -> FormatColumn("fac", "FAC", 6, 6, 0, "20", "center", "number",$next_fact);
	$objGrid -> FormatColumn("estado", "Estado",  40, 30, 0, "80", "left", "select:00 sin solicitar:01 solicitado:02 cotizado proveedor:02 cotizado cliente:03 aprobado por cliente:04 solicitar instalacion:05 instalacion:06 en provision:07 en produccion:08 dado de baja:09 sin factibilidad:10 rechazada cliente:11 cliente rechazado:12 trasladado","00 sin solicitar");
	//$objGrid -> FormatColumn("rut", "R.U.T.", 20, 20, 0,"80", "left");	
	$objGrid -> FormatColumn("nombre", "Nombre Completo", 150, 150, 0, "200", "left");
	$objGrid -> FormatColumn("direccion", "Direccion", 80, 80, 0,"280", "left");
	//&$objGrid -> FormatColumn("contacto", "Contacto", 100, 200, 0,"180", "left");	
	$objGrid -> FormatColumn("ancho_banda", "Ancho de Banda", 100, 200, 0,"100", "right");

	$objGrid -> FormatColumn("solicitud", "Fecha solicitud",  10, 10, 0, "80", "center", "date:dmyy:-",$hoy);
	//$objGrid -> FormatColumn("aprobacion", "Fecha aprobacion",  10, 10, 0, "80", "center", "date:dmyy:-");
	//$objGrid -> FormatColumn("instalacion", "Fecha Instalacion",  10, 10, 0, "80", "center", "date:dmyy:-");
	$objGrid -> FormatColumn("dias","Dias Habiles", "13", "20", 0, "30","center");
	//$objGrid -> FormatColumn("comentario", "Comentarios", 200, 200, 0, "300", "left");
	#es: Definir la condicion o condiciones y la clase CSS a usar si esta condición se cumple
	//$objGrid -> addCellStyle ("estado", "['estado']=='0'", "bold");

	$objGrid -> addCellStyle ("estado", "['estado']=='01 solicitado'", "colornegro");
	$objGrid -> addCellStyle ("estado", "['estado']=='02 cotizado cliente'", "colorazul");
	$objGrid -> addCellStyle ("estado", "['estado']=='05 instalacion'", "colorrojo");
	$objGrid -> addCellStyle ("estado", "['estado']=='06 en provision'", "colormagenta");
	$objGrid -> addCellStyle ("estado", "['estado']=='07 en produccion'", "colorverde");
	$objGrid -> addCellStyle ("estado", "['estado']=='09 sin factibilidad'", "colorgris");
	$objGrid -> addCellStyle ("estado", "['estado']=='10 rechazada cliente'", "colorgris");
	$objGrid -> addRowStyle ("['estado']=='00 sin solicitar'", "colorrojo");	
	$objGrid -> addRowStyle ("['estado']=='00 sin solicitar'", "fondoazul");	
	$objGrid -> addRowStyle ("['estado']=='02 cotizado cliente'", "activedata");	
	$objGrid -> addRowStyle ("['estado']=='05 instalacion'", "miss_date");	
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
	//require_once('variables_generales.php');
	
	#es: Por ultimo, renderizar el Grid
	$objGrid-> grid();

	#es: Crear la funcion que procesara la informacion, siempre debe recibir $arrData=array() como parámetro
	function fDias($arrData=array()){
		$hoy = fhoy_str();

		foreach($arrData as $key=>$row){
			$row['dias']      = "";
			$row['dias_inst'] = "";
			$status = substr($row['estado'],1,2);
			switch ($status) {
			case ($status < 3)  :	//Antes de aceptado por cliente
				#es: Preparar el nuevo valor del campo
				list($year,$mes,$dia) = explode("-",$row['solicitud']);
				$fecha_inicial = $dia."-".$mes."-".$year;
				$fdias =  Evalua(DiasHabiles($fecha_inicial, $hoy));
				if ($fdias < 1) $fdias ="";
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
	function DiasHabiles($fecha_inicial,$fecha_final){
		list($year,$mes,$dia) = explode("-",$fecha_inicial);
		$ini = mktime(0, 0, 0, $dia , $mes, $year);
		list($year,$mes,$dia) = explode("-",$fecha_final);
		$fin = mktime(0, 0, 0, $dia , $mes, $year);
		if ( ($ini > mktime(0, 0, 0, "01" , "01", "2016")) && ($fin > mktime(0, 0, 0, "01" , "01", "2016")) ){
			list($dia,$mes,$year) = explode("-",$fecha_inicial);
			$ini = mktime(0, 0, 0, $mes , $dia, $year);
			list($diaf,$mesf,$yearf) = explode("-",$fecha_final);
			$fin = mktime(0, 0, 0, $mesf , $diaf, $yearf);
			$r = 1;
			while($ini <= $fin){
				$ini = mktime(0, 0, 0, $mes , $dia+$r, $year);
				$newArray[] .=$ini;
				$r++;
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
			$fecha   = getdate($dia);
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

		$conn = new mysqli("localhost", "tnasolut_data_studio", "P3rf3ct0.,", "tnasolut_factibilidades");
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
