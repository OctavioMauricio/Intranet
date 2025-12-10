<?php
// ==========================================================
// /comisiones/sort/tabla_datos.php
// Tabla Principal – Informe de Comisiones
// Autor: Mauricio Araneda
// Fecha: 2025-11-05
// Codificación: UTF-8 sin BOM
// ==========================================================

// --- Manejo de sesión ---
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

// --- Validar que exista la query enviada desde tabla.php ---
if (!isset($query)) {
    echo "<tr><td colspan='20' style='color:red; font-weight:bold;'>ERROR: Query no recibida.</td></tr>";
    return;
}

// --- Conexión a base de datos ---
$conn = DbConnect("tnasolut_sweet");

$result = mysqli_query($conn, $query);

// Si ocurre un error SQL, lo mostramos
if (!$result) {
    echo "<tr><td colspan='20' style='color:red; font-weight:bold;'>ERROR SQL: "
         . mysqli_error($conn) . "</td></tr>";
    return;
}

// Inicializar contadores
$ptr = 0;
$tot_venta = 0;
$tot_costo = 0;
$tot_margen = 0;
$tot_neto_comi = 0;
$tot_comi = 0;
$tot_sgv = 0;

// --- Recorrer filas ---
while ($row = mysqli_fetch_assoc($result)) {
    $ptr++;
?>
    <tr>
        <td><?php echo $ptr; ?></td>

        <td align="center">
            <a target="_blank" href="<?php echo $row['coti_url']; ?>">
                <?php echo $row['coti_num']; ?>
            </a>
        </td>

        <td align="center">
            <a target="_blank" href="<?php echo $row['fac_url']; ?>">
                <?php echo $row['fac_num']; ?>
            </a>
        </td>

        <td align="center"><?php echo $row["fac_fecha"]; ?></td>
        <td><?php echo $row["fac_cliente"]; ?></td>
        <td align="center"><?php echo $row["fac_estado"]; ?></td>
        <td><?php echo $row["fac_vendedor"]; ?></td>
        <td align="center"><?php echo $row["fechacierre"]; ?></td>
        <td align="center"><?php echo $row["meta_uf"]; ?></td>
        <td align="right"><?php echo $row["cierre_uf"]; ?></td>
        <td align="center"><?php echo $row["cumplimiento"]; ?></td>
        <td align="center"><?php echo $row["comision"]; ?></td>

        <td align="right"><?php echo number_format($row["neto_uf"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["costo_uf"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["margen_uf"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["neto_comi_uf"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["comision_uf"], 2, ',', '.'); ?></td>
        <td align="right"><?php echo number_format($row["comi_sgv_uf"], 2, ',', '.'); ?></td>
    </tr>
<?php
    // Acumular totales
    $tot_venta     += $row['neto_uf'];
    $tot_costo     += $row['costo_uf'];
    $tot_margen    += $row['margen_uf'];
    $tot_neto_comi += $row['neto_comi_uf'];
    $tot_comi      += $row['comision_uf'];
    $tot_sgv       += $row['comi_sgv_uf'];
}

// Cerrar conexión
mysqli_close($conn);
?>

<!-- Totales -->
<tr>
    <td colspan="12" align="right"><b>TOTALES</b>&nbsp;&nbsp;</td>

    <th align="right"><?php echo number_format($tot_venta, 2, ',', '.'); ?></th>
    <th align="right"><?php echo number_format($tot_costo, 2, ',', '.'); ?></th>
    <th align="right"><?php echo number_format($tot_margen, 2, ',', '.'); ?></th>
    <th align="right"><?php echo number_format($tot_neto_comi, 2, ',', '.'); ?></th>
    <th align="right"><?php echo number_format($tot_comi, 2, ',', '.'); ?></th>
    <th align="right"><?php echo number_format($tot_sgv, 2, ',', '.'); ?></th>
</tr>

<!-- Botón Excel -->
<tr>
    <td colspan="16" align="right">
        <input type="button" onClick="exportToExcel('empTable')" value="Export to Excel" />
    </td>
</tr>
