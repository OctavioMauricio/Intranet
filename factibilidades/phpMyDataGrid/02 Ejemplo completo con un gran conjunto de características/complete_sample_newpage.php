<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>phpMyDataGrid Sample</title>
<link type='text/css' rel='stylesheet' href='style.css' />
</head>

<body>
	<div id='ghead'>
    	<div id='glogo'>
			<a href="index.php">phpMyDataGrid Professional - Sample of use</a>
        </div>
    </div>
	<table border="0" id="bg">
	  <tr>
		<td id="content">
        	<?php
				require_once('class/phpmydatagrid.class.php');
				
				#en: Get id Parameter
				#es: Obtener el parámetro id
				$id = (isset($_GET['id']))?$_GET['id']:die('Parametter is missing');
				
				#en: Process data by using the id, in this case, just to print it
				#es: Realizar las acciones necesarias, en este caso solo imprimir la id
				echo "<p>" . $id . " has been received as a parameter, let's show the user payments</p>";
				echo "<p>Se ha recibido " . $id . " como parámetro, Vamos a mostrar los pagos</p>";

				mysql_connect("127.0.0.1", "user", "password");
				mysql_select_db("guru_sample_a");
				
				$strQuery = sprintf("select * from payment_history where emp_id=%s", magic_quote($id));
				$objData = mysql_query($strQuery);

				echo "<hr />";
				$c = 0;
				while ($row = mysql_fetch_array($objData)){
					echo "<p>";
					echo "Date: " . $row['payment_date'] . "<br />";
					echo "Amount: " . $row['amount'] . "<br />";
					echo "</p>";
					$c++;
				}
				if ($c == 0) echo "No payments found - No se han encontrado pagos";
				echo "<hr />";
			?>
            <br />
		</td>
	  </tr>
	</table>
</body>
</html>