<?php
// ============================================================
// config.php
// Configuración de conexión a MySQL para XAMPP.
// En XAMPP normalmente el usuario es root y la contraseña está vacía.
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'api_clientes_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Se define el tipo de respuesta por defecto como JSON.
header('Content-Type: application/json; charset=utf-8');

// Permite pruebas locales desde herramientas como Postman.
// Para producción, esto debe restringirse a dominios permitidos.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
?>