<?php
/**
 * =============================================================================
 * ARCHIVO: /intranet/includes/sweet_session_check.php
 * =============================================================================
 * 
 * SweetSessionCheck - Módulo de Verificación de Sesión SuiteCRM
 * 
 * Lee la sesión de SuiteCRM directamente desde los archivos de sesión PHP
 * ya que SuiteCRM usa un nombre de sesión diferente (PHPSESSID) que no se
 * comparte con la sesión de Kickoff (icontel_intranet_sess).
 * 
 * @author Mauricio Araneda (mAo)
 * @date 2025-12-08
 */

class SweetSessionCheck {
    
    /**
     * Obtiene la cookie PHPSESSID de SuiteCRM
     * 
     * @return string|null Session ID de SuiteCRM o null
     */
    private static function getSweetSessionId() {
        // SuiteCRM usa PHPSESSID como nombre de sesión
        return $_COOKIE['PHPSESSID'] ?? null;
    }
    
    /**
     * Lee el archivo de sesión de SuiteCRM
     * 
     * @param string $session_id Session ID de SuiteCRM
     * @return array|null Datos de la sesión o null si no existe
     */
    private static function readSweetSessionFile($session_id) {
        if (empty($session_id)) {
            return null;
        }
        
        // Rutas comunes de sesiones en cPanel/Linux
        $possible_paths = [
            '/tmp/sess_' . $session_id,
            '/var/cpanel/php/sessions/ea-php74/sess_' . $session_id,
            '/var/cpanel/php/sessions/ea-php73/sess_' . $session_id,
            '/var/cpanel/php/sessions/ea-php72/sess_' . $session_id,
            '/var/cpanel/php/sessions/ea-php70/sess_' . $session_id,
            session_save_path() . '/sess_' . $session_id,
        ];
        
        foreach ($possible_paths as $path) {
            if (file_exists($path) && is_readable($path)) {
                $session_data = file_get_contents($path);
                
                if ($session_data !== false) {
                    // Decodificar datos de sesión PHP
                    $decoded = self::unserializeSession($session_data);
                    return $decoded;
                }
            }
        }
        
        return null;
    }
    
    /**
     * Deserializa datos de sesión PHP
     * 
     * @param string $session_data Datos serializados de la sesión
     * @return array Array con variables de sesión
     */
    private static function unserializeSession($session_data) {
        $vars = [];
        $offset = 0;
        
        while ($offset < strlen($session_data)) {
            if (!strstr(substr($session_data, $offset), "|")) {
                break;
            }
            
            $pos = strpos($session_data, "|", $offset);
            $num = $pos - $offset;
            $varname = substr($session_data, $offset, $num);
            $offset += $num + 1;
            
            $data = unserialize(substr($session_data, $offset));
            $vars[$varname] = $data;
            $offset += strlen(serialize($data));
        }
        
        return $vars;
    }
    
    /**
     * Verifica si existe una sesión activa de SuiteCRM
     * 
     * @return bool True si hay sesión activa, false en caso contrario
     */
    public static function isLoggedIn() {
        $session_id = self::getSweetSessionId();
        
        if (!$session_id) {
            return false;
        }
        
        $session_data = self::readSweetSessionFile($session_id);
        
        if (!$session_data) {
            return false;
        }
        
        // Verificar si existe el user_id de SuiteCRM en la sesión
        return isset($session_data['authenticated_user_id']) && !empty($session_data['authenticated_user_id']);
    }
    
    /**
     * Obtiene el ID del usuario autenticado en SuiteCRM
     * 
     * @return string|null User ID o null si no hay sesión
     */
    public static function getUserId() {
        $session_id = self::getSweetSessionId();
        
        if (!$session_id) {
            return null;
        }
        
        $session_data = self::readSweetSessionFile($session_id);
        
        if (!$session_data) {
            return null;
        }
        
        return $session_data['authenticated_user_id'] ?? null;
    }
    
    /**
     * Obtiene los datos del usuario desde la base de datos de SuiteCRM
     * 
     * @return array|null Array con datos del usuario o null si no existe
     */
    public static function getUserData() {
        $user_id = self::getUserId();
        
        if (!$user_id) {
            return null;
        }
        
        // Conectar a la base de datos de SuiteCRM
        $conn = new mysqli("localhost", "data_studio", "1Ngr3s0.,", "tnasolut_sweet");
        
        if ($conn->connect_error) {
            error_log("SweetSessionCheck: Error de conexión - " . $conn->connect_error);
            return null;
        }
        
        mysqli_set_charset($conn, "utf8");
        
        // Consultar datos del usuario
        $stmt = $conn->prepare("
            SELECT 
                id,
                user_name,
                first_name,
                last_name,
                CONCAT(first_name, ' ', last_name) as full_name,
                email1 as email,
                status,
                is_admin
            FROM users
            WHERE id = ? AND deleted = 0
        ");
        
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $user_data = null;
        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
        }
        
        $stmt->close();
        $conn->close();
        
        return $user_data;
    }
    
    /**
     * Obtiene los security groups del usuario desde la base de datos
     * 
     * @param string $user_id ID del usuario
     * @return array Array de security groups con id y name
     */
    public static function getSecurityGroups($user_id) {
        if (empty($user_id)) {
            return [];
        }
        
        // Conectar a la base de datos de SuiteCRM
        $conn = new mysqli("localhost", "data_studio", "1Ngr3s0.,", "tnasolut_sweet");
        
        if ($conn->connect_error) {
            error_log("SweetSessionCheck: Error de conexión - " . $conn->connect_error);
            return [];
        }
        
        mysqli_set_charset($conn, "utf8");
        
        // Consultar security groups del usuario
        $stmt = $conn->prepare("
            SELECT 
                sg.id,
                sg.name,
                sg.description
            FROM securitygroups sg
            JOIN securitygroups_users sgu ON sg.id = sgu.securitygroup_id
            WHERE sgu.user_id = ? 
              AND sgu.deleted = 0 
              AND sg.deleted = 0
            ORDER BY sg.name
        ");
        
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $groups = [];
        while ($row = $result->fetch_assoc()) {
            $groups[] = $row;
        }
        
        $stmt->close();
        $conn->close();
        
        return $groups;
    }
    
    /**
     * Obtiene información de la sesión de SuiteCRM (para debugging)
     * 
     * @return array Array con información de la sesión
     */
    public static function getSessionInfo() {
        $session_id = self::getSweetSessionId();
        
        if (!$session_id) {
            return [
                'error' => 'No hay cookie PHPSESSID de SuiteCRM',
                'sweet_session_id' => null,
                'session_file_found' => false,
            ];
        }
        
        $session_data = self::readSweetSessionFile($session_id);
        
        $info = [
            'sweet_session_id' => $session_id,
            'session_file_found' => $session_data !== null,
            'authenticated_user_id' => $session_data['authenticated_user_id'] ?? null,
            'user_name' => $session_data['user_name'] ?? null,
            'is_admin' => $session_data['is_admin'] ?? null,
            'session_vars_count' => $session_data ? count($session_data) : 0,
        ];
        
        return $info;
    }
    
    /**
     * Obtiene todas las variables de la sesión de SuiteCRM (para debugging)
     * 
     * @return array|null Array con todas las variables o null
     */
    public static function getAllSessionVars() {
        $session_id = self::getSweetSessionId();
        
        if (!$session_id) {
            return null;
        }
        
        return self::readSweetSessionFile($session_id);
    }
    
    /**
     * Obtiene la ruta del archivo de sesión y su contenido raw (para debugging)
     * 
     * @return array Array con path y contenido
     */
    public static function getSessionFileDebug() {
        $session_id = self::getSweetSessionId();
        
        if (!$session_id) {
            return [
                'session_id' => null,
                'file_path' => null,
                'file_exists' => false,
                'file_readable' => false,
                'raw_content' => null,
                'content_length' => 0,
            ];
        }
        
        // Rutas comunes de sesiones en cPanel/Linux
        $possible_paths = [
            '/tmp/sess_' . $session_id,
            '/var/cpanel/php/sessions/ea-php74/sess_' . $session_id,
            '/var/cpanel/php/sessions/ea-php73/sess_' . $session_id,
            '/var/cpanel/php/sessions/ea-php72/sess_' . $session_id,
            '/var/cpanel/php/sessions/ea-php70/sess_' . $session_id,
            session_save_path() . '/sess_' . $session_id,
        ];
        
        foreach ($possible_paths as $path) {
            if (file_exists($path)) {
                $readable = is_readable($path);
                $content = $readable ? file_get_contents($path) : null;
                
                return [
                    'session_id' => $session_id,
                    'file_path' => $path,
                    'file_exists' => true,
                    'file_readable' => $readable,
                    'raw_content' => $content,
                    'content_length' => $content ? strlen($content) : 0,
                ];
            }
        }
        
        return [
            'session_id' => $session_id,
            'file_path' => 'No encontrado en rutas comunes',
            'file_exists' => false,
            'file_readable' => false,
            'raw_content' => null,
            'content_length' => 0,
            'tried_paths' => $possible_paths,
        ];
    }
}
?>
