<?php
//=====================================================
// /intranet/kickoff_ajax/cm_casos_abiertos.php
// Casos Abiertos – Versión AJAX
// Autor: Mauricio Araneda
// Actualizado: 03-12-2025
// Codificación: UTF-8 sin BOM
//=====================================================

// Mostrar errores durante desarrollo AJAX
error_reporting(E_ALL);
ini_set('display_errors', 1);

mb_internal_encoding("UTF-8");

// Mantener sesión del KickOff
session_name('icontel_intranet_sess');
session_start();

// Cargar contexto del KickOff
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/security_groups.php";

// Variables
$db_sweet       = $db_sweet ?? "tnasolut_sweet";
$sg_id          = $_SESSION['sg_id'] ?? "";
$url_nuevo_caso = "/intranet/casos/crear.php";

// ------------------------------------------------------
// Ejecutar Stored Procedure
// ------------------------------------------------------
$conn = DbConnect($db_sweet);
$conn->set_charset("utf8mb4");

$sql = "CALL Kick_Off_Operaciones_Abiertos('" . $conn->real_escape_string($sg_id) . "')";
$result = $conn->query($sql);

$ptr = 0;
$contenido = "";
$muestra = ($result && $result->num_rows > 0);

// ------------------------------------------------------
// Procesar resultados
// ------------------------------------------------------
if ($muestra) {

    while ($row = $result->fetch_assoc()) {

        $ptr++;

        // Color según prioridad
        switch ($row["prioridad"]) {
            case "P1E": $color = "red"; break;
            case "P1":  $color = "orangered"; break;
            case "P2":  $color = "orange"; break;
            case "P3":  $color = "dimgray"; break;
            default:    $color = "inherit"; break;
        }

        $contenido .= "<tr style='color:$color;'>";

        $contenido .= "<td>{$ptr}</td>";

        $contenido .= '<td><a target="_blank" href="' . htmlspecialchars($row["url_caso"]) . '">' .
                        htmlspecialchars($row["numero"]) .
                      '</a></td>';

        $contenido .= "<td>" . htmlspecialchars($row["prioridad_descr"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["asunto"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["estado"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["en_espera_de"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["categoria"]) . "</td>";
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
        <td colspan='12' style='padding:10px; text-align:center; color:#AAA;'>
            🚫 No se encontraron casos abiertos
        </td>
    </tr>";
}

$conn->close();

$titulo = "🧾 Casos Abiertos";
?>

<!-- ===================================================== -->
<!-- TABLA HTML – Casos Abiertos -->
<!-- ===================================================== -->

<table id="casos_abiertos" border="0" width="100%" cellpadding="0" cellspacing="0">

    <tr style="color:white; background:#512554;">
        <td colspan="11" class="titulo" style="padding:8px 10px; font-size:16px;">
            <?= $titulo ?>
        </td>

        <td align="right" style="font-size:20px; background:#512554;">
            <a href="<?= $url_nuevo_caso ?>" 
               target="_blank" 
               style="color:white; text-decoration:none;">
               +
            </a>&nbsp;&nbsp;&nbsp;
        </td>
    </tr>

    <tr>
        <th class='subtitulo'>#</th>
        <th class='subtitulo'>Nº</th>
        <th class='subtitulo'>Prioridad</th>
        <th class='subtitulo'>Asunto</th>
        <th class='subtitulo'>Estado</th>
        <th class='subtitulo'>En Espera De</th>
        <th class='subtitulo'>Categoría</th>
        <th class='subtitulo'>Asignado a</th>
        <th class='subtitulo'>Razón Social</th>
        <th class='subtitulo'>F. Creación</th>
        <th class='subtitulo'>F. Modif.</th>
        <th class='subtitulo'>Días</th>
    </tr>

    <?= $contenido ?>

</table>