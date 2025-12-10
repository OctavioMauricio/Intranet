        <?php
            $conn = DbConnect($db_sweet);
            $sql = "CALL CM_Ordenes_de_Compra_Pendientes()";       
            $resultado = $conn->query($sql);
            $ptr=0;
            $contenido = "";
 			$muestra = $resultado->num_rows;
            if($resultado->num_rows > 0)  { 
                while($lin = $resultado->fetch_assoc()) {
                 $ptr ++; 
    			// $style = ' style="color: orange;" ';	
	 			switch ($lin["coti_estado_factura"]){    
                  case "DTE Pagado":
					if($lin["opor_estado"] == "Facturado / Cerrado") {
						$contenido .= '<tr style="color: orangered" >';
					} else {
						$contenido .= '<tr style="color: orange" >';
					}
                    break;
                  default:
                    $contenido .= '<tr>';                     
                  }  
                  $contenido .= "<td>".$ptr."</td>";
                  $contenido .= "<td>".$lin["coti_numero"]."</td>";
                  $contenido .= '<td colspan="2"><a target="_blank" href="'.$lin["url_coti"].'">'.$lin["coti_titulo"].'</a></td>';
                  $contenido .= "<td>".$lin["coti_estado_factura"]."</td>";					
                  $contenido .= "<td>".$lin["numero_dte"]."</td>";					
                  $contenido .= "<td align='right'>".$lin["moneda"]."</td>";	
				  if($lin["moneda"]=="$") {
                 		$contenido .= "<td align='right'>".number_format($lin["coti_neto"],0)."</td>";
				  } else {
				        $contenido .= "<td align='right'>".number_format($lin["coti_neto"],2)."</td>";
				  }
                  $contenido .= '<td align="right"><a target="_blank" href="'.$lin["url_opor"].'">'.$lin["opor_numero"].'</a></td>';
                  $contenido .= "<td>".$lin["op_nombre"]."</td>";					
                  $contenido .= "<td>".$lin["opor_estado"]."</td>";				
                  $contenido .= "<td>".$lin["account"]."</td>";
                  $contenido .= "</tr>";
                }
            } else {
			  $style = "";
              $contenido = "<tr><td colspan='9'>No se encontraron Oportunidades pendientes.</td></tr>";
            }
            $conn->close(); 
            unset($resultado);
            unset($conn);
			$td = '<td colspan="11" align="left" valign="top" class="titulo" >&nbsp;&nbsp;🧾 Ordenes de Compra Pendientes</td>
			<td align="right" style="font-size: 20px;"><a href="'.$url_nueva_cotizacion.'" target="new"><b>+<B></a>&nbsp;&nbsp;&nbsp;';

        ?>
        <table id="Ordenes_de_Compra" align="center" width="100%">
            <tr align="left" style="color: white;background-color: #512554;">
			   <?PHP echo $td; ?>
            </tr>
            <tr align="left" >
                <th class="subtitulo" width="1%"> # </th>
                <th class="subtitulo" width="1%">N°</th>
                <th class="subtitulo" width="8%" colspan="2">Asunto</th>
                <th class="subtitulo" width="2%">Estado</th>
                <th class="subtitulo" width="2%">Nº DTE</th>
                <th class="subtitulo" width="1%" align="center">$</th>
                <th class="subtitulo" width="1%" align="center">Bruto</th>
                <th class="subtitulo" width="1%" align="right">OP #</th>
                <th class="subtitulo" width="8%">OP Nombre</th>
                <th class="subtitulo" width="2%">OP Etapa/Estado</th>
                <th class="subtitulo" width="8%">Proveedor</th>               
            </tr>
             <?PHP echo $contenido; ?>
        </table>  
		<div><button style="color: #512554; border: none" onclick="capa('Ordenes_de_Compra')">Ordenes de Compra [Muestra/Oculta <?PHP echo $ptr; ?>]</button></div>
		<?PHP if(!$muestra) echo "<script>capa('Ordenes_de_Compra');</script>"; ?>