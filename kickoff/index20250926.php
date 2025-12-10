<?php
session_start();
$_SESSION['donde'] = "KickOff";

$ventas      = "/ Ventas / -..Ghislaine / -..MAO / -..Natalia / -..Monica / -..Raquel";
$operaciones = "/ -..Bryan / Operaciones / -..Alex /";
$sac         = "/ Servicio al Cliente / -..Maria José / -..DAM /";
$admin       = "/ -..MAM";
$proveedores = "/ -..MAO";
$mao_mam     = "/ -..MAO / -..MAM";

include_once("config.php");
include_once("security_groups.php");

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
        $sg_name = "Soporte Soporte tecnico";
        $_SESSION['sg_id'] = $sg_id;
    }
}

// Buscar el nombre del grupo seleccionado
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
    <link rel="stylesheet" href="css/kickoff.css" />
    <link href="./css/rebote.css" rel="stylesheet" type="text/css" />
    <meta http-equiv="refresh" content="300">
<meta charset="UTF-8">
</head>

<body bgcolor="#FFFFFF" text="#1F1D3E" link="#E95300" onload="BodyOnLoad()">
<div id="page">
    <div id="header">
        <?php include_once(__DIR__ . "/cm_header.php"); ?>
    </div>

    <div id="content">
        <div class="cargando"><span class="texto">iContel</span></div>

        <div hidden id="capa_casos" style="background-color: darkblue; color: white;">
            <?php include_once(__DIR__ . "/../casos/index.php"); ?>
        </div>

        <div hidden id="capa_iconos" style="background-color: white;">
            <iframe src="../app/menu.php"></iframe>
        </div>

        <div hidden id="capa_buscadores" style="background-color: white;">
            <iframe src="./buscadores/index.php"></iframe>
        </div>

        <?php
        if (strpos($proveedores, $sg_name)) {
            include_once(__DIR__ . "/cm_casos_abiertos_sujeto_a_cobro.php");
        }

        if (strpos($mao_mam, $sg_name)) {
            include_once(__DIR__ . "/cm_traslados_y_bajas.php");
        }

        if (strpos($admin, $sg_name)) {
            include_once(__DIR__ . "/cm_casos_abiertos_debaja.php");
        }

        include_once(__DIR__ . "/cm_casos_abiertos.php");

        if (strpos($ventas, $sg_name) || strpos($admin, $sg_name)) {
            include_once(__DIR__ . "/cm_cobranza_comercial.php");
        }

        if (strpos($ventas, $sg_name)) {
            include_once(__DIR__ . "/cm_clientes_potenciales.php");
        }

        include_once(__DIR__ . "/cm_tareas_pendientes.php");

        if ($sg_name != "Soporte tecnico") {
            include_once(__DIR__ . "/cm_oportunidades_abiertas.php");
        }

        if (strpos($ventas . $operaciones, $sg_name)) {
            include_once(__DIR__ . "/cm_oportunidades_en_Demo.php");
        }

        if (strpos($ventas, $sg_name) && $sg_name != "-..MAO") {
            include_once(__DIR__ . "/cm_oportunidades_Archivadas.php");
        }

        if (strpos($sac, $sg_name)) {
            include_once(__DIR__ . "/cm_casos_abiertos_seguimiento.php");
            include_once(__DIR__ . "/cm_casos_abiertos_congelados.php");
        }

        if (strpos($admin, $sg_name)) {
            include_once(__DIR__ . "/cm_ordenes_de_compra_pendientes.php");
        }

        if (strpos($sac, $sg_name)) {
            include_once(__DIR__ . "/cm_cobranza_comercial.php");
        }
        ?>
        <br><br>
    </div>
</div>

<!-- Script para ocultar la imagen de espera cuando la página esté cargada -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.cargando').classList.add('ocultar');
    document.querySelector('.contenido').style.display = 'block';
});
</script>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('header');
    const content = document.getElementById('content');

    if (header && content) {
      const headerHeight = header.offsetHeight;
      content.style.paddingTop = `${headerHeight - 20}px`; // +10px de margen visual opcional
    }
  });
</script>
</body>
</html>