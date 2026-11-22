<?php
/**
 * BookVerse — Conexión a la Base de Datos mediante PDO
 *
 * Configuración técnica:
 * - Modo de errores: PDO::ERRMODE_EXCEPTION (excepciones activadas)
 * - Juego de caracteres: utf8mb4 (soporte completo de caracteres y emojis)
 * - Modo de obtención predeterminado: PDO::FETCH_ASSOC
 * - Emulación de sentencias preparadas desactivada (seguridad nativa contra inyecciones SQL)
 */

// Localizar y cargar config.php (puede estar en la raíz o en includes/)
$rutaConfigRaiz = dirname(__DIR__) . '/config.php';
$rutaConfigIncludes = __DIR__ . '/config.php';

if (file_exists($rutaConfigRaiz)) {
    require_once $rutaConfigRaiz;
} elseif (file_exists($rutaConfigIncludes)) {
    require_once $rutaConfigIncludes;
}

// Parámetros de conexión con valores predeterminados de seguridad
$dbHost    = defined('DB_HOST') ? DB_HOST : 'localhost';
$dbName    = defined('DB_NAME') ? DB_NAME : 'bookverse_db';
$dbUser    = defined('DB_USER') ? DB_USER : 'root';
$dbPass    = defined('DB_PASS') ? DB_PASS : '';
$dbCharset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';

$dsn = "mysql:host={$dbHost};dbname={$dbName};charset={$dbCharset}";

$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $opciones);
} catch (PDOException $e) {
    // Registrar el error real en los logs del servidor para depuración
    error_log("Error PDO en BookVerse: " . $e->getMessage());

    // Mensaje controlado para el usuario sin exponer credenciales ni rutas internas
    die("Error al conectar con la base de datos. Por favor, asegúrese de haber importado 'docs/sql/schema.sql' y de que el servicio MySQL esté iniciado en XAMPP.");
}

/**
 * Función auxiliar para obtener la instancia PDO en cualquier ámbito de función
 *
 * @return PDO
 */
function obtenerConexion(): PDO
{
    global $pdo;
    return $pdo;
}
