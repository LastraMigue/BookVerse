<?php
// BookVerse — Sistema de Autenticación y Control de Acceso

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexion.php';

// Inicia la sesión para un usuario autenticado y regenera el ID de sesión
// para mitigar ataques de fijación de sesión (Session Fixation).
function iniciarSesionUsuario(array $usuario): void
{
    // Regenerar el ID de sesión de forma segura
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

// Devuelve el ID numérico del usuario en sesión o null si es invitado
function obtenerUsuarioIdSesion(): ?int
{
    $usuario = obtenerUsuarioAutenticado();
    return $usuario ? (int)$usuario['id'] : null;
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

// Exige que la persona usuaria haya iniciado sesión.
// Si no tiene sesión, la redirige a IniciarSesion.php.
// Si es administrador, lo redirige a administracion.php según las reglas del proyecto.
function exigirSesion(?string $urlRetorno = null): void
{
    if (!estaAutenticado()) {
        $param = $urlRetorno ? '?redirect=' . urlencode($urlRetorno) : '';
        header('Location: IniciarSesion.php' . $param);
        exit;
    }

    // Regla del proyecto: el administrador solo ve moderación
    if (esAdmin()) {
        header('Location: administracion.php');
        exit;
    }
}

// Exige privilegios de administrador.
// Si no está autenticado, redirige a IniciarSesion.php.
// Si es usuario estándar sin permisos de administración, lo redirige al catálogo.
function exigirAdmin(): void
{
    if (!estaAutenticado()) {
        header('Location: IniciarSesion.php');
        exit;
    }

    if (!esAdmin()) {
        header('Location: catalogo.php');
        exit;
    }
}

// Redirige a los usuarios que ya tienen una sesión activa.
// Se utiliza en las páginas de acceso (IniciarSesion.php y CrearCuenta.php).
function redirigirSiAutenticado(): void
{
    if (estaAutenticado()) {
        if (esAdmin()) {
            header('Location: administracion.php');
        } else {
            header('Location: catalogo.php');
        }
        exit;
    }
}

// Control de acceso para páginas públicas (inicio, catálogo, detalle de libro).
// Regla técnica del proyecto: Si el administrador intenta abrir cualquier otra
// página de la aplicación que no sea moderación, el servidor lo redirige a moderación.
function verificarAccesoPaginaPublica(): void
{
    if (esAdmin()) {
        header('Location: administracion.php');
        exit;
    }
}
