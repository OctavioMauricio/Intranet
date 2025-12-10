<?php
// ==========================================================
// /comisiones/sort/tabla.php
// Tabla Principal – Informe de Comisiones
// Autor: Mauricio Araneda
// Fecha: 2025-11-05
// Codificación: UTF-8 sin BOM
// ==========================================================
// ==========================================
// Configuración global de sesión para Comisiones
// ==========================================
session_name('icontel_intranet_sess');

// Asegurar cookie válida para todos los subdominios
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '.icontel.cl');

// Seguridad
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// --- Validación mínima ---
if (
    empty($_SESSION['query']) ||
    empty($_SESSION['agrupar']) ||
    empty($_SESSION['orden'])
) {
    echo "<h3>Error: Falta información de sesión para generar la tabla.</h3>";
    exit;
}

// --- Construir query final ---
$query = $_SESSION['query'] . $_SESSION['agrupar'] . $_SESSION['orden'];

// --- Configuración del informe ---
include "config.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once("../../meta_data/meta_data.html"); ?>
    <title>Comisiones de Ventas</title>

    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="jquery-3.3.1.min.js"></script>
    <script src="script.js"></script>

    <style>
        table {
            border: none;
            color: black;
            font-size: 10px;
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 4px;
            font-size: 12px;
        }
        th {
            background-color: #1F1D3E;
            color: white;
            cursor: pointer;
        }
        body {
            margin: 0;
            padding: 0;
            font-size: 10px;
            background-color: #FFFFFF;
            color: #1F1D3E;
        }
        table tbody tr:nth-child(odd) {
            background: #F6F9FA;
        }
        table tbody tr:nth-child(even) {
            background: #FFFFFF;
        }
    </style>

    <script>
        // Exportación Excel
        function exportToExcel(tableId){
            let tableData = document.getElementById(tableId).outerHTML;
            tableData = tableData.replace(/<A[^>]*>|<\/A>/g, ""); 
            tableData = tableData.replace(/<input[^>]*>|<\/input>/gi, "");

            let a = document.createElement('a');
            a.href = `data:application/vnd.ms-excel, ${encodeURIComponent(tableData)}`;
            a.download = 'Comisiones_' + getRandomNumbers() + '.xls';
            a.click();
        }

        function getRandomNumbers() {
            let d = new Date();
            return `${d.getHours()}${d.getMinutes()}${d.getSeconds()}${Math.floor(Math.random()*100)}`;
        }
    </script>
</head>

<body>
    <div class="container">
        <input type="hidden" id="sort" value="asc">

        <table id="empTable" border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>#</th>
                    <th data-column="coti_num">Coti N°</th>
                    <th data-column="fac_num">Fact N°</th>
                    <th data-column="fac_fecha">Fecha</th>
                    <th data-column="fac_cliente">Cliente</th>
                    <th data-column="fac_estado">Tipo</th>
                    <th data-column="fac_vendedor">Ejecutiv@</th>
                    <th data-column="fechacierre">Fecha Cierre</th>
                    <th data-column="meta_uf">Meta UF</th>
                    <th data-column="cierre_uf">Cierre UF</th>
                    <th data-column="cumplimiento">% Cumplimiento</th>
                    <th data-column="comision">% Comisión</th>
                    <th data-column="neto_uf">Neto UF</th>
                    <th data-column="costo_uf">Costo UF</th>
                    <th data-column="margen_uf">Margen UF</th>
                    <th data-column="neto_comi_uf">Neto Comi</th>
                    <th data-column="comision_uf">Comisión</th>
                    <th data-column="comi_sgv_uf">Comi SGV</th>
                </tr>
            </thead>

            <tbody>
                <?php include_once("tabla_datos.php"); ?>
            </tbody>
        </table>

    </div>
</body>
</html>
