<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$haySesion = isset($_SESSION['usuario']);

// Calcula la ruta relativa hacia la raíz del proyecto sin depender de la carpeta en htdocs
$projectRoot = str_replace('\\', '/', realpath(dirname(__DIR__)));
$scriptDir = str_replace('\\', '/', dirname(realpath($_SERVER['SCRIPT_FILENAME'])));
$rel = trim(substr($scriptDir, strlen($projectRoot)), '/');
$depth = $rel === '' ? 0 : substr_count($rel, '/') + 1;
$base = str_repeat('../', $depth);
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
