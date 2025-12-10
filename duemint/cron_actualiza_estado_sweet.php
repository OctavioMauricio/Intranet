<?php
// ==========================================================
// CRON: Actualiza estado financiero Sweet para clientes Duemint
// Autor: Mauricio Araneda
// Fecha: 2025-11-07
// Codificación: UTF-8 forzada
// ==========================================================

// Forzar UTF-8 en todo el script
header('Content-Type: text/plain; charset=UTF-8');
mb_internal_encoding("UTF-8");
ini_set('default_charset', 'UTF-8');

// Zona horaria
date_default_timezone_set("America/Santiago");

include_once("config.php");

// ==========================================================
// FUNCIÓN: Conexión segura UTF-8
// ==========================================================
function ConnUTF8($dbname) {
    $c = DbConnect($dbname);

    if ($c && !$c->connect_error) {
        $c->set_charset("utf8mb4");
        $c->query("SET NAMES utf8mb4");
        $c->query("SET CHARACTER SET utf8mb4");
    }
    return $c;
}

// Conexión a icontel_clientes (UTF-8)
$conn = ConnUTF8("icontel_clientes");
if ($conn->connect_error) {
    die("❌ ERROR DB CLIENTES: " . $conn->connect_error);
}

// Parámetros del SP
$p_status        = 3;
$p_min_docs      = 0;
$p_rut           = '';
$p_nombre        = '';
$p_dias_vencidos = 60;

$sql = sprintf(
    "CALL search_by_status_min_docs(%d,%d,'%s','%s',%d)",
    $p_status,
    $p_min_docs,
    $conn->real_escape_string($p_rut),
    $conn->real_escape_string($p_nombre),
    $p_dias_vencidos
);

// Ejecuta SP
$result = $conn->query($sql);
if (!$result) {
    die("❌ Error ejecutando SP: " . $conn->error);
}

$contador = 0;

// Estados permitidos
$permitidos = [
    "activo",
    "anticipo",
    "baja",
    "baja_forzada",
    "esporadico",
    "prospect",
    "prospecto",
    "reemplazado"
];

// Recorrer filas del SP
while ($row = $result->fetch_assoc()) {

    $rut = str_replace(['.', ' '], '', trim($row['rut_cliente']));
    $estadoActual = strtolower(trim($row['estado_financiero_sweet']));

    if (!in_array($estadoActual, $permitidos)) {
        continue;
    }

    // Conexión UTF-8 a Sweet
    $conn2 = ConnUTF8("tnasolut_sweet");
    if ($conn2->connect_error) continue;

    // UPDATE
    $sqlUpdate = "
        UPDATE accounts_cstm
        SET estatusfinanciero_c = 'cobranza_comercial'
        WHERE REPLACE(REPLACE(rut_c,'.',''),' ','') = '{$rut}'
    ";

    if ($conn2->query($sqlUpdate)) {
        $contador++;
    }

    $conn2->close();
}

// Cerrar todo
$result->close();
$conn->next_result();
$conn->close();

// Resultado final
echo "✅ Proceso completado. Registros actualizados: {$contador}\n";
?>
