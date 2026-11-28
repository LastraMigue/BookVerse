<?php
// BookVerse — Cierre de sesión
require_once __DIR__ . '/../includes/auth.php';

cerrarSesionUsuario();

header('Location: login.php?mensaje=sesion_cerrada');
exit;
