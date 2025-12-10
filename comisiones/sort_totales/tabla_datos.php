<?PHP // include que muestra datos en tabla.php y fech_details.php
$conn = DbConnect("tnasolut_sweet");
echo "esta es la query:".$query;
$result = mysqli_query($conn,$query);
$ptr = 0;
$tot_sgv = 0;
while($row = mysqli_fetch_array($result)){
	$ptr ++; 
	?>
  
	<tr>
		<td><?php echo $ptr;     					    	?></td>
		<td><?php echo $row["fac_vendedor"]; 				?></td>
		<!--td align="center"><?php echo $row["fac_estado"];	?></td-->
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
	$tot_sgv     	+= $row['comi_sgv_uf'];
} ?>
	<tr>
		<td colspan="2" align="right"><b>TOTALES</b>&nbsp;&nbsp;</td>
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

