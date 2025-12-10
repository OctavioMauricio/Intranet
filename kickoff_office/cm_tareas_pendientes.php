        <?php
            $conn = DbConnect("tnaoffice_suitecrm");
            $sql = "CALL Kick_Off_Operaciones_Tareas_Abiertas('".$sg_id."')"; 
            $resultado = $conn->query($sql);
            $datos =[];
            $ptr=0;
			$muestra =  false; //$resultado->num_rows;
            $contenido = "";
            $url_caso = "https://sweet.tnaoffice.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DCases%26action%3DDetailView%26record%3D";     
            $url_opor = "https://sweet.tnaoffice.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DOpportunities%26action%3DDetailView%26record%3D";  
            $url_cuenta = "https://sweet.tnaoffice.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DAccounts%26action%3DDetailView%26record%3D";
            $url_insidente = "https://sweet.tnaoffice.cl/index.php?module=Bugs&action=DetailView&record=";
            if ($resultado && $resultado->num_rows > 0) {  
                $muestra = true;
                while ($lin = $resultado->fetch_assoc()) { $datos[] = $lin;}
                // --- Ordenar por fecha de vencimiento ASC ---
                usort($datos, function($a, $b) {
                    $fa = strtotime(str_replace('/', '-', $a['f_vencimiento']));
                    $fb = strtotime(str_replace('/', '-', $b['f_vencimiento']));
                    // Si alguna fecha no es válida, la empuja al final
                    if ($fa === false) return 1;
                    if ($fb === false) return -1;
                    return $fa <=> $fb;
                });  
                // Recorrer array ya ordenado
                foreach ($datos as $lin) {
				  $style = ' style="color: orange;" ';
                  $ptr ++; 
                   $importancia = $lin["prioridad"];
                   if ($lin["dias"] > 10) {
                       $importancia = "4 Baja";
                   }
                  switch ($importancia){    
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
                  $contenido .= '<td><a target="_blank" href="'.$lin["url"].'">'.$lin["tarea"].'</a></td>';
                 // $contenido .= "<td>".$lin["categoria"]."</td>";
                  $contenido .= "<td>".$lin["prioridad"]."</td>";
                  $contenido .= "<td>".$lin["usuario"]."</td>";                    
                  $contenido .= "<td>".$lin["estado"]."</td>";
               //   $contenido .= "<td>".$lin["enesperade"]."</td>";
                  switch ($lin["origen"]){ 
                  case "Cases":
                        $url ="";
                        $contenido .= "<td>CASO</td>";
                        $contenido .= '<td align="center"><a target="_blank" href="'.$url_caso.$lin["origen_id"].'">'.$lin["numero"].'</a></td>';                        
                        break;
                  case "Opportunities":
                        $contenido .= "<td>OPORTUNIDAD</td>";
                         $contenido .= '<td align="center"><a target="_blank" href="'.$url_opor.$lin["origen_id"].'">'.$lin["numero"].'</a></td>';                        
                       break;
                  case "Accounts":
                        $contenido .= "<td>CUENTA</td>";
                         $contenido .= '<td align="center"><a target="_blank" href="'.$url_cuenta.$lin["origen_id"].'">'.$lin["numero"].'</a></td>';                        
                        break;
                  case "Bugs":
                        $contenido .= "<td>Insidente</td>";
                         $contenido .= '<td align="center"><a target="_blank" href="'.$url_insidente.$lin["origen_id"].'">'.$lin["numero"].'</a></td>';                        
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
            } 
            else 
            {
              $style = "";
              $contenido = "<tr><td colspan='11'>No se encontraron Tareas pendientes.</td></tr>";
            }
            $conn->close(); 
            unset($resultado);
            unset($conn);
			$td = '<td colspan="10" align="left" valign="bottom" class="titulo" >&nbsp;&nbsp;⏳ Tareas Abiertas ASIGNADAS A USTED</td>
			<td colspan="1" align="right" valign="bottom" style="font-size: 20px; color: white; background-color: #512554;"><a href="'.$url_nueva_tarea.'" target="new" title="Crea Nuerva Tarea"><b>+<b></a>&nbsp;&nbsp;&nbsp;</td>';
        ?>
        <table id="tareas" align="center" width="100%">
            <tr align="center">			
              <?PHP echo $td; ?>
            </tr>
            <tr align="left">
                <th class="subtitulo" width="2%"> # </th>
                <th class="subtitulo" width="20%">Asunto</th>
                <!--th class="subtitulo" width="6%">Categoria</th-->
                <th class="subtitulo" width="6%">Prioridad</th>
                <th class="subtitulo" width="8%">Asignado a</th>                    
                <th class="subtitulo" width="5%">Estado</th>
                <!--th class="subtitulo" width="25%">En Espera de</th-->
                <th class="subtitulo" width="7%">Origen Tipo</th>
                <th class="subtitulo" width="5%">Nº</th>
                <th class="subtitulo" width="5%">Fecha Crea.</th>
                <th class="subtitulo" width="5%">Fecha Modif.</th>
                <th class="subtitulo" width="5%">Fecha Vencim.</th>
				<th class="subtitulo" width="3%"  align="right">Días</th>
			</tr>
             <?PHP echo $contenido; ?>
        </table> 
		<div><button style="color: #512554;" onclick="capa('tareas')">Tareas Pendientes [Muestra/Oculta <?PHP echo $ptr; ?>]</button></div>
		<?PHP if(!$muestra) echo "<script>capa('tareas');</script>"; ?>
