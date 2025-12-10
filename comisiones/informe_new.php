<?php
require_once __DIR__ . '/session_config.php';
include_once("includes/config.php");

if(isset($_POST["submit"])) {
    // Inicializar variables para evitar errores
    $vendedores = "";
    $tipo_factura = "";
    
    // Sanitizar entrada
    function limpiar_input($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    // Validar y asignar valores
    if(!empty($_POST["ejecutivo"])) {
        $vendedores = genera_condicion($_POST["ejecutivo"], "vc.fac_vendedor");
    }
    if(!empty($_POST["Tipo_Factura"])) {
        $tipo_factura = genera_condicion($_POST["Tipo_Factura"], "vc.fac_estado");
    }
    if(!empty($_POST["inicio"])) {
        $fecha_ini = limpiar_input($_POST["inicio"]);
    }
    if(!empty($_POST["fin"])) {
        $fecha_fin = limpiar_input($_POST["fin"]);
    }

    // Convertir fechas para mostrar en el informe
    $desde = date('d-m-Y', strtotime($fecha_ini));
    $hasta = date('d-m-Y', strtotime($fecha_fin));

    // Consulta SQL con filtros
    $sql = "SELECT  
                vc.fac_num,
                vc.fac_url,
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
            WHERE vc.fac_fecha BETWEEN '{$fecha_ini}' AND '{$fecha_fin}' ";

    $groupby = " GROUP BY vc.fac_num";

    $_SESSION["query"] = $sql . $vendedores . $tipo_factura; 
    $_SESSION["agrupar"] = $groupby;
    $_SESSION['orden'] = " ORDER BY vc.fac_num";
    $_SESSION['vendedores'] = $vendedores;
    $_SESSION['tipo_factura'] = $tipo_factura;
    $_SESSION['desde'] = $fecha_ini;
    $_SESSION['hasta'] = $fecha_fin;
} else {
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once("../meta_data/meta_data.html"); ?>   
    <title>Comisiones iContel Telecomunicaciones</title>
    <style>
        html, body, div {
            margin: 0;
            padding: 0;
            height: 100%;
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
            padding: 0;
            margin: 0;
            display: block; 
            width: 100%;  
            height: 90%;
        }
        footer {
            background-color: white;
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 25px;
            color: gray;
            font-size: 12px;
        }
        /* Estilos de enlaces */
        a {
            color: darkslategrey;
            text-decoration: none;
        }
        a:hover {
            color: darkgrey;
            font-size: 20px;
            font-weight: bold;
        }
        a:active {
            color: blue;
        }
    </style>
    <script>
        function abre_url(url) {
            window.open(url, "_blank"); 
        }
    </script>
</head>
<body>
    <table align="center" border="0" width="100%" bgcolor="#1F1D3E">
        <tr align="center" style="color: white; background-color: #1F1D3E;">
            <th width="200" valign="top" align="left">
                <img src="images/logo_icontel_azul.jpg" height="60" alt=""/>
            </th>
            <td>
                <table width="100%" height="100%" border="0" bgcolor="#1F1D3E">
                    <tr>
                        <th colspan="2" align="center" style="font-size: 20px;">
                            Informe de Comisiones entre el <?php echo "{$desde} y el {$hasta}"; ?>
                        </th>
                    </tr>
                    <tr style="color: white; background-color: #1F1D3E;">
                        <td align="center" style="font-size: 12px;">
                            (Click sobre los títulos para ordenar)
                        </td>
                        <td align="right">
                            <span onclick="abre_url('informe_resumen.php')" style="cursor: pointer;"><b>Resumen</b></span>
                        </td>
                    </tr>
                </table>
            </td>    
        </tr>
    </table>
    <iframe src="sort/tabla.php"></iframe>
</body>
<?php echo isset($footer) ? $footer : ""; ?>
</html>
