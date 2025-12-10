<?php
//=========================================================
// /intranet/kickoff/update_comentario_sweet.php
// Actualiza comentario de estado de cobranza en Sweet
// Creado por Mauricio Araneda
// Fecha: 10/11/2025
//=========================================================

//////// Funciones //////////////
function DbConnect($dbname){ // se conecta a base de datos y devuelve $conn
    $server   = "localhost";
//    $user     = "tnasolut_data_studio";
//    $password = "P3rf3ct0.,";
    $user     = "data_studio";
    $password = "1Ngr3s0.,";
    // me conecto a la Base de Datos
    $conn = new mysqli($server, $user, $password);
    if ($conn->connect_error) { die("No me pude conectar a servidor localhost: " . $conn->connect_error); }
    $dummy = mysqli_set_charset ($conn, "utf8");

    $bd_seleccionada = mysqli_select_db($conn, $dbname);
    if (!$bd_seleccionada) { die ('No se puede usar '.$dbname.' : ' . mysql_error()); }
    return($conn);
}
$db_sweet = 'tnasolut_sweet';

header('Content-Type: application/json');

$rut = $_POST['rut'] ?? '';
$comentario = $_POST['comentario'] ?? '';

if (empty($rut)) {
    echo json_encode(['success' => false, 'error' => 'RUT no recibido']);
    exit;
}

$conn = DbConnect($db_sweet);
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Error de conexión']);
    exit;
}

$rut = $conn->real_escape_string($rut);
$comentario = $conn->real_escape_string($comentario);

$sql = "
    UPDATE accounts_cstm 
    SET comentario_estado_c = '{$comentario}' 
    WHERE REPLACE(REPLACE(TRIM(rut_c), '.', ''), ' ', '') = '{$rut}'
    LIMIT 1";

if ($conn->query($sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$conn->close();
