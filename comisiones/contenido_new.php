<?php
include_once("config.php");

// Fechas predefinidas
$fecha_actual = new DateTime();
$fecha_actual->setTime(0, 0);

$fecha_fin = clone $fecha_actual;
$fecha_fin->setDate($fecha_fin->format('Y'), $fecha_fin->format('m'), 25);

$fecha_inicio = clone $fecha_actual;
$fecha_inicio->modify('first day of last month')->setDate($fecha_inicio->format('Y'), $fecha_inicio->format('m'), 26);

$fecha_inicio_formato = $fecha_inicio->format('Y-m-d');
$fecha_fin_formato = $fecha_fin->format('Y-m-d');

// Ejecutivos preseleccionados
$ejecutivos_preseleccionados = ["Ghislaine Rivera", "Natalia Diaz", "Raquel Maulen"];
?>

<div class='container'>
    <form method='post' target="_blank" action="informe_new.php">
        <table width="55%" align="center" border="0" id="tblData" name="tblData">
            <tr align="center" style="color: white; background-color: #1F1D3E;">
                <th colspan="2" width="200" height="130" valign="top" align="left">
                    <img src="./images/logo_icontel_azul.jpg" height="115" alt=""/>
                </th>
                <td colspan="2">
                    <table width="100%" height="100%">
                        <tr height="90">
                            <th align="right" valign="bottom" style="font-size: 20px;">
                                Informe de Comisiones de Venta
                            </th>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="inicio">Fecha de Inicio:</label>
                    <input type="date" id="inicio" name="inicio" value="<?php echo $fecha_inicio_formato; ?>" required /><br><br>
                    <label for="fin">Fecha de Fin:</label>
                    <input type="date" id="fin" name="fin" value="<?php echo $fecha_fin_formato; ?>" required />
                </td>
                <td>
                    <label for="ejecutivo">Ejecutivo/a:</label>
                    <select id="ejecutivo" name="ejecutivo[]" multiple size="10" required>
                        <?php
                        foreach ($vendedores as $vendedor) {
                            $selected = in_array($vendedor, $ejecutivos_preseleccionados) ? "selected" : "";
                            echo "<option value='$vendedor' $selected>$vendedor</option>";
                        }
                        ?>
                    </select>
                </td>              
                <td><?php crea_select($tipo_factura, "Tipo Factura"); ?></td>              
                <td>
                    <input type='submit' name='submit' value='Generar Informe'><br><br>
                    <input type="reset" name='reset' value='RESET'>
                </td>
            </tr>
        </table>
    </form>
</div>
