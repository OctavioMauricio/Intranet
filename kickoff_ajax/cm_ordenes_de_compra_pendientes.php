<?php
// ==========================================================
// KickOff AJAX – Órdenes de Compra Pendientes
// /kickoff_ajax/cm_ordenes_de_compra_pendientes.php
// Autor: Mauricio Araneda (mAo)
// Fecha: 2025-12
// Codificación: UTF-8 sin BOM
// ==========================================================

mb_internal_encoding("UTF-8");

// ------------------------------------------------------
// Cargar bootstrap AJAX (sesión + $sg_id + DbConnect)
// ------------------------------------------------------
require_once __DIR__ . "/ajax_bootstrap.php";

// Seguridad mínima
if ($sg_id === "" || $sg_name === "") {
    echo "<div style='padding:20px; color:red;'>❌ Error: sesión inválida.</div>";
    exit;
}

// ------------------------------------------------------
// Conexión a SweetCRM
// ------------------------------------------------------
$conn = DbConnect($db_sweet);
$conn->set_charset("utf8mb4");

// Procedimiento almacenado
$sql = "CALL CM_Ordenes_de_Compra_Pendientes()";
$resultado = $conn->query($sql);

$ptr       = 0;
$contenido = "";
$muestra   = ($resultado && $resultado->num_rows > 0);

// ------------------------------------------------------
// Construcción de filas
// ------------------------------------------------------
if ($muestra) {

    while ($lin = $resultado->fetch_assoc()) {

        $ptr++;

        // ===============================
        //  COLOR POR ESTADO DE FACTURA
        // ===============================
        switch ($lin["coti_estado_factura"]) {

            case "DTE Pagado":

                if ($lin["opor_estado"] == "Facturado / Cerrado") {
                    $color = "color:orangered;";
                } else {
                    $color = "color:orange;";
                }
                break;

            default:
                $color = "color:#333;";
        }

        $contenido .= "<tr style='{$color}'>";

        // Celdas seguras
        $contenido .= "<td width='1%'>{$ptr}</td>";

       
        $contenido .= "<td width='1%'>" . htmlspecialchars($lin["coti_numero"]) . "</td>";

        $contenido .= '<td colspan="2"><a target="_blank" href="' .
                       htmlspecialchars($lin["url_coti"]) . '">' .
                       htmlspecialchars($lin["coti_titulo"]) . '</a></td>';

        $contenido .= "<td>" . htmlspecialchars($lin["coti_estado_factura"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($lin["numero_dte"]) . "</td>";
        $contenido .= "<td align='right'>" . htmlspecialchars($lin["moneda"]) . "</td>";

        // Formato numérico según moneda
        if ($lin["moneda"] == "$") {
            $monto = number_format($lin["coti_neto"], 0);
        } else {
            $monto = number_format($lin["coti_neto"], 2);
        }

        $contenido .= "<td align='right'>{$monto}</td>";

        $contenido .= '<td align="right"><a target="_blank" href="' .
                       htmlspecialchars($lin["url_opor"]) . '">' .
                       htmlspecialchars($lin["opor_numero"]) . '</a></td>';

        $contenido .= "<td>" . htmlspecialchars($lin["op_nombre"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($lin["opor_estado"]) . "</td>";
        $contenido .= "<td>" . htmlspecialchars($lin["account"]) . "</td>";

        $contenido .= "</tr>";
    }

} else {

    $contenido = "
        <tr>
            <td colspan='12' style='text-align:center; padding:12px; color:#666;'>
                ⚠️ No se encontraron Órdenes de Compra pendientes.
            </td>
        </tr>";

}

$conn->close();
unset($resultado);
unset($conn);

// URL nueva cotización
$url_nueva_cotizacion = "https://sweet.icontel.cl/index.php?module=AOS_Quotes&action=EditView";

?>

<!-- ======================================================= -->
<!--  TABLA ÓRDENES DE COMPRA PENDIENTES — VERSIÓN AJAX       -->
<!-- ======================================================= -->

<table id="Ordenes_de_Compra" width="100%" cellspacing="0" cellpadding="0" border="0">

    <!-- Título -->
    <tr style="background:#512554; color:white;">
        <td colspan="11" class="titulo" style="padding:8px;">
            &nbsp;&nbsp;🧾 Órdenes de Compra Pendientes
        </td>
        <td align="right" style="padding-right:15px;">
            <a href="<?= $url_nueva_cotizacion ?>" 
               target="new"
               style="color:white; font-size:22px; text-decoration:none;"
               title="Crear Nueva Cotización">
               +
            </a>
        </td>
    </tr>

    <!-- Encabezado -->
    <tr class="subtitulo">
        <th width="1%">#</th>
        <th width="1%">N°</th>
        <th width="8%" colspan="2">Asunto</th>
        <th width="2%">Estado</th>
        <th width="2%">N° DTE</th>
        <th width="1%" align="center">$</th>
        <th width="1%" align="center">Bruto</th>
        <th width="1%" align="right">OP #</th>
        <th width="8%">OP Nombre</th>
        <th width="2%">OP Etapa/Estado</th>
        <th width="8%">Proveedor</th>
    </tr>

    <?= $contenido ?>

</table>
