        <?php
 			$url_nuevo_caso = "https://sweet.icontel.cl/index.php?module=Cases&action=EditView";
            $conn = DbConnect($db_sweet);
            $sql = "CALL CM_Casos_Abiertos_Seguimiento('".$sg_id."')";                        
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
			  $style = "";
              $contenido = "<tr><td colspan='9'>No se encontraron datos Casos en Seguimiento</td></tr>";
            }
            $conn->close();
            unset($result);
            unset($conn);
        ?>
        <table id="casos_en_seguimiento" align="center" width="100%">
          <tr align="left"  style="color: white; background-color: #512554;">
              <td colspan=10 align="left" valign="bottom" style="font-size: 20px;">&nbsp;&nbsp;🧾 Casos Abiertos en Seguimiento
			  </td>
			  <td align="right" style="font-size: 20px;"><a href="<?PHP echo $url_nuevo_caso; ?>"><b>+</b></a>&nbsp;&nbsp;&nbsp;
			  </td>
          </tr>
		 <tr align="left">
			<th style="color: white; background-color: #512554;"> # </th>
			<th style="color: white; background-color: #512554;">Prioridad</th>
			<th style="color: white; background-color: #512554;"h>Número</th>
			<th style="color: white; background-color: #512554;" width="15%">Asunto</th>
			<th style="color: white; background-color: #512554;">Estado</th>
			<th style="color: white; background-color: #512554;" width="12%">En Espera de</th>
			<th style="color: white; background-color: #512554;">Categoría</th>
			<th style="color: white; background-color: #512554;">Asignado a</th>                    
			<th style="color: white; background-color: #512554;">Razón Social</th>
			<th style="color: white; background-color: #512554;" width="3%">Fecha<br>Creación</th>
			<th style="color: white; background-color: #512554;" width="3%">Fecha<br>Modificación</th>
		 </tr>
         <?PHP echo $contenido; ?>
        </table> 
		<div><button style="color: #512554; border: none" onclick="capa('casos_en_seguimiento')">Casos en Seguimiento [Muestra/Oculta <?PHP echo $ptr; ?>]</div>
		<?PHP if(!$muestra) echo "<script>capa('casos_en_seguimiento');</script>"; ?>