<?php
// BookVerse — Catálogo público (solo libros publicados)

require_once __DIR__ . '/../includes/libros.php';

$busqueda = trim($_GET['q'] ?? '');
$generoIdFiltro = filter_input(INPUT_GET, 'genero', FILTER_VALIDATE_INT) ?: null;

$generos = obtenerGeneros($pdo);
$librosPublicados = obtenerLibrosPublicados($pdo, $busqueda !== '' ? $busqueda : null, $generoIdFiltro);

$mensajeExito = '';
if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'libro_pendiente') {
    $mensajeExito = "Tu libro está pendiente de verificación.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explora los libros publicados de BookVerse y encuentra tu próxima lectura favorita.">
    <title>Catálogo de libros | BookVerse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/base.css">
</head>
<body class="pagina-catalogo pagina-catalogo-principal">
    <?php include '../includes/navbar.php'; ?>

    <section class="marco-catalogo">
        <main class="panel-catalogo">
            <header class="navegacion-catalogo">
                <form class="busqueda-catalogo" role="search" action="catalog.php" method="get">
                    <input type="search" name="q" value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>" aria-label="Buscar libros por título o autor" placeholder="Buscar por título o autor">
                </form>
            </header>

            <?php if ($mensajeExito !== ''): ?>
                <div class="alert alert-success mx-3 mt-3" role="alert">
                    <?= htmlspecialchars($mensajeExito, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <section class="presentacion-catalogo" aria-labelledby="titulo-catalogo">
                <p class="rotulo-catalogo">La biblioteca de BookVerse</p>
                <h1 id="titulo-catalogo">Una buena historia siempre te encuentra.</h1>
                <p class="descripcion-presentacion-catalogo">Explora los libros publicados de nuestra comunidad. Elige un género, déjate llevar y descubre qué leer después.</p>
                <div class="resumen-catalogo">
                    <span><strong><?= count($librosPublicados) ?></strong> libros publicados</span>
                    <span><strong><?= count($generos) ?></strong> géneros disponibles</span>
                </div>
            </section>

            <div class="cuerpo-catalogo">
                <aside class="barra-lateral-catalogo">
                    <div class="encabezado-filtros">
                        <h2>Explora por género</h2>
                        <span><a href="catalog.php">Todos</a></span>
                    </div>
                    <nav class="enlaces-generos" aria-label="Géneros del catálogo">
                        <?php foreach ($generos as $genero): ?>
                            <a href="catalog.php?genero=<?= (int)$genero['id'] ?>" <?= $generoIdFiltro === (int)$genero['id'] ? 'class="fw-bold"' : '' ?>>
                                <?= htmlspecialchars($genero['nombre'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>
                    <div class="nota-catalogo">
                        <p>¿Tienes otro libro en mente?</p>
                        <a href="book-form.php">Propón un título <span aria-hidden="true">→</span></a>
                    </div>
                </aside>

                <section class="resultados-catalogo" aria-labelledby="titulo-seleccion">
                    <div class="encabezado-resultados">
                        <div>
                            <p>Empieza a explorar</p>
                            <h2 id="titulo-seleccion">Historias para descubrir</h2>
                            <small>Solo se muestran libros aprobados y publicados en el catálogo.</small>
                        </div>
                        <span class="etiqueta-seleccion">Catálogo público</span>
                    </div>

                    <?php if (empty($librosPublicados)): ?>
                        <div class="alert alert-info" role="alert">
                            No se encontraron libros publicados con los criterios seleccionados.
                        </div>
                    <?php else: ?>
                        <div class="cuadricula-libros">
                            <?php foreach ($librosPublicados as $libro): ?>
                                <a class="enlace-tarjeta-libro" href="book.php?id=<?= (int)$libro['id'] ?>">
                                    <article class="tarjeta-libro">
                                        <div class="portada-libro portada-viento" role="img" aria-label="Portada de <?= htmlspecialchars($libro['titulo'], ENT_QUOTES, 'UTF-8') ?>">
                                            <span>BOOKVERSE</span>
                                            <strong><?= htmlspecialchars($libro['titulo'], ENT_QUOTES, 'UTF-8') ?></strong>
                                            <small><?= htmlspecialchars($libro['autor'], ENT_QUOTES, 'UTF-8') ?></small>
                                        </div>
                                        <div class="datos-libro">
                                            <span class="genero-libro"><?= htmlspecialchars($libro['genero_nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                                            <h3><?= htmlspecialchars($libro['titulo'], ENT_QUOTES, 'UTF-8') ?></h3>
                                            <p class="autor-libro"><?= htmlspecialchars($libro['autor'], ENT_QUOTES, 'UTF-8') ?></p>
                                            <p class="descripcion-libro"><?= htmlspecialchars($libro['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                                        </div>
                                    </article>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        </main>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
