        <?php
 			$url_nuevo_caso = "https://sweet.icontel.cl/index.php?module=Cases&action=EditView";
            $conn = DbConnect($db_sweet);
            $sql = "CALL Kick_Off_Operaciones_Abiertos_sujeto_a_cobro()";                        
            $result = $conn->query($sql);
            $ptr=0;
            $contenido = "";
 			$muestra = $result->num_rows;
           if($result->num_rows > 0)  { 
                while($row = $result->fetch_assoc()) {
  				$style = ' style="color: orange;" ';
                $ptr ++; 
                  switch ($row["prioridad"]){    
                  case "P1E":
                    $contenido .= '<tr style="color: red" >';
                    break;
                  case "P1":
                    $contenido .= '<tr style="color: orangered" >';
                    break;
                  case "P2":
                    $contenido .= '<tr style="color: orange" >';
                    break;
                  case "P3":
                    $contenido .= '<tr style="color: green" >';
                    break;
                  default:
                    $contenido .= '<tr style="color: grey">';                     
                  }   
                  $contenido .= "<td>".$ptr."</td>";
                  $contenido .= '<td><a target="_blank" href="'.$row["url_caso"].'">'.$row["numero"].'</a></td>';
                  $contenido .= "<td>".$row["asunto"]."</td>";
                  $contenido .= "<td>".$row["prioridad_descr"]."</td>";                      
                  $contenido .= "<td>".$row["estado"]."</td>";
                  $contenido .= "<td>".$row["categoria"]."</td>";
                  $contenido .= "<td>".$row["tipo"]."</td>";
                  $contenido .= "<td>".$row["nombre"]." ".$row["apellido"]."</td>";                    
                  $contenido .= "<td>".$row["cliente"]."</td>";
                  $contenido .= "<td>".$row["f_creacion"]."</td>";
                  $contenido .= "<td>".$row["f_modifica"]."</td>";
                  $contenido .= "<td align='right'>".$row["dias"]."&nbsp;&nbsp;</td>";					
                  $contenido .= "</tr>";
                }
            } else {
			  $style = ""; 
              $contenido = "<tr><td colspan='9'>No se encontraron datos de Casos Abiertos Sujetos a Cobro</td></tr>";
            }
            $conn->close();
            unset($result);
            unset($conn);
			$td ='<td colspan="11" align="left" valign="bottom" class="titulo" >&nbsp;&nbsp;💵 Casos Sujetos a Cobro</td><td style="font-size: 20px;"><a href="'.$url_nuevo_caso.'" title="Crear Nuevo Casos" target="new"><b>+</b></a>&nbsp;&nbsp;&nbsp;';
        ?>
        <table id="casos_sujeto_a_cobro" align="center" width="100%" class="subtitulo">
          <tr align="center" style="color: white; background-color: #512554">			  
              <?PHP echo $td; ?>
             </tr>
            <tr align="left">
                <th class="subtitulo" width="1%"> # </th>
                <th class="subtitulo" width="3%" align="center">N°</th>
                <th class="subtitulo" width="20%">Asunto</th>
                <th class="subtitulo" width ="8%" >Prioridad</th>
                <th class="subtitulo" width="3%">Estado</th>                
                <th class="subtitulo" width="5%">Categoría</th>
                <th class="subtitulo" width="8%">Tipo</th>
                <th class="subtitulo" width="10%">Asignado a</th>                    
                <th class="subtitulo" width="18%">Razón Social</th>
                <th class="subtitulo" width="5%">F.Creación</th>
                <th class="subtitulo" width="5%">F.Modifica.</th>
 				<th class="subtitulo" width="2%"  align="right">Días&nbsp;&nbsp;</th>
           </tr>
             <?PHP echo $contenido; ?>
        </table>   
		<div><button style="color: #512554; border: none" onclick="capa('casos_sujeto_a_cobro')">Casos Sujeto a Cobro [Muestra/Oculta <?PHP echo $ptr; ?>]</button></div>	
		<?PHP if(!$muestra) echo "<script>capa('casos_sujeto_a_cobro');</script>"; ?>