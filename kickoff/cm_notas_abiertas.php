<?php
    // --- Conexión a la base de datos Sweet ---
    $conn = DbConnect($db_sweet);

    // --- URL para crear una nueva nota ---
    $url_nueva_nota = "https://sweet.icontel.cl/index.php?module=Notes&action=EditView&return_module=Notes&return_action=DetailView";

    // --- Llamada al procedimiento almacenado ---
    $sql = "CALL cm_notas_abiertas('".$sg_id."')";
    $result = $conn->query($sql);

    // --- Variables iniciales ---
    $ptr = 0;
    $contenido = "";
    $muestra = $result->num_rows;

    // --- Si hay resultados ---
    if ($muestra > 0) { 
        while ($row = $result->fetch_assoc()) {
            $ptr++; 
            $dias = (int)$row["dias_sin_modificar"];

            // --- Cambiar color segÃºn dÃ­as sin modificar ---
            if ($dias > 4) {
                $contenido .= '<tr style="color: red;">';              // Rojo
            } elseif ($dias >= 3 && $dias <= 4) {
                $contenido .= '<tr style="color: orange;">';           // Naranjo
            } else {
                $contenido .= '<tr style="color: green;">';            // Verde
            }

            // --- Generar contenido de la fila ---
            $contenido .= "<td>".$ptr."</td>";
            $contenido .= '<td><a target="_blank" href="'.$row["url_nota"].'">'.htmlspecialchars($row["asunto"]).'</a></td>';
            $contenido .= "<td>".$row["fecha_creacion"]."</td>";                      
            $contenido .= "<td>".$row["relacionado_con"]."</td>";                      
            $contenido .= "<td>".$row["nota_estado"]."</td>";
            $contenido .= "<td>".$row["departamento"]."</td>";
            $contenido .= "<td>".$row["asignado_a"]."</td>";
            $contenido .= "<td>".$row["modificado_por"]."</td>";
            $contenido .= "<td>".$row["fecha_modificacion"]."</td>";
            $contenido .= "<td align='right'>".$dias."&nbsp;&nbsp;</td>";	
            $contenido .= "</tr>";
        }
    } else {
        // --- Si no hay registros ---
        $contenido = "<tr><td colspan='10'>No se encontraron datos de Notas Abiertas</td></tr>";
    }

    // --- Cierra conexión y limpia ---
    $conn->close();
    unset($result);
    unset($conn);

    // --- Cabecera superior de tabla ---
    $td = '
        <td colspan="9" 
            align="left" 
            valign="middle" 
            class="titulo" 
            style="font-size: 18px; font-weight: bold; color: #C39BD3; background-color: #512554; padding: 8px; white-space: nowrap;">
            &nbsp;&nbsp;📋 Notas Abiertas
        </td>
        <td 
            align="right" 
            valign="middle" 
            style="font-size: 22px; font-weight: bold; color: #C39BD3; background-color: #512554; padding-right: 12px; white-space: nowrap;">
            <a 
                href="'.$url_nueva_nota.'" 
                target="_blank" 
                title="Crear Nueva Nota" 
                style="color: #C39BD3; text-decoration: none; font-size: 24px;"><b>+<b>
            </a>
        </td>';
?>

<!-- ============================ -->
<!-- ======= ESTILO GLOBAL ====== -->
<!-- ============================ -->
<style>
  #notas_abiertas {
    width: 100%;
    border-collapse: collapse;
    table-layout: auto;  /* permite ajustar el ancho de columnas */
  }

  #notas_abiertas th,
  #notas_abiertas td {
    padding: 6px 8px;
    white-space: nowrap;   /* evita que el texto salte de lÃƒÆ’Ã‚Â­nea */
    text-overflow: ellipsis;
    overflow: hidden;
  }

  /* Encabezado de columnas */
  #notas_abiertas th.subtitulo {
    background-color: #512554;
    color: #C39BD3;
    font-weight: bold;
    text-align: left;
    white-space: nowrap;
  }

  /* Contenedor para scroll horizontal */
  .tabla-contenedor {
    width: 100%;
    overflow-x: auto;
  }
</style>

<!-- ============================ -->
<!-- ======= TABLA HTML ========= -->
<!-- ============================ -->
<div class="tabla-contenedor">
<table id="notas_abiertas" border="0" align="center" cellpadding="0" cellspacing="0">
  <!-- Cabecera superior -->
  <tr align="left" style="background-color: #512554; color: #C39BD3; white-space: nowrap;">
    <?php echo $td; ?>
  </tr>

  <!-- Encabezados de columna -->
  <tr align="left">
      
    <th width="1%" class="subtitulo"><span onclick="sortTableLocal('tablaNotas',0,false)">#</span></th>
    <th width="40%" class="subtitulo"><span onclick="sortTableLocal('tablaNotas',1,false)">Asunto</span></th>
    <th width="10%" class="subtitulo">F. Creació    n</th>
    <th width="10%" class="subtitulo">Relacionado Con</th>                
    <th width="10%" class="subtitulo">Estado</th>                
    <th width="10%" class="subtitulo">Categoría</th>
    <th width="10%" class="subtitulo">Asignado a</th>                    
    <th width="10%" class="subtitulo">Modificado Por</th>
    <th width="10%" class="subtitulo">F. Modif.</th>
    <th width="5%" class="subtitulo" align="right">Días&nbsp;&nbsp;</th>
  </tr>

  <!-- Contenido dinÃ¡mico -->
  <?php echo $contenido; ?>
</table>
</div>

<!-- ============================ -->
<!-- ======= BOTON OCULTAR ====== -->
<!-- ============================ -->
<div>
  <button style="color: #512554; border: none;" onclick="capa('notas_abiertas')">
    Notas Abiertas [Muestra/Oculta <?php echo $ptr; ?>]
  </button>
  <?php if (!$muestra) echo "<script>capa('notas_abiertas');</script>"; ?>
</div>
