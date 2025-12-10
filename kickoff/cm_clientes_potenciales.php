        <?php
            $conn = DbConnect($db_sweet);
            $sql = "CALL Clientes_Potenciales_Pendientes()";       
            $resultado = $conn->query($sql);
            $ptr=0;
            $contenido = "";
			$muestra = $resultado->num_rows;
            if($resultado->num_rows > 0)  { 
                while($lin = $resultado->fetch_assoc()) {
				  $style = ' style="color: orange;" ';
                  $ptr ++; 
				  if ($lin["dias"] >0 ) {
						$contenido .= '<tr style="color: red" >';					
				  } else {
						switch ($lin["estado"]){    
						  case "1 Nuevo":
							$contenido .= '<tr style="color: red" >';
							break;
						  case "2 Asignado":
							$contenido .= '<tr style="color: orange" >';
							break;
						  case "3 En Proceso":
							$contenido .= '<tr style="color: green" >';
							break;
						  case "4 Retomar en 3 meses":
							$contenido .= '<tr style="color: green" >';
							break;
						  default:
							$contenido .= '<tr>';                     
						  }   				
				  }	
                  $contenido .= "<td>".$ptr."</td>";
                  $contenido .= '<td colspan="2"><a target="_blank" href="'.$lin["url_lead"].'">'.$lin["nombre"].'</a></td>';
                  // $contenido .= "<td>".$lin["cuenta"]."</td>";
                  $contenido .= "<td>".$lin["estado"]."</td>";
                  $contenido .= "<td>".$lin["campana"]."</td>";
                  $contenido .= "<td>".$lin["usuario"]."</td>";
                  $contenido .= "<td>".$lin["f_creacion"]."</td>";
                  $contenido .= "<td>".$lin["f_prox_paso"]."</td>";
                  $contenido .= "<td align='right'>".$lin["dias"]."&nbsp;&nbsp;</td>";
                  $contenido .= "</tr>";
                }
            } else {
			  $style = "";
              $contenido = "<tr><td colspan='8'>No se encontraron Clientes Potenciales pendientes.</td></tr>";
            }
            $conn->close(); 
            unset($resultado);
            unset($conn);
			$td = '<td colspan="8" align="left" valign="bottom" class="titulo" >&nbsp;&nbsp;🧲 Clientes Potenciales en Proceso</td>
				  <td colspan="1" align="right" valign="bottom" style="font-size: 20px;" class="titulo" ><a 
				  href="'.$url_nuevo_lead.'" target="new" title="Lista Cliente Potenciales">+</a>&nbsp;&nbsp;&nbsp;</td>';
        ?>
 
<table id="clientes_potenciales" align="center" width="100%">
            <tr align="center" class="subtitulo"><?PHP echo $td; ?></tr>
	<tr align="left" <?PHP echo $style_titulo; ?></tr>
				<th class="subtitulo"> # </th>
				<th class="subtitulo" width="25%" colspan="2">Nombre</th>                    
				<!--th class="subtitulo" width="20%">Cuenta</th-->                
				<th class="subtitulo" width="10%">Estado</th>
				<th class="subtitulo" >Campaña</th>
				<th class="subtitulo" width="11%">Asignado a</th>
				<th class="subtitulo" width="10%">Fecha Creación</th>
				<th class="subtitulo" width="10%">F Prox. Paso</th>
				<th class="subtitulo" width="1%"  align="right">Días&nbsp;&nbsp;</th>
			</tr>
             <?PHP echo $contenido; ?>
        </table>   
		<div><button style="color: #512554; border: none" onclick="capa('clientes_potenciales')">Clientes Potenciales [Muestra/Oculta <?PHP echo $ptr; ?>]</button></div>
		<?PHP if(!$muestra) echo "<script>capa('clientes_potenciales');</script>"; ?>