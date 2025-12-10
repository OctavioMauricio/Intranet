<!-- ==========================================================
🧩 Header Corporativo – Intranet iConTel
Ubicación: /home/icontel/public_html/intranet/factibilidades/header/header.php
Autor: Mauricio Araneda (mAo)
Última actualización: 11-11-2025
========================================================== -->

<?php
// Si no viene definido, usamos un título genérico
if (!isset($tituloApp) || empty($tituloApp)) {
    $tituloApp = "Intranet TNA Group";
}
?>

<table align="center" border="0" width="100%">
  <tr align="center" style="color: white;">
    <th width="200" height="70" valign="middle" align="left">
      <img src="../images/tna_group.png" height="60" alt="Logo iConTel" style="margin-left:15px;"/>
    </th>
    <td>
      <table width="100%" height="100%">
        <tr height="35">
          <th align="center" style="font-size: 22px;">
            <?php echo htmlspecialchars($tituloApp, ENT_QUOTES, 'UTF-8'); ?>
          </th>
        </tr>
      </table>
    </td>
  </tr>
</table>