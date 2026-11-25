<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pagina-catalogo pagina-formulario">
    <header class="barra-superior barra-inicio">
        <nav class="enlaces-superiores" aria-label="Enlaces a las páginas">
            <a href="inicio.php">Inicio</a>
            <a href="catalogo.php">Catálogo</a>
            <a href="FormularioLibro.php" aria-current="page">Formulario</a>
            <details class="ajustes-navbar">
                <summary>Ajustes</summary>
                <div>
                    <a href="MisListas.php">Listas</a>
                    <a href="IniciarSesion.php">Login</a>
                </div>
            </details>
        </nav>
    </header>
    <section class="marco-catalogo marco-formulario">
        <main class="panel-catalogo panel-formulario">
            <header class="navegacion-catalogo">
                <a class="titulo-catalogo" href="inicio.php" aria-label=""><span></span><strong></strong></a>
                <form class="busqueda-catalogo" role="search">
                
                    <input type="search" aria-label="">
                </form>
            </header>
            <div class="contenido-formulario">
                <div class="encabezado-formulario">
                    <div><p></p><h2></h2><small></small></div>
                    <button class="boton-cerrar-formulario" type="button" aria-label=""></button>
                </div>
                <div class="separador-formulario"></div>
                <form class="formulario-libro" id="book-form" action="#" method="post">
                    <div class="campos-formulario">
                        <h3></h3>
                        <label class="etiqueta-formulario" for="titulo"></label>
                        <input class="campo-texto" id="titulo" name="titulo" type="text">
                        <label class="etiqueta-formulario" for="autor"></label>
                        <input class="campo-texto" id="autor" name="autor" type="text">
                        <div class="fila-formulario">
                            <div>
                                <label class="etiqueta-formulario" for="genero"></label>
                                <select class="campo-texto" id="genero" name="genero"><option value=""></option></select>
                            </div>
                            <div>
                                <label class="etiqueta-formulario" for="publicacion"></label>
                                <input class="campo-texto" id="publicacion" name="publicacion" type="number">
                            </div>
                        </div>
                        <label class="etiqueta-formulario" for="sinopsis"></label>
                        <textarea id="sinopsis" name="sinopsis"></textarea>
                        <small class="contador-caracteres"></small>
                    </div>
                    <div class="columna-portada">
                        <h3></h3>
                        <div class="carga-portada">
                            <span class="simbolo-carga"></span>
                            <strong></strong>
                            <small></small>
                            <button type="button"></button>
                            <em></em>
                        </div>
                        <div class="recomendacion"><strong></strong><p></p></div>
                    </div>
                </form>
                <div class="acciones-formulario">
                    <button type="button"></button>
                    <button type="submit" form="book-form"></button>
                </div>
            </div>
        </main>
    </section>
</body>
</html>
