<?php
//=====================================================
// /intranet/kickoff/include/security_check.php
// Control unificado de sesión y autenticación iContel
// Autor: Mauricio Araneda
// Última actualización: 09-11-2025
//=====================================================

// =====================================================
// ?? Configuración de sesión segura (3 horas)
// =====================================================
ini_set('session.gc_maxlifetime', 10800);      // 3h duración en servidor
ini_set('session.cookie_lifetime', 10800);     // 3h en navegador
ini_set('session.save_path', '/home/icontel/tmp_sessions');
@mkdir('/home/icontel/tmp_sessions', 0700, true); // Asegura carpeta si no existe

// Nombre único de sesión
session_name('icontel_intranet_sess');

// Configuración de la cookie segura
session_set_cookie_params([
  'lifetime' => 10800,
  'path' => '/',
  'domain' => 'intranet.icontel.cl',
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Lax'
]);

// Iniciar sesión si no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// =====================================================
// ? Validación de autenticación
// =====================================================
if (empty($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Usuario no autenticado ? redirigir al login
    header('Location: /login/login.html?error=not_logged_in');
    exit;
}

// =====================================================
// ?? Control de inactividad
// =====================================================
$INACTIVITY_LIMIT = 60 * 60 * 3; // 3 horas de inactividad
$now = time();

if (isset($_SESSION['last_activity']) && ($now - $_SESSION['last_activity'] > $INACTIVITY_LIMIT)) {
    session_unset();
    session_destroy();
    header('Location: /login/login.html?error=session_expired');
    exit;
}

// Refrescar marca de actividad
$_SESSION['last_activity'] = $now;

// =====================================================
// (Opcional) Datos útiles globales disponibles
// =====================================================
// $_SESSION['name']        ? Usuario logueado
// $_SESSION['cliente']     ? Nombre o razón social
// $_SESSION['sg_id']       ? ID de grupo SweetCRM (KickOff)
?>