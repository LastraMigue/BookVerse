<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explora una selección de libros de BookVerse y encuentra tu próxima lectura favorita.">
    <title>Catálogo de libros | BookVerse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/base.css">
</head>
<body class="pagina-catalogo pagina-catalogo-principal">
    <?php include 'navbar.php'; ?>

    <section class="marco-catalogo">
        <main class="panel-catalogo">
            <header class="navegacion-catalogo">
                <form class="busqueda-catalogo" role="search" action="catalogo.php" method="get">
                    
                    <input type="search" name="q" aria-label="Buscar libros por título o autor" placeholder="Buscar por título o autor">
                </form>
            </header>

            <section class="presentacion-catalogo" aria-labelledby="titulo-catalogo">
                <p class="rotulo-catalogo">La biblioteca de BookVerse</p>
                <h1 id="titulo-catalogo">Una buena historia siempre te encuentra.</h1>
                <p class="descripcion-presentacion-catalogo">Explora esta selección de clásicos y aventuras inolvidables. Elige un género, déjate llevar y descubre qué leer después.</p>
                <div class="resumen-catalogo">
                    <span><strong>5</strong> libros de ejemplo</span>
                    <span><strong>5</strong> mundos por descubrir</span>
                </div>
            </section>

            <div class="cuerpo-catalogo">
                <aside class="barra-lateral-catalogo">
                    <div class="encabezado-filtros">
                        <h2>Explora por género</h2>
                        <span>Selección</span>
                    </div>
                    <nav class="enlaces-generos" aria-label="Géneros de la selección">
                        <a href="#libro-fantasia"> Fantasía <b>1</b></a>
                        <a href="#libro-terror"> Terror <b>1</b></a>
                        <a href="#libro-romance"> Romance <b>1</b></a>
                        <a href="#libro-misterio"> Misterio <b>1</b></a>
                        <a href="#libro-realismo"> Realismo mágico <b>1</b></a>
                    </nav>
                    <div class="nota-catalogo">
                        <p>¿Tienes otro libro en mente?</p>
                        <a href="FormularioLibro.php">Propón un título <span aria-hidden="true">→</span></a>
                    </div>
                </aside>

                <section class="resultados-catalogo" aria-labelledby="titulo-seleccion">
                    <div class="encabezado-resultados">
                        <div>
                            <p>Empieza a explorar</p>
                            <h2 id="titulo-seleccion">Historias para descubrir</h2>
                            <small>Una pequeña selección para inspirar tu próxima lectura.</small>
                        </div>
                        <span class="etiqueta-seleccion">Selección de ejemplo</span>
                    </div>

                    <div class="cuadricula-libros">
                        <a class="enlace-tarjeta-libro" href="libro.php#el-nombre-del-viento">
                            <article class="tarjeta-libro" id="libro-fantasia">
                                <div class="portada-libro portada-viento" role="img" aria-label="Portada ilustrada de El nombre del viento">
                                    <span>BOOKVERSE</span>
                                    <strong>El nombre<br>del viento</strong>
                                    <small>Patrick Rothfuss</small>
                                </div>
                                <div class="datos-libro">
                                    <span class="genero-libro">Fantasía épica</span>
                                    <h3>El nombre del viento</h3>
                                    <p class="autor-libro">Patrick Rothfuss</p>
                                    <p class="descripcion-libro">Magia, música y leyendas en una aventura de formación y misterio.</p>
                                    <div class="valoracion-libro" aria-label="Valoración de ejemplo: 4,8 de 5"><span aria-hidden="true">★★★★★</span><b>4,8</b></div>
                                </div>
                            </article>
                        </a>

                        <a class="enlace-tarjeta-libro" href="libro.php#dracula">
                            <article class="tarjeta-libro" id="libro-terror">
                                <img class="portada-libro" src="assets/img/dracula.webp" alt="Portada clásica de Drácula">
                                <div class="datos-libro">
                                    <span class="genero-libro">Terror gótico</span>
                                    <h3>Drácula</h3>
                                    <p class="autor-libro">Bram Stoker</p>
                                    <p class="descripcion-libro">Un clásico inquietante sobre un misterioso conde y su leyenda.</p>
                                    <div class="valoracion-libro" aria-label="Valoración de ejemplo: 4,7 de 5"><span aria-hidden="true">★★★★★</span><b>4,7</b></div>
                                </div>
                            </article>
                        </a>

                        <a class="enlace-tarjeta-libro" href="libro.php#orgullo-y-prejuicio">
                            <article class="tarjeta-libro" id="libro-romance">
                                <img class="portada-libro" src="assets/img/orgullo-y-prejuicio.webp" alt="Portada de Orgullo y prejuicio">
                                <div class="datos-libro">
                                    <span class="genero-libro">Romance · Clásico</span>
                                    <h3>Orgullo y prejuicio</h3>
                                    <p class="autor-libro">Jane Austen</p>
                                    <p class="descripcion-libro">Ingenio, primeras impresiones y una historia que sigue conquistando lectores.</p>
                                    <div class="valoracion-libro" aria-label="Valoración de ejemplo: 4,9 de 5"><span aria-hidden="true">★★★★★</span><b>4,9</b></div>
                                </div>
                            </article>
                        </a>

                        <a class="enlace-tarjeta-libro" href="libro.php#el-sabueso-de-los-baskerville">
                            <article class="tarjeta-libro" id="libro-misterio">
                                <img class="portada-libro" src="assets/img/el-sabueso-de-los-baskerville.webp" alt="Ilustración de El sabueso de los Baskerville">
                                <div class="datos-libro">
                                    <span class="genero-libro">Misterio</span>
                                    <h3>El sabueso de los Baskerville</h3>
                                    <p class="autor-libro">Arthur Conan Doyle</p>
                                    <p class="descripcion-libro">Sherlock Holmes investiga una leyenda oscura en los páramos ingleses.</p>
                                    <div class="valoracion-libro" aria-label="Valoración de ejemplo: 4,6 de 5"><span aria-hidden="true">★★★★★</span><b>4,6</b></div>
                                </div>
                            </article>
                        </a>

                        <a class="enlace-tarjeta-libro" href="libro.php#cien-anos-de-soledad">
                            <article class="tarjeta-libro" id="libro-realismo">
                                <img class="portada-libro" src="assets/img/cien-anos-de-soledad.webp" alt="Portada de Cien años de soledad">
                                <div class="datos-libro">
                                    <span class="genero-libro">Realismo mágico</span>
                                    <h3>Cien años de soledad</h3>
                                    <p class="autor-libro">Gabriel García Márquez</p>
                                    <p class="descripcion-libro">La saga de una familia y un pueblo donde lo extraordinario forma parte de lo cotidiano.</p>
                                    <div class="valoracion-libro" aria-label="Valoración de ejemplo: 4,9 de 5"><span aria-hidden="true">★★★★★</span><b>4,9</b></div>
                                </div>
                            </article>
                        </a>
                    </div>
                </section>
            </div>
        </main>
    </section>
</body>
</html>
