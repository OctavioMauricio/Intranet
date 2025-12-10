<?php
// ==========================================================
// KickOff AJAX â€“ Casos Abiertos de Baja
// /kickoff_ajax/cm_casos_abiertos_debaja.php
// Autor: Mauricio Araneda (mAo)
// Fecha: 2025-12
// ==========================================================

mb_internal_encoding("UTF-8");

// ------------------------------------------------------
// CARGAR CONTEXTO COMPLETO (igual que cm_casos_abiertos)
// ------------------------------------------------------
include_once("config.php");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$sg_id   = $_SESSION['sg_id']   ?? '';
$sg_name = $_SESSION['sg_name'] ?? '';

if ($sg_id === '') {
    echo "<div style='padding:20px; color:red;'>Error: no hay grupo asignado.</div>";
    exit;
}

// ------------------------------------------------------
// CONEXION BASE DE DATOS
// ------------------------------------------------------
$conn = DbConnect($db_sweet);

if (!$conn) {
    echo "<div style='padding:20px; color:red;'>Error de conexiÃ³n a la base de datos.</div>";
    exit;
}

// ------------------------------------------------------
// CONSULTA -> SP ORIGINAL
// ------------------------------------------------------
// $sql = "CALL Kickoff_Casos_Abiertos_DeBaja('" . $conn->real_escape_string($sg_id) . "')";
$sql = "CALL Kick_Off_Casos_Abiertos_de_baja()";
$result = $conn->query($sql);

if (!$result) {
    echo "<div style='padding:20px; color:red;'>Error en consulta SQL.</div>";
    exit;
}

// ------------------------------------------------------
// RENDERIZACION
// ------------------------------------------------------
$ptr = 0;
$contenido = "";

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $ptr++;

        // Color por prioridad
        switch ($row["prioridad"]) {
            case "P1E": $contenido .= '<tr style="color:red">'; break;
            case "P1":  $contenido .= '<tr style="color:orangered">'; break;
            case "P2":  $contenido .= '<tr style="color:orange">'; break;
            case "P3":  $contenido .= '<tr style="color:dimgray">'; break;
            default:    $contenido .= '<tr>';
        }

        $contenido .= "<td>{$ptr}</td>";
        $contenido .= '<td><a target="_blank" href="'.$row["url_caso"].'">'.$row["numero"].'</a></td>';
        $contenido .= "<td>{$row["prioridad_descr"]}</td>";
        $contenido .= "<td>{$row["asunto"]}</td>";
        $contenido .= "<td>{$row["estado"]}</td>";
        $contenido .= "<td>{$row["en_espera_de"]}</td>";
        $contenido .= "<td>{$row["categoria"]}</td>";
        $contenido .= "<td>{$row["nombre"]} {$row["apellido"]}</td>";
        $contenido .= "<td>{$row["cliente"]}</td>";
        $contenido .= "<td>{$row["f_creacion"]}</td>";
        $contenido .= "<td>{$row["f_modifica"]}</td>";
        $contenido .= "<td align='right'>{$row["dias"]}&nbsp;&nbsp;</td>";
        $contenido .= "</tr>";
    }

} else {
    $contenido = "<tr><td colspan='12' style='padding:12px;'>No hay casos abiertos de baja</td></tr>";
}

$conn->close();
?>

<!-- ==========================================================
     TABLA (SIN BOTONES OCULTAR)
========================================================== -->
<table id="casos_abiertos_debaja" border="0" align="center" width="100%" cellpadding="0" cellspacing="0">

    <tr style="color:white; background-color:#512554;">
        <td colspan="11" class="titulo" style="font-weight:bold; height:38px;">
            &nbsp;&nbsp;📁 Casos Abiertos De Baja
        </td>
        <td>&nbsp;</td>
    </tr>

    <tr style="color:white; background-color:#512554;">
        <th width="1%"  class="subtitulo">#</th>
        <th width="2%"  class="subtitulo">NÂº</th>
        <th width="2%"  class="subtitulo">Prioridad</th>
        <th width="11%" class="subtitulo">Asunto</th>
        <th width="2%"  class="subtitulo">Estado</th>
        <th width="8%"  class="subtitulo">En Espera De</th>
        <th width="3%"  class="subtitulo">CategorÃ­a</th>
        <th width="5%"  class="subtitulo">Asignado a</th>
        <th width="15%" class="subtitulo">RazÃ³n Social</th>
        <th width="3%"  class="subtitulo">F.CreaciÃ³n</th>
        <th width="3%"  class="subtitulo">F.Modif.</th>
        <th width="2%"  class="subtitulo" align="right">DÃ­as&nbsp;&nbsp;</th>
    </tr>

    <?= $contenido ?>

</table>
