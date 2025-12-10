<?php
// ==========================================================
// duemint/informe.php
// INFORME DE CLIENTES DUEMINT - FORMATO TNA
// Autor: Mauricio Araneda
// Fecha: 2025-11-05
// ==========================================================
//include_once(__DIR__ . '/../includes/security_check.php');

// --- Parámetros del buscador (GET) ---
$rut           = isset($_GET["rut"]) ? trim($_GET["rut"]) : '';
$nombre        = isset($_GET["nombre"]) ? trim($_GET["nombre"]) : '';
$status        = isset($_GET["status"]) ? intval($_GET["status"]) : 0;
$min_docs      = isset($_GET["min_docs"]) ? intval($_GET["min_docs"]) : 0;
$dias_status   = isset($_GET["dias_status"]) ? intval($_GET["dias_status"]) : 0;

// 🔥 NUEVO: Días vencidos
$dias_vencidos = isset($_GET["dias_vencidos"]) ? intval($_GET["dias_vencidos"]) : 0;

// --- Limpieza de RUT ---
$rut = str_replace(['.', ' '], '', strtoupper($rut));

// --- Construcción de URL del iframe ---
$iframe_url = "tabla.php"
    . "?rut={$rut}"
    . "&nombre={$nombre}"
    . "&status={$status}"
    . "&min_docs={$min_docs}"
    . "&dias_status={$dias_status}"
    . "&dias_vencidos={$dias_vencidos}";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php include_once("../meta_data/meta_data.html"); ?>
  <meta charset="UTF-8" />
  <title>Informe de Clientes Duemint</title>

  <style type="text/css">
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      background-color: #19173C;
      font-family: Arial, Helvetica, sans-serif;
      overflow: hidden;
    }

    /* === Encabezado === */
    table {
      border: none;
      color: white;
      font-size: 15px;
      border-collapse: collapse;
      background-color: #1F1D3E;
      width: 100%;
    }

    /* === Iframe principal === */
    iframe {
      border: none;
      width: 100%;
      height: calc(100vh - 160px); /* 🔹 deja espacio para cabecera */
      display: block;
      overflow: auto;
      scrollbar-width: thin;
    }

    /* === Footer fijo === */
    footer {
      background-color: white;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 200px;
      color: gray;
      font-size: 12px;
      text-align: center;
      line-height: 25px;
      border-top: 2px solid #512554;
      z-index: 9000;
    }

    a:link, a:visited { color: gray; text-decoration: none; }
    a:hover           { color: darkgrey; font-weight: bold; }
    a:active          { color: blue; }
  </style>
</head>

<body>
  <!-- 🧾 ENCABEZADO -->
  <table align="center" border="0" width="100%">
    <tr align="center" style="color: white; background-color: #1F1D3E;">
      <th width="200" height="130" valign="top" align="left">
        <img src="../images/logo_icontel_azul.jpg" height="115" alt="Logo iContel"/>
      </th>
      <td>
        <table width="100%" height="100%">
          <tr height="90">
            <th align="center" style="font-size: 20px;">
              Informe de Clientes Duemint
            </th>
          </tr>
          <tr>
            <td align="center" style="font-size: 12px;">
              (Click sobre los Títulos para ordenar)
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- 📊 RESULTADOS -->
  <iframe id="tablaFrame" 
          src="<?php echo htmlspecialchars($iframe_url, ENT_QUOTES, 'UTF-8'); ?>">
  </iframe>

  <!-- ⚙️ FOOTER -->
  <?php include_once("../footer/footer.php"); ?>

  <script src="js/tabla.js"></script>
</body>
</html>