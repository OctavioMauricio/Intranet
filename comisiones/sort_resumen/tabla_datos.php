<?php
// ==========================================================
// /comisiones/sort_resumen/tabla.php
// Tabla Resumen – Informe de Comisiones
// Autor: Mauricio Araneda
// Fecha: 2025-11-05
// Codificación: UTF-8 sin BOM
// ==========================================================
$conn = DbConnect("tnasolut_sweet");
$result = mysqli_query($conn,$query);
$ptr = 0;
?>
<script>
function toggleRow(cb, rowId) {
    const row = document.getElementById(rowId);
    row.style.display = cb.checked ? '' : 'none';
    updateTotals();
}
function parseFloatSafe(val) {
    return parseFloat(val.replaceAll('.', '').replace(',', '.')) || 0;
}
function updateTotals() {
    let venta = 0, costo = 0, margen = 0, neto_comi = 0, comi = 0, sgv = 0;
    document.querySelectorAll('tr.data-row').forEach(row => {
        if (row.style.display !== 'none') {
            venta += parseFloatSafe(row.querySelector('.venta').innerText);
            costo += parseFloatSafe(row.querySelector('.costo').innerText);
            margen += parseFloatSafe(row.querySelector('.margen').innerText);
            neto_comi += parseFloatSafe(row.querySelector('.neto_comi').innerText);
            comi += parseFloatSafe(row.querySelector('.comi').innerText);
            sgv += parseFloatSafe(row.querySelector('.sgv').innerText);
        }
    });
    document.getElementById('tot_venta').innerText = venta.toLocaleString('de-DE', {minimumFractionDigits: 2});
    document.getElementById('tot_costo').innerText = costo.toLocaleString('de-DE', {minimumFractionDigits: 2});
    document.getElementById('tot_margen').innerText = margen.toLocaleString('de-DE', {minimumFractionDigits: 2});
    document.getElementById('tot_neto_comi').innerText = neto_comi.toLocaleString('de-DE', {minimumFractionDigits: 2});
    document.getElementById('tot_comi').innerText = comi.toLocaleString('de-DE', {minimumFractionDigits: 2});
    document.getElementById('tot_sgv').innerText = sgv.toLocaleString('de-DE', {minimumFractionDigits: 2});
}
window.onload = updateTotals;
</script>
<?php
while($row = mysqli_fetch_array($result)){
    $ptr++;
    $row_id = "row_" . $ptr;
    ?>
    <tr id="<?= $row_id ?>" class="data-row" style="font-size: 12px">
        <td><?= $ptr ?></td>
        <td><?= $row["fac_vendedor"] ?></td>
        <td align="center"><?= $row["fac_estado"] ?></td>
        <td class="venta" align="right"><?= number_format($row["neto_uf"], 2, ',', '.') ?></td>
        <td class="costo" align="right"><?= number_format($row["costo_uf"], 2, ',', '.') ?></td>
        <td class="margen" align="right"><?= number_format($row["margen_uf"], 2, ',', '.') ?></td>
        <td class="neto_comi" align="right"><?= number_format($row["neto_comi_uf"], 2, ',', '.') ?></td>
        <td class="comi" align="right"><?= number_format($row["comision_uf"], 2, ',', '.') ?></td>
        <td class="sgv" align="right"><?= number_format($row["comi_sgv_uf"], 2, ',', '.') ?></td>
        <td align="center"><input type="checkbox" checked onchange="toggleRow(this, '<?= $row_id ?>')"></td>
    </tr>
<?php } ?>
<tr style="background-color: #1F1D3E; color: white; font-weight: bold; font-size: 12px">
    <td colspan="3" align="right"><b>TOTALES</b>&nbsp;&nbsp;</td>
    <td align="right" id="tot_venta">0,00</td>
    <td align="right" id="tot_costo">0,00</td>
    <td align="right" id="tot_margen">0,00</td>
    <td align="right" id="tot_neto_comi">0,00</td>
    <td align="right" id="tot_comi">0,00</td>
    <td align="right" id="tot_sgv">0,00</td>
    <td></td>
</tr>
<tr>
    <td colspan="16" align="right">
        <input type="button" onClick="exportToExcel('empTable')" value="Export to Excel" />
    </td>
</tr>
