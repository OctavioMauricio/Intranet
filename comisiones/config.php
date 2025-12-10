<?php  
		// error_reporting(E_ALL);
		// ini_set('display_errors', '1');
	function DbConnect($dbname){ // se conecta a base de datos y devuelve $conn
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
	function busca_columna($sql, $columna){
		$datos = Array();
		$conn = DbConnect("tnasolut_sweet");
		$result = $conn->query($sql);
		if ($result->num_rows > 0)   {  
			 while($row = $result->fetch_assoc()) {
				array_push($datos, $row[$columna]); 
			} 
		} 
		$conn->close(); 
		return($datos);
	}
	function crea_select($datos, $name){  
		?>
		<table>
			<tr>
				<td align="center" >Elija <?php echo $name; ?>: </td>
			</tr>
			<tr>
				<td><select name='<?php echo $name; ?>[]'  multiple size = 10>
					<?php for ($i = 0; $i < count($datos); $i++) { ?>
						 <option value = '<?php echo $datos[$i]; ?>'>&nbsp;<?php echo $datos[$i]; ?> &nbsp;</option>
					<?php  } ?>   
				</select></td>
			</tr>
		</table><?php
	}
	$vendedores		= busca_columna("CALL `ventas_vendedores_all`()","vendedor"); 	
	$tipos_factura	= busca_columna("CALL `ventas_tipo_factura_all`()","tipo_factura"); 	
?>
