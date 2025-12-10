<?php
//=====================================================
// /intranet/kickoff/cd_casos_abiertos.php
// busca casos pendientes.
// Autor: Mauricio Araneda
// Actualizado: 08-11-2025
//=====================================================
mb_internal_encoding("UTF-8");
$conn = DbConnect($db_sweet);
$sql = "CALL Kick_Off_Operaciones_Abiertos('" . $sg_id . "')";                        
$result = $conn->query($sql);

$ptr = 0;
$contenido = "";
$muestra = $result->num_rows;

if ($result->num_rows > 0) { 
    $style = ' style="color: orange;" ';
    while ($row = $result->fetch_assoc()) {
        $ptr++; 
        switch ($row["prioridad"]) {    
            case "P1E":
                $contenido .= '<tr style="color: red">';
                break;
            case "P1":
                $contenido .= '<tr style="color: orangered">';
                break;
            case "P2":
                $contenido .= '<tr style="color: orange">';
                break;
            case "P3":
                $contenido .= '<tr style="color: dimgray">';
                break;
            default:
                $contenido .= '<tr>';                     
        }   

        $contenido .= "<td>{$ptr}</td>";
        $contenido .= '<td><a target="_blank" href="' . $row["url_caso"] . '">' . $row["numero"] . '</a></td>';
        $contenido .= "<td>" . $row["prioridad_descr"] . "</td>";                      
        $contenido .= "<td>" . $row["asunto"] . "</td>";
        $contenido .= "<td>" . $row["estado"] . "</td>";
        $contenido .= "<td>" . $row["en_espera_de"] . "</td>";
        $contenido .= "<td>" . $row["categoria"] . "</td>";
        $contenido .= "<td>" . $row["nombre"] . " " . $row["apellido"] . "</td>";                    
        $contenido .= "<td>" . $row["cliente"] . "</td>";
        $contenido .= "<td>" . $row["f_creacion"] . "</td>";
        $contenido .= "<td>" . $row["f_modifica"] . "</td>";
        $contenido .= "<td align='right'>" . $row["dias"] . "&nbsp;&nbsp;</td>";					
        $contenido .= "</tr>";
    }
} else {
    $style = "";
    $contenido = "<tr><td colspan='12'>âš ï¸ No se encontraron datos de Casos Abiertos</td></tr>";
}

$conn->close();
unset($result);
unset($conn);

$td = '<td colspan="11" align="left" valign="top" class="titulo" >&nbsp;&nbsp;🧾 Casos Abiertos &nbsp;&nbsp;&nbsp;</td>
<td align="right" valign="top" style="font-size: 20px; color: white; background-color: #512554;">
    <a style="color: white;" href="' . $url_nuevo_caso . '" target="new" title="Crear Nuevo Caso">+</a>&nbsp;&nbsp;&nbsp;
</td>';
?>

<table id="casos_abiertos" border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr align="left" style="color: white; background-color: #512554;">
        <?php echo $td; ?>
    </tr>
    <tr align="left" style="color: white; background-color: #512554;">
        <th width="1%" class="subtitulo">#</th>
        <th width="2%" class="subtitulo">Nº</th>
        <th width="2%" class="subtitulo">Prioridad</th>
        <th width="11%" class="subtitulo">Asunto</th>
        <th width="2%" class="subtitulo">Estado</th>                
        <th width="8%" class="subtitulo">En Espera De</th>                
        <th width="3%" class="subtitulo">Categoría</th>
        <th width="5%" class="subtitulo">Asignado a</th>                    
        <th width="15%" class="subtitulo">Razón Social</th>
        <th width="3%" class="subtitulo">F.Creación</th>
        <th width="3%" class="subtitulo">F.Modif.</th>
        <th width="2%" class="subtitulo" align="right">Días&nbsp;&nbsp;</th>
    </tr>
    <?php echo $contenido; ?>
</table>

<div>
    <button style="color: #512554; border: none" onclick="capa('casos_abiertos')">
        Casos Abiertos [Muestra/Oculta <?php echo $ptr; ?>]
    </button>
    <?php if (!$muestra) echo "<script>capa('casos_abiertos');</script>"; ?>
</div>
