<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$haySesion = isset($_SESSION['usuario']);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="styles.css">
<header class="barra-superior barra-inicio">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-bookverse" aria-label="Navegación principal">
        <a class="navbar-brand navbar-marca" href="inicio.php">BookVerse</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu-principal" aria-controls="menu-principal" aria-expanded="false" aria-label="Abrir menú de navegación">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu-principal">
            <ul class="navbar-nav enlaces-superiores">
                <li class="nav-item"><a class="nav-link" href="inicio.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="catalogo.php">Catálogo</a></li>
                <li class="nav-item"><a class="nav-link" href="FormularioLibro.php">Proponer un libro</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $haySesion ? htmlspecialchars($_SESSION['usuario']['nombre'], ENT_QUOTES, 'UTF-8') : 'Ajustes' ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="MisListas.php">Mis listas</a></li>
                        <?php if ($haySesion): ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="IniciarSesion.php">Iniciar sesión</a></li>
                            <li><a class="dropdown-item" href="CrearCuenta.php">Crear cuenta</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
