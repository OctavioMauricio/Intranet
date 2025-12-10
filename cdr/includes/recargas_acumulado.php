<?php // muestra linea recarga y acumula (debe ser un include_once(includes/recargas_acumulador.php))

				echo "<tr class='".$class."'>";

					echo "<td style='width: 20px' ' align='right'>".$contador."</td>";				
					echo "<td style='width: 500px'><div align=\"left\" style=\"align-content: left; text-align: left\">".$cliente."</div></td>";			
					echo "<td>".$rut."</td>";				
					echo "<td>".date('d/m/Y',strtotime($desde))."</td>";				
					echo "<td>".date('d/m/Y',strtotime($hasta))."</td>";				
					echo "<td align='right'>".number_format($llamadas, 0)."</td>";				
					echo "<td align='right'>".number_format($consumo, 2)."</td>";				
					echo "<td align='right'>".number_format($total, 0)."</td>";	
					echo "<td align='right'>".number_format($plan, 0)."</td>";	
					echo "<td align='right'>".number_format($planvalor, 0)."</td>";	
					echo "<td align='right'>".number_format($recargas, 0)."</td>";	
					echo "<td align='right'>".number_format($valorrecargas, 0)."</td>";
					if($plan == 1) echo "/B>";
				echo "</tr>";
				$totalrecargas += $valorrecargas;
				$totalllamadas += $llamadas;
				$totalminutos  += $consumo;
				$totalvalor	   += $total;
				$totalplanmin  += $plan;					
				$totalplanval  += $planvalor;
				$cantrecargas  += $recargas;
?>