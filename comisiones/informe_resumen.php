<?php
// ==========================================================
// /comisiones/informe_resumen.php
// Informe de Comisiones – Resumen por Ejecutivo y Estado
// Autor: Mauricio Araneda
// Fecha: 2025-11-05
// Codificación: UTF-8 sin BOM
// ==========================================================

// ==========================================
// Configuración global de sesión para Comisiones
// ==========================================
session_name('icontel_intranet_sess');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '.icontel.cl');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ------------------------------------------
// Configuración
// ------------------------------------------
include_once("includes/config.php");

// Validación correcta
if (
    empty($_SESSION['query']) ||
    empty($_SESSION['com_inicio']) ||
    empty($_SESSION['com_fin'])
) {
    echo "<h3 style='color:red'>Error: No existe información para generar el resumen.</h3>";
    exit;
}

// Fechas para el título
$desde = date('d-m-Y', strtotime($_SESSION['com_inicio']));
$hasta = date('d-m-Y', strtotime($_SESSION['com_fin']));

// Query base desde sesión
$sql_base = $_SESSION['query'];

// ------------------------------------------
// Agrupación exclusiva para el RESUMEN
// ------------------------------------------
$groupby_resumen = " GROUP BY vc.fac_vendedor, vc.fac_estado ";
$orderby_resumen = " ORDER BY vc.fac_vendedor ";

// Guardar solo para el resumen
$_SESSION['resumen_group'] = $groupby_resumen;
$_SESSION['resumen_order'] = $orderby_resumen;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once("../meta_data/meta_data.html"); ?>
    <meta charset="UTF-8">
    <title>Resumen Comisiones iContel Telecomunicaciones</title>

<style>
    html, body, div {
        margin:0;
        padding:0;
        height:100%;
    }
    table {
        border: none;
        color: white;
        font-size: 15px;
        border-collapse: collapse;
        background-color: #19173C;
    }
    iframe {
        border: none;
        width:100%;
        height:90%;
        display:block;
    }
    a { color: darkslategrey; }
    a:hover {
        color: darkgrey;
        font-size: 20px;
        font-weight: bold;
    }
</style>
</head>

<body>

<table width="100%" bgcolor="#1F1D3E">
    <tr style="color:white;">
        <th width="200" align="left" valign="top">
            <img src="images/logo_icontel_azul.jpg" height="60" alt="">
        </th>
        <td>
            <table width="100%" bgcolor="#1F1D3E">
                <tr>
                    <th colspan="2" style="font-size:20px;">
                        Resumen de Comisiones entre el <?php echo "{$desde} y el {$hasta}"; ?>
                    </th>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Cargar resumen -->
<iframe src="sort_resumen/tabla.php"></iframe>

</body>
</html>
