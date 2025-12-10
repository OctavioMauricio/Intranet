<?php 
	require_once __DIR__ . '/session_config.php';
	$query =  $_SESSION['query'] . $_SESSION['agrupar'] . $_SESSION['orden'];
	include "config.php"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
    <?PHP include_once("../../meta_data/meta_data.html"); ?>
    <title>Servicios Activos por Cliente</title>
        <link href='style.css' rel='stylesheet' type='text/css'>
        <script src='jquery-3.3.1.min.js' type='text/javascript'></script>
        <script src='script.js' type='text/javascript'></script>
     <style type="text/css">
        table {
               border: none;
               color: #1F1D3E;
               color: black;
               font-size: 10px;
               border-collapse: collapse;
           }   
          th, td {
              padding: 4px;
              font-size: 12px;
         }
         th {
            background-color: #1F1D3E; 
            color: white;
         }
         body{
            margin:0;
            padding:0px;
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
            font-size: 10px;
            background-color: #FFFFFF;
            color: #1F1D3E;
        }
        table tbody tr:nth-child(odd) {
            background: #F6F9FA;
        }
        table tbody tr:nth-child(even) {
            background: #FFFFFF;
        }
        table thead {
          background: #444;
          color: #fff;
          font-size: 18px;
        }
        table {
          border-collapse: collapse;
        }            
    </style>
    <script type="text/javascript">
        function exportToExcel(tableId){
            let tableData = document.getElementById(tableId).outerHTML;
            tableData = tableData.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
            tableData = tableData.replace(/<input[^>]*>|<\/input>/gi, ""); //remove input params

            let a = document.createElement('a');
            a.href = `data:application/vnd.ms-excel, ${encodeURIComponent(tableData)}`
            a.download = 'Comisiones_' + getRandomNumbers() + '.xls'
            a.click()
        }
        function getRandomNumbers() {
            let dateObj = new Date()
            let dateTime = `${dateObj.getHours()}${dateObj.getMinutes()}${dateObj.getSeconds()}`

            return `${dateTime}${Math.floor((Math.random().toFixed(2)*100))}`
        }        
    </script>     
    </head>
    <body bgcolor="#FFFFFF" text="#1F1D3E" link="#E95300" >
        <div class='container'>
            <input type='hidden' id='sort' value='asc'>			
            <table id="empTable" name="empTable" width='100%' border='1' cellpadding='10'>
                <tr>
                    <th>#</span></th>
                    <th><span onclick='sortTable("coti_num");'>Coti N°</span></th>
                    <th><span onclick='sortTable("fac_num");'>Fact N°</span></th>
                    <th><span onclick='sortTable("fac_fecha");'>Fecha</span></th>
                    <th><span onclick='sortTable("fac_cliente");'>Cliente</span></th>
                    <th><span onclick='sortTable("fac_estado");'>Tipo</span></th>
                    <th><span onclick='sortTable("fac_vendedor");'>Ejecutiv@</span></th>
                    <th><span onclick='sortTable("fechacierre");'>Fecha Cierre</span></th>
                    <th><span onclick='sortTable("meta_uf");'>Meta UF</span></th>
                    <th><span onclick='sortTable("cierre_uf");'>Cierre UF</span></th>
                    <th><span onclick='sortTable("cumplimiento");'>% Cumplimiento</span></th>
                    <th><span onclick='sortTable("comision");'>% Comision</span></th>
                    <th><span onclick='sortTable("neto_uf");'>Neto UF</span></th>
                    <th><span onclick='sortTable("costo_uf");'>Costo UF</span></th>
                    <th><span onclick='sortTable("margen_uf");'>Margen UF</span></th>
                    <th><span onclick='sortTable("neto_comi_uf");'>Neto Comi</span></th>
                    <th><span onclick='sortTable("comision_uf");'>Comisión</span></th>
                    <th><span onclick='sortTable("comi_sgv_uf");'>Comi SGV</th>
               </tr>
               <?php 
$conn = DbConnect("tnasolut_sweet");
$result = mysqli_query($conn,$query);
$ptr = 0;
while($row = mysqli_fetch_array($result)){
	$ptr ++; 
	?>  
 	<tr>
		<td><?php echo $ptr; ?></td>
		<td align="center"><a target="_blank" href="<?PHP echo $row["coti_url"]; ?>"><?php echo $row["coti_num"]; ?></a></td>
		<td align="center"><a target="_blank" href="<?PHP echo $row["fac_url"]; ?>"><?php echo $row["fac_num"]; ?></a></td>
		<td align="center"><?php echo $row["fac_fecha"]; 	?></td>
		<td><?php echo $row["fac_cliente"];					?></td>
		<td align="center"><?php echo $row["fac_estado"];	?></td>
		<td><?php echo $row["fac_vendedor"]; 				?></td>
		<td align="center"><?php echo $row["fechacierre"]; 	?></td>
		<td align="center"><?php echo $row["meta_uf"]; 		?></td>
		<td align="right"><?php echo $row["cierre_uf"]; 	?></td>
		<td align="center"><?php echo $row["cumplimiento"]; ?></td>
		<td align="center"><?php echo $row["comision"]; 	?></td>
        <td align="right"><?php echo number_format($row["neto_uf"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["costo_uf"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["margen_uf"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["neto_comi_uf"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["comision_uf"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["comi_sgv_uf"], 2, ',', '.'); ?></td>
	</tr> 
<?php 
	$tot_venta    	+= $row['neto_uf'];
	$tot_costo    	+= $row['costo_uf'];
	$tot_margen    	+= $row['margen_uf'];
	$tot_neto_comi	+= $row['neto_comi_uf'];	
	$tot_comi     	+= $row['comision_uf'];
	$tot_sgv     	 += $row['comi_sgv_uf'];
} ?>
	<tr>
		<td colspan="12" align="right"><b>TOTALES</b>&nbsp;&nbsp;</td>
		<th align="right"><?php echo number_format($tot_venta, 2, ',', '.'); ?></th>
		<th align="right"><?php echo number_format($tot_costo, 2, ',', '.'); ?></th>
		<th align="right"><?php echo number_format($tot_margen, 2, ',', '.'); ?></th>
		<th align="right"><?php echo number_format($tot_neto_comi, 2, ',', '.'); ?></th>
		<th align="right"><?php echo number_format($tot_comi, 2, ',', '.'); ?></th>
		<th align="right"><?php echo number_format($tot_sgv, 2, ',', '.'); ?></th>
	</tr>
	<tr>
		<td colspan="16" align="right">
			<input type="button" onClick="exportToExcel('empTable')" value="Export to Excel" />
		</td>
	</tr>

			   ?>
            </table><br><br>
             <br><br>     
        </div>
    </body>
</html>