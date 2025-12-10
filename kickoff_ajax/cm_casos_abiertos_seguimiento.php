<?php
// ======================================================
// /intranet/kickoff_ajax/cm_casos_abiertos_seguimiento.php
// Casos Abiertos – Seguimiento (Versión AJAX)
// Autor: Mauricio Araneda
// Actualizado: 03-12-2025
// Codificación: UTF-8 sin BOM
// ======================================================

// Mostrar errores durante desarrollo AJAX
error_reporting(E_ALL);
ini_set('display_errors', 1);

mb_internal_encoding("UTF-8");

// Mantener la sesión del KickOff
session_name('icontel_intranet_sess');
session_start();

// Cargar contexto del KickOff
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/security_groups.php";

// Variables necesarias
$db_sweet       = $db_sweet ?? "tnasolut_sweet";
$sg_id          = $_SESSION['sg_id'] ?? "";

$url_nuevo_caso = "https://sweet.icontel.cl/index.php?module=Cases&action=EditView";

// Ejecutar SP
$conn = DbConnect($db_sweet);
$conn->set_charset("utf8mb4");

$sql = "CALL CM_Casos_Abiertos_Seguimiento('" . $conn->real_escape_string($sg_id) . "')";
$result = $conn->query($sql);

$ptr       = 0;
$contenido = "";
$muestra   = ($result && $result->num_rows > 0);

if ($muestra) {

    while ($row = $result->fetch_assoc()) {

        $ptr++;

        // Colores según prioridad
        switch ($row["prioridad"]) {
            case "P1E": $color = "red"; break;
            case "P1":  $color = "orangered"; break;
            case "P2":  $color = "orange"; break;
            case "P3":  $color = "green"; break;
            default:    $color = "inherit"; break;
        }

        $contenido .= "<tr style='color:$color;'>";

        $contenido .= "<td>{$ptr}</td>";
        $contenido .= "<td>" . htmlspecialchars($row["prioridad_descr"]) . "</td>";

        $contenido .= '<td><a target="_blank" href="' . 
                        htmlspecialchars($row["url_caso"]) . '">' .
                        htmlspecialchars($row["numero"]) . '</a></td>';

        $contenido .= "<td>" . htmlspecialchars($row["asunto"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["estado"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["en_espera_de"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["categoria"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["nombre"] . " " . $row["apellido"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["cliente"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["f_creacion"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($row["f_modifica"]) . "</td>";

        $contenido .= "</tr>";
    }

} else {

    $contenido = "
        <tr>
            <td colspan='11' style='padding:10px; text-align:center; color:#AAA;'>
                ⚠️ No se encontraron Casos en Seguimiento
            </td>
        </tr>";
}

$conn->close();
unset($result);
unset($conn);
?>
<style>
/* Contenedor con scroll */
.tabla-scroll {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    display: block;
    padding-bottom: 6px;
}

/* La tabla puede ser más grande, pero nunca obligará a la pantalla a crecer */
#casos_en_seguimiento {
    border-collapse: collapse;
    width: max-content;      /* 🔥 Clave: la tabla crece solo lo necesario */
    min-width: 100%;         /* 🔥 Clave: se ajusta al ancho inicial */
}

/* Celdas */
#casos_en_seguimiento td,
#casos_en_seguimiento th {
    padding: 6px 8px;
    white-space: nowrap;
}

/* Título */
#casos_en_seguimiento .titulo {
    background: #512554 !important;
    color: white !important;
    font-weight: bold;
}

/* Subtítulos */
#casos_en_seguimiento th.subtitulo {
    background: #512554 !important;
    color: white !important;
    font-weight: bold;
}
</style>
<table id="casos_en_seguimiento" align="center" width="100%" cellpadding="0" cellspacing="0">

    <!-- Título -->
    <tr align="left" class="titulo">
        <td colspan="10" style="font-size:20px; padding:8px;">
            &nbsp;&nbsp;🧊 Casos Abiertos en Seguimiento
        </td>

        <td align="right" class="titulo">
            <a href="<?= $url_nuevo_caso ?>" 
               target="_blank" 
               style="color:white; text-decoration:none;">
                <b>+</b>
            </a>&nbsp;&nbsp;&nbsp;
        </td>
    </tr>

    <!-- Encabezado -->
    <tr align="left" class="subtitulo">
        <th>#</th>
        <th>Prioridad</th>
        <th>Número</th>
        <th width="15%">Asunto</th>
        <th>Estado</th>
        <th width="12%">En Espera de</th>
        <th>Categoría</th>
        <th>Asignado a</th>
        <th>Razón Social</th>
        <th width="3%">Fecha<br>Creación</th>
        <th width="3%">Fecha<br>Modificación</th>
    </tr>

    <?= $contenido ?>

</table>