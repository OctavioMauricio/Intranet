<?php
// ==========================================================
// KickOff AJAX – Tareas DELEGADAS POR USTED
// /intranet/kickoff_ajax/cm_tareas_pendientes_delegadas.php
// Autor: Mauricio Araneda (mAo)
// Versión AJAX – UTF-8 sin BOM
// ==========================================================

mb_internal_encoding("UTF-8");

// Bootstrap común AJAX (sesión + config + $sg_id + $sg_name + DbConnect)
require_once __DIR__ . "/ajax_bootstrap.php";

$conn = DbConnect($db_sweet);

// -------------------------------------------------------------
// Ejecutar SP que trae las tareas delegadas por el usuario
// -------------------------------------------------------------
$sql = "CALL Kick_Off_Tareas_Abiertas_Creadas('" . $conn->real_escape_string($sg_id) . "')";
$resultado = $conn->query($sql);

$datos = [];
$ptr = 0;
$muestra = false;
$contenido = "";

// URLs base SweetCRM
$url_caso      = "https://sweet.icontel.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DCases%26action%3DDetailView%26record%3D";
$url_opor      = "https://sweet.icontel.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DOpportunities%26action%3DDetailView%26record%3D";
$url_cuenta    = "https://sweet.icontel.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DAccounts%26action%3DDetailView%26record%3D";
$url_insidente = "https://sweet.icontel.cl/index.php?module=Bugs&action=DetailView&record=";

// -------------------------------------------------------------
// Procesar resultados
// -------------------------------------------------------------
if ($resultado && $resultado->num_rows > 0) {

    $muestra = true;

    while ($row = $resultado->fetch_assoc()) {
        $datos[] = $row;
    }

    // Ordenar por fecha de vencimiento
    usort($datos, function ($a, $b) {
        $fa = strtotime(str_replace('/', '-', $a['f_vencimiento']));
        $fb = strtotime(str_replace('/', '-', $b['f_vencimiento']));
        if ($fa === false) return 1;
        if ($fb === false) return -1;
        return $fa <=> $fb;
    });

    foreach ($datos as $lin) {

        $ptr++;

        // Reglas de color por prioridad
        $importancia = $lin["prioridad"];
        if ($lin["dias"] > 10) {
            $importancia = "4 Baja"; // Override
        }

        switch ($importancia) {
            case "1 URGENTE ESCALADO": $contenido .= '<tr style="color:red;">'; break;
            case "2 URGENTE":          $contenido .= '<tr style="color:orangered;">'; break;
            case "3 Alta":             $contenido .= '<tr style="color:orange;">'; break;
            case "4 Baja":             $contenido .= '<tr style="color:green;">'; break;
            default:                   $contenido .= '<tr>'; break;
        }

        // Columnas
        $contenido .= "<td>{$ptr}</td>";
        $contenido .= '<td><a target="_blank" href="' . htmlspecialchars($lin["url"]) . '">' .
                        htmlspecialchars($lin["tarea"]) . '</a></td>';
        $contenido .= "<td>" . htmlspecialchars($lin["categoria"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($lin["prioridad"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($lin["asignado"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($lin["estado"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($lin["enesperade"]) . "</td>";

        // Origen + Link según tipo
        switch ($lin["origen"]) {
            case "Cases":
                $contenido .= "<td>CASO</td>";
                $contenido .= '<td align="center"><a target="_blank" href="' . $url_caso . $lin["origen_id"] . '">' .
                    htmlspecialchars($lin["numero"]) . '</a></td>';
                break;

            case "Opportunities":
                $contenido .= "<td>OPORTUNIDAD</td>";
                $contenido .= '<td align="center"><a target="_blank" href="' . $url_opor . $lin["origen_id"] . '">' .
                    htmlspecialchars($lin["numero"]) . '</a></td>';
                break;

            case "Accounts":
                $contenido .= "<td>CUENTA</td>";
                $contenido .= '<td align="center"><a target="_blank" href="' . $url_cuenta . $lin["origen_id"] . '">' .
                    htmlspecialchars($lin["numero"]) . '</a></td>';
                break;

            case "Bugs":
                $contenido .= "<td>INCIDENTE</td>";
                $contenido .= '<td align="center"><a target="_blank" href="' . $url_insidente . $lin["origen_id"] . '">' .
                    htmlspecialchars($lin["numero"]) . '</a></td>';
                break;

            default:
                $contenido .= "<td>" . htmlspecialchars($lin["origen"]) . "</td>";
                $contenido .= "<td></td>";
        }

        $contenido .= "<td>" . htmlspecialchars($lin["f_creacion"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($lin["f_modifica"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($lin["f_vencimiento"]) . "</td>";
        $contenido .= "<td align='right'>" . htmlspecialchars($lin["dias"]) . "</td>";

        $contenido .= "</tr>";
    }
} else {
    $contenido = "<tr><td colspan='13'>No se encontraron Tareas Delegadas.</td></tr>";
}

$conn->close();
unset($resultado);
unset($conn);

// Título del módulo
$td = '
<td colspan="12" class="titulo" style="text-align:left;">
    &nbsp;&nbsp;🕑 Tareas Abiertas DELEGADAS POR USTED
</td>
<td style="font-size:20px; color:white; background:#512554;" align="right">
    <a href="'.$url_nueva_tarea.'" target="new" title="Crear Nueva Tarea"><b>+</b></a>
    &nbsp;&nbsp;&nbsp;
</td>';
?>

<table id="tareas_delegadas" align="center" width="100%">
    <tr><?= $td ?></tr>

    <tr class="subtitulo">
        <th>#</th>
        <th>Asunto</th>
        <th>Categoría</th>
        <th>Prioridad</th>
        <th>Asignado a</th>
        <th>Estado</th>
        <th>En Espera de</th>
        <th>Origen Tipo</th>
        <th>N°</th>
        <th>Creación</th>
        <th>Modificación</th>
        <th>Vencimiento</th>
        <th align="right">Días</th>
    </tr>

    <?= $contenido ?>
</table>
