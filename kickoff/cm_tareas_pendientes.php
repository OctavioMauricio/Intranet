<?php
//=====================================================
// /intranet/kickoff/cm_tareas_pendientes.php
// Tareas Pendientes – versión EDITABLE
// Autor: Mauricio Araneda (mAo)
// Actualizado: 29-11-2025
// Codificación: UTF-8 sin BOM
//=====================================================

mb_internal_encoding("UTF-8");

require_once "config.php"; // incluye sweet_get_dropdown_api()
$conn = DbConnect($db_sweet);

// ---------------------------------------------------------
// Listas via API SweetCRM
// ---------------------------------------------------------
$lista_categoria = sweet_get_dropdown_api("categoria_list");
$lista_prioridad = sweet_get_dropdown_api("task_priority_dom");
$lista_estado    = sweet_get_dropdown_api("task_status_dom");

// ---------------------------------------------------------
// Usuarios activos (assigned_user_id)
// ---------------------------------------------------------
$sqlUsers = "
    SELECT id, first_name, last_name 
    FROM users
    WHERE deleted = 0 AND status = 'Active'
    ORDER BY first_name, last_name
";
$rsUsers = $conn->query($sqlUsers);

$lista_usuarios = [];
while ($u = $rsUsers->fetch_assoc()) {
    $lista_usuarios[$u["id"]] = trim($u["first_name"] . " " . $u["last_name"]);
}

// ---------------------------------------------------------
// Ejecutar SP (tareas abiertas)
// ---------------------------------------------------------
// [SEGURIDAD] $sg_id viene del login (index.php), solo escapamos para SQL
$sql = "CALL Kick_Off_Operaciones_Tareas_Abiertas('" . $conn->real_escape_string($sg_id) . "')";
$resultado = $conn->query($sql);

$datos = [];
if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $datos[] = $row;
    }
}

// ---------------------------------------------------------
// Ordenar por fecha de vencimiento (YYYY-MM-DD)
// ---------------------------------------------------------
usort($datos, function($a, $b) {
    return strcmp($a["f_vencimiento"], $b["f_vencimiento"]);
});
?>

<link rel="stylesheet" href="css/cm_tareas_pendientes.css">
<!-- ===================================================== -->
<!-- TABLA EDITABLE -->
<!-- ===================================================== -->
<table id="tareas" align="center" width="100%">

<tr align="center">
    <td colspan="12" class="titulo" align="left">
        &nbsp;&nbsp;⏳ Tareas Abiertas ASIGNADAS A USTED
    </td>
    <td align="right" style="font-size:20px; color:white; background:#512554;">
        <a href="<?=$url_nueva_tarea?>" target="new"><b>+</b></a>&nbsp;&nbsp;&nbsp;
    </td>
</tr>

<tr class="subtit" align="left">
    <th class="titulo">#</th>
    <th class="titulo">Asunto</th>
    <th class="titulo">Categoría</th>
    <th class="titulo">Prioridad</th>
    <th class="titulo">Asignado a</th>
    <th class="titulo">Estado</th>
    <th  class="titulo">En Espera de</th>
    <th class="titulo">Origen</th>
    <th class="titulo">Nº</th>
    <th class="titulo">Fecha Crea.</th>
    <th class="titulo">Fecha Modif.</th>
    <th class="titulo">Vence</th>
    <th class="titulo" align="right">Días</th>
</tr>

<?php
$ptr = 0;

foreach ($datos as $lin):

    $ptr++;

    $id = $lin["id"] ?? "";
    if (!$id) continue;

    // Usuario asignado
    $usuario_id = $lin["usuario_id"] ?? $lin["assigned_user_id"] ?? "";

    // Fecha vencimiento en formato SQL correcto
    $fechaSQL = $lin["f_vencimiento"];

    // ----------------------------------------------
    // CLASE CSS POR PRIORIDAD (API REAL)
    // ----------------------------------------------
    $p = trim($lin["prioridad"]);

    switch ($p) {
        case "URGENTE_E":
            $class = "prio-escalado";
            break;

        case "URGENTE":
            $class = "prio-urgente";
            break;

        case "High":
            $class = "prio-alta";
            break;

        case "Low":
            $class = "prio-baja";
            break;

        default:
            $class = "";
    }
?>
<tr data-id="<?=$id?>" class="<?=$class?>">

    <td><?=$ptr?></td>

    <td>
        <a target="_blank" href="<?=htmlspecialchars($lin["url"])?>"><?=htmlspecialchars($lin["tarea"])?></a>
    </td>

    <td>
        <select data-campo="categoria">
        <?php foreach ($lista_categoria as $k => $v): ?>
            <option value="<?=$k?>" <?=($k==$lin["categoria"]?"selected":"")?>><?=$v?></option>
        <?php endforeach; ?>
        </select>
    </td>

    <td>
        <select data-campo="prioridad">
        <?php foreach ($lista_prioridad as $k => $v): ?>
            <option value="<?=$k?>" <?=($k==$lin["prioridad"]?"selected":"")?>><?=$v?></option>
        <?php endforeach; ?>
        </select>
    </td>

    <td>
        <select data-campo="assigned_user_id">
        <?php foreach ($lista_usuarios as $uid => $uname): ?>
            <option value="<?=$uid?>" <?=($uid==$usuario_id?"selected":"")?>><?=$uname?></option>
        <?php endforeach; ?>
        </select>
    </td>

    <td>
        <select data-campo="estado">
        <?php foreach ($lista_estado as $k => $v): ?>
            <option value="<?=$k?>" <?=($k==$lin["estado"]?"selected":"")?>><?=$v?></option>
        <?php endforeach; ?>
        </select>
    </td>

    <td>
        <input type="text"  data-campo="en_espera" style="width:200px;" value="<?=htmlspecialchars($lin["enesperade"])?>">
    </td>

    <td><?=htmlspecialchars($lin["origen"])?></td>
    <td align="center"><?=htmlspecialchars($lin["numero"])?></td>
    <td><?=$lin["f_creacion"]?></td>
    <td><?=$lin["f_modifica"]?></td>

    <td>
        <input type="date"
               data-campo="date_due"
               data-id="<?=$id?>"
               value="<?=$fechaSQL?>"
               style="width:120px; text-align:center;">
    </td>

    <td align="right"><?=htmlspecialchars($lin["dias"])?></td>

</tr>

<?php endforeach; ?>
</table>

<script src="js/cm_tareas_pendientes.js"></script>
<script src="js/cm_sort.js"></script>