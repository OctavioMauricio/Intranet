<?php
// ==========================================================
// kickoff/cm_header.php
// Header de KickOff
// Autor: Mauricio Araneda
// Fecha: 2025-11-17
// Codificación: UTF-8 sin BOM
// ==========================================================
mb_internal_encoding("UTF-8");
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
?>
<meta charset="UTF-8">

<style type="text/css">
    body,td,th { 
        font-family: Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif; 
    }
    html, body {
      height: auto !important;
      min-height: 100%;
    }

    a { color: #000000; }

    table.no-sort:first-of-type {
    position: sticky;
    top: 0;
    z-index: 9999;
    background: white; /* importantísimo para que el sticky quede sólido */
}
#header-fixed {
    position: sticky;
    top: 0;
    z-index: 9999;
    background: white !important;
    display: block;
}
#content {
  margin-top: 22px;
  flex: 1;
  overflow-y: auto;
  padding: 0px;
}


/* === Frases motivacionales === */
#frase-centro {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  font-weight: 600;
  color: #512554;
  width: 100%;
  height: 100%;
  text-align: center;
}

#frase-motivacional {
  margin: 0;
  text-align: right;
  font-size: 12px;
  font-weight: 600;
  color: #512554;
}

@media (min-width: 768px){
 #frase-centro {font-size: 16px;}
 #frase-motivacional{ font-size: 18px; }
}
</style>

<!-- ========================================================== -->
<!-- 🧭 FUNCIÓN GLOBAL DE SALIDA FUERA DEL IFRAME -->
<!-- ========================================================== -->
<script>
// Redirige SIEMPRE al marco principal, no dentro del iframe
function salirDelSistema() {

    if (!confirm("¿Deseas cerrar tu sesión y salir del sistema?")) {
        return false;
    }

    // Rompe el marco y redirige al login principal
    window.top.location.href = "https://intranet.icontel.cl/index.php";
    return false; 
}
</script>
<div id="header-fixed">

<table class="no-sort" id="header">
  <tbody>
    <tr>
      <td valign="top" style="padding:0;margin:0;height:5px;border:none;border-spacing:0;">
        <table class="no-sort" border="0">
          <tbody>

            <!-- 🧠 Fila 1 -->
            <tr 
                style="
                    color:white;
                    background-color:#64C2C8;
                    background-image:url('images/Office.png');
                    background-repeat:no-repeat;
                    background-position:center center;
                    background-size:cover;
                    cursor:pointer;
                "
                onclick="window.top.location.href='https://intranet.icontel.cl';"
            >
                <td colspan="8" style="height:140px;"></td>
            </tr>
            <!-- ⚙️ Fila 3 -->
            <tr style="background-color:#1F1D3E;color:white;">
              <td height="38">
                <p class="infoheader2">UF <?php echo $UF; ?>&nbsp;&nbsp;US <?php echo $USD; ?>&nbsp;&nbsp;Al <?php echo $UF_Fecha; ?></p>
              </td>

              <td align="center"><p class="botonheader2"><text onclick="mostrarSolo('capa_casos')" onMouseOver="this.style.cursor='pointer'"/><b> </b></text></p></td>

              <td align="center"><p class="botonheader2"><text onclick="mostrarSolo('capa_iconos')" onMouseOver="this.style.cursor='pointer'"/><b> </b></text></p></td>

              <td align="center"><p class="botonheader2"><text onclick="mostrarSolo('capa_buscadores')" onMouseOver="this.style.cursor='pointer'"/><b>     </b></text></p></td>

              <td></td>

              <td align="center"><p class="infoheader2" id="reloj">Hora: --:--:--</p></td>

              <td align="center"><p class="infoheader2">Cuadro de Mando de <?php echo $sg_name; ?></p></td>

              <td width="11%" align="right">
                <form action="" method="post" name="form_select" id="form_select">
                  <p><?php echo $select; ?></p>
                </form>
              </td>
            </tr>

          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
</div>
<?php
// ==========================================================
// 🔁 AUTO-REFRESH Persistente
// ==========================================================
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_POST['auto_refresh'])) {
    $_SESSION['auto_refresh'] = ($_POST['auto_refresh'] === 'true');
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) exit;
}
$autoRefreshState = !empty($_SESSION['auto_refresh']);
?>

<script>
function iniciarAutoRefreshHeader() {
  const REFRESH_INTERVAL = 300000; // 5 minutos
  let autoRefreshTimer = null;
  const toggle = document.getElementById('autoRefreshToggle');
  const barra = document.getElementById('auto-refresh-bar');

  if (!toggle || !barra) {
    setTimeout(iniciarAutoRefreshHeader, 300);
    return;
  }

  toggle.checked = <?php echo $autoRefreshState ? 'true' : 'false'; ?>;
  if (toggle.checked) iniciarAutoRefresh();

  toggle.addEventListener('change', function() {
    const activo = this.checked;
    guardarEstadoSesion(activo);
    if (activo) iniciarAutoRefresh();
    else detenerAutoRefresh();
  });

  function guardarEstadoSesion(activo) {
    fetch("", {
      method: "POST",
      headers: { "X-Requested-With": "XMLHttpRequest" },
      body: new URLSearchParams({ auto_refresh: activo })
    });
  }

  function iniciarAutoRefresh() {
    detenerAutoRefresh();
    autoRefreshTimer = setInterval(() => location.reload(), REFRESH_INTERVAL);
  }

  function detenerAutoRefresh() {
    if (autoRefreshTimer) clearInterval(autoRefreshTimer);
  }
}

document.addEventListener("DOMContentLoaded", iniciarAutoRefreshHeader);
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const header = document.getElementById("header-fixed");
    if (!header) return;

    // 🔧 Forzar reflow (ACTIVA sticky)
    header.style.display = 'none';
    void header.offsetHeight;
    header.style.display = 'block';
});
</script>

