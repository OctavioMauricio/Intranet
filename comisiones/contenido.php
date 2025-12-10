<?php
// ==========================================================
// contenido.php
// Formulario Inicial – Informe de Comisiones
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
// Configuración
// ------------------------------------------
include_once("config.php");

// ------------------------------------------
// Fechas predefinidas
// ------------------------------------------
$fecha_actual = new DateTime();
$fecha_actual->setTime(0, 0);

$fecha_fin = clone $fecha_actual;
$fecha_fin->setDate($fecha_fin->format('Y'), $fecha_fin->format('m'), 25);

$fecha_inicio = clone $fecha_actual;
$fecha_inicio->modify('first day of last month')
             ->setDate($fecha_inicio->format('Y'), $fecha_inicio->format('m'), 26);

$fecha_inicio_formato = $fecha_inicio->format('Y-m-d');
$fecha_fin_formato    = $fecha_fin->format('Y-m-d');

// ------------------------------------------
// Preselección de Ejecutivos / Tipos
// ------------------------------------------
$ejecutivos_preseleccionados = [
    "Ghislaine Rivera",
    "Natalia Diaz",
    "Raquel Maulen",
    "Rocio Tiznado"
];

$tipo_facturas_preseleccionadas = [
    "Anual", "Bienal", "Mensual",
    "Lista para Facturar", "Pendiente", "Unica"
];

?>
<div class='container'>
    <form method='post' target="_blank" action="informe.php">
        <table width="60%" align="center" border="0" id="tblData" name="tblData">

            <tr style="color: white; background-color: #1F1D3E;">
                <td colspan="4">
                    <table width="100%" align="center" border="0"
                           style="background-color: #1F1D3E; color: white;">
                        <tr>
                            <th valign="top" align="left">
                                <img src="./images/logo_icontel_azul.jpg" height="80" alt=""/>
                            </th>
                            <td colspan="3" align="center" style="font-size: 20px; vertical-align: middle;">
                                Informe de Comisiones de Venta
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>

                <!-- Fechas -->
                <td>
                    <label for="inicio">Fecha de Inicio:</label>
                    <input type="date" id="inicio" name="inicio"
                           value="<?php echo $fecha_inicio_formato; ?>" required /><br><br>

                    <label for="fin">Fecha de Fin:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input type="date" id="fin" name="fin"
                           value="<?php echo $fecha_fin_formato; ?>" required />
                </td>

                <!-- Ejecutivos -->
                <td>
                    <label for="ejecutivo">Ejecutivo/a:</label><br>
                    <select id="ejecutivo" name="ejecutivo[]" multiple size="12" required>
                        <?php foreach ($vendedores as $vendedor): ?>
                            <?php $sel = in_array($vendedor, $ejecutivos_preseleccionados) ? "selected" : ""; ?>
                            <option value="<?php echo $vendedor; ?>" <?php echo $sel; ?>>
                                <?php echo $vendedor; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <!-- Tipos de Factura -->
                <td>
                    <label for="tipo_factura">Tipo Factura:</label><br>
                    <select id="tipo_factura" name="tipo_factura[]" multiple size="12" required>
                        <?php foreach ($tipos_factura as $tipo): ?>
                            <?php $sel = in_array($tipo, $tipo_facturas_preseleccionadas) ? "selected" : ""; ?>
                            <option value="<?php echo $tipo; ?>" <?php echo $sel; ?>>
                                <?php echo $tipo; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <!-- Botones -->
                <td>
                    <input type='submit' name='submit' value='Generar Informe'><br><br>
                    <input type="reset" name='reset' value='RESET'>
                </td>
            </tr>

        </table>
    </form>
</div>
