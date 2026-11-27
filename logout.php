<?php
// BookVerse — Cierre de Sesión (Logout)
// Archivo: logout.php
// Rol: Integrante 1 (Backend núcleo, base de datos y Git)

require_once __DIR__ . '/includes/auth.php';

cerrarSesionUsuario();

header('Location: IniciarSesion.php?mensaje=sesion_cerrada');
exit;
