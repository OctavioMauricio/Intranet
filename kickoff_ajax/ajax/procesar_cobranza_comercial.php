<?php
// ==============================================================
// /kickoff/ajax/procesar_cobranza_comercial.php
// Ejecuta SP + actualiza estatusfinanciero_c automáticamente
// Se llama desde el icono 🔄 en Cobranza Comercial
// ==============================================================

header("Content-Type: application/json; charset=UTF-8");
mb_internal_encoding("UTF-8");

require_once "../config.php";

// Conexión Sweet
$conn = DbConnect($db_sweet);
if (!$conn) {
    echo json_encode(["ok" => false, "msg" => "Error de conexión con Sweet"]);
    exit;
}

$conn->set_charset("utf8mb4");

// ==========================================================
// ESTADOS QUE **NO DEBEN SER CAMBIADOS**
// ==========================================================
$estadosBloqueados = [
    "cobranza_comercial",
    "acuerdo_cobranza_comer",
    "Extrajudicial",
    "Suspendido",
    "suspender"
];

// ==========================================================
// 1. Ejecutar SP
// ==========================================================
$sql = "CALL search_by_status_min_docs(3, 0, '', '', 60)";
$res = $conn->query($sql);

if (!$res) {
    echo json_encode([
        "ok" => false,
        "msg" => "Error al ejecutar SP: " . $conn->error
    ]);
    exit;
}

$procesadas = 0;
$omitidas   = 0;

while ($row = $res->fetch_assoc()) {

    $idCuenta = $row["id_cuenta"] ?? "";
    if (!$idCuenta) continue;

    // ------------------------------------------------------
    // Obtener estado actual desde accounts_cstm
    // ------------------------------------------------------
    $qEstado = $conn->query("
        SELECT estatusfinanciero_c 
        FROM accounts_cstm 
        WHERE id_c = '{$idCuenta}'
    ");

    if (!$qEstado) continue;

    $rEstado = $qEstado->fetch_assoc();
    $estadoActual = trim($rEstado["estatusfinanciero_c"] ?? "");

    // Si está en lista de NO CAMBIAR → saltar
    if (in_array($estadoActual, $estadosBloqueados, true)) {
        $omitidas++;
        continue;
    }

    // ------------------------------------------------------
    // Actualizar estado a cobranza_comercial
    // ------------------------------------------------------
    $upd = $conn->query("
        UPDATE accounts_cstm 
        SET estatusfinanciero_c = 'cobranza_comercial'
        WHERE id_c = '{$idCuenta}'
    ");

    if ($upd) {
        $procesadas++;
    }
}

$conn->close();

// ==========================================================
// RESPUESTA JSON
// ==========================================================
echo json_encode([
    "ok"         => true,
    "procesadas" => $procesadas,
    "omitidas"   => $omitidas
]);
exit;
