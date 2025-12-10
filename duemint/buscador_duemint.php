<?php
// ==========================================================
// Buscador de Clientes Duemint - iContel / TNA Group
// duemint/buscador_duemint.php
// Autor: Mauricio Araneda
// Fecha: 2025-11-04
// Codificación: UTF-8 sin BOM
// ==========================================================
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Buscador de Clientes Duemint</title>
</head>
<body>
<form action="/duemint/informe.php" method="get" target="_blank" name="duemint" id="duemint">
  <table height="100%" style="background-color: #1F1D3E; color: white; border: 1px solid dimgrey;">
    <tbody>

      <tr>
        <td colspan="2" align="center">
          <strong style="font-size: 20px;">Clientes Duemint</strong>
        </td>
      </tr>

      <!-- RUT -->
      <tr>
        <td width="120px"><label for="rut">RUT Cliente:</label></td>
        <td>
          <input style="background-color: lightgray; color: black; width: 120px;"
                 name="rut" type="text" id="rut" placeholder="Ej: 76222123-5">
        </td>
      </tr>

      <!-- Nombre -->
      <tr>
        <td><label for="nombre">Nombre Cliente:</label></td>
        <td>
          <input style="background-color: lightgray; color: black; width: 120px;"
                 name="nombre" type="text" id="nombre" placeholder="Parte del nombre">
        </td>
      </tr>

      <!-- Estado -->
      <tr>
        <td><label for="status">Estado:</label></td>
        <td>
          <select name="status" id="status" style="background-color: lightgray; color: black; width: 120px;">
            <option value="0">Todos</option>
            <option value="1">Pagado</option>
            <option value="2">Por Vencer</option>
            <option value="3" selected>Vencido</option>
            <option value="4">Anulado</option>
            <option value="5">Compensado</option>
          </select>
        </td>
      </tr>

      <!-- Cant. Docs -->
      <tr>
        <td><label for="min_docs">Cant. Docs:</label></td>
        <td>
          <input style="background-color: lightgray; color: black; width: 120px;"
                 name="min_docs" type="number" id="min_docs" min="0" value="0">
        </td>
      </tr>

      <!-- Días en Status -->
      <tr>
        <td><label for="dias_status">Días en Status:</label></td>
        <td>
          <input style="background-color: lightgray; color: black; width: 120px;"
                 name="dias_status" type="number" id="dias_status" min="0" placeholder="Ej: 30">
        </td>
      </tr>

      <!-- Días Vencidos (mayores o iguales a) -->
      <tr>
        <td><label for="dias_vencidos">Días Vencidos ≥</label></td>
        <td>
          <input style="background-color: lightgray; color: black; width: 120px;"
                 name="dias_vencidos" type="number" id="dias_vencidos" min="0" value="60" placeholder="Ej: 60">
        </td>
      </tr>

      <tr><td colspan="2" height="80">&nbsp;</td></tr>

      <!-- Botones -->
      <tr>
        <td align="center">
          <button type="button" onclick="resetForm('duemint')"
                  style="background-color: lightgray; color: gray; font-size: 12px;">
            Limpiar
          </button>
        </td>
        <td align="center">
          <input style="background-color: lightgray; font-size: 12px;"
                 name="submit" type="submit" value="Buscar Duemint">
        </td>
      </tr>

    </tbody>
  </table>
</form>

<script>
function resetForm(id) {
  const f = document.getElementById(id);
  if (!f) return;

  f.reset();
  document.getElementById('status').value = '3';        // Estado por defecto: Vencido
  document.getElementById('min_docs').value = 3;        // Docs mínimos
  document.getElementById('dias_vencidos').value = 60;  // 60 días por defecto
}

document.getElementById("duemint").addEventListener("submit", function () {
  const rut = document.getElementById("rut");
  if (rut && rut.value.trim() !== "") {
    rut.value = rut.value.replace(/[.\s]/g, "").toUpperCase();
  }
});
</script>
</body>
</html>
