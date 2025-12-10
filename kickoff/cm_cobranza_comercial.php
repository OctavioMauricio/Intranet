<?php
// ==========================================================
// KickOff AJAX – Cobranza Comercial
// /kickoff_ajax/cm_cobranza_comercial.php
// Autor: Mauricio Araneda (mAo)
// Fecha: 2025-12
// Codificación: UTF-8 sin BOM
// ==========================================================

mb_internal_encoding("UTF-8");

// Iniciar sesión si no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Forzar cabecera de codificación
header('Content-Type: text/html; charset=utf-8');

// Configuración general
require_once "config.php";

// Seguridad mínima
if ($sg_id === "" || $sg_name === "") {
    echo "<div style='padding:20px; color:red;'>❗ Error: sesión inválida.</div>";
    exit;
}

// Cargar el INCLUDE ORIGINAL (SP, funciones, contenido, colores de filas, etc.)
require_once __DIR__ . "/includes/cm_cobranza_comercia_include.php";
?>

<link rel="stylesheet" href="css/cm_cobranza_comercial.css?v=1">

<style>
/* ==========================================================
   ESTILO MODERNO - TÍTULO + SUBTÍTULO DE COLUMNAS
   Mantiene look elegante SIN perder colores dinámicos
   ========================================================== */

#cobranza {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

/* ===========================
   ENCABEZADO (modern glass)
   =========================== */
#cobranza thead th,
#cobranza tr.subtitulo th {
    background: rgba(255, 255, 255, 0.15); /* transparente elegante */
    backdrop-filter: blur(8px);
    color: #C39BD3 !important;            /* violeta corporativo */
    font-weight: 700;
    padding: 10px 6px;
    border-bottom: 2px solid #d4c4dd;
    text-align: center;
}

/* ===========================
   Título principal
   =========================== */
#modulo_cobranza_comercial .titulo {
    background: rgba(81,37,84,0.25); /* violeta soft */
    backdrop-filter: blur(6px);
    color: #C39BD3 !important;
    font-size: 20px;
    font-weight: 700;
    padding: 10px;
}

/* ===========================
   Celdas generales
   =========================== */
#cobranza tr td {
    padding: 6px 6px;
    border-bottom: 1px solid #eee;
    background: transparent !important;
}

/* ==========================================================
   RESTAURAR COLORES DINÁMICOS DE FILAS (PHP)
   ========================================================== */
#cobranza tr[style] td,
#cobranza tr[style] a {
    color: inherit !important; /* respeta color rojo/naranja/verde del SP */
    font-weight: 500;
}

/* Hover elegante (NO afecta colores dinámicos) */
#cobranza tr:hover td {
    background: rgba(0,0,0,0.04) !important;
}

/* Ícono de recarga */
#reloadModulo {
    cursor: pointer;
    font-size: 22px;
}
</style>

<div id="modulo_cobranza_comercial">

    <!-- =======================
         TÍTULO DEL MÓDULO
         ======================= -->
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

    <!-- =======================
         TABLA DE COBRANZA
         ======================= -->
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

<script src="js/cm_cobranza_comercial_v2.js"></script>
