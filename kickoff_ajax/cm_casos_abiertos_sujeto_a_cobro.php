<?php
// ==========================================================
// KickOff AJAX – Casos Abiertos Sujeto a Cobro
// /kickoff_ajax/cm_casos_abiertos_sujeto_a_cobro.php
// Autor: Mauricio Araneda (mAo)
// Fecha: 2025-12
// Codificación: UTF-8 sin BOM
// ==========================================================

mb_internal_encoding("UTF-8");

// ------------------------------------------------------
// Bootstrap AJAX (sesión + $sg_id + $sg_name + DbConnect)
// ------------------------------------------------------
require_once __DIR__ . "/ajax_bootstrap.php";

// Seguridad
if ($sg_id === "" || $sg_name === "") {
    echo "<div style='padding:20px; color:red;'>❌ Error: sesión inválida.</div>";
    exit;
}

// ------------------------------------------------------
// Conexión SweetCRM
// ------------------------------------------------------
$conn = DbConnect($db_sweet);
$conn->set_charset("utf8mb4");

// Procedimiento almacenado
$sql = "CALL Kick_Off_Operaciones_Abiertos_sujeto_a_cobro()";
$result = $conn->query($sql);

// ------------------------------------------------------
// Construcción de datos
// ------------------------------------------------------
$ptr       = 0;
$contenido = "";
$muestra   = ($result && $result->num_rows > 0);

if ($muestra) {

    while ($row = $result->fetch_assoc()) {

        $ptr++;

        // Color por prioridad
        switch ($row["prioridad"]) {
            case "P1E": $color = "color:red;";        break;
            case "P1":  $color = "color:orangered;";  break;
            case "P2":  $color = "color:orange;";     break;
            case "P3":  $color = "color:green;";      break;
            default:    $color = "color:gray;";
        }

        $contenido .= "<tr style='{$color}'>";

        // Fila segura
        $contenido .= "<td width'=1%'>{$ptr}</td>";

        $contenido .= "<td width'=1%'><a target='_blank' href='" . 
                      htmlspecialchars($row["url_caso"]) . "'>" .
                      htmlspecialchars($row["numero"]) .
                      "</a></td>";

        $contenido .= "<td>" . htmlspecialchars($row["asunto"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["prioridad_descr"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["estado"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["categoria"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["tipo"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["nombre"] . " " . $row["apellido"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["cliente"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["f_creacion"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["f_modifica"]) . "</td>";
        $contenido .= "<td align='right'>" . htmlspecialchars($row["dias"]) . "&nbsp;&nbsp;</td>";

        $contenido .= "</tr>";
    }

} else {

    $contenido = "
        <tr>
            <td colspan='12' style='padding:12px; text-align:center; color:#666;'>
                ⚠️ No se encontraron Casos Sujetos a Cobro.
            </td>
        </tr>";
}

$conn->close();
unset($result);
unset($conn);
?>

<!-- ===================================== -->
<!--  TABLA HTML – Casos Sujeto a Cobro     -->
<!-- ===================================== -->

<table id="casos_sujeto_a_cobro" width="100%" cellpadding="0" cellspacing="0" border="0">

    <tr style="background:#512554; color:white;">
        <td colspan="12" class="titulo" style="padding:8px;">
            &nbsp;&nbsp;💰 Casos Sujetos a Cobro
        </td>
    </tr>

    <tr style="background:#512554; color:white;">
        <th class="subtitulo" width="1%">#</th>
        <th class="subtitulo" width="1%">Nº</th>
        <th class="subtitulo" width="20%">Asunto</th>
        <th class="subtitulo" width="8%">Prioridad</th>
        <th class="subtitulo" width="3%">Estado</th>
        <th class="subtitulo" width="5%">Categoría</th>
        <th class="subtitulo" width="8%">Tipo</th>
        <th class="subtitulo" width="10%">Asignado a</th>
        <th class="subtitulo" width="18%">Razón Social</th>
        <th class="subtitulo" width="5%">F. Creación</th>
        <th class="subtitulo" width="5%">F. Modifica.</th>
        <th class="subtitulo" width="2%" align="right">Días&nbsp;&nbsp;</th>
    </tr>

    <?= $contenido ?>

</table>