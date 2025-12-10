<?PHP // include que muestra datos en tabla.php y fech_details.php
include_once('config.php');
$conn = DbConnect("tnasolut_sweet");
$query = "SELECT 
	    fac_vendedor,
	    Recurrente,
	    Unica,
	    Total,
	    SGV
	FROM (
	    SELECT 
        fac_vendedor,
        SUM(CASE WHEN fac_estado != 'Única' THEN comision_uf ELSE 0 END) AS Recurrente,
        SUM(CASE WHEN fac_estado = 'Única' THEN comision_uf ELSE 0 END) AS Unica,
        SUM(comision_uf) AS Total,
        SUM(comi_sgv_uf) AS SGV
    	FROM ventas_comisiones as vc
   	    WHERE fac_fecha BETWEEN '".$_SESSION['desde'] ."' AND '".$_SESSION['hasta'] ."' ".$_SESSION['vendedores']." GROUP BY fac_vendedor
	) AS subconsulta
UNION ALL
	SELECT 
	    '__Totales',
	    SUM(CASE WHEN fac_estado != 'Única' THEN comision_uf ELSE 0 END),
	    SUM(CASE WHEN fac_estado = 'Única' THEN comision_uf ELSE 0 END),
	    SUM(comision_uf),
	    SUM(comi_sgv_uf)
	FROM ventas_comisiones as vc
   	WHERE fac_fecha BETWEEN '".$_SESSION['desde'] ."' AND '".$_SESSION['hasta'] ."' ".$_SESSION['vendedores']." ORDER BY fac_vendedor; ";
$result = mysqli_query($conn,$query);
$ptr = 0;
$tot_sgv = 0;
while($row = mysqli_fetch_array($result)){
	$ptr ++; 
	?>
  
	<tr>
		<td><?php echo $ptr;     					    	?></td>
		<td><?php echo $row["fac_vendedor"]; 				?></td>
        <td align="right"><?php echo number_format($row["Recurrente"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["Unica"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["Total"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["SGV"], 2, ',', '.'); ?></td>
 	</tr> 
<?php 
/*    
	$tot_venta    	+= $row['neto_uf'];
	$tot_costo    	+= $row['costo_uf'];
	$tot_margen    	+= $row['margen_uf'];
	$tot_neto_comi	+= $row['neto_comi_uf'];	
	$tot_comi     	+= $row['comision_uf'];
	$tot_sgv     	+= $row['comi_sgv_uf'];
*/
} ?>
	<!--tr>
		<td colspan="2" align="right"><b>TOTALES</b>&nbsp;&nbsp;</td>
		<th align="right"><?php echo number_format($tot_venta, 2, ',', '.'); ?></th>
		<th align="right"><?php echo number_format($tot_costo, 2, ',', '.'); ?></th>
		<th align="right"><?php echo number_format($tot_margen, 2, ',', '.'); ?></th>
		<th align="right"><?php echo number_format($tot_neto_comi, 2, ',', '.'); ?></th>
		<th align="right"><?php echo number_format($tot_comi, 2, ',', '.'); ?></th>
		<th align="right"><?php echo number_format($tot_sgv, 2, ',', '.'); ?></th>
	</tr-->
	<tr>
		<td colspan="16" align="right">
			<input type="button" onClick="exportToExcel('empTable')" value="Export to Excel" />
		</td>
	</tr>

