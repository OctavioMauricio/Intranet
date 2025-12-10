<?php
 		//error_reporting(E_ALL);
		//ini_set('display_errors', '1');
             $conn = DbConnect($db_sweet);
            $sql = "CALL Kick_Off_Operaciones_Abiertos('".$sg_id."')";                        
            $result = $conn->query($sql);
            $ptr=0;
            $contenido = "";
            if($result->num_rows > 0)  { 
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
                  $contenido .= '<td><a target="_blank" href="'.$row["url_caso"].'">'.$row{"numero"}.'</a></td>';
                  $contenido .= "<td>".$row["asunto"]."</td>";
                  $contenido .= "<td>".$row["estado"]."</td>";
                  $contenido .= "<td>".$row["en_espera_de"]."</td>";
                  $contenido .= "<td>".$row["categoria"]."</td>";
                 // $contenido .= "<td>".$row["tipo"]."</td>";
                  $contenido .= "<td>".$row["nombre"]." ".$row["apellido"]."</td>";                    
                  $contenido .= "<td>".$row["cliente"]."</td>";
                  $contenido .= "<td>".$row["f_creacion"]."</td>";
                  $contenido .= "<td>".$row["f_modifica"]."</td>";
                  $contenido .= "<td align='right'>".$row["dias"]."&nbsp;&nbsp;</td>";					
                  $contenido .= "</tr>";
                }
            } else {
              $contenido = "<tr><td colspan='9'>No se encontraron datos de Casos Abiertos</td></tr>";
            }
            $conn->close();
            unset($result);
            unset($conn);
			//$td = '<td colspan="4" align="right" valign="bottom"><h2>'.$sg_name.': <a href="https://sweet.icontel.cl/index.php?entryPoint=NuevoCaso" target="new">Casos</a> Abiertos</h2>';
			 $td ='<td colspan="5" align="right" valign="top"><h2>'.$sg_name.': <a    href="https://sweet.icontel.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DCases%26action%3DEditView%26return_module%3DCases%26return_action%3DDetailView" target="new" title="Crear Nuevo Caso">Casos </a>De BAJA </h2>';

			$td .= '<form  action="" method="post" name="form_select" id="form_select">'.$select.'</form>';
			$td.='</td>';
        ?>
        <table id="tabla_casos" border="0" align="center" width="100%" cellpadding="0" cellspacing="0" >
          <tr align="center" style="color: white;background-color: #1F1D3E;">
			 <td colspan=5 align="left" valign="top">
				 <table border="0"  width="100%" cellpadding="0" cellspacing="0">
				 </table>
			  </td> 
			  <td valign="top">Casos por CategorÃ­a<br>
			<img src="../images/grafico.jpeg" width="50"  alt="" onclick="capa('capa_casos')" 				     onMouseOver="this.style.cursor='pointer'"/></td>
			  <td valign="top"><br>Favoritos<br>
			<img src="../images/favoritos.jpeg" width="50"  alt="" onclick="capa('capa_iconos')" onMouseOver="this.style.cursor='pointer'"/></td>
            <td colspan="1" align="center" valign="top">
				  UF &nbsp;<?php echo $UF; ?> &nbsp;&nbsp;<br>
				  US &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $USD; ?>&nbsp;&nbsp;&nbsp;<br>
				  al&nbsp;&nbsp;<?PHP echo $UF_Fecha; ?>&nbsp;&nbsp;&nbsp;
			  </td>			  			  
              <?PHP  echo $td; ?>
            </tr>
            <tr align="left">
                <th width="1%"> # </th>
                <th width="2%">Prioridad</th>
                <th width="2%">NÃºmero</th>
                <th width="15%">Asunto</th>
                <th width="2%" >Estado</th>                
                <th width="5%">En Espera De</th>                
                <th width="3%">CategorÃ­a</th>
                <!--th width="10%">Tipo Caso</th-->
                <th width="10%">Asignado a</th>                    
                <th width="8%">RazÃ³n Social</th>
                <th width="3%">F.CreaciÃ³n</th>
                <th width="3%">F.Modif.</th>
 				<th width="2%"  align="right">DÃ­as&nbsp;&nbsp;</th>
           </tr>
             <?PHP echo $contenido; ?>
        </table>   
<div><button onclick="capa('tabla_casos')">Casos Abiertos [Muestra/Oculta]</button></div>