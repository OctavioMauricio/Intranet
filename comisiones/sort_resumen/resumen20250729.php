 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
    <?PHP include_once("../meta_data/meta_data.html"); ?>   
      <title>Resumen Comisiones iContel Telecomunicaciones</title>
     <style type="text/css">
        html, body, div {
            margin:0;
            padding:0;
            height:100%;
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
        }
        table{
               border: none;
               background-color: white;
               color: #1F1D3E;;
               font-size: 15px;
               border-collapse: collapse;
               background-color: #19173C;
               border-collapse: collapse;

           }  
         th{
             background-color: #1F1D3E;
             color: white;
         }
         td {
            background-color: white;
             color: #1F1D3E;
             
         }
        iframe {
            border: none;
            border-collapse: collapse;
            padding: 0;
            margin: 0;
            display:block; 
            width:100%;  
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
            /* unvisited link */
            a:link {
              color: darkslategrey;
            }

            /* visited link */
            a:visited {
              color: white;
            }

            /* mouse over link */
            a:hover {
              color: darkgrey;
              font-size: 20px;
              font-weight: bold;
            }

            /* selected link */
            a:active {
                color: blue; }   
        </style>
 </head>
 <body>

<?php 
	session_start();
	include "config.php"; 
    $desde = strtotime($_SESSION['desde']);
    $hasta = strtotime($_SESSION['hasta']);
?>
<?php
try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener los datos
    $query = "
        SELECT vc.fac_vendedor,
               vc.fac_estado,
               SUM(vc.comision_uf) AS comision_uf,
               SUM(vc.comi_sgv_uf) AS comi_sgv_uf
        FROM ventas_comisiones AS vc
        WHERE vc.fac_fecha BETWEEN '".$_SESSION['desde']."' AND '".$_SESSION['hasta']."'";
     $query .= $_SESSION['vendedores'] .
        " GROUP BY vc.fac_vendedor, vc.fac_estado
        ORDER BY vc.fac_vendedor
    ";

    // Ejecutar la consulta
    $stmt = $pdo->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Procesar los resultados
    $summary = [];
    $sgv_summary = ['Unica' => 0, 'Recurrente' => 0, 'Total' => 0];
    foreach ($results as $row) {
        $vendedor = $row['fac_vendedor'];
        $estado = $row['fac_estado'];
        $comision_uf = (float)$row['comision_uf'];
        $comi_sgv_uf = (float)$row['comi_sgv_uf'];

        // Procesar datos por vendedor
        if (!isset($summary[$vendedor])) {
            $summary[$vendedor] = [
                'Unica' => 0,
                'Recurrente' => 0,
                'Total' => 0
            ];
        }

        if ($estado === 'Unica') {
            $summary[$vendedor]['Unica'] += $comision_uf;
            $sgv_summary['Unica'] += $comi_sgv_uf;
        } else {
            $summary[$vendedor]['Recurrente'] += $comision_uf;
            $sgv_summary['Recurrente'] += $comi_sgv_uf;
        }

        $summary[$vendedor]['Total'] = $summary[$vendedor]['Unica'] + $summary[$vendedor]['Recurrente'];
    }

    // Calcular total SGV
    $sgv_summary['Total'] = $sgv_summary['Unica'] + $sgv_summary['Recurrente'];

    // Calcular totales generales
    $totals = ['Unica' => 0, 'Recurrente' => 0, 'Total' => 0];
    foreach ($summary as $vendedor => $data) {
        foreach ($totals as $key => &$total) {
            $total += $data[$key];
        }
    }

    // Generar la tabla HTML
    ?> 
     <table border='1'  align="center" width="450px">
        <thead>
            <tr>
                <th colspan='4' align="center" style='font-size: 15px'><strong>Comisiones por Ejecutiv@
                    desde el <?PHP echo date('d/m/Y', $desde)." al ".date('d/m/Y', $hasta); ?>
                    
                    </strong></th>
            </tr>
            <tr>
                <th align="left">Ejecutiv@</th>
                <th>Unica UF</th>
                <th>Recurrente UF</th>
                <th>Total UF</th>
            </tr>
          </thead>
          <tbody>
<?PHP
    // Filas por vendedor
    foreach ($summary as $vendedor => $data) {
        echo "<tr>
                <td align='left'>{$vendedor}</td>
                <td align='right'>".number_format($data['Unica'],2)."</td>
                <td align='right'>".number_format($data['Recurrente'],2)."</td>
                <td align='right'>".number_format($data['Total'],2)."</td>
              </tr>";
    }

 
    // Fila de totales
    echo "<tr>
            <td align='left'><strong>Totales</strong></td>
            <th align='right'><strong>".number_format($totals['Unica'],2)."</strong></th>
            <th align='right'><strong>".number_format($totals['Recurrente'],2)."</strong></th>
            <th align='right'><strong>".number_format($totals['Total'],2)."</strong></th>
          </tr>
          <tr><td colspan='4' style='font-size: 15'>.    </td></tr>";    
    // Línea SGV
    echo "<tr>
            <td align='left'>Ghislaine Rivera</td>
            <td align='right'>".number_format($sgv_summary['Unica'],2)."</td>
            <td align='right'>".number_format($sgv_summary['Recurrente'],2)."</td>
            <td align='right'>".number_format($sgv_summary['Total'],2)."</td>
          </tr>";


    echo "</tbody>";
    echo "</table>";
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
     
   </body>
</html>
     
