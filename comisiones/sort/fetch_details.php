<?php
// 1. Iniciar la sesión para poder acceder a $_SESSION
require_once __DIR__ . '/session_config.php';

// 2. Incluir el archivo de configuración para la conexión a la BD
include "../includes/config.php";

// 3. Lista blanca de columnas que se permiten ordenar (SEGURIDAD)
 $allowed_columns = [
    'coti_num', 'fac_num', 'fac_fecha', 'fac_cliente', 'fac_estado', 
    'fac_vendedor', 'fechacierre', 'meta_uf', 'cierre_uf', 'cumplimiento', 
    'comision', 'neto_uf', 'costo_uf', 'margen_uf', 'neto_comi_uf', 
    'comision_uf', 'comi_sgv_uf'
];

 $columnName = $_POST['columnName'];

// 4. Verificar si la columna enviada está en la lista blanca
if (!in_array($columnName, $allowed_columns)) {
    // Si no está, detener la ejecución para prevenir ataques
    die("Error: Intento de ordenación no válido.");
}

// 5. Construir la cláusula ORDER BY de forma segura
 $orden = " ORDER BY " . $columnName . " " . $_POST['sort'];

// 6. Ensamblar la consulta final usando las variables de sesión
 $query = $_SESSION['query'] . $_SESSION['agrupar'] . $orden;

// 7. Incluir el archivo que genera el HTML de la tabla y lo imprime
include_once("tabla_datos.php");
?>