<?php
// ==========================================================
// 🧩 Sistema de Factibilidades – TNA Solutions
// Archivo: /home/icontel/public_html/intranet/factibilidades/index.php
// Descripción: Página principal del módulo Factibilidades
//               Carga header fijo, iframe de contenido y footer corporativo.
// Autor: Mauricio Araneda (mAo)
// Última actualización: 11-11-2025
// ==========================================================

// --- Activar errores solo en desarrollo ---
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

//include_once("./session.php");

// --- Determinar qué archivo se carga en el iframe ---
$iframe = "cab_det_new.php";
if (isset($_GET['fac']) && is_numeric($_GET['fac'])) {
    $fac = $_GET['fac'];
    $iframe .= "?fac=" . $fac;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <?php include_once("meta_data/meta_data.html"); ?>
  <meta charset="utf-8">
  <title>Factibilidades iConTel SpA</title>

  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden; /* evita scroll del body */
      font-family: Arial, sans-serif;
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 70px;
      background-color: #1F1D3E; /* Color corporativo */
      border: none; /* ✅ sin bordes */
      z-index: 1000;
    }

    iframe {
      display: block;
      position: absolute;
      top: 70px; /* debajo del header */
      bottom: 30px; /* encima del footer */
      left: 0;
      width: 100%;
      height: calc(100% - 100px);
      border: none;
      overflow: auto;
    }

    footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 30px;
      background-color: #1F1D3E; /* mismo color que el header */
      color: #ffffff; /* texto blanco */
      font-size: 12px;
      text-align: center;
      line-height: 30px;
      border: none;
      z-index: 1000;
    }

    a:link, a:visited {
      color: gray;
      text-decoration: none;
    }

    a:hover {
      color: darkgrey;
      font-weight: bold;
    }
  </style>
    
</head>
<body>

  <!-- ✅ Header fijo -->
  <header>
  <?php
        $tituloApp = "Factibilidades de Enlaces"; // título que mostrará el header
        include_once("header/header1.php");
    ?>
    <!--table align="center" border="0" width="100%">
      <tr align="center" style="color: white;">
        <th width="200" height="70" valign="middle" align="left">
          <img src="../images/tna_group.png" height="60" alt="Logo iContel" style="margin-left:15px;"/>
        </th>
        <td>
          <table width="100%" height="100%">
            <tr height="35">
              <th align="center" style="font-size: 22px;">
                Factibilidades
              </th>
            </tr>
            <tr>
              <td align="center" style="font-size: 13px; color:#E0E0E0;">
                (Click sobre los Títulos para ordenar)
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table-->
  </header>

  <!-- ✅ Contenido central -->
  <iframe src="<?php echo htmlspecialchars($iframe, ENT_QUOTES, 'UTF-8'); ?>"></iframe>

  <!-- ✅ Footer fijo -->
  <footer>
    <?php include_once("footer/footer.php"); ?>
  </footer>

</body>
</html>