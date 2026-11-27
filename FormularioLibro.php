<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Propón un libro para que más lectores puedan descubrirlo en BookVerse.">
    <title>Proponer un libro | BookVerse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/estructura.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/FormularioLibro.css">
</head>
<body class="pagina-catalogo pagina-formulario">
    <?php include 'navbar.php'; ?>

    <section class="marco-catalogo marco-formulario">
        <main class="panel-catalogo panel-formulario">
            <div class="contenido-formulario">
                <div class="encabezado-formulario">
                    <div>
                        <p>Entre lectores hacemos crecer la biblioteca</p>
                        <h1>Propón un libro</h1>
                        <small>Comparte los datos del libro que te gustaría ver en BookVerse.</small>
                    </div>
                    <a class="boton-cerrar-formulario" href="catalogo.php" aria-label="Volver al catálogo">×</a>
                </div>
                <div class="separador-formulario"></div>
                <form class="formulario-libro row g-4" id="formulario-libro" action="#" method="post" enctype="multipart/form-data">
                    <div class="campos-formulario col-12 col-lg-7">
                        <h2>Datos del libro</h2>
                        <label class="etiqueta-formulario" for="titulo">Título</label>
                        <input class="campo-texto form-control" id="titulo" name="titulo" type="text" autocomplete="off" placeholder="Ej.: El nombre del viento" required>

                        <label class="etiqueta-formulario" for="autor">Autor o autora</label>
                        <input class="campo-texto form-control" id="autor" name="autor" type="text" autocomplete="off" placeholder="Ej.: Patrick Rothfuss" required>

                        <div class="fila-formulario row g-3">
                            <div class="col-12 col-md-6">
                                <label class="etiqueta-formulario" for="genero">Género</label>
                               <select class="campo-texto form-select" id="genero" name="genero" required>
                                    <option value="" selected disabled>Elige un género</option>
                                    <option value="fantasia">Fantasía</option>
                                    <option value="ciencia-ficcion">Ciencia ficción</option>
                                    <option value="misterio">Misterio</option>
                                    <option value="romance">Romance</option>
                                    <option value="aventura">Aventura</option>
                                    <option value="historica">Novela histórica</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="etiqueta-formulario" for="publicacion">Año de publicación</label>
                                <input class="campo-texto form-control" id="publicacion" name="publicacion" type="number" min="1" max="2026" placeholder="Ej.: 2007">
                            </div>
                        </div>

                        <label class="etiqueta-formulario" for="sinopsis">Sinopsis</label>
                        <textarea class="form-control" id="sinopsis" name="sinopsis" rows="5" maxlength="1500" placeholder="Cuéntales a otros lectores de qué trata..." required></textarea>
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
                            <p>Comprueba el título y el nombre del autor antes de enviar la propuesta. Así será más fácil para otros lectores encontrarla.</p>
                        </div>
                    </div>
                </form>
                <div class="acciones-formulario">
                    <a class="btn boton-cancelar-formulario" href="catalogo.php">Cancelar</a>
                    <button class="btn boton-enviar-formulario" type="submit" form="formulario-libro">Enviar propuesta <span aria-hidden="true">→</span></button>
                </div>
            </div>
        </main>
    </section>
</body>
</html>
