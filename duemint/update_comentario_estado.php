<?php
// ==========================================================
// UPDATE COMENTARIO ESTADO FINANCIERO
// Autor: Mauricio Araneda
// Fecha: 2025-11-07
// ==========================================================

include_once("config.php");
header('Content-Type: application/json');

// Validación básica
if (!isset($_POST['rut']) || !isset($_POST['comentario'])) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit;
}

$rut        = trim($_POST['rut']);
$comentario = trim($_POST['comentario']);

if ($rut === '') {
    echo json_encode(["success" => false, "message" => "RUT vacío"]);
    exit;
}

// Sanitizar RUT (quitar puntos y espacios)
$rut = str_replace(['.', ' '], '', strtoupper($rut));

// Conexión a BD SuiteCRM
$conn = DbConnect("tnasolut_sweet");

// Buscar cuenta por RUT
$sql_find = "
    SELECT id_c 
    FROM accounts_cstm
    WHERE REPLACE(REPLACE(TRIM(rut_c),'.',''),' ','') = '$rut'
    LIMIT 1
";

$res = $conn->query($sql_find);

if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $id_c = $row['id_c'];

    // Actualizar comentario estado
    $sql_update = sprintf(
        "UPDATE accounts_cstm 
         SET comentario_estado_c = '%s'
         WHERE id_c = '%s'",
        $conn->real_escape_string($comentario),
        $conn->real_escape_string($id_c)
    );

    if ($conn->query($sql_update)) {
        echo json_encode(["success" => true, "message" => "Comentario actualizado"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No se encontró cuenta con el RUT."]);
}

$conn->close();
