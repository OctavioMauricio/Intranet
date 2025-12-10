<?php
// ==========================================================
// KickOff AJAX – Cobranza Comercial
// /kickoff_ajax/cm_cobranza_comercial.php
// Autor: Mauricio Araneda (mAo)
// Fecha: 2025-12
// Codificación: UTF-8 sin BOM
// ==========================================================

mb_internal_encoding("UTF-8");

// Bootstrap AJAX (sesión + $sg_id + $sg_name + DbConnect)
require_once __DIR__ . "/ajax_bootstrap.php";

// Seguridad mínima
if ($sg_id === "" || $sg_name === "") {
    echo "<div style='padding:20px; color:red;'>❌ Error: sesión inválida.</div>";
    exit;
}

// ---------------------------------------------
// Cargar el INCLUDE ORIGINAL
// (usa las mismas funciones, SPs y formato)
// ---------------------------------------------
require_once __DIR__ . "/includes/cm_cobranza_comercia_include.php";
?>

<link rel="stylesheet" href="css/cm_cobranza_comercial.css?v=1">

<div id="modulo_cobranza_comercial">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="11" class="titulo">
                &nbsp;&nbsp;📊💰 Cobranza Comercial
            </td>

            <td align="right" class="titulo">
                <span id="reloadModulo" class="reload-off">🔄</span>&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
    </table>

    <table id="cobranza">
        <tr class="subtitulo">
            <th>#</th>
            <th width="20%" class="sortable" data-col="razon">Razón Social</th>
            <th width="15%" class="sortable" data-col="estado">Estado Sweet</th>
            <th width="20%" class="sortable" data-col="comentario">Comentario</th>
            <th style="text-align:right;" class="sortable" data-col="monto">Monto Bruto</th>
            <th class="sortable" data-col="docs">Docs</th>
            <th class="sortable" data-col="dias_venc">Días Venc.</th>
            <th class="sortable" data-col="ejecutivo">Ejecutivo</th>
            <th class="sortable" data-col="fecha">F. Modif</th>
            <th class="sortable" data-col="dias">Días</th>
        </tr>

        <?= $contenido ?>
    </table>

</div>

<script src="js/cm_cobranza_comercial.js?v=<?= time() ?>"></script>
