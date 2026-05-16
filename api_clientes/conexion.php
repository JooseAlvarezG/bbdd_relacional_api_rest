<?php
// ============================================================
// conexion.php
// Conexión segura usando PDO.
// PDO permite trabajar con consultas preparadas, lo que ayuda
// a prevenir inyección SQL.
// ============================================================

require_once 'config.php';

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // Lanza excepciones ante errores.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Devuelve arreglos asociativos.
        PDO::ATTR_EMULATE_PREPARES => false              // Usa preparaciones reales del motor.
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'Error de conexión con la base de datos',
        'detalle' => $e->getMessage()
    ]);
    exit;
}
?>