<?php
// ==========================================================
// index.php
// INFORME DE Comisiones - FORMATO TNA
// Autor: Mauricio Araneda
// Fecha: 2025-11-05
// Codificación: UTF-8 sin BOM
// ==========================================================

// --- Codificación UTF-8 ---
header("Content-Type: text/html; charset=UTF-8");

// --- Sesión unificada Comisiones (TNA Group) ---
session_name('icontel_intranet_sess');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '.icontel.cl');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Inicial</title>

  <style>
    /* Estilos para la imagen de espera */
    .cargando {
      position: relative;
      width: 100%;
      height: 100vh;
    }

    /* Estilos para el texto "iContel" */
    .cargando .texto {
      background: linear-gradient(to right, #ff0000, #ffa500, #ffff00, #008000);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      position: absolute;
      font-size: 48px;
      font-weight: bold;
      animation: flotar 6s linear infinite;
    }

    .cargando.ocultar {
      display: none;
    }

    @keyframes flotar {
      0%   { top: 10%; left: 20%; transform: rotate(10deg); }
      25%  { top: 5%;  left: 90%; transform: rotate(-20deg); }
      50%  { top: 95%; left: 80%; transform: rotate(30deg); }
      75%  { top: 80%; left: 10%; transform: rotate(-10deg); }
      100% { top: 10%; left: 20%; transform: rotate(10deg); }
    }
  </style>
</head>

<body>

  <!-- Imagen de espera -->
  <div class="cargando">
    <span class="texto">iContel</span>
  </div>

  <!-- Contenedor del formulario de comisiones -->
  <div class="contenido" style="display:none;">
      <?php include_once 'contenido.php'; ?>
  </div>

  <script>
    // Mostrar contenido al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelector('.cargando').classList.add('ocultar');
      document.querySelector('.contenido').style.display = 'block';
    });
  </script>

</body>
</html>
