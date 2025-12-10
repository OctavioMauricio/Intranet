<?php
// ==========================================================
// /comisiones/informe.php
// Informe de Comisiones – Procesador y Visualizador
// Autor: Mauricio Araneda
// Fecha: 2025-11-05
// Codificación: UTF-8 sin BOM
// ==========================================================

// ------------------------------------------
// Sesión unificada Comisiones (TNA Group)
// ------------------------------------------
session_name('icontel_intranet_sess');

ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '.icontel.cl');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ------------------------------------------
// Configuración base
// ------------------------------------------
include_once("includes/config.php");

// Validación de acceso
if (!isset($_POST['submit'])) {
    echo "<h3>Error: Acceso inválido.</h3>";
    exit;
}

// ==========================================================
// 1) SANITIZAR Y RECIBIR VARIABLES
// ==========================================================
function limpiar_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

$fecha_ini = limpiar_input($_POST["inicio"]);
$fecha_fin = limpiar_input($_POST["fin"]);

$ejecutivos    = isset($_POST["ejecutivo"]) ? $_POST["ejecutivo"] : [];
$tipos_factura = isset($_POST["tipo_factura"]) ? $_POST["tipo_factura"] : [];

$desde = date('d-m-Y', strtotime($fecha_ini));
$hasta = date('d-m-Y', strtotime($fecha_fin));

// Guardar valores para pasos siguientes
$_SESSION['com_inicio']     = $fecha_ini;
$_SESSION['com_fin']        = $fecha_fin;
$_SESSION['com_ejecutivos'] = $ejecutivos;
$_SESSION['com_tipos']      = $tipos_factura;

// ==========================================================
// 2) FILTROS SQL
// ==========================================================
$vendedores_sql = "";
$tipo_sql       = "";

if (!empty($ejecutivos)) {
    $vendedores_sql = genera_condicion($ejecutivos, "vc.fac_vendedor");
}
if (!empty($tipos_factura)) {
    $tipo_sql = genera_condicion($tipos_factura, "vc.fac_estado");
}

// ==========================================================
// 3) GENERAR TABLA TEMPORAL
// ==========================================================
$query_tmp = "CALL ventas_comisiones_periodo('{$fecha_ini}', '{$fecha_fin}')";
$tmp = recrea_base_comisiones($query_tmp);

// ==========================================================
// 4) QUERY PRINCIPAL PARA TABLA
// ==========================================================
$sql_base = "
    SELECT  
        vc.fac_num,
        vc.coti_num,
        vc.fac_url,
        vc.coti_url,
        vc.fac_fecha,
        vc.fechacierre,
        vc.meta_uf,
        vc.cierre_uf,
        vc.cumplimiento,
        vc.comision,
        SUM(vc.neto_uf) AS neto_uf,
        SUM(vc.costo_uf) AS costo_uf,
        SUM(vc.neto_uf) - SUM(vc.costo_uf) AS margen_uf,
        SUM(vc.neto_comi_uf) AS neto_comi_uf,
        SUM(vc.comision_uf) AS comision_uf,
        SUM(vc.comi_sgv_uf) AS comi_sgv_uf,
        vc.fac_estado,
        vc.fac_coti,
        vc.fac_nv,
        vc.fac_cliente,
        vc.fac_vendedor
    FROM ventas_comisiones AS vc
    WHERE vc.fac_fecha BETWEEN '{$fecha_ini}' AND '{$fecha_fin}'
";

$groupby = "
 GROUP BY 
    vc.fac_num, vc.coti_num, vc.fac_url, vc.coti_url, vc.fac_fecha,
    vc.fechacierre, vc.meta_uf, vc.cierre_uf, vc.cumplimiento, vc.comision,
    vc.fac_estado, vc.fac_coti, vc.fac_nv, vc.fac_cliente, vc.fac_vendedor
";

// Guardar para sort/tabla.php
$_SESSION["query"]        = $sql_base . $vendedores_sql . $tipo_sql;
$_SESSION["agrupar"]      = $groupby;
$_SESSION['orden']        = " ORDER BY vc.fac_num";
$_SESSION['vendedores']   = $vendedores_sql;
$_SESSION['tipo_factura'] = $tipo_sql;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once("../meta_data/meta_data.html"); ?>   
    <meta charset="UTF-8">
    <title>Comisiones iContel Telecomunicaciones</title>

    <style>
        html, body, div { margin:0; padding:0; height:100%; }
        table {
            color: white;
            border-collapse: collapse;
            font-size: 15px;
            background-color: #19173C;
        }
        iframe {
            width: 100%;
            height: 90%;
            border: none;
            display: block;
        }
        a { color: darkslategrey; text-decoration: none; }
        a:hover { color: darkgrey; font-weight: bold; }
    </style>

    <script>
        function abre_url(url) {
            window.open(url, "_blank");
        }
    </script>
</head>

<body>

<table width="100%" bgcolor="#1F1D3E">
    <tr style="color:white;">
        <th width="200" valign="top" align="left">
            <img src="images/logo_icontel_azul.jpg" height="60" alt=""/>
        </th>
        <td>
            <table width="100%" bgcolor="#1F1D3E">
                <tr>
                    <th colspan="2" style="font-size:20px;">
                        Informe de Comisiones entre el <?php echo "{$desde} y el {$hasta}"; ?>
                    </th>
                </tr>
                <tr>
                    <td style="font-size:12px;" align="center">
                        (Click sobre los títulos para ordenar)
                    </td>
                    <td align="right">
                        <span onclick="abre_url('informe_resumen.php')" style="cursor:pointer;"><b>Resumen</b></span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<iframe src="sort/tabla.php"></iframe>

</body>
</html>
