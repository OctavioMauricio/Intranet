        <?php
            $conn = DbConnect($db_sweet);
            $sql = "CALL Kick_Off_Operaciones_Tareas_Abiertas('".$sg_id."')"; 
            $resultado = $conn->query($sql);
            $ptr=0;
            $contenido = "";
            $url_caso = "https://sweet.icontel.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DCases%26action%3DDetailView%26record%3D";      
            $url_opor = "https://sweet.icontel.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DOpportunities%26action%3DDetailView%26record%3D";  
            $url_cuenta = "https://sweet.icontel.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DAccounts%26action%3DDetailView%26record%3D";
            $url_insidente = "https://sweet.icontel.cl/index.php?module=Bugs&action=DetailView&record=";
            if($resultado->num_rows > 0)  { 
                while($lin = $resultado->fetch_assoc()) {
                  $ptr ++; 
                  switch ($lin["prioridad"]){    
                  case "1 URGENTE ESCALADO":
                    $contenido .= '<tr style="color: red" >';
                    break;
                  case "2 URGENTE":
                    $contenido .= '<tr style="color: orangered" >';
                    break;
                 case "3 Alta":
                    $contenido .= '<tr style="color: orange" >';
                    break;
                  case "4 Baja":
                    $contenido .= '<tr style="color: green" >';
                    break;
                  default:
                    $contenido .= '<tr>';                     
                  }   
                  $contenido .= "<td>".$ptr."</td>";
                  $contenido .= "<td>".$lin["prioridad"]."</td>";
                  $contenido .= "<td>".$lin["usuario"]."</td>";                    
                  $contenido .= "<td>".$lin["estado"]."</td>";
                  $contenido .= "<td>".$lin["categoria"]."</td>";
                  $contenido .= '<td><a target="_blank" href="'.$lin["url"].'">'.$lin{"tarea"}.'</a></td>';
                  switch ($lin["origen"]){ 
                  case "Cases":
                        $url ="";
                        $contenido .= "<td>CASO</td>";
                        $contenido .= '<td align="center"><a target="_blank" href="'.$url_caso.$lin["origen_id"].'">'.$lin{"numero"}.'</a></td>';                        
                        break;
                  case "Opportunities":
                        $contenido .= "<td>OPORTUNIDAD</td>";
                         $contenido .= '<td align="center"><a target="_blank" href="'.$url_opor.$lin["origen_id"].'">'.$lin{"numero"}.'</a></td>';                        
                       break;
                  case "Accounts":
                        $contenido .= "<td>CUENTA</td>";
                         $contenido .= '<td align="center"><a target="_blank" href="'.$url_cuenta.$lin["origen_id"].'">'.$lin{"numero"}.'</a></td>';                        
                        break;
                  case "Bugs":
                        $contenido .= "<td>Insidente</td>";
                         $contenido .= '<td align="center"><a target="_blank" href="'.$url_insidente.$lin["origen_id"].'">'.$lin{"numero"}.'</a></td>';                        
                        break;
                  default:
                        $contenido .= "<td> ".$lin["origen"]." </td>";
                        $contenido .= "<td> </td>";
                  }   
                  $contenido .= "<td>".$lin["f_creacion"]."</td>";
                  $contenido .= "<td>".$lin["f_modifica"]."</td>";
                  $contenido .= "<td>".$lin["f_vencimiento"]."</td>";
                  $contenido .= "<td align='right'>".$lin["dias"]."&nbsp;</td>";
                  $contenido .= "</tr>";
                }
            } else {
              $contenido = "<tr><td colspan='9'>No se encontraron Tareas pendientes.</td></tr>";
            }
            $conn->close(); 
            unset($resultado);
            unset($conn);
			$td = '<td colspan=45" align="right" valign="bottom"><h2>'.$sg_name.': <a href="https://intranet.icontel.cl/kickoff/tareas/index.php?sg_id='.$sg_id.'" target="new" title="Lista Tareas Pendientes">Tareas</a> Abiertas</h2></td>';
        ?>
        <table align="center" width="100%">
            <tr align="center" style="color: white;background-color: #1F1D3E;">
			  <td colspan="8" align="left"><?PHP include_once("../tareas/busca_hori.php");?></td>
				
              <?PHP echo $td; ?>
            </tr>
            <tr align="left">
                <th width="2%"> # </th>
                <th width="10%">Prioridad</th>
                <th width="11%">Asignado a</th>                    
                <th width="7%">Estado</th>
                <th width="7%">Categoria</th>
                <th width="40">Asunto</th>
                <th width="7%">Origen Tipo</th>
                <th width="5%">Origen NÂº</th>
                <th width="5%">Fecha CreaciÃ³n</th>
                <th width="5%">Fecha ModificaciÃ³n</th>
                <th width="5%">Fecha Vencimiento</th>
				<th width="5%"  align="right">DÃ­as<br>Restantes</th>
			</tr>
             <?PHP echo $contenido; ?>
        </table>   
<button onclick="capa('casos_congelados')">Casos Abiertos [Muestra/Oculta]</button>