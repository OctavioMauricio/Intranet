<?php
   // activo mostrar errores
  //  error_reporting(E_ALL);
  //  ini_set('display_errors', '1');
$host     = "localhost";
$user     = "data_studio";
$password = "1Ngr3s0.,";
$db_tna   = "tnasolut_sweet";
$db_clientes   = "icontel_clientes";
date_default_timezone_set('UTC'); // hora de la base de datos
$userTimeZone = new DateTimeZone('America/Santiago');  // hora de chile
$hoy = date("d-m-Y H:i:s");


function DbConnect($db) {
    $server   = "localhost";
    $user     = "data_studio";
    $password = "1Ngr3s0.,";

    // 🔹 Conectar directamente a la base
    $conn = new mysqli($server, $user, $password, $db);

    // 🔸 Detectar errores
    if ($conn->connect_error) {
        die("❌ No se pudo conectar a la base de datos '{$db}': " . $conn->connect_error);
    }
mysqli_set_charset($conn, "utf8mb4");
    // 🔹 Asegurar codificaci�n UTF-8
    if (!$conn->set_charset("utf8")) {
        die("⚠️ Error configurando UTF8: " . $conn->error);
    }

    // ✅ Confirmaci�n (solo para debug, puedes quitarlo luego)
    //echo "Conectado exitosamente a la base: <b>{$db}</b><br>";

    return $conn;
}

function horacl($date) {
    global $userTimeZone;
    $dateNeeded = new DateTime($date); 
    $dateNeeded->setTimeZone($userTimeZone);
    return($dateNeeded->format('d-m-Y H:i:s')) ;
}