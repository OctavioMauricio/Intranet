	<?php
 		$conn = DbConnect($db_sweet);
		$sql = "CALL Kick_Off_Casos_Abiertos_de_baja()";                        
		$result = $conn->query($sql);
		$ptr=0;
		$contenido = "";
		$muestra = $result->num_rows;
		if($result->num_rows > 0)  { 
			while($row = $result->fetch_assoc()) {
				$ptr ++; 
				$contenido .= '<tr style="color: red" >';
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
				$contenido .= "<td align='right'>".$row["dias"]."&nbsp;&nbsp;</td>";	
				$contenido .= "</tr>";
			}
		} else {
		  $contenido = "<tr><td colspan='12'>No se encontraron datos de Casos de Baja</td></tr>";
		}
		$conn->close();
		unset($result);
		unset($conn);
		$td ='<td colspan="11" align="left" valign="top" style="font-size: 20px; color: white; background-color: #512554;">&nbsp;&nbsp;🗑️ Casos De Baja y Término de Contrato</td><td align="right" valign="top" style="font-size: 20px; color: white; background-color: #512554;"><a href="'.$url_nuevo_caso.'" target="new" title="Crear Nuevo Caso">+</a>&nbsp;&nbsp;&nbsp;</td>';
	?>
	<table id="casos_debaja" border="0" align="center" width="100%" cellpadding="0" cellspacing="0" >
	  <tr align="left" class="subtitulo">
		  <?PHP  echo $td; ?>
		</tr>
	  <tr align="left" class="subtitulo">
			<th width="1%" class="subtitulo"> # </th>
			<th width="2%" class="subtitulo">Prioridad</th>
			<th width="2%" class="subtitulo">Número</th>
			<th width="15%" class="subtitulo">Asunto</th>
			<th width="2%" class="subtitulo">Estado</th>                
			<th width="5%" class="subtitulo">En Espera De</th>                
			<th width="3%" class="subtitulo">Categoría</th>
			<!--th width="10%">Tipo Caso</th-->
			<th width="10%" class="subtitulo">Asignado a</th>                    
			<th width="8%" class="subtitulo">Razónn Social</th>
			<th width="3%" class="subtitulo">F.Creación</th>
			<th width="3%" class="subtitulo">F.Modif.</th>
			<th width="2%" class="subtitulo" align="right">DÃ­as&nbsp;&nbsp;</th>
	   </tr>
		 <?PHP echo $contenido; ?>
	</table>   
	<div><button style="color: #512554; border: none" onclick="capa('casos_debaja')">Casos de Baja [Muestra/Oculta <?PHP echo $ptr; ?>]</button></div>
	<?PHP if(!$muestra) echo "<script>capa('casos_debaja');</script>"; ?>
