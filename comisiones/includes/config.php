<?PHP // config.php datos de configuraciones y generales
   // activo mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    date_default_timezone_set("America/Santiago");
    $hoy = date("d-m-Y H:i:s");
    $footer ="<footer align='center' style='background-color: white; position: absolute; bottom: 0; width: 100%; height: 25px; color: gray; font-size: 12px;'>
		&#9786; &#169;&#174;&#8482; Copyright <span id='Year'></span><b> iConTel </b>- &#9742; <a href='tel:228409988'>+56 2 2840 9988</a> - &#x1F4EC; <a href='mailto: contacto@icontel.cl?subject=Contacto desde la Intranet de iContel.'>contacto@icontel.cl</a> - &#x1F3E0; Badajoz 45, piso 17, Las Condes, Santiago, Chile. 
		<script type='text/javascript'	>
			var d = new Date(); 
			document.getElementById('Year').innerHTML = d.getFullYear();
		</script>	
	</footer>	    	
";
////// Funciones //////////////
function genera_condicion($opciones, $campo){
     if(isset($opciones)) {
        $condicion = "AND (";
        $ptr = 0;
        foreach($opciones as $opcion){
            if($ptr >0) $condicion .= " OR ";
            $condicion .= $campo . " = '".$opcion."'";
            $ptr ++;
        }    
        $condicion .= ") \n\n";
    } else $condicion = "";
    return($condicion);
}

function DbConnect($dbname){ // se conecta a base de datos y devuelve $conn
//	echo "llegue:".$dbname;
    $server   = "localhost";
    $user     = "tnasolut_data_studio";
    $password = "P3rf3ct0.,";
    // me conecto a la Base de Datos
    $conn = new mysqli($server, $user, $password);
    if ($conn->connect_error) { die("No me pude conectar a servidor localhost: " . $conn->connect_error); }
    $dummy = mysqli_set_charset ($conn, "utf8");    
    $bd_seleccionada = mysqli_select_db($conn, $dbname);
    if (!$bd_seleccionada) { die ('No se puede usar '.$dbname.' : ' . mysql_error()); }
    return($conn);
}

function recrea_base_servicios_activos(){
    $conn = DbConnect("tnasolut_sweet");
    $result = $conn->query("CALL `recurrencias_vacia`()");
    $result = $conn->query("CALL `recurrencias_insert`()");
    $conn->close(); 
    return;        
}

function busca_columna($sql){
    $datos = Array();
    $conn = DbConnect("tnasolut_sweet");
    $result = $conn->query($sql);
    if ($result->num_rows > 0)   {  
         while($row = $result->fetch_assoc()) {
            array_push($datos, $row['dato']); 
        } 
    } 
    $conn->close(); 
    return($datos);
}

function busca_categorias(){
    $sql = "CALL `searchcategories`()";
    $conn = DbConnect("tnasolut_sweet");
    $result = $conn->query($sql);
    if ($result->num_rows > 0)   {  
        $categorias = Array();
        while($row = $result->fetch_assoc()) {
            if($row['categoria'] <> "Gasto" && $row['categoria'] <> "Varios" && $row['categoria'] <> "TelevisiÃƒÂ³n" && $row['categoria'] <> "Equipo Computacional" )  
            array_push($categorias, $row['categoria']); 
        } 
    } 
    $conn->close(); 
    return($categorias);
}

 function busca_servicios(){
    $sql = "CALL `searchservices`()";
    // me conecto a la Base de Datos
    $conn = DbConnect("tnasolut_sweet");
    $result = $conn->query($sql);
    if ($result->num_rows > 0)   {  
        $servicios = Array();
        while($row = $result->fetch_assoc()) {
             array_push($servicios, $row['servicio']);
        } 
    } 
    $conn->close(); 
    return($servicios);
}

    function crea_select($datos, $name){   
        ?><table>
            <tr>
                <td align="center" >Elija <?php echo $name; ?>: </td>
            </tr>
            <tr>
                <td><select name='<?php echo $name; ?>[]'  multiple size = 11>
                    <?php for ($i = 0; $i < count($datos); $i++) { ?>
                         <option value = '<?php echo $datos[$i]; ?>'><?php echo $datos[$i]; ?></option>
                    <?php  } ?>   
                </select></td>
            </tr>
        </table>
    <?php
    }

	function recrea_base_comisiones($sql){
		$conn = DbConnect("tnasolut_sweet");
		$result = $conn->query("DROP TABLE IF EXISTS ventas_comisiones;");
		$result = $conn->query($sql);
		$conn->close(); 
        return;        
	}









?>
