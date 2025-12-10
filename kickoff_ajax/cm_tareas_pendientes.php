<?php
//=====================================================
// /intranet/kickoff_ajax/cm_tareas_pendientes.php
// Tareas Pendientes – versión AJAX (idéntico a producción)
// Autor: Mauricio Araneda (mAo)
// Adaptación AJAX: uso estándar ajax_bootstrap.php
//=====================================================

// Estándar Kickoff AJAX (sesión + $sg_id + $sg_name + seguridad + DbConnect)
require_once __DIR__ . "/ajax_bootstrap.php";

mb_internal_encoding("UTF-8");

require_once "config.php"; // incluye sweet_get_dropdown_api()
$conn = DbConnect($db_sweet);

// ---------------------------------------------------------
// Listas via API SweetCRM (idéntico a producción)
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
// Ejecutar SP (tareas abiertas) – uso de $sg_id como producción
// ---------------------------------------------------------
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

<style>
#tareas select,
#tareas input[type="text"],
#tareas input[type="date"] {
    border: none;
    outline: none;
    background: transparent;
    font-size: 13px;
    width: 98%;
}

#tareas select:focus,
#tareas input:focus {
    outline: none;
    border: none;
    box-shadow: none;
}
</style>

<div class="tabla-scroll">
<table id="tareas" align="center" width="100%">
    <!-- ... (table content) ... -->
<?php
// ... (PHP table sorting logic not shown here, just wrapper)
?>

<tr align="center">
    <td colspan="13" class="titulo" align="left">
        &nbsp;&nbsp;⏳ Tareas Abiertas ASIGNADAS A USTED
    </td>
    <td align="right" style="font-size:20px; color:white; background:#512554;">
        <a href="<?=$url_nueva_tarea?>" target="new"><b>+</b></a>&nbsp;&nbsp;&nbsp;
    </td>
</tr>

<tr class="subtit" align="left">
    <th class="subtitulo" style="width:22px;">&nbsp;</th>
    <th class="subtitulo">#</th>
    <th class="subtitulo">Asunto</th>
    <th class="subtitulo">Categoría</th>
    <th class="subtitulo">Prioridad</th>
    <th class="subtitulo">Asignado a</th>
    <th class="subtitulo">Estado</th>
    <th class="subtitulo">En Espera de</th>
    <th class="subtitulo">Origen</th>
    <th class="subtitulo">Nº</th>
    <th class="subtitulo">Fecha Crea.</th>
    <th class="subtitulo">Fecha Modif.</th>
    <th class="subtitulo">Vence</th>
    <th class="subtitulo" align="right">Días</th>
</tr>

<?php
$ptr = 0;

foreach ($datos as $lin):

    $ptr++;

    $id = $lin["id"] ?? "";
    if (!$id) continue;

    $usuario_id = $lin["usuario_id"] ?? $lin["assigned_user_id"] ?? "";
    $fechaSQL   = $lin["f_vencimiento"];

    // ----------------------------------------------
    // CLASE CSS POR PRIORIDAD (sin cambios)
    // ----------------------------------------------
    $p = trim($lin["prioridad"]);

    switch ($p) {
        case "URGENTE_E":  $class = "prio-escalado";  break;
        case "URGENTE":    $class = "prio-urgente";   break;
        case "High":       $class = "prio-alta";      break;
        case "Low":        $class = "prio-baja";      break;
        default:           $class = "";
    }
?>
<tr data-id="<?=$id?>" class="<?=$class?>">

    <td><?=$ptr?></td>

    <td>
        <a target="_blank" href="<?=htmlspecialchars($lin["url"])?>">
            <?=htmlspecialchars($lin["tarea"])?>
        </a>
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
        <input type="text" data-campo="en_espera" style="width:200px;" value="<?=htmlspecialchars($lin["enesperade"])?>">
    </td>

    <td><?=htmlspecialchars($lin["origen"])?></td>
    <td align="center"><?=htmlspecialchars($lin["numero"])?></td>
    <td><?=$lin["f_creacion"]?></td>
    <td><?=$lin["f_modifica"]?></td>

    <td>
        <input type="date"
               data-campo="date_due"
               value="<?=$fechaSQL?>"
               style="width:120px; text-align:center;">
    </td>

    <td align="right"><?=htmlspecialchars($lin["dias"])?></td>

</tr>

<?php endforeach; ?>
</table>
</div>

<script src="js/cm_tareas_pendientes.js?v=<?=time()?>_5"></script>
<script src="js/cm_sort.js?v=<?=time()?>"></script>
