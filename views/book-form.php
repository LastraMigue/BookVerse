<?php
// BookVerse — Formulario para proponer un nuevo libro

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/libros.php';

// Exigir sesión activa (si no hay sesión redirige a login; si es admin redirige a moderación)
exigirSesion('/BookVerse/views/book-form.php');

$generos = obtenerGeneros($pdo);
$errores = [];
$titulo = '';
$autor = '';
$isbn = '';
$generoId = '';
$descripcion = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $generoId = $_POST['genero_id'] ?? '';
    $descripcion = trim($_POST['descripcion'] ?? '');

    $usuario = obtenerUsuarioAutenticado();
    $resultado = proponerLibro($pdo, [
        'titulo'      => $titulo,
        'autor'       => $autor,
        'isbn'        => $isbn,
        'genero_id'   => $generoId,
        'descripcion' => $descripcion
    ], (int)$usuario['id']);

    if ($resultado['exito']) {
        header('Location: catalog.php?mensaje=libro_pendiente');
        exit;
    }

    $errores = $resultado['errores'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Propón un libro para que más lectores puedan descubrirlo en BookVerse.">
    <title>Proponer un libro | BookVerse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/base.css">
</head>
<body class="pagina-catalogo pagina-formulario">
    <?php include '../includes/navbar.php'; ?>

    <section class="marco-catalogo marco-formulario">
        <main class="panel-catalogo panel-formulario">
            <div class="contenido-formulario">
                <div class="encabezado-formulario">
                    <div>
                        <p>Entre lectores hacemos crecer la biblioteca</p>
                        <h1>Propón un libro</h1>
                        <small>Comparte los datos del libro que te gustaría ver en BookVerse.</small>
                    </div>
                    <a class="boton-cerrar-formulario" href="catalog.php" aria-label="Volver al catálogo">×</a>
                </div>
                <div class="separador-formulario"></div>

                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            <?php foreach ($errores as $error): ?>
                                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form class="formulario-libro row g-4" id="formulario-libro" action="book-form.php" method="post" enctype="multipart/form-data">
                    <div class="campos-formulario col-12 col-lg-7">
                        <h2>Datos del libro</h2>
                        <label class="etiqueta-formulario" for="titulo">Título</label>
                        <input class="campo-texto form-control" id="titulo" name="titulo" type="text" autocomplete="off" placeholder="Ej.: El nombre del viento" value="<?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>" required>

                        <label class="etiqueta-formulario" for="autor">Autor o autora</label>
                        <input class="campo-texto form-control" id="autor" name="autor" type="text" autocomplete="off" placeholder="Ej.: Patrick Rothfuss" value="<?= htmlspecialchars($autor, ENT_QUOTES, 'UTF-8') ?>" required>

                        <div class="fila-formulario row g-3">
                            <div class="col-12 col-md-6">
                                <label class="etiqueta-formulario" for="genero_id">Género</label>
                                <select class="campo-texto form-select" id="genero_id" name="genero_id" required>
                                    <option value="" disabled <?= $generoId === '' ? 'selected' : '' ?>>Elige un género</option>
                                    <?php foreach ($generos as $genero): ?>
                                        <option value="<?= (int)$genero['id'] ?>" <?= (string)$genero['id'] === (string)$generoId ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($genero['nombre'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="etiqueta-formulario" for="isbn">ISBN</label>
                                <input class="campo-texto form-control" id="isbn" name="isbn" type="text" autocomplete="off" maxlength="20" placeholder="Ej.: 978-8401352836" value="<?= htmlspecialchars($isbn, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                        </div>

                        <label class="etiqueta-formulario" for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" maxlength="1500" placeholder="Cuéntales a otros lectores de qué trata..." required><?= htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8') ?></textarea>
                        <small class="contador-caracteres">Hasta 1.500 caracteres. Evita incluir el final de la historia.</small>
                    </div>
                    <div class="columna-portada col-12 col-lg-5">
                        <h2>Portada <span>(opcional)</span></h2>
                        <div class="carga-portada">
                            <span class="simbolo-carga" aria-hidden="true">↑</span>
                            <strong>Añade una imagen de portada</strong>
                            <small id="ayuda-portada">Formatos admitidos: JPG, PNG o WebP.</small>
                            <input class="entrada-portada visually-hidden" id="portada" name="portada" type="file" accept="image/jpeg,image/png,image/webp" aria-describedby="ayuda-portada">
                            <label class="boton-seleccionar-portada" for="portada">Seleccionar imagen</label>
                            <em>No es necesario adjuntar una portada para proponer el libro.</em>
                        </div>
                        <div class="recomendacion">
                            <strong>Un buen consejo</strong>
                            <p>Comprueba el título, el autor y el ISBN antes de enviar la propuesta. Tu libro pasará a revisión antes de publicarse.</p>
                        </div>
                    </div>
                </form>
                <div class="acciones-formulario">
                    <a class="btn boton-cancelar-formulario" href="catalog.php">Cancelar</a>
                    <button class="btn boton-enviar-formulario" type="submit" form="formulario-libro">Enviar propuesta <span aria-hidden="true">→</span></button>
                </div>
            </div>
        </main>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
