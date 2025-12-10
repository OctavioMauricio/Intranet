<?php
//=========================================================
// /intranet/kickoff/update_estado_sweet.php
// Actualiza el esdado finaciero de la cuenta en Sweet
// Creado por Mauricio Araneda
// Fecha: 10/11/2025
//=========================================================

require_once("config.php");
$conn = DbConnect($db_sweet);
$conn->set_charset("utf8mb4");

$rut    = $_POST['rut'] ?? '';
$estado = $_POST['estado'] ?? '';

if ($rut == '' || $estado == '') {
    echo json_encode(["success" => false]);
    exit;
}

$sql = "
UPDATE accounts ac
JOIN accounts_cstm acc ON acc.id_c = ac.id
SET 
    acc.estatusfinanciero_c = ?,
    ac.date_modified = NOW()
WHERE REPLACE(REPLACE(TRIM(acc.rut_c), '.', ''), ' ', '') = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $estado, $rut);
$ok = $stmt->execute();

echo json_encode(["success" => $ok]);