<?php
// ==========================================================
// KickOff / icontel.php – Control directo de sesión
// ==========================================================

// Configuración unificada de sesión
session_name('icontel_intranet_sess');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '.icontel.cl');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// ------------------------------------------------------
// 🔒 Validación de sesión (destruida / expirada / inválida)
// ------------------------------------------------------
if (empty($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {

    // ============================
    // 🔥 1. Eliminar sesión
    // ============================
    session_unset();
    session_destroy();

    // ============================
    // 🔥 2. Borrar TODAS las cookies de sesión
    // ============================
    if (isset($_COOKIE['icontel_intranet_sess'])) {
        setcookie('icontel_intranet_sess', '', time() - 3600, '/', '.icontel.cl', true, true);
    }

    if (isset($_COOKIE['rememberme'])) {
        setcookie('rememberme', '', time() - 3600, '/', '.icontel.cl', true, true);
    }

    // ============================
    // 🔥 3. Redirigir SIEMPRE la página completa (NO el iframe)
    // ============================

    // Si este archivo está dentro de un iframe → romperlo
    echo "<script>
            if (window.top !== window.self) {
                window.top.location.href = 'https://intranet.icontel.cl/index.php?error=session_expired';
            } else {
                window.location.href = 'https://intranet.icontel.cl/index.php?error=session_expired';
            }
          </script>";
    exit;
}

// 🔵 Si llegó aquí, la sesión es válida y se puede seguir cargando KickOff

// -----------------------------------------------------
// 🔍 MODO DEBUG: desde sesión y/o parámetro debug=1
// -----------------------------------------------------
$DEBUG_MODE = false;

// Si index.php ya dejó debug en la sesión
if (!empty($_SESSION['debug']) && $_SESSION['debug'] === true) {
    $DEBUG_MODE = true;
}

// Si viene por URL ?debug=1, forzamos debug y lo guardamos
if (isset($_GET['debug']) && $_GET['debug'] == '1') {
    $DEBUG_MODE = true;
    $_SESSION['debug'] = true;
}

// -----------------------------------------------------
// 🧪 DEBUG: MOSTRAR DUMP DE SESIÓN Y PREGUNTAR
// SOLO si está en modo debug y aún no se ha confirmado ok
// -----------------------------------------------------
if ($DEBUG_MODE && !isset($_GET['ok'])) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Cuadro de Mando</title>
<style>
body { font-family: Arial; background:#EEE; padding:40px; }
button {
    padding:10px 20px; background:#27304A; color:#FFF;
    border:none; border-radius:5px; cursor:pointer;
    font-size:16px;
}
button:hover { background:#1F1D3E; }
pre {
    background:white; padding:20px; border:1px solid #CCC;
    overflow:auto;
}
</style>
</head>
<body>

<h2>🔍 Debug de Sesión — KickOff</h2>
<p>Este es el estado de <strong>$_SESSION</strong> recibido desde <em>index.php</em>:</p>

<pre><?php var_dump($_SESSION); ?></pre>

<form method="get">
    <input type="hidden" name="ok" value="1">
    <?php if ($DEBUG_MODE): ?>
        <input type="hidden" name="debug" value="1">
    <?php endif; ?>
    <button type="submit">Continuar al Cuadro de Mando</button>
</form>

</body>
</html>
<?php
    exit;
}
// -----------------------------------------------------
// FIN DEBUG — CONTINÚA CARGA DE KICKOFF
// -----------------------------------------------------

// -----------------------------------------------------
// VARIABLES Y CONFIGURACIONES PROPIAS DE KICKOFF
// -----------------------------------------------------
$ventas      = "/ Ventas / -..Ghislaine / -..MAO / -..Natalia / -..Monica / -..Raquel";
$operaciones = "/ -..Bryan / Operaciones / -..Alex /";
$sac         = "/ Servicio al Cliente / -..Maria José / -..DAM /";
$admin       = "/ -..MAM / -..MAO ";
$proveedores = "/ -..MAO";
$mao_mam     = "/ -..MAO / -..MAM";

include_once("config.php");
include_once("security_groups.php");

// Manejo de grupo seleccionado
if (isset($_POST['sg'])) {
    $sg_id = $_POST['sg'];
    $_SESSION['sg_id'] = $sg_id;
}

if (isset($_GET['sg'])) {
    $sg_id = $_GET['sg'];
    $_SESSION['sg_id'] = $sg_id;
}

if (!isset($sg_id)) {
    if (isset($_SESSION['sg_id'])) {
        $sg_id = $_SESSION['sg_id'];
    } else {
        $sg_id = "a03a40e8-bda8-0f1b-b447-58dcfb6f5c19"; // soporte
        $_SESSION['sg_id'] = $sg_id;
    }
}

// Obtener nombre del grupo
$sg_name = '';
foreach ($grupos as $grupo) {
    if ($grupo['id'] == $sg_id) {
        $sg_name = $grupo['name'];
        break;
    }
}

// Exponer variables JS
echo "<script>
        var sg_id = '$sg_id';
        var sg_name = '$sg_name';
      </script>";

// Incluir metadatos
include_once("meta_data/meta_data.html");
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Cuadro de Mando</title>
    <script type="text/javascript" src="js/kickoff.js"></script>
    <link rel="stylesheet" href="./css/kickoff.css" />
    <link href="./css/rebote.css" rel="stylesheet" type="text/css" />
    <?php
    // ===================================================
    // 🔁 META REFRESH CONTROLADO POR SESSION
    // ===================================================
    $refreshHabilitado = false;

    // Debug deshabilita el refresh
    if (!isset($_SESSION['debug']) || $_SESSION['debug'] === false) {

        // Auto-refresh activado desde el header
        if (!empty($_SESSION['auto_refresh'])) {
            $refreshHabilitado = true;
        }
    }

    // Si corresponde → insertar refresh
    if ($refreshHabilitado) {
        echo '<meta http-equiv="refresh" content="300">';
    }
    ?><meta charset="UTF-8">
    <style>
    html, body {
      height: auto !important;
      min-height: 100vh;
      margin: 0;
      padding: 0;
      overflow-y: auto;
    }
    #page {
      min-height: calc(100vh - 40px);
      position: relative;
    }
    footer {
      position: fixed;
      bottom: 0; left: 0;
      width: 100%;
      background-color: white;
      color: #1F1D3E;
      text-align: center;
      font-size: 12px;
      border-top: 2px solid #512554;
      height: 25px;
      line-height: 25px;
      z-index: 9000;
      box-shadow: 0 -1px 3px rgba(0,0,0,0.1);
    }
    </style>
</head>

<body bgcolor="#FFFFFF" text="#1F1D3E" link="#E95300" onload="BodyOnLoad()">
<div id="page">
    <div id="header">
        <?php include_once("cm_header.php"); ?>
    </div>
    <div id="content">
        <div class="cargando"><span class="texto">iContel</span></div>

        <div hidden id="capa_casos">
            <?php include_once("../casos/index.php"); ?>
        </div>
        <div hidden id="capa_iconos">
            <iframe src="../app/menu.php"></iframe>
        </div>

        <div hidden id="capa_buscadores">
            <iframe src="https://intranet.icontel.cl/kickoff/buscadores/index.php"></iframe>
        </div>



<?php
if (!empty($sg_name) && strpos($proveedores, $sg_name) !== false) include_once("cm_casos_abiertos_sujeto_a_cobro.php");
if (!empty($sg_name) && strpos($mao_mam, $sg_name) !== false) include_once("cm_traslados_y_bajas.php");
if (!empty($sg_name) && strpos($admin, $sg_name) !== false) include_once("cm_casos_abiertos_debaja.php");

include_once("cm_casos_abiertos.php");

if (!empty($sg_name) && (strpos($ventas, $sg_name) !== false || strpos($admin, $sg_name) !== false)) include_once("cm_cobranza_comercial.php");
if (!empty($sg_name) && strpos($ventas, $sg_name) !== false) include_once("cm_clientes_potenciales.php");

include_once("cm_tareas_pendientes.php");
include_once("cm_tareas_pendientes_delegadas.php");
include_once("cm_notas_abiertas.php");

if (!empty($sg_name) && $sg_name != "Soporte tecnico") include_once("cm_oportunidades_abiertas.php");

if (!empty($sg_name) && strpos($ventas . $operaciones, $sg_name) !== false) include_once("cm_oportunidades_en_Demo.php");

if (!empty($sg_name) && strpos($ventas, $sg_name) !== false && $sg_name != "-..MAO") include_once("cm_oportunidades_Archivadas.php");

if (!empty($sg_name) && strpos($sac, $sg_name) !== false) {
    include_once("cm_casos_abiertos_seguimiento.php");
    include_once("cm_casos_abiertos_congelados.php");
}

if (!empty($sg_name) && strpos($admin, $sg_name) !== false) include_once("cm_ordenes_de_compra_pendientes.php");
?>
        <br><br>
    </div>
</div>

<?php include_once("../footer/footer_oscuro.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.cargando').classList.add('ocultar');
});

window.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('header');
    const content = document.getElementById('content');
    const footer = document.querySelector('footer');

    let total = 0;
    if (header) total += header.offsetHeight;
    if (footer) total += footer.offsetHeight;

    if (content) content.style.minHeight = `calc(100vh - ${total}px)`;
});
</script>

</body>
</html>