<?php
//=========================================================
// /intranet/index.php
// Controlador central de Login + Redirección según Rol
// Autor: Mauricio Araneda (mAo)
// Fecha: 2025-11
// Codificación: UTF-8 sin BOM
//=========================================================

ob_start();

// -----------------------------------------------------
// 🔐 CONFIGURAR SESIÓN
// -----------------------------------------------------
session_name('icontel_intranet_sess');
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '.icontel.cl',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

// -----------------------------------------------------
// GET → BORRAR SESIÓN Y MOSTRAR LOGIN
// -----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    session_unset();
    session_destroy();
    setcookie('icontel_intranet_sess','',time()-3600,'/','.icontel.cl');

    session_start();

    $DEBUG = isset($_GET['debug']) && $_GET['debug']=="1";
    if ($DEBUG) $_SESSION['debug']=true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Intranet – Acceso</title>
<style>
body { font-family: Arial; background:#1F1D3E; color:white; margin:0; padding:0; }
.form-box {
    width:320px; margin:140px auto; padding:25px;
    background:#27304A; border-radius:8px;
    box-shadow:0 0 10px rgba(0,0,0,0.4);
}
input {
    width:100%; padding:10px; margin-bottom:12px;
    border-radius:5px; border:1px solid #C39BD3;
    background:#fff; color:#000;
}
button {
    width:100%; padding:10px; background:#512554;
    color:white; border:none; border-radius:5px;
    cursor:pointer; font-size:15px;
}
button:hover { background:#7D3C98; }
</style>
</head>

<body>
<div class="form-box">
    <form action="" method="post">
        <h3>Acceso a Intranet</h3>

        <label>Usuario</label>
        <input type="text" name="username" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <?php if (!empty($_SESSION['debug'])): ?>
            <input type="hidden" name="debug" value="1">
        <?php endif; ?>

        <button type="submit">Ingresar</button>
    </form>
</div>
</body>
</html>
<?php
    exit;
}

// -----------------------------------------------------
// POST → VALIDAR LOGIN
// -----------------------------------------------------
$username = trim($_POST['username']);
$password = trim($_POST['password']);

if (isset($_POST['debug']) && $_POST['debug']=="1") {
    $_SESSION['debug'] = true;
}

$username = mb_strtolower(str_replace(['.', ' ', '-'], '', $username));

$con = new mysqli("localhost", "tnasolut_app", "1Ngr3s0.,", "tnasolut_app");
if ($con->connect_errno) {
    die("❌ Error MySQL: " . $con->connect_error);
}

$stmt = $con->prepare("
    SELECT id, username, password, razon_social, rut, sec_id, rol, sec_id_office
    FROM clientes
    WHERE LOWER(REPLACE(REPLACE(REPLACE(username,'.',''),' ',''),'-','')) = ?
");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) { // no encontró al usuario}
    
    // 🔥 BORRAR TODO: sesión + cookie
    session_unset();
    session_destroy();
    setcookie('icontel_intranet_sess','', time() - 3600, '/', '.icontel.cl');

    // 🔄 Redirigir SIEMPRE al login limpio
    header("Location: https://intranet.icontel.cl");
    exit;
}


$stmt->bind_result($id, $db_user, $db_pass, $rs, $rut, $sec_id, $rol, $sec_id_office);
$stmt->fetch();

if ($password !== $db_pass) {
    echo "<h3 style='color:red;text-align:center;'>❌ Contraseña incorrecta</h3>";
    exit;
}

session_regenerate_id(true);

$_SESSION['loggedin'] = true;
$_SESSION['id']             = $id;
$_SESSION['name']           = $db_user;
$_SESSION['cliente']        = $rs;
$_SESSION['rut']            = $rut;
$_SESSION['sg_id']          = $sec_id;
$_SESSION['sec_id_office']  = $sec_id_office;
$_SESSION['rol']            = $rol;

// -----------------------------------------------------
// 🎯 NUEVA LÓGICA DE DESTINO SEGÚN ROL
// -----------------------------------------------------
$ROL = $_SESSION['rol'];

if ($ROL === "iContel") {
    $iframe_url = "https://intranet.icontel.cl/kickoff_ajax/icontel.php";
}
elseif ($ROL === "Office") {
    $iframe_url = "https://intranet.icontel.cl/kickoff_office/office.php";
}
elseif ($ROL === "Admin") {
    $iframe_url = "https://intranet.icontel.cl/kickoff_ajax/index.php"; // con pestañas
}
else {
    // Rol desconocido → iContel por defecto
    $iframe_url = "https://intranet.icontel.cl";
}

// -----------------------------------------------------
// SI NO ES DEBUG → MOSTRAR IFRAME PRINCIPAL
// -----------------------------------------------------
if (empty($_SESSION['debug'])) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Intranet</title>
<style>
body { margin:0; padding:0; overflow:hidden; }
iframe { width:100%; height:100vh; border:none; }
</style>
</head>
<body>

<!-- 🎯 TODO EL SISTEMA QUEDA DENTRO DE ESTE FRAME -->
<iframe src="<?php echo $iframe_url; ?>"></iframe>

</body>
</html>
<?php
    exit;
}

// -----------------------------------------------------
// DEBUG
// -----------------------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Sesión iniciada (Debug)</title>
</head>
<body style="font-family:Arial; background:#F0F0F0; padding:40px;">

<h2>✅ Login correcto — Sesión inicializada</h2>

<pre style="background:#FFF;padding:20px;border:1px solid #CCC;"><?php var_dump($_SESSION); ?></pre>

<form action="<?php echo $iframe_url; ?>">
    <button style="padding:10px 20px;background:#27304A;color:white;border:none;">
        Continuar
    </button>
</form>

</body>
</html>
<?php
exit;
?>


