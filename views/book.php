<?php
// BookVerse — Detalle de libro (solo libros publicados)

require_once __DIR__ . '/../includes/libros.php';
require_once __DIR__ . '/../includes/resenas.php';

$libroId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$libro = $libroId ? obtenerLibroPublicadoPorId($pdo, $libroId) : null;

$resenas = [];
$valoracion = ['total' => 0, 'media' => null];
if ($libro !== null) {
    $resenas = escaparResenasParaVista(obtenerResenasPorLibro($pdo, (int)$libro['id']));
    $valoracion = obtenerMediaValoracion($pdo, (int)$libro['id']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Consulta detalles, sinopsis y reseñas de libros publicados en BookVerse.">
    <title><?= $libro ? htmlspecialchars($libro['titulo'], ENT_QUOTES, 'UTF-8') . ' | BookVerse' : 'Libro no disponible | BookVerse' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/base.css">
</head>
<body class="pagina-catalogo pagina-detalle">
    <?php include '../includes/navbar.php'; ?>

    <section class="marco-catalogo">
        <main class="panel-catalogo panel-detalle">
            <?php if ($libro === null): ?>
                <div class="p-4">
                    <div class="alert alert-warning" role="alert">
                        Este libro no existe o aún está pendiente de verificación.
                    </div>
                    <a class="btn btn-outline-secondary" href="catalog.php">Volver al catálogo</a>
                </div>
            <?php else: ?>
                <div class="contenedor-fichas-libro">
                    <article class="ficha-libro">
                        <div class="cuerpo-detalle">
                            <div class="contenido-principal-detalle">
                                <nav class="migas-de-pan" aria-label="Ruta de navegación">
                                    <a href="catalog.php">Catálogo</a><span></span>
                                    <a href="catalog.php?genero=<?= (int)$libro['genero_id'] ?>"><?= htmlspecialchars($libro['genero_nombre'], ENT_QUOTES, 'UTF-8') ?></a><span></span>
                                    <b><?= htmlspecialchars($libro['titulo'], ENT_QUOTES, 'UTF-8') ?></b>
                                </nav>
                                <div class="resumen-libro">
                                    <div class="portada-detalle">
                                        <img class="portada-ilustrada" src="../assets/img/bv_1_libro_cerrado.svg" alt="Portada de <?= htmlspecialchars($libro['titulo'], ENT_QUOTES, 'UTF-8') ?>">
                                    </div>
                                    <div class="informacion-resumen">
                                        <p class="etiqueta-genero"><?= htmlspecialchars($libro['genero_nombre'], ENT_QUOTES, 'UTF-8') ?></p>
                                        <h1><?= htmlspecialchars($libro['titulo'], ENT_QUOTES, 'UTF-8') ?></h1>
                                        <small><?= htmlspecialchars($libro['autor'], ENT_QUOTES, 'UTF-8') ?></small>
                                        <p class="dato-valoraciones">
                                            <?php if ($valoracion['media'] !== null): ?>
                                                <?= htmlspecialchars((string)$valoracion['media'], ENT_QUOTES, 'UTF-8') ?> de 5 · <?= (int)$valoracion['total'] ?> reseñas
                                            <?php else: ?>
                                                Sin valoraciones todavía
                                            <?php endif; ?>
                                        </p>
                                        <button class="boton-agregar-lista" type="button">Añadir a mi lista</button>
                                        <button class="boton-agregar-estanteria" type="button">Guardar en estantería</button>
                                    </div>
                                </div>
                                <section class="sinopsis">
                                    <h2>Descripción</h2>
                                    <p><?= nl2br(htmlspecialchars($libro['descripcion'], ENT_QUOTES, 'UTF-8')) ?></p>
                                </section>
                            </div>
                            <aside class="barra-lateral-detalle">
                                <h2>Detalles del libro</h2>
                                <dl>
                                    <dt>ISBN</dt><dd><?= htmlspecialchars($libro['isbn'], ENT_QUOTES, 'UTF-8') ?></dd>
                                    <dt>Género</dt><dd><?= htmlspecialchars($libro['genero_nombre'], ENT_QUOTES, 'UTF-8') ?></dd>
                                    <dt>Fecha de alta</dt><dd><?= htmlspecialchars($libro['fecha_envio'], ENT_QUOTES, 'UTF-8') ?></dd>
                                </dl>
                                <div class="seccion-valoracion">
                                    <h2>Valoraciones</h2>
                                    <div class="valoracion-media">
                                        <strong><?= $valoracion['media'] !== null ? htmlspecialchars((string)$valoracion['media'], ENT_QUOTES, 'UTF-8') : '—' ?></strong>
                                    </div>
                                    <p class="aviso-valoraciones"><?= (int)$valoracion['total'] ?> reseñas registradas.</p>
                                </div>
                                <section class="resenas">
                                    <div class="encabezado-resenas"><h2>Reseñas</h2><a href="catalog.php">Volver</a></div>
                                    <?php if (empty($resenas)): ?>
                                        <p>Todavía no hay reseñas para este libro.</p>
                                    <?php else: ?>
                                        <?php foreach ($resenas as $resena): ?>
                                            <article>
                                                <div class="imagen-perfil" aria-hidden="true"></div>
                                                <div>
                                                    <strong><?= $resena['usuario_nombre'] ?></strong>
                                                    <div><span><?= (int)$resena['valoracion'] ?> / 5 ★</span></div>
                                                    <p><?= $resena['texto_formateado'] ?></p>
                                                </div>
                                            </article>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </section>
                            </aside>
                        </div>
                    </article>
                </div>
            <?php endif; ?>
        </main>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
