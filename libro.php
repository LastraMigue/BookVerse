<?php
// BookVerse — Detalle del Libro y Gestión de Reseñas
// Rol: Backend y Base de Datos (PDO, Sesiones y Seguridad ILERNA)

require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/resenas.php';

// Mapeo entre slug de URL / ancla y el ID real del libro en la BD
$mapaLibros = [
    'el-nombre-del-viento'           => 3,
    'dracula'                        => 8,
    'orgullo-y-prejuicio'            => 6,
    'el-sabueso-de-los-baskerville' => 5,
    'cien-anos-de-soledad'           => 1
];

// Obtener sesión de usuario (null si es invitado)
$usuarioActual = obtenerUsuarioAutenticado();
$usuarioId = $usuarioActual ? (int)$usuarioActual['id'] : null;

// Mensajes flash
$mensajeExito = '';
$mensajeError = '';

// Procesar acciones POST (guardar/actualizar o eliminar reseña)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = filter_input(INPUT_POST, 'accion', FILTER_DEFAULT);

    if ($accion === 'guardar_resena') {
        if (!$usuarioId) {
            $slugRetorno = filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
            header('Location: IniciarSesion.php?redirect=' . urlencode('libro.php#' . $slugRetorno));
            exit;
        }

        $libroIdForm = filter_input(INPUT_POST, 'libro_id', FILTER_VALIDATE_INT);
        $valoracion = filter_input(INPUT_POST, 'valoracion', FILTER_VALIDATE_INT);
        $texto = $_POST['texto'] ?? '';
        $slug = filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

        if (!$libroIdForm) {
            $mensajeError = "ID de libro no válido.";
        } else {
            $res = guardarOActualizarResena($pdo, $libroIdForm, $usuarioId, (int)$valoracion, (string)$texto);
            if ($res['exito']) {
                header("Location: libro.php?guardado=1#" . $slug);
                exit;
            } else {
                $mensajeError = $res['mensaje'];
            }
        }
    } elseif ($accion === 'eliminar_resena') {
        if (!$usuarioId) {
            header('Location: IniciarSesion.php');
            exit;
        }

        $resenaId = filter_input(INPUT_POST, 'resena_id', FILTER_VALIDATE_INT);
        $slug = filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

        if ($resenaId) {
            $res = eliminarResenaPropia($pdo, $resenaId, $usuarioId);
            if ($res['exito']) {
                header("Location: libro.php?eliminado=1#" . $slug);
                exit;
            } else {
                $mensajeError = $res['mensaje'];
            }
        }
    }
}

if (isset($_GET['guardado'])) {
    $mensajeExito = "¡Tu reseña ha sido procesada con éxito!";
} elseif (isset($_GET['eliminado'])) {
    $mensajeExito = "Tu reseña ha sido eliminada.";
}

// Cargar estadísticas y reseñas reales de la base de datos para cada libro
$datosLibros = [];
foreach ($mapaLibros as $slug => $id) {
    $stats = obtenerMediaValoracion($pdo, $id);
    $listaResenas = obtenerResenasPorLibro($pdo, $id);
    $miResena = $usuarioId ? obtenerResenaDeUsuario($pdo, $id, $usuarioId) : null;

    $datosLibros[$slug] = [
        'id'        => $id,
        'media'     => $stats['media'],
        'total'     => $stats['total'],
        'resenas'   => escaparResenasParaVista($listaResenas),
        'mi_resena' => $miResena
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Consulta portadas, sinopsis y detalles de libros seleccionados por BookVerse.">
    <title>Detalles del libro | BookVerse</title>
</head>
<body class="pagina-catalogo pagina-detalle">
    <?php include 'navbar.php'; ?>

    <section class="marco-catalogo">
        <main class="panel-catalogo panel-detalle">
            <header class="navegacion-catalogo">
                <a class="titulo-catalogo" href="inicio.php" aria-label="Ir a BookVerse">
                    <span>BV</span>
                    <strong>BookVerse</strong>
                </a>
            </header>

            <?php if (!empty($mensajeExito)): ?>
                <div class="alert alert-success m-3" role="alert">
                    <?= htmlspecialchars($mensajeExito, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($mensajeError)): ?>
                <div class="alert alert-danger m-3" role="alert">
                    <?= htmlspecialchars($mensajeError, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <div class="contenedor-fichas-libro">
                <!-- ================= FICHA 1: EL NOMBRE DEL VIENTO ================= -->
                <?php $d = $datosLibros['el-nombre-del-viento']; ?>
                <article class="ficha-libro" id="el-nombre-del-viento">
                    <div class="cuerpo-detalle">
                        <div class="contenido-principal-detalle">
                            <nav class="migas-de-pan" aria-label="Ruta de navegación">
                                <a href="catalogo.php">Catálogo</a><span></span>
                                <a href="catalogo.php#libro-fantasia">Fantasía épica</a><span></span>
                                <b>El nombre del viento</b>
                            </nav>
                            <div class="resumen-libro">
                                <div class="portada-detalle">
                                    <img class="portada-ilustrada" src="assets/img/bv_1_libro_cerrado.svg" alt="Ilustración de un libro cerrado para El nombre del viento">
                                </div>
                                <div class="informacion-resumen">
                                    <p class="etiqueta-genero">Fantasía épica</p>
                                    <h1>El nombre del viento</h1>
                                    <small>Patrick Rothfuss</small>
                                    <div class="estrellas-detalle" aria-label="Valoración: <?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?>">
                                        <b><?= renderizarEstrellasVisuales($d['media']) ?></b>
                                    </div>
                                    <p class="dato-valoraciones"><?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?></p>
                                    
                                    <button class="boton-agregar-lista" type="button">Añadir a mi lista</button>
                                    
                                    <!-- Botón para Abrir Ventana Emergente (Modal) o Redirigir a Login -->
                                    <?php if ($usuarioId): ?>
                                        <button class="boton-agregar-estanteria" type="button" data-bs-toggle="modal" data-bs-target="#modalResena-el-nombre-del-viento">
                                            <?= $d['mi_resena'] ? 'Editar mi reseña' : 'Añadir reseña' ?>
                                        </button>
                                    <?php else: ?>
                                        <a href="IniciarSesion.php?redirect=<?= urlencode('libro.php#el-nombre-del-viento') ?>" class="boton-agregar-estanteria text-decoration-none d-inline-flex align-items-center justify-content-center">
                                            Añadir reseña
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <section class="sinopsis">
                                <h2>Sinopsis</h2>
                                <p>En una posada apartada, Kvothe decide contar la verdadera historia de su vida, desde sus primeros años entre artistas hasta su llegada a la Universidad.</p>
                                <p>Una aventura de fantasía sobre la música, el conocimiento y las leyendas que nacen alrededor de una persona.</p>
                            </section>
                        </div>
                        <aside class="barra-lateral-detalle">
                            <h2>Detalles del libro</h2>
                            <dl>
                                <dt>Publicación</dt><dd>2007</dd>
                                <dt>Origen</dt><dd>Estados Unidos</dd>
                                <dt>Idioma original</dt><dd>Inglés</dd>
                                <dt>Género</dt><dd>Fantasía épica</dd>
                            </dl>
                            <div class="seccion-valoracion">
                                <h2>Valoraciones</h2>
                                <div class="valoracion-media">
                                    <strong><?= $d['media'] !== null ? number_format($d['media'], 1, ',', '.') : '—' ?></strong>
                                    <span><?= renderizarEstrellasVisuales($d['media']) ?></span>
                                </div>
                                <p class="aviso-valoraciones"><?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <section class="resenas">
                                <div class="encabezado-resenas">
                                    <h2>Reseñas de la comunidad</h2>
                                    <a href="catalogo.php">Volver</a>
                                </div>
                                <?php if (empty($d['resenas'])): ?>
                                    <p class="text-muted small">Sé el primero en compartir tu opinión sobre este libro.</p>
                                <?php else: ?>
                                    <?php foreach ($d['resenas'] as $r): ?>
                                        <article>
                                            <div class="imagen-perfil" aria-hidden="true"></div>
                                            <div>
                                                <strong><?= $r['usuario_nombre'] ?></strong>
                                                <div><span><?= renderizarEstrellasVisuales((float)$r['valoracion']) ?></span></div>
                                                <p><?= $r['texto_formateado'] ?></p>
                                                <?php if ($usuarioId && $r['usuario_id'] === $usuarioId): ?>
                                                    <form method="POST" action="libro.php#el-nombre-del-viento" onsubmit="return confirm('¿Seguro que deseas eliminar tu reseña?');" class="mt-1">
                                                        <input type="hidden" name="accion" value="eliminar_resena">
                                                        <input type="hidden" name="resena_id" value="<?= $r['id'] ?>">
                                                        <input type="hidden" name="slug" value="el-nombre-del-viento">
                                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0 border-0" style="font-size: 11px;">Eliminar mi reseña</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </article>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </section>
                        </aside>
                    </div>

                    <!-- Modal Reseña para El Nombre del Viento -->
                    <?php if ($usuarioId): ?>
                    <div class="modal fade" id="modalResena-el-nombre-del-viento" tabindex="-1" aria-labelledby="labelModal-el-nombre-del-viento" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="labelModal-el-nombre-del-viento"><?= $d['mi_resena'] ? 'Editar mi reseña' : 'Añadir reseña' ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <form method="POST" action="libro.php#el-nombre-del-viento">
                                    <input type="hidden" name="accion" value="guardar_resena">
                                    <input type="hidden" name="libro_id" value="<?= $d['id'] ?>">
                                    <input type="hidden" name="slug" value="el-nombre-del-viento">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="val-viento" class="form-label font-monospace">Puntuación (de 1 a 5 estrellas):</label>
                                            <select name="valoracion" id="val-viento" class="form-select" required>
                                                <?php $miVal = $d['mi_resena']['valoracion'] ?? 5; ?>
                                                <option value="5" <?= $miVal == 5 ? 'selected' : '' ?>>★★★★★ (5 estrellas - Obra maestra)</option>
                                                <option value="4" <?= $miVal == 4 ? 'selected' : '' ?>>★★★★☆ (4 estrellas - Muy bueno)</option>
                                                <option value="3" <?= $miVal == 3 ? 'selected' : '' ?>>★★★☆☆ (3 estrellas - Bueno)</option>
                                                <option value="2" <?= $miVal == 2 ? 'selected' : '' ?>>★★☆☆☆ (2 estrellas - Regular)</option>
                                                <option value="1" <?= $miVal == 1 ? 'selected' : '' ?>>★☆☆☆☆ (1 estrella - No me gustó)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="txt-viento" class="form-label">Tu opinión sobre el libro:</label>
                                            <textarea name="texto" id="txt-viento" class="form-control" rows="4" placeholder="Escribe al menos 5 caracteres sobre lo que te ha parecido..." minlength="5" required><?= htmlspecialchars($d['mi_resena']['texto'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning"><?= $d['mi_resena'] ? 'Actualizar reseña' : 'Publicar reseña' ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </article>

                <!-- ================= FICHA 2: DRACULA ================= -->
                <?php $d = $datosLibros['dracula']; ?>
                <article class="ficha-libro" id="dracula">
                    <div class="cuerpo-detalle">
                        <div class="contenido-principal-detalle">
                            <nav class="migas-de-pan" aria-label="Ruta de navegación">
                                <a href="catalogo.php">Catálogo</a><span></span>
                                <a href="catalogo.php#libro-terror">Terror gótico</a><span></span>
                                <b>Drácula</b>
                            </nav>
                            <div class="resumen-libro">
                                <div class="portada-detalle"><img src="assets/img/dracula.webp" alt="Portada clásica de Drácula, de Bram Stoker"></div>
                                <div class="informacion-resumen">
                                    <p class="etiqueta-genero">Terror gótico</p>
                                    <h1>Drácula</h1>
                                    <small>Bram Stoker</small>
                                    <div class="estrellas-detalle" aria-label="Valoración: <?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?>">
                                        <b><?= renderizarEstrellasVisuales($d['media']) ?></b>
                                    </div>
                                    <p class="dato-valoraciones"><?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?></p>
                                    
                                    <button class="boton-agregar-lista" type="button">Añadir a mi lista</button>
                                    
                                    <?php if ($usuarioId): ?>
                                        <button class="boton-agregar-estanteria" type="button" data-bs-toggle="modal" data-bs-target="#modalResena-dracula">
                                            <?= $d['mi_resena'] ? 'Editar mi reseña' : 'Añadir reseña' ?>
                                        </button>
                                    <?php else: ?>
                                        <a href="IniciarSesion.php?redirect=<?= urlencode('libro.php#dracula') ?>" class="boton-agregar-estanteria text-decoration-none d-inline-flex align-items-center justify-content-center">
                                            Añadir reseña
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <section class="sinopsis">
                                <h2>Sinopsis</h2>
                                <p>Jonathan Harker viaja a los Cárpatos para cerrar una venta inmobiliaria con el conde Drácula, sin imaginar la pesadilla que está a punto de desatar sobre Inglaterra.</p>
                                <p>La obra definitiva sobre el mito del vampiro contada a través de diarios, cartas y telegramas que mantienen la tensión hasta el final.</p>
                            </section>
                        </div>
                        <aside class="barra-lateral-detalle">
                            <h2>Detalles del libro</h2>
                            <dl>
                                <dt>Publicación</dt><dd>1897</dd>
                                <dt>Origen</dt><dd>Irlanda</dd>
                                <dt>Idioma original</dt><dd>Inglés</dd>
                                <dt>Género</dt><dd>Terror gótico</dd>
                            </dl>
                            <div class="seccion-valoracion">
                                <h2>Valoraciones</h2>
                                <div class="valoracion-media">
                                    <strong><?= $d['media'] !== null ? number_format($d['media'], 1, ',', '.') : '—' ?></strong>
                                    <span><?= renderizarEstrellasVisuales($d['media']) ?></span>
                                </div>
                                <p class="aviso-valoraciones"><?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <section class="resenas">
                                <div class="encabezado-resenas">
                                    <h2>Reseñas de la comunidad</h2>
                                    <a href="catalogo.php">Volver</a>
                                </div>
                                <?php if (empty($d['resenas'])): ?>
                                    <p class="text-muted small">Sé el primero en compartir tu opinión sobre este libro.</p>
                                <?php else: ?>
                                    <?php foreach ($d['resenas'] as $r): ?>
                                        <article>
                                            <div class="imagen-perfil" aria-hidden="true"></div>
                                            <div>
                                                <strong><?= $r['usuario_nombre'] ?></strong>
                                                <div><span><?= renderizarEstrellasVisuales((float)$r['valoracion']) ?></span></div>
                                                <p><?= $r['texto_formateado'] ?></p>
                                                <?php if ($usuarioId && $r['usuario_id'] === $usuarioId): ?>
                                                    <form method="POST" action="libro.php#dracula" onsubmit="return confirm('¿Seguro que deseas eliminar tu reseña?');" class="mt-1">
                                                        <input type="hidden" name="accion" value="eliminar_resena">
                                                        <input type="hidden" name="resena_id" value="<?= $r['id'] ?>">
                                                        <input type="hidden" name="slug" value="dracula">
                                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0 border-0" style="font-size: 11px;">Eliminar mi reseña</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </article>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </section>
                        </aside>
                    </div>

                    <!-- Modal Drácula -->
                    <?php if ($usuarioId): ?>
                    <div class="modal fade" id="modalResena-dracula" tabindex="-1" aria-labelledby="labelModal-dracula" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="labelModal-dracula"><?= $d['mi_resena'] ? 'Editar mi reseña' : 'Añadir reseña' ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <form method="POST" action="libro.php#dracula">
                                    <input type="hidden" name="accion" value="guardar_resena">
                                    <input type="hidden" name="libro_id" value="<?= $d['id'] ?>">
                                    <input type="hidden" name="slug" value="dracula">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="val-dracula" class="form-label font-monospace">Puntuación (de 1 a 5 estrellas):</label>
                                            <select name="valoracion" id="val-dracula" class="form-select" required>
                                                <?php $miVal = $d['mi_resena']['valoracion'] ?? 5; ?>
                                                <option value="5" <?= $miVal == 5 ? 'selected' : '' ?>>★★★★★ (5 estrellas - Obra maestra)</option>
                                                <option value="4" <?= $miVal == 4 ? 'selected' : '' ?>>★★★★☆ (4 estrellas - Muy bueno)</option>
                                                <option value="3" <?= $miVal == 3 ? 'selected' : '' ?>>★★★☆☆ (3 estrellas - Bueno)</option>
                                                <option value="2" <?= $miVal == 2 ? 'selected' : '' ?>>★★☆☆☆ (2 estrellas - Regular)</option>
                                                <option value="1" <?= $miVal == 1 ? 'selected' : '' ?>>★☆☆☆☆ (1 estrella - No me gustó)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="txt-dracula" class="form-label">Tu opinión sobre el libro:</label>
                                            <textarea name="texto" id="txt-dracula" class="form-control" rows="4" placeholder="Escribe al menos 5 caracteres sobre lo que te ha parecido..." minlength="5" required><?= htmlspecialchars($d['mi_resena']['texto'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning"><?= $d['mi_resena'] ? 'Actualizar reseña' : 'Publicar reseña' ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </article>

                <!-- ================= FICHA 3: ORGULLO Y PREJUICIO ================= -->
                <?php $d = $datosLibros['orgullo-y-prejuicio']; ?>
                <article class="ficha-libro" id="orgullo-y-prejuicio">
                    <div class="cuerpo-detalle">
                        <div class="contenido-principal-detalle">
                            <nav class="migas-de-pan" aria-label="Ruta de navegación">
                                <a href="catalogo.php">Catálogo</a><span></span>
                                <a href="catalogo.php#libro-romance">Romance clásico</a><span></span>
                                <b>Orgullo y prejuicio</b>
                            </nav>
                            <div class="resumen-libro">
                                <div class="portada-detalle"><img src="assets/img/orgullo-y-prejuicio.webp" alt="Portada de Orgullo y prejuicio, de Jane Austen"></div>
                                <div class="informacion-resumen">
                                    <p class="etiqueta-genero">Romance clásico</p>
                                    <h1>Orgullo y prejuicio</h1>
                                    <small>Jane Austen</small>
                                    <div class="estrellas-detalle" aria-label="Valoración: <?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?>">
                                        <b><?= renderizarEstrellasVisuales($d['media']) ?></b>
                                    </div>
                                    <p class="dato-valoraciones"><?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?></p>
                                    
                                    <button class="boton-agregar-lista" type="button">Añadir a mi lista</button>
                                    
                                    <?php if ($usuarioId): ?>
                                        <button class="boton-agregar-estanteria" type="button" data-bs-toggle="modal" data-bs-target="#modalResena-orgullo-y-prejuicio">
                                            <?= $d['mi_resena'] ? 'Editar mi reseña' : 'Añadir reseña' ?>
                                        </button>
                                    <?php else: ?>
                                        <a href="IniciarSesion.php?redirect=<?= urlencode('libro.php#orgullo-y-prejuicio') ?>" class="boton-agregar-estanteria text-decoration-none d-inline-flex align-items-center justify-content-center">
                                            Añadir reseña
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <section class="sinopsis">
                                <h2>Sinopsis</h2>
                                <p>Elizabeth Bennet y el señor Darcy deben aprender a mirar más allá de sus primeras impresiones en una sociedad donde las apariencias lo son todo.</p>
                                <p>Una comedia romántica brillante e irónica que sigue viva más de dos siglos después de su publicación.</p>
                            </section>
                        </div>
                        <aside class="barra-lateral-detalle">
                            <h2>Detalles del libro</h2>
                            <dl>
                                <dt>Publicación</dt><dd>1813</dd>
                                <dt>Origen</dt><dd>Reino Unido</dd>
                                <dt>Idioma original</dt><dd>Inglés</dd>
                                <dt>Género</dt><dd>Romance clásico</dd>
                            </dl>
                            <div class="seccion-valoracion">
                                <h2>Valoraciones</h2>
                                <div class="valoracion-media">
                                    <strong><?= $d['media'] !== null ? number_format($d['media'], 1, ',', '.') : '—' ?></strong>
                                    <span><?= renderizarEstrellasVisuales($d['media']) ?></span>
                                </div>
                                <p class="aviso-valoraciones"><?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <section class="resenas">
                                <div class="encabezado-resenas">
                                    <h2>Reseñas de la comunidad</h2>
                                    <a href="catalogo.php">Volver</a>
                                </div>
                                <?php if (empty($d['resenas'])): ?>
                                    <p class="text-muted small">Sé el primero en compartir tu opinión sobre este libro.</p>
                                <?php else: ?>
                                    <?php foreach ($d['resenas'] as $r): ?>
                                        <article>
                                            <div class="imagen-perfil" aria-hidden="true"></div>
                                            <div>
                                                <strong><?= $r['usuario_nombre'] ?></strong>
                                                <div><span><?= renderizarEstrellasVisuales((float)$r['valoracion']) ?></span></div>
                                                <p><?= $r['texto_formateado'] ?></p>
                                                <?php if ($usuarioId && $r['usuario_id'] === $usuarioId): ?>
                                                    <form method="POST" action="libro.php#orgullo-y-prejuicio" onsubmit="return confirm('¿Seguro que deseas eliminar tu reseña?');" class="mt-1">
                                                        <input type="hidden" name="accion" value="eliminar_resena">
                                                        <input type="hidden" name="resena_id" value="<?= $r['id'] ?>">
                                                        <input type="hidden" name="slug" value="orgullo-y-prejuicio">
                                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0 border-0" style="font-size: 11px;">Eliminar mi reseña</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </article>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </section>
                        </aside>
                    </div>

                    <!-- Modal Orgullo y Prejuicio -->
                    <?php if ($usuarioId): ?>
                    <div class="modal fade" id="modalResena-orgullo-y-prejuicio" tabindex="-1" aria-labelledby="labelModal-orgullo-y-prejuicio" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="labelModal-orgullo-y-prejuicio"><?= $d['mi_resena'] ? 'Editar mi reseña' : 'Añadir reseña' ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <form method="POST" action="libro.php#orgullo-y-prejuicio">
                                    <input type="hidden" name="accion" value="guardar_resena">
                                    <input type="hidden" name="libro_id" value="<?= $d['id'] ?>">
                                    <input type="hidden" name="slug" value="orgullo-y-prejuicio">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="val-orgullo" class="form-label font-monospace">Puntuación (de 1 a 5 estrellas):</label>
                                            <select name="valoracion" id="val-orgullo" class="form-select" required>
                                                <?php $miVal = $d['mi_resena']['valoracion'] ?? 5; ?>
                                                <option value="5" <?= $miVal == 5 ? 'selected' : '' ?>>★★★★★ (5 estrellas - Obra maestra)</option>
                                                <option value="4" <?= $miVal == 4 ? 'selected' : '' ?>>★★★★☆ (4 estrellas - Muy bueno)</option>
                                                <option value="3" <?= $miVal == 3 ? 'selected' : '' ?>>★★★☆☆ (3 estrellas - Bueno)</option>
                                                <option value="2" <?= $miVal == 2 ? 'selected' : '' ?>>★★☆☆☆ (2 estrellas - Regular)</option>
                                                <option value="1" <?= $miVal == 1 ? 'selected' : '' ?>>★☆☆☆☆ (1 estrella - No me gustó)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="txt-orgullo" class="form-label">Tu opinión sobre el libro:</label>
                                            <textarea name="texto" id="txt-orgullo" class="form-control" rows="4" placeholder="Escribe al menos 5 caracteres sobre lo que te ha parecido..." minlength="5" required><?= htmlspecialchars($d['mi_resena']['texto'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning"><?= $d['mi_resena'] ? 'Actualizar reseña' : 'Publicar reseña' ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </article>

                <!-- ================= FICHA 4: EL SABUESO DE LOS BASKERVILLE ================= -->
                <?php $d = $datosLibros['el-sabueso-de-los-baskerville']; ?>
                <article class="ficha-libro" id="el-sabueso-de-los-baskerville">
                    <div class="cuerpo-detalle">
                        <div class="contenido-principal-detalle">
                            <nav class="migas-de-pan" aria-label="Ruta de navegación">
                                <a href="catalogo.php">Catálogo</a><span></span>
                                <a href="catalogo.php#libro-misterio">Misterio victoriano</a><span></span>
                                <b>El sabueso de los Baskerville</b>
                            </nav>
                            <div class="resumen-libro">
                                <div class="portada-detalle"><img src="assets/img/el-sabueso-de-los-baskerville.webp" alt="Ilustración para El sabueso de los Baskerville, de Arthur Conan Doyle"></div>
                                <div class="informacion-resumen">
                                    <p class="etiqueta-genero">Misterio victoriano</p>
                                    <h1>El sabueso de los Baskerville</h1>
                                    <small>Arthur Conan Doyle</small>
                                    <div class="estrellas-detalle" aria-label="Valoración: <?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?>">
                                        <b><?= renderizarEstrellasVisuales($d['media']) ?></b>
                                    </div>
                                    <p class="dato-valoraciones"><?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?></p>
                                    
                                    <button class="boton-agregar-lista" type="button">Añadir a mi lista</button>
                                    
                                    <?php if ($usuarioId): ?>
                                        <button class="boton-agregar-estanteria" type="button" data-bs-toggle="modal" data-bs-target="#modalResena-el-sabueso-de-los-baskerville">
                                            <?= $d['mi_resena'] ? 'Editar mi reseña' : 'Añadir reseña' ?>
                                        </button>
                                    <?php else: ?>
                                        <a href="IniciarSesion.php?redirect=<?= urlencode('libro.php#el-sabueso-de-los-baskerville') ?>" class="boton-agregar-estanteria text-decoration-none d-inline-flex align-items-center justify-content-center">
                                            Añadir reseña
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <section class="sinopsis">
                                <h2>Sinopsis</h2>
                                <p>Una antigua maldición parece perseguir a los herederos de la familia Baskerville en los brumosos páramos de Dartmoor.</p>
                                <p>Sherlock Holmes y el doctor Watson deberán desentrañar si se trata de un monstruo legendario o de un crimen meticulosamente planeado.</p>
                            </section>
                        </div>
                        <aside class="barra-lateral-detalle">
                            <h2>Detalles del libro</h2>
                            <dl>
                                <dt>Publicación</dt><dd>1902</dd>
                                <dt>Origen</dt><dd>Reino Unido</dd>
                                <dt>Idioma original</dt><dd>Inglés</dd>
                                <dt>Género</dt><dd>Misterio</dd>
                            </dl>
                            <div class="seccion-valoracion">
                                <h2>Valoraciones</h2>
                                <div class="valoracion-media">
                                    <strong><?= $d['media'] !== null ? number_format($d['media'], 1, ',', '.') : '—' ?></strong>
                                    <span><?= renderizarEstrellasVisuales($d['media']) ?></span>
                                </div>
                                <p class="aviso-valoraciones"><?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <section class="resenas">
                                <div class="encabezado-resenas">
                                    <h2>Reseñas de la comunidad</h2>
                                    <a href="catalogo.php">Volver</a>
                                </div>
                                <?php if (empty($d['resenas'])): ?>
                                    <p class="text-muted small">Sé el primero en compartir tu opinión sobre este libro.</p>
                                <?php else: ?>
                                    <?php foreach ($d['resenas'] as $r): ?>
                                        <article>
                                            <div class="imagen-perfil" aria-hidden="true"></div>
                                            <div>
                                                <strong><?= $r['usuario_nombre'] ?></strong>
                                                <div><span><?= renderizarEstrellasVisuales((float)$r['valoracion']) ?></span></div>
                                                <p><?= $r['texto_formateado'] ?></p>
                                                <?php if ($usuarioId && $r['usuario_id'] === $usuarioId): ?>
                                                    <form method="POST" action="libro.php#el-sabueso-de-los-baskerville" onsubmit="return confirm('¿Seguro que deseas eliminar tu reseña?');" class="mt-1">
                                                        <input type="hidden" name="accion" value="eliminar_resena">
                                                        <input type="hidden" name="resena_id" value="<?= $r['id'] ?>">
                                                        <input type="hidden" name="slug" value="el-sabueso-de-los-baskerville">
                                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0 border-0" style="font-size: 11px;">Eliminar mi reseña</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </article>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </section>
                        </aside>
                    </div>

                    <!-- Modal El Sabueso de los Baskerville -->
                    <?php if ($usuarioId): ?>
                    <div class="modal fade" id="modalResena-el-sabueso-de-los-baskerville" tabindex="-1" aria-labelledby="labelModal-el-sabueso-de-los-baskerville" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="labelModal-el-sabueso-de-los-baskerville"><?= $d['mi_resena'] ? 'Editar mi reseña' : 'Añadir reseña' ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <form method="POST" action="libro.php#el-sabueso-de-los-baskerville">
                                    <input type="hidden" name="accion" value="guardar_resena">
                                    <input type="hidden" name="libro_id" value="<?= $d['id'] ?>">
                                    <input type="hidden" name="slug" value="el-sabueso-de-los-baskerville">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="val-baskerville" class="form-label font-monospace">Puntuación (de 1 a 5 estrellas):</label>
                                            <select name="valoracion" id="val-baskerville" class="form-select" required>
                                                <?php $miVal = $d['mi_resena']['valoracion'] ?? 5; ?>
                                                <option value="5" <?= $miVal == 5 ? 'selected' : '' ?>>★★★★★ (5 estrellas - Obra maestra)</option>
                                                <option value="4" <?= $miVal == 4 ? 'selected' : '' ?>>★★★★☆ (4 estrellas - Muy bueno)</option>
                                                <option value="3" <?= $miVal == 3 ? 'selected' : '' ?>>★★★☆☆ (3 estrellas - Bueno)</option>
                                                <option value="2" <?= $miVal == 2 ? 'selected' : '' ?>>★★☆☆☆ (2 estrellas - Regular)</option>
                                                <option value="1" <?= $miVal == 1 ? 'selected' : '' ?>>★☆☆☆☆ (1 estrella - No me gustó)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="txt-baskerville" class="form-label">Tu opinión sobre el libro:</label>
                                            <textarea name="texto" id="txt-baskerville" class="form-control" rows="4" placeholder="Escribe al menos 5 caracteres sobre lo que te ha parecido..." minlength="5" required><?= htmlspecialchars($d['mi_resena']['texto'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning"><?= $d['mi_resena'] ? 'Actualizar reseña' : 'Publicar reseña' ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </article>

                <!-- ================= FICHA 5: CIEN AÑOS DE SOLEDAD ================= -->
                <?php $d = $datosLibros['cien-anos-de-soledad']; ?>
                <article class="ficha-libro" id="cien-anos-de-soledad">
                    <div class="cuerpo-detalle">
                        <div class="contenido-principal-detalle">
                            <nav class="migas-de-pan" aria-label="Ruta de navegación">
                                <a href="catalogo.php">Catálogo</a><span></span>
                                <a href="catalogo.php#libro-realismo">Realismo mágico</a><span></span>
                                <b>Cien años de soledad</b>
                            </nav>
                            <div class="resumen-libro">
                                <div class="portada-detalle"><img src="assets/img/cien-anos-de-soledad.webp" alt="Portada de Cien años de soledad, de Gabriel García Márquez"></div>
                                <div class="informacion-resumen">
                                    <p class="etiqueta-genero">Realismo mágico</p>
                                    <h1>Cien años de soledad</h1>
                                    <small>Gabriel García Márquez</small>
                                    <div class="estrellas-detalle" aria-label="Valoración: <?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?>">
                                        <b><?= renderizarEstrellasVisuales($d['media']) ?></b>
                                    </div>
                                    <p class="dato-valoraciones"><?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?></p>
                                    
                                    <button class="boton-agregar-lista" type="button">Añadir a mi lista</button>
                                    
                                    <?php if ($usuarioId): ?>
                                        <button class="boton-agregar-estanteria" type="button" data-bs-toggle="modal" data-bs-target="#modalResena-cien-anos-de-soledad">
                                            <?= $d['mi_resena'] ? 'Editar mi reseña' : 'Añadir reseña' ?>
                                        </button>
                                    <?php else: ?>
                                        <a href="IniciarSesion.php?redirect=<?= urlencode('libro.php#cien-anos-de-soledad') ?>" class="boton-agregar-estanteria text-decoration-none d-inline-flex align-items-center justify-content-center">
                                            Añadir reseña
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <section class="sinopsis">
                                <h2>Sinopsis</h2>
                                <p>La familia Buendía atraviesa varias generaciones en Macondo, un pueblo donde lo extraordinario convive con la vida cotidiana.</p>
                                <p>Una obra fundamental del realismo mágico que explora la memoria, el paso del tiempo y los ciclos familiares.</p>
                            </section>
                        </div>
                        <aside class="barra-lateral-detalle">
                            <h2>Detalles del libro</h2>
                            <dl>
                                <dt>Publicación</dt><dd>1967</dd>
                                <dt>Origen</dt><dd>Colombia</dd>
                                <dt>Idioma original</dt><dd>Español</dd>
                                <dt>Género</dt><dd>Realismo mágico</dd>
                            </dl>
                            <div class="seccion-valoracion">
                                <h2>Valoraciones</h2>
                                <div class="valoracion-media">
                                    <strong><?= $d['media'] !== null ? number_format($d['media'], 1, ',', '.') : '—' ?></strong>
                                    <span><?= renderizarEstrellasVisuales($d['media']) ?></span>
                                </div>
                                <p class="aviso-valoraciones"><?= htmlspecialchars(renderizarTextoMedia($d['media'], $d['total']), ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <section class="resenas">
                                <div class="encabezado-resenas">
                                    <h2>Reseñas de la comunidad</h2>
                                    <a href="catalogo.php">Volver</a>
                                </div>
                                <?php if (empty($d['resenas'])): ?>
                                    <p class="text-muted small">Sé el primero en compartir tu opinión sobre este libro.</p>
                                <?php else: ?>
                                    <?php foreach ($d['resenas'] as $r): ?>
                                        <article>
                                            <div class="imagen-perfil" aria-hidden="true"></div>
                                            <div>
                                                <strong><?= $r['usuario_nombre'] ?></strong>
                                                <div><span><?= renderizarEstrellasVisuales((float)$r['valoracion']) ?></span></div>
                                                <p><?= $r['texto_formateado'] ?></p>
                                                <?php if ($usuarioId && $r['usuario_id'] === $usuarioId): ?>
                                                    <form method="POST" action="libro.php#cien-anos-de-soledad" onsubmit="return confirm('¿Seguro que deseas eliminar tu reseña?');" class="mt-1">
                                                        <input type="hidden" name="accion" value="eliminar_resena">
                                                        <input type="hidden" name="resena_id" value="<?= $r['id'] ?>">
                                                        <input type="hidden" name="slug" value="cien-anos-de-soledad">
                                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0 border-0" style="font-size: 11px;">Eliminar mi reseña</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </article>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </section>
                        </aside>
                    </div>

                    <!-- Modal Cien años de soledad -->
                    <?php if ($usuarioId): ?>
                    <div class="modal fade" id="modalResena-cien-anos-de-soledad" tabindex="-1" aria-labelledby="labelModal-cien-anos-de-soledad" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="labelModal-cien-anos-de-soledad"><?= $d['mi_resena'] ? 'Editar mi reseña' : 'Añadir reseña' ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <form method="POST" action="libro.php#cien-anos-de-soledad">
                                    <input type="hidden" name="accion" value="guardar_resena">
                                    <input type="hidden" name="libro_id" value="<?= $d['id'] ?>">
                                    <input type="hidden" name="slug" value="cien-anos-de-soledad">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="val-soledad" class="form-label font-monospace">Puntuación (de 1 a 5 estrellas):</label>
                                            <select name="valoracion" id="val-soledad" class="form-select" required>
                                                <?php $miVal = $d['mi_resena']['valoracion'] ?? 5; ?>
                                                <option value="5" <?= $miVal == 5 ? 'selected' : '' ?>>★★★★★ (5 estrellas - Obra maestra)</option>
                                                <option value="4" <?= $miVal == 4 ? 'selected' : '' ?>>★★★★☆ (4 estrellas - Muy bueno)</option>
                                                <option value="3" <?= $miVal == 3 ? 'selected' : '' ?>>★★★☆☆ (3 estrellas - Bueno)</option>
                                                <option value="2" <?= $miVal == 2 ? 'selected' : '' ?>>★★☆☆☆ (2 estrellas - Regular)</option>
                                                <option value="1" <?= $miVal == 1 ? 'selected' : '' ?>>★☆☆☆☆ (1 estrella - No me gustó)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="txt-soledad" class="form-label">Tu opinión sobre el libro:</label>
                                            <textarea name="texto" id="txt-soledad" class="form-control" rows="4" placeholder="Escribe al menos 5 caracteres sobre lo que te ha parecido..." minlength="5" required><?= htmlspecialchars($d['mi_resena']['texto'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning"><?= $d['mi_resena'] ? 'Actualizar reseña' : 'Publicar reseña' ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </article>
            </div>
        </main>
    </section>
</body>
</html>
