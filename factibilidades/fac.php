<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>

<?php
	function fnext_fac() { // recupera la última fac utilizada y le suma 1
		// Crea conexion a mlysq

		$conn = new mysqli('localhost', 'tnasolut_data_studio', 'P3rf3ct0.,', 'tnasolut_factibilidades');
		// Checkea conexion
		if ($conn->connect_error) die("Error de Conexion: " . $conn->connect_error);
		echo $sql = "SELECT * FROM `enlaces_cabecera` ORDER BY `enlaces_cabecera`.`fac` DESC LIMIT 1";
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
echo $tmp=	fnext_fac();
	
	
?>	



</body>
</html>