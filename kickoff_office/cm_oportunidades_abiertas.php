        <?php
// ==========================================================
// kickoff_office/cm_oportunidades_abiertas.php
// Oportunidades de Kickoff de TNA Office
// Autor: Mauricio Araneda
// Fecha: 2025-11-17
// CodificaciÃ³n: UTF-8 sin BOM
// ==========================================================

    // --- Conexión a la base de datos Sweet ---
    $conn = DbConnect("tnaoffice_suitecrm");
           $sql = "CALL Oportunidades_Pendientes('".$sg_id."')";       
            $resultado = $conn->query($sql);
            $ptr=0;
            $contenido = "";
 			$muestra = false;
            $datos =[];
 /*
            if($resultado->num_rows > 0)  { 
                $muestra = true;
                while($lin = $resultado->fetch_assoc()) {
*/
            if ($resultado && $resultado->num_rows > 0) {  
                $muestra = true;
                while ($lin = $resultado->fetch_assoc()) { $datos[] = $lin;}
                // Ordenar por 'dias' ascendente
                usort($datos, function($a, $b) { return $a['dias'] - $b['dias']; });
                //    while($lin = $resultado->fetch_assoc()) {
                // Recorrer array ya ordenado
                foreach ($datos as $lin) {
				  $style = ' style="color: orange;" ';					
                  $ptr ++; 
                  $importancia = $lin["estado"];
                  if ($lin["dias"] > 10) { $importancia = "cotizar"; }
                  switch ($importancia){    
                  case "1 Escalado Urgente":
                    $contenido .= '<tr style="color: red" >';
                    break;
                  case "2 Aceptadado, listo para Instalar":
                    $contenido .= '<tr style="color: orangered" >';
                    break;
                  case "4 Pre InstalaciÃ³n":
                    $contenido .= '<tr style="color: orangered" >';
                    break;
                  case "3 Generar NV":
                    $contenido .= '<tr style="color: orange" >';
                    break;
                  case "Cotizar":
                    $contenido .= '<tr style="color: green" >';
                    break;
                  default:
                    $contenido .= '<tr>';                     
                  }   
                  $contenido .= "<td>".$ptr."</td>";
                  $contenido .= '<td colspan="2"><a target="_blank" href="'.$lin["url_opor"].'">'.$lin["nombre"].'</a></td>';
                  $contenido .= "<td>".$lin["numero"]."</td>";
                  $contenido .= "<td>".$lin["cliente"]."</td>";
                  $contenido .= "<td>".$lin["estado"]."</td>";
                  $contenido .= "<td>".$lin["asignado"]."</td>";
                  $contenido .= "<td>".$lin["ejecutivo"]."</td>";					
                  $contenido .= "<td>".$lin["f_creacion"]."</td>";
                  $contenido .= "<td>".$lin["f_modifica"]."</td>";
                  //$contenido .= "<td>".$lin["proximo_paso"]."</td>";
                  $contenido .= "<td>".$lin["f_proximo_paso"]."</td>";					
                  $contenido .= "<td align='right'>".$lin["dias"]."&nbsp;&nbsp;</td>";
                  $contenido .= "</tr>";
                }
            } else {
			  $style = "";
              $contenido = "<tr><td colspan='9'>⚠️ No se encontraron Oportunidades pendientes.</td></tr>";
            }
            $conn->close(); 
            unset($resultado);
            unset($conn);
			$td = '<td colspan="11" align="left" valign="top" class="titulo" >&nbsp;&nbsp;💼 Oportunidades en Curso</td>
			<td colspan="1" align="right" valign="top" style="font-size: 20px; background-color: #512554;"><a href="'.$url_nueva_oportunidad.'" target="new"><b>+<b></a>&nbsp;&nbsp;&nbsp;</td>';
        ?>
        <table id="oportunidades" align="center" width="100%">
            <tr align="left" class="subtitulo">
 			   <?PHP echo $td; ?>
            </tr>
            <tr align="left">
                <th class="subtitulo" width="1%"> # </th>
                <th class="subtitulo" width="18%" colspan="2">Asunto a</th>                    
                <th class="subtitulo" width="2%">Número</th>
                <th class="subtitulo" width="13%">Cliente</th>
                <th class="subtitulo" width="6%">Estado</th>
                <th class="subtitulo" width="8%">Asignado a</th>
                <th class="subtitulo" width="8%">Ejecutiv@</th>
                <th class="subtitulo" width="5%">Fecha<br>Creación</th>
                <th class="subtitulo" width="5%">Fecha<br>Modificación</th>               
				<!--th class="subtitulo" width="20%">Proximo Paso</th-->               
                <th class="subtitulo" width="5%">Fecha<br>P.Paso</th>                               
				<th class="subtitulo" width="5%"  align="right">Días<br>Restantes</th>
            </tr>
             <?PHP echo $contenido; ?>
        </table>  
		<div><button style="color: #512554; border: none" onclick="capa('oportunidades')">Oportunidades en Curso [Muestra/Oculta <?PHP echo $ptr; ?>]</button></div>
		<?PHP if(!$muestra) echo "<script>capa('oportunidades');</script>"; ?>