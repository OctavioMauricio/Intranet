<?php
// ==========================================================
// /kickoff/cm_cobranza_comercial.php
// Autor: Mauricio Araneda
// Fecha: 2025-11-20
// Versión: Dinámico + UTF-8 + Sin Bordes + Recarga AJAX    🔄 
// CodificaciÃ³n: UTF-8 sin BOM
// ==========================================================

mb_internal_encoding("UTF-8");
error_reporting(0);

$conn = DbConnect($db_sweet);
if (!$conn) {
    die("<b>Error:</b> No se pudo conectar a Sweet.");
}
$conn->set_charset('utf8mb4');

// ==========================================================
// FUNCIÓN PARA OBTENER LA LISTA DESDE SUITECRM
// ==========================================================
function obtenerListaSweet($listName)
{
    $url = "https://sweet.icontel.cl/custom/tools/get_dropdown.php?list=" . urlencode($listName);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    $resp = curl_exec($ch);
    curl_close($ch);

    if (!$resp) return [];
    $json = json_decode($resp, true);
    return (is_array($json) ? $json : []);
}

// ==========================================================
// SELECT ESTADOS (SIN BORDES, TRANSPARENTE)
// ==========================================================
function selectSweetEstado($estadoActual, $lista)
{
    $html  = "<div class='estado-container'>";
    $html .= "<select class='estado-sweet'>";

    foreach ($lista as $item) {
        $key   = htmlspecialchars($item['key'],   ENT_QUOTES, 'UTF-8');
        $label = htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8');

        $sel = (strcasecmp(trim($estadoActual), trim($key)) === 0) ? "selected" : "";
        $html .= "<option value=\"$key\" $sel>$label</option>";
    }

    $html .= "</select><span class='estado-icono'></span></div>";
    return $html;
}

// ==========================================================
// CACHE LISTA ESTADOS
// ==========================================================
$LISTA_ESTADO_SWEET = obtenerListaSweet("Estatus_financiero");

// ==========================================================
// CONSULTA PRINCIPAL
// ==========================================================
$sql = "SELECT 
    CONCAT(
        'https://sweet.icontel.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DAccounts%26action%3DDetailView%26record%3D', ac.id
    ) AS url_cuenta,
    ac.name AS cliente,
    CONCAT(us.first_name, ' ', us.last_name) AS ejecutivo,
    acc.estatusfinanciero_c AS estado,
    acc.comentario_estado_c AS comentario,
    DATE_FORMAT(CONVERT_TZ(ac.date_modified, '+00:00', '-04:00'), '%d/%m/%Y') AS fecha_modif,
    DATEDIFF(NOW(), ac.date_modified) AS dias,
    REPLACE(REPLACE(TRIM(acc.rut_c), '.', ''), ' ', '') AS rut_limpio,
    duemint.estado_duemint,
    duemint.nom_estado_duemint,
    duemint.dias_duemint,
    duemint.num_doc_duemint,
    duemint.monto_duemint

FROM tnasolut_sweet.accounts AS ac
JOIN tnasolut_sweet.accounts_cstm AS acc ON acc.id_c = ac.id
JOIN tnasolut_sweet.users AS us ON us.id = ac.assigned_user_id

LEFT JOIN (
    SELECT 
        d.clientTaxId AS rut,
        d.status AS estado_duemint,
        d.statusName AS nom_estado_duemint,
        CASE
            WHEN d.status = 1 THEN COALESCE(DATEDIFF(DATE(NOW()), MAX(d.paidDate)), 0)
            WHEN d.status = 2 THEN COALESCE(MIN(DATEDIFF(d.dueDate, DATE(NOW()))), 0)
            WHEN d.status = 3 THEN COALESCE(DATEDIFF(DATE(NOW()), MIN(d.dueDate)), 0)
            WHEN d.status = 4 THEN COALESCE(DATEDIFF(DATE(NOW()), MAX(d.issueDate)), 0)
            WHEN d.status = 5 THEN COALESCE(DATEDIFF(DATE(NOW()), MAX(d.issueDate)), 0)
            ELSE 0
        END AS dias_duemint,
        COUNT(d.number) AS num_doc_duemint,
        SUM(d.total) AS monto_duemint
    FROM icontel_clientes.cron_duemint_documents AS d
    WHERE d.status = 3
    GROUP BY d.clientTaxId, d.status, d.statusName
) AS duemint 
ON duemint.rut = REPLACE(REPLACE(TRIM(acc.rut_c), '.', ''), ' ', '')

WHERE acc.estatusfinanciero_c IN (
    'cobranza_comercial',
    'acuerdo_cobranza_comer',
    'suspender',
    'Suspendido',
    'retencion_posible_baja'
)

ORDER BY acc.estatusfinanciero_c DESC, duemint.estado_duemint ASC";

$result = $conn->query($sql);
$contenido = "";
$ptr = 0;

// ==========================================================
// CONSTRUCCIÓN TABLA
// ==========================================================
while ($row = $result->fetch_assoc()) {

    $ptr++;
    $estado = strtolower(trim($row["estado"]));

switch ($estado) {
    case 'suspender':                  $clase = 'estado-suspender'; break;
    case 'suspendido':                 $clase = 'estado-suspendido'; break;
    case 'cobranza_comercial':         $clase = 'estado-cobranza';  break;
    case 'acuerdo_cobranza_comer':     $clase = 'estado-acuerdo_cobranza_comer'; break;
    default:                           $clase = '';                 break;
}
    $rut        = htmlspecialchars($row["rut_limpio"]);
    $comentario = htmlspecialchars($row["comentario"] ?? '', ENT_QUOTES, 'UTF-8');
    $cliente    = htmlspecialchars($row["cliente"], ENT_QUOTES, 'UTF-8');

    $contenido .= "<tr class='$clase'>";
    $contenido .= "<td>$ptr</td>";

    // Cliente
    $contenido .= "<td style='text-align:left;'>
        <a target='_blank' 
           href='" . htmlspecialchars($row["url_cuenta"]) . "' 
           style='color:#1F1D3E; text-decoration:none;'>
            $cliente
        </a>
    </td>";

    // Estado Sweet
    $contenido .= "<td class='estado-sweet-cell' data-rut='$rut'>" .
                  selectSweetEstado($row["estado"], $LISTA_ESTADO_SWEET) .
                  "</td>";

    // Comentario
    $contenido .= "<td class='comentario-cell' data-rut='$rut'>
        <div class='comentario-container'>
            <input type='text' class='comentario-input' value='$comentario'>
            <span class='comentario-icono'></span>
        </div>
    </td>";

    // NÃºmeros
    $contenido .= "<td style='text-align:right;'>$ " . number_format($row["monto_duemint"], 0, ',', '.') . "</td>";
    $contenido .= "<td>" . (int)$row["num_doc_duemint"] . "</td>";
    $contenido .= "<td>" . (int)$row["dias_duemint"] . "</td>";
    $contenido .= "<td>" . htmlspecialchars($row["ejecutivo"], ENT_QUOTES, 'UTF-8') . "</td>";
    $contenido .= "<td>" . htmlspecialchars($row["fecha_modif"]) . "</td>";
    $contenido .= "<td>" . (int)$row["dias"] . "</td>";

    $contenido .= "</tr>";
}

$conn->close();
?>

<!-- ====================================================== -->
<!-- HEADER SUPERIOR (TIPO Casos Abiertos) -->
<!-- ====================================================== -->

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
    <td colspan="11" 
        align="left" valign="top" 
        class="titulo" 
        style="background-color:#512554; color:white; font-weight:bold; font-size:16px; height:38px;">
        
        &nbsp;&nbsp;ðŸ’°ðŸ“„ Cobranza Comercial&nbsp;&nbsp;&nbsp;
    </td>

    <td align="right" valign="top" 
        style="font-size:20px; color:white; background-color:#512554;">
        
        <span id="reloadModulo" 
              title="Recargar mÃ³dulo"
              style="cursor:pointer; color:white;">
            ðŸ”„
        </span>&nbsp;&nbsp;&nbsp;
    </td>
</tr>
</table>

<!-- ====================================================== -->
<!-- TABLA HTML -->
<!-- ====================================================== -->
<table id="cobranza">
  <tr class="subtitulo" style="background-color:#512554; color:white; font-size:12px;">    
    <th>#</th>
    <th>Razón Social</th>
    <th width="12%">Estado Sweet</th>
    <th>Comentario</th>
    <th style="text-align:right;">Monto Bruto</th>
    <th>Docs</th>
    <th>Díass Venc.</th>
    <th>Ejecutivo</th>
    <th>F. Modif</th>
    <th>Días</th>
  </tr>

  <?= $contenido ?>
</table>

<!-- ====================================================== -->
<!-- TOGGLE -->
<!-- ====================================================== -->
<div id="toggleWrapper" 
     style="margin:10px 0; cursor:pointer; font-size:12px; color:#1F1D3E;">
    Cobranza Comercial <span id="toggleTexto">[Ocultar <?= $ptr ?>]</span>
</div>

<script>
document.getElementById("toggleWrapper").addEventListener("click", function() {
    const tabla = document.getElementById("cobranza");
    const texto = document.getElementById("toggleTexto");

    if (tabla.style.display === "none") {
        tabla.style.display = "table";
        texto.textContent = "[Ocultar <?= $ptr ?>]";
    } else {
        tabla.style.display = "none";
        texto.textContent = "[Mostrar <?= $ptr ?>]";
    }
});
</script>

<!-- ====================================================== -->
<!-- AJAX: RECARGAR SOLO ESTE MÓDULO -->
<!-- ====================================================== -->
<script>
function recargarModulo() {

    const boton = document.getElementById("reloadModulo");
    boton.classList.add("rotar");

    fetch('cm_cobranza_comercial.php')
        .then(res => res.text())
        .then(html => {

            const temp = document.createElement("div");
            temp.innerHTML = html;

            // Reemplaza TODO el módulo completo
            const nuevoModulo = temp.querySelector("#modulo_cobranza_comercial");

            if (nuevoModulo) {
                document.querySelector("#modulo_cobranza_comercial").outerHTML = nuevoModulo.outerHTML;
            }

            // Reasignar eventos
            bindEventosEstado();
            bindEventosComentario();

            document.getElementById("reloadModulo").addEventListener("click", recargarModulo);

            setTimeout(()=> boton.classList.remove("rotar"), 300);
        });
}
    
document.getElementById("reloadModulo").addEventListener("click", recargarModulo);
</script>

<!-- ====================================================== -->
<!-- EVENTOS: Estado y Comentario -->
<!-- ====================================================== -->
<script>
function bindEventosEstado() {

    document.querySelectorAll('.estado-sweet').forEach(sel => {

        sel.onchange = function() {

            const cell  = this.closest('td.estado-sweet-cell');
            const rut   = cell.dataset.rut;
            const valor = this.value;
            const icono = cell.querySelector('.estado-icono');

            fetch('update_estado_sweet.php', {
                method:'POST',
                headers:{'Content-Type':'application/x-www-form-urlencoded'},
                body:`rut=${encodeURIComponent(rut)}&estado=${encodeURIComponent(valor)}`
            })
            .then(r=>r.json())
            .then(d=>{
                icono.textContent = d.success ? "✅" : "❌";
                icono.style.color = d.success ? "limegreen" : "red";

                if (d.success) recargarModulo();
            });
        };
    });
}

function bindEventosComentario() {

    document.querySelectorAll('.comentario-input').forEach(inp => {

        inp.onchange = function() {

            const cell  = this.closest('td.comentario-cell');
            const rut   = cell.dataset.rut;
            const valor = this.value;
            const icono = cell.querySelector('.comentario-icono');

            fetch('update_comentario_sweet.php', {
                method:'POST',
                headers:{'Content-Type':'application/x-www-form-urlencoded'},
                body:`rut=${encodeURIComponent(rut)}&comentario=${encodeURIComponent(valor)}`
            })
            .then(r=>r.json())
            .then(d=>{
                icono.textContent = d.success ? "✅" : "❌";
                icono.style.color = d.success ? "limegreen" : "red";
                if (d.success) recargarModulo();
            });
        };
    });
}

// Inicializar eventos
bindEventosEstado();
bindEventosComentario();
</script>

<!-- ====================================================== -->
<!-- CSS FINAL -->
<!-- ====================================================== -->
<style>
#cobranza, #cobranza th, #cobranza td {
    border: none !important;
}


.estado-sweet {
    width:85%;
    padding:2px;
    background:transparent !important;
    border:0 !important;
    color:inherit !important;
    font-size:12px;
    outline:none;
}

.comentario-input {
    width:85%;
    background:transparent;
    border:0 !important;
    outline:none;
    font-size:12px;
}

#reloadModulo.rotar {
    transform:rotate(360deg);
}

.titulo {
    background:#512554 !important;
    color:white !important;
}
#cobranza .subtitulo th {
    background-color: #512554 !important;
    color: white !important;
    font-size: 12px;
}    
/* Estado: Suspender */
.estado-suspender .estado-sweet {
    background-color: #ffe0e0 !important;
    color: #b30000 !important;
    border: 0px solid #b30000 !important;
}

/* Estado: Suspendido */
.estado-suspendido .estado-sweet {
    background-color: #ffb3b3 !important;
    color: #000 !important;
    border: 0px solid #000 !important;
}

/* Estado: Cobranza Comercial */
.estado-cobranza .estado-sweet {
    background-color: orange !important;
    color: black !important;
    border: 0px solid #000 !important;
}
/* Estado: Acuerdo Cobranza Comercial */
.estado-acuerdo_cobranza_comer .estado-sweet {
    background-color: #cce5ff !important;   /* azul suave */
    color: #004085 !important;
    border: 1px solid #004085 !important;
    }
tr.subtitulo {
    background-color: #512554 !important;
    color: #C39BD3 !important;
}

tr.subtitulo th {
    background-color: #512554 !important;
    color: #C39BD3 !important;
    font-size: 12px !important;
}    
</style>