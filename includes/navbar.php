<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$haySesion = isset($_SESSION['usuario']);

// Detecta desde qué profundidad se sirve este navbar para ajustar rutas
$depth = substr_count($_SERVER['SCRIPT_NAME'], '/') - 2; // relativo a /BookVerse/
$base = str_repeat('../', max($depth, 0));
?>
<header class="barra-superior barra-inicio">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-bookverse" aria-label="Navegación principal">
        <a class="navbar-brand navbar-marca" href="<?= $base ?>views/home.php">BookVerse</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu-principal" aria-controls="menu-principal" aria-expanded="false" aria-label="Abrir menú de navegación">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu-principal">
            <ul class="navbar-nav enlaces-superiores">
                <li class="nav-item"><a class="nav-link" href="<?= $base ?>views/home.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $base ?>views/catalog.php">Catálogo</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $base ?>views/book-form.php">Proponer un libro</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $base ?>views/my-lists.php">Mis listas</a></li>
                <?php if ($haySesion): ?>
                    <li class="nav-item">
                        <span class="nav-link">Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base ?>auth/logout.php">Cerrar sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $base ?>auth/login.php">Iniciar sesión</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>
