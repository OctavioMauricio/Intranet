        <?php
 			$url_nuevo_caso = "https://sweet.icontel.cl/index.php?module=Cases&action=EditView";
            $conn = DbConnect($db_sweet);
            $sql = "CALL CM_Casos_Abiertos_Congelados('".$sg_id."')";                        
            $result = $conn->query($sql);
            $ptr=0;
            $contenido = "";
			$muestra = $result->num_rows;
            if($result->num_rows > 0)  { 
				$style = ' style="color: orange;" ';				
                while($row = $result->fetch_assoc()) {
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
                    $contenido .= '<tr>';                     
                  }   
                  $contenido .= "<td>".$ptr."</td>";
                  $contenido .= "<td>".$row["prioridad_descr"]."</td>";                      
                  $contenido .= '<td><a target="_blank" href="'.$row["url_caso"].'">'.$row["numero"].'</a></td>';
                  $contenido .= "<td>".$row["asunto"]."</td>";
                  $contenido .= "<td>".$row["estado"]."</td>";
                  $contenido .= "<td>".$row["en_espera_de"]."</td>";
                  $contenido .= "<td>".$row["categoria"]."</td>";
                  $contenido .= "<td>".$row["nombre"]." ".$row["apellido"]."</td>";                    
                  $contenido .= "<td>".$row["cliente"]."</td>";
                  $contenido .= "<td>".$row["f_creacion"]."</td>";
                  $contenido .= "<td>".$row["f_modifica"]."</td>";
                  $contenido .= "</tr>";
                }
            } else {
			  $style ="";
              $contenido = "<tr><td colspan='9'>No se encontraron Casos Abiertos Congelados= ".$categoria."</td></tr>";
            }
            $conn->close();
            unset($result);
            unset($conn);
        ?>
        <table id="casos_congelados" align="center" width="100%">
          <tr align="center">
              <td colspan="10" align="left" valign="bottom" class="titulo">&nbsp;&nbsp;🧾 Casos Abiertos Congelados</td>
			  <td style="font-size: 20px; background-color: #512554;"><a href="<?PHP echo $url_nuevo_caso; ?>" title="Crea Caso" target="new"><b>+</b></a>
			  </td>
            </tr>
            <tr align="left"  style="color: white;background-color: midnightblue">
                <th class="subtitulo"> # </th>
                <th class="subtitulo" width="8%">Prioridad</th>
                <th class="subtitulo">Número</th>
                <th class="subtitulo" width="15%">Asunto</th>
                <th class="subtitulo">Estado</th> 
				<th class="subtitulo" width="12%">En Espera De</th>
                <th class="subtitulo">Categoría</th>
                <th class="subtitulo">Asignado a</th>                    
                <th class="subtitulo">Razón Social</th>
                <th class="subtitulo" width="3%">Fecha<br>Creación</th>
                <th class="subtitulo" width="3%">Fecha<br>Modificación</th>
            </tr>
             <?PHP echo $contenido; ?>
        </table> 
		<div><button style="color: #512554; border: none" onclick="capa('casos_congelados')">Casos Congelados [Muestra/Oculta <?PHP echo $ptr; ?>]</button></div>
		<?PHP if(!$muestra) echo "<script>capa('casos_congelados');</script>"; ?>