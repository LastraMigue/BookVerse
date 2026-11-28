<?php
// BookVerse — Sistema de autenticación y control de acceso

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexion.php';

// Devuelve la ruta base del proyecto respecto al DocumentRoot (ej. /Act_servidor/BookVerse)
function obtenerBaseUrl(): string
{
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $projectRoot = str_replace('\\', '/', realpath(dirname(__DIR__)));

    if ($docRoot !== '' && strpos($projectRoot, $docRoot) === 0) {
        return rtrim(substr($projectRoot, strlen($docRoot)), '/');
    }

    return '';
}

// Inicia la sesión para un usuario autenticado y regenera el ID de sesión
function iniciarSesionUsuario(array $usuario): void
{
    session_regenerate_id(true);

    $_SESSION['usuario'] = [
        'id'             => (int)$usuario['id'],
        'nombre'         => (string)$usuario['nombre'],
        'email'          => (string)$usuario['email'],
        'es_admin'       => (int)$usuario['es_admin'] === 1,
        'fecha_registro' => (string)($usuario['fecha_registro'] ?? '')
    ];
}

// Cierra la sesión activa del usuario de forma completa y segura
function cerrarSesionUsuario(): void
{
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
}

// Devuelve los datos del usuario en sesión o null si no hay sesión activa
function obtenerUsuarioAutenticado(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

// Comprueba si hay una persona usuaria con sesión activa
function estaAutenticado(): bool
{
    return isset($_SESSION['usuario']) && is_array($_SESSION['usuario']) && !empty($_SESSION['usuario']['id']);
}

// Comprueba si la persona con sesión activa tiene privilegios de administrador
function esAdmin(): bool
{
    return estaAutenticado() && !empty($_SESSION['usuario']['es_admin']);
}

// Exige que la persona usuaria haya iniciado sesión
function exigirSesion(?string $urlRetorno = null): void
{
    $base = obtenerBaseUrl();

    if (!estaAutenticado()) {
        $param = $urlRetorno ? '?redirect=' . urlencode($urlRetorno) : '';
        header('Location: ' . $base . '/auth/login.php' . $param);
        exit;
    }

    if (esAdmin()) {
        header('Location: ' . $base . '/views/administration.php');
        exit;
    }
}

// Exige privilegios de administrador
function exigirAdmin(): void
{
    $base = obtenerBaseUrl();

    if (!estaAutenticado()) {
        header('Location: ' . $base . '/auth/login.php');
        exit;
    }

    if (!esAdmin()) {
        header('Location: ' . $base . '/views/catalog.php');
        exit;
    }
}

// Redirige a usuarios que ya tienen sesión activa (para login/register)
function redirigirSiAutenticado(): void
{
    $base = obtenerBaseUrl();

    if (estaAutenticado()) {
        if (esAdmin()) {
            header('Location: ' . $base . '/views/administration.php');
        } else {
            header('Location: ' . $base . '/views/catalog.php');
        }
        exit;
    }
}

// Control de acceso para páginas públicas
function verificarAccesoPaginaPublica(): void
{
    $base = obtenerBaseUrl();

    if (esAdmin()) {
        header('Location: ' . $base . '/views/administration.php');
        exit;
    }
}
