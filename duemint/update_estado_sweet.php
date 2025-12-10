<?php
// ==========================================================
// UPDATE ESTADO SWEET POR RUT (versión blindada JSON)
// ==========================================================

ob_start(); // ✨ Evita que se envíe basura antes del JSON
header('Content-Type: application/json');

include_once("config.php");

// --- Validaciones ---
if (!isset($_POST['rut']) || !isset($_POST['estado'])) {
    ob_clean();
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit;
}

$rut    = trim($_POST['rut']);
$estado = trim($_POST['estado']);

if ($rut === '' || $estado === '') {
    ob_clean();
    echo json_encode(["success" => false, "message" => "Parámetros vacíos"]);
    exit;
}

// Limpiar RUT
$rut = strtoupper(preg_replace('/[^0-9Kk]/', '', $rut));

$conn = DbConnect("tnasolut_sweet");

// Buscar cuenta usando limpieza completa (igual a SP)
$sql_find = "
    SELECT id_c 
    FROM accounts_cstm 
    WHERE UPPER(REGEXP_REPLACE(rut_c, '[^0-9Kk]', '')) = 
          UPPER('$rut')
    LIMIT 1
";

$res = $conn->query($sql_find);

if ($res && $res->num_rows > 0) {
    $row  = $res->fetch_assoc();
    $id_c = $row['id_c'];

    $sql_update = sprintf(
        "UPDATE accounts_cstm 
         SET estatusfinanciero_c = '%s'
         WHERE id_c = '%s'",
        $conn->real_escape_string($estado),
        $conn->real_escape_string($id_c)
    );

    if ($conn->query($sql_update)) {
        ob_clean();
        echo json_encode(["success" => true]);
        exit;
    } else {
        ob_clean();
        echo json_encode(["success" => false, "message" => $conn->error]);
        exit;
    }

} else {
    ob_clean();
    echo json_encode(["success" => false, "message" => "RUT no encontrado."]);
    exit;
}
