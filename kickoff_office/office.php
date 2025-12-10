<?php
// ==========================================================
// kickoff/office.php
// kickoff de TNA Office
// Autor: Mauricio Araneda
// Fecha: 2025-11-17
// Codificación: UTF-8 sin BOM
// ==========================================================
mb_internal_encoding("UTF-8");

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

    // Destruir la sesión completamente
    session_unset();
    session_destroy();

    // Borrar cookie rememberme si existe
    if (isset($_COOKIE['rememberme'])) {
        setcookie('rememberme', '', time() - 3600, '/', '.icontel.cl', true, true);
    }

    // 👉 Redirigir SIEMPRE al index unificado de intranet
    header("Location: https://intranet.icontel.cl");
    exit;
}

// 🔵 Si llegó aquí, la sesión es válida y se puede seguir cargando KickOff

// -----------------------------------------------------
// 🔍 MODO DEBUG: desde sesión y/o parámetro debug=1
// -----------------------------------------------------
$DEBUG_MODE = false;

// Si index.php ya dejó debug en la sesión
if (!empty($_SESSION['debug']) && ($_SESSION['debug'] === true)) {
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
$ventas      = "/ .-..Ghislaine / .-..MAO / .-..Natalia / .-..Karla / .-..Rocio / ";
//$operaciones = "/ -..Bryan / Operaciones / -..Alex /";
$sac         = "/ .-..Maria José / .-..DAM /";
$admin       = "/ .-..MAM / .-..MAO ";
//$proveedores = "/ -..MAO";
$mao_mam     = "/ .-..MAO / .-..MAM";

include_once("config.php");
include_once("security_groups.php");

// ======================================================
// 🔧 Manejo correcto de sg_id basado en la sesión
// ======================================================

/*
//  1) Si viene por POST (desde el select del header)
if (isset($_POST['sg'])) {
    $_SESSION['sg_id'] = $_POST['sg'];
}

// 2) Si viene por GET (cambio manual)
if (isset($_GET['sg'])) {
    $_SESSION['sg_id'] = $_GET['sg'];
}

echo "<pre style='background:white;color:black;padding:20px;'>";
var_dump($_GET);
echo "</pre>";
echo "<pre style='background:white;color:black;padding:20px;'>";
var_dump($_POST);
echo "</pre>";


// 3) PRIORIDAD: valor en la sesión
if (isset($_SESSION['sec_id_office']) && !empty($_SESSION['sec_id_office'])) {
    $sg_id = $_SESSION['sec_id_office'];
} else {
    // 4) Valor por defecto (ventas)
    $sec_id_= "8226b570-5bdb-66e9-8399-69224778d1da";
    $_SESSION['sec_id_office'] = $sec_id;
}
*/


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
    if (isset($_SESSION['sec_id_office'])) {
        $sg_id = $_SESSION['sec_id_office'];
    } else {
        $sg_id = "8226b570-5bdb-66e9-8399-69224778d1da"; // soporte
        $_SESSION['sec_id_office'] = "8226b570-5bdb-66e9-8399-69224778d1da";
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
      background-color: black;
      color: white;
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
 //      echo "SG_Name= {$sg_name} sg_id={$sg_id}";
       // if (strpos($proveedores, $sg_name)) include_once("cm_casos_abiertos_sujeto_a_cobro.php");
        //if (strpos($mao_mam, $sg_name)) include_once("cm_traslados_y_bajas.php");
        //if (strpos($admin, $sg_name)) include_once("cm_casos_abiertos_debaja.php");

        include_once("cm_casos_abiertos.php");
//echo "Ventas = {$ventas}<br>sg:name={$sg_name}";
        //if (strpos($ventas, $sg_name) || strpos($admin, $sg_name)) include_once("cm_cobranza_comercial.php");
        include_once("cm_clientes_potenciales.php");

        include_once("cm_tareas_pendientes.php");
      //  include_once("cm_tareas_pendientes_delegadas.php");
        include_once("cm_notas_abiertas.php");

        if ($sg_name != "Soporte tecnico") include_once("cm_oportunidades_abiertas.php");

        //if (strpos($ventas . $operaciones, $sg_name)) include_once("cm_oportunidades_en_Demo.php");

        //if (strpos($ventas, $sg_name) && $sg_name != "-..MAO") include_once("cm_oportunidades_Archivadas.php");

        if (strpos($sac, $sg_name)) {
       //     include_once("cm_casos_abiertos_seguimiento.php");
        //    include_once("cm_casos_abiertos_congelados.php");
        }

   //     if (strpos($admin, $sg_name)) include_once("cm_ordenes_de_compra_pendientes.php");
        ?>
        <br><br>
    </div>
</div>

<?php include_once("footer/footer_oscuro.php"); ?>

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