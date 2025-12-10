<?php
// ==========================================================
// KickOff AJAX – Bootstrap común para TODOS los módulos
// Autor: Mauricio Araneda (mAo)
// Fecha: 2025-12
// Codificación: UTF-8 sin BOM
// ==========================================================

// -------------------------
// 1) CONFIGURAR SESIÓN
// -------------------------
session_name('icontel_intranet_sess');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '.icontel.cl');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// -------------------------
// 2) VALIDACIÓN DE SESIÓN
// -------------------------
$sg_id   = $_SESSION['sg_id']   ?? '';
$sg_name = $_SESSION['sg_name'] ?? '';
$usuario = $_SESSION['usuario'] ?? '';

if ($sg_id === '' || $sg_name === '') {

    // Log para debugging
    error_log("❌ AJAX ERROR — SESIÓN VACÍA: sg_id={$sg_id}, sg_name={$sg_name}, usuario={$usuario}");

    // Error visible en AJAX
    die("
        <div style='padding:20px; color:#a00; background:#fee; 
             border:1px solid #d77; border-radius:6px;'>
            ❌ Error: sesión no válida<br>
            <small>Por favor recargue KickOff.</small>
        </div>
    ");
}

// Log OK
//error_log("✔ AJAX OK — sg_id={$sg_id}, sg_name={$sg_name}, usuario={$usuario}");

// -------------------------
// 3) INCLUIR CONFIGURACIÓN
// -------------------------
require_once __DIR__ . "/config.php"; // rutas absolutas para evitar errores

// -------------------------
// 4) EXPONER VARIABLES
// -------------------------
$GLOBALS["sg_id"]   = $sg_id;
$GLOBALS["sg_name"] = $sg_name;
$GLOBALS["usuario"] = $usuario;

