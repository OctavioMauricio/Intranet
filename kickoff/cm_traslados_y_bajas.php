        <?php
			$conn = DbConnect($db_sweet);
            $sql = "CALL CM_Cotizaciones_baja_traslado()";       
            $resultado = $conn->query($sql);
            $ptr=0;
            $contenido = "";
 			$muestra = $resultado->num_rows;
            if($resultado->num_rows > 0)  { 
                while($lin = $resultado->fetch_assoc()) {
				  $style = ' style="color: orange;" ';					
                  $ptr ++; 
                  switch ($lin["coti_estado"]){    
                  case "SUSPENDIDO":
                    $contenido .= '<tr style="color: red" >';
                    break;
                  case "Posible Traslado":
                    $contenido .= '<tr style="color: orangered" >';
                    break;
                  case "Posible Traslado":
                    $contenido .= '<tr style="color: orangered" >';
                    break;
                  case "Generar Baja":
                    $contenido .= '<tr style="color: orange" >';
                    break;
                  case "Cotizar":
                    $contenido .= '<tr style="color: green" >';
                    break;
                  default:
                    $contenido .= '<tr>';                     
                  }   
                  $contenido .= "<td>".$ptr."</td>";
                  $contenido .= '<td><a target="_blank" href="'.$lin["url_coti"].'">'.$lin["coti_numero"].'</a></td>';
                  $contenido .= "<td>".$lin["coti_nombre"]."</td>";
                  $contenido .= "<td>".$lin["coti_estado"]."</td>";
                  $contenido .= "<td>".$lin["asignado"]."</td>";					
                  $contenido .= "<td>".$lin["coti_ejecutiva"]."</td>";					
                  $contenido .= "<td>".$lin["coti_moneda"]."</td>";					
                  $contenido .= "<td align='right'>".number_format($lin["coti_neto"],2)."</td>";
                  $contenido .= '<td><a target="_blank" href="'.$lin["url_opor"].'">'.$lin["opor_numero"].'</a></td>';
                  $contenido .= "<td>".$lin["cliente"]."</td>";
                  $contenido .= "<td>".date("d/m/Y",strtotime($lin["coti_fecha_u_m"]))."</td>";					
                  $contenido .= "<td align='right'>".$lin["dias"]."&nbsp;&nbsp;</td>";
                  $contenido .= "</tr>";
                }
            } else {
			  $style = "";
              $contenido = "<tr><td colspan='12'>No se encontraron Oportunidades pendientes.</td></tr>";
            }
            $conn->close(); 
            unset($resultado);
            unset($conn);
			$td = '<td colspan="11" align="left" valign="top" class="titulo" >&nbsp;&nbsp;⚠️ Cotizaciones de Baja o Traslado</td>
			<td colspan="1" align="right" valign="top" style="font-size: 20px; background-color: #512554;"><a href="'.$url_nueva_cotizacion.'" target="new">+</a>&nbsp;&nbsp;&nbsp;</td>';
        ?>
        <table id="cotizaciones" align="center" width="100%">
            <tr align="left" class="subtitulo">
 			   <?PHP echo $td; ?>
            </tr>
            <tr align="left">
                <th class="subtitulo" width="1%"> # </th>
                <th class="subtitulo" width="2%">Nº</th>
                <th class="subtitulo" width="20%">Asunto</th>                    
                <th class="subtitulo" width="4%">Estado</th>
                <th class="subtitulo" width="8%">Asignado a</th>
                <th class="subtitulo" width="8%">Ejecutiv@</th>
                <th class="subtitulo" width="1%">$</th>
                <th class="subtitulo" width="1%">Neto</th>               
				<th class="subtitulo" width="2%">OP Nº</th>               
				<th class="subtitulo" width="15%">Cliente</th>               
                <th class="subtitulo" width="1%">Modificada</th>                               
				<th class="subtitulo" width="1%"  align="right">Días</th>
            </tr>
             <?PHP echo $contenido; ?>
        </table>  
		<div><button style="color: #512554; border: none" onclick="capa('cotizaciones')">Traslados o Bajas [Muestra/Oculta <?PHP echo $ptr; ?>]</button></div>
		<?PHP if(!$muestra) echo "<script>capa('oportunidades');</script>"; ?>