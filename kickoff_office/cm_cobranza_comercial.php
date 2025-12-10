<?php
// ==========================================================
// Cobranza Comercial - iContel / TNA Group
// kickoff/cm_cobranza_comercial.php
// Descripci�n: Vista principal del m�dulo de cobranza comercial.
// Autor: Mauricio Araneda
// Fecha: 2025-11-20
// Codificaci�n: UTF-8 sin BOM
// ==========================================================

// Iniciar sesión si no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Configuraci�n general
require_once "config.php";

// Cargar el include que genera $contenido y $ptr
require_once "includes/cm_cobranza_comercia_include.php";
?>

<!-- CSS DEL M�DULO -->
<link rel="stylesheet" href="css/cm_cobranza_comercial.css?v=1">

<div id="modulo_cobranza_comercial">

    <!-- TÍTULO SUPERIOR -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="11" class="titulo">
                &nbsp;&nbsp;💰📄 Cobranza Comercial
            </td>
            <td align="right" class="titulo">
                <span id="reloadModulo" class="reload-off">🔄</span>&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
    </table>

    <!-- TABLA DE RESULTADOS -->
    <table id="cobranza">
        <tr class="subtitulo">
            <th>#</th>
            <th width="20%">Razón Social</th>
            <th width="15%">Estado Sweet</th>
            <th>Comentario</th>
            <th style="text-align:right;">Monto Bruto</th>
            <th>Docs</th>
            <th>Días Venc.</th>
            <th>Ejecutivo</th>
            <th>F. Modif</th>
            <th>Días</th>
        </tr>

        <?= $contenido ?>
    </table>

    <!-- TOGGLE DE MÓDULO -->
    <div id="toggleWrapper">
        Cobranza Comercial <span id="toggleTexto">[Ocultar <?= $ptr ?>]</span>
    </div>

    <!-- JavaScript del módulo -->
    <script src="js/cm_cobranza_comercial.js?v=1"></script>

</div>