<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Organiza tus próximas lecturas y vuelve a los libros que más te gustan en BookVerse.">
    <title>Mis listas | BookVerse</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pagina-catalogo pagina-listas">
    <?php include 'navbar.php'; ?>

    <section class="marco-catalogo marco-listas">
        <main class="panel-catalogo panel-listas">
            <header class="navegacion-catalogo">
                <a class="titulo-catalogo" href="inicio.php" aria-label="Ir a BookVerse">
                    <span>BV</span>
                    <strong>BookVerse</strong>
                </a>
                <form class="busqueda-catalogo" role="search" action="catalogo.php" method="get">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.5" cy="10.5" r="6.5"></circle>
                        <path d="M15.5 15.5L20 20"></path>
                    </svg>
                    <input type="search" name="q" aria-label="Buscar libros" placeholder="Buscar por título o autor">
                </form>
            </header>

            <div class="cuerpo-listas">
                <aside class="barra-lateral-biblioteca">
                    <p class="rotulo-biblioteca">BookVerse</p>
                    <h2>Tu biblioteca</h2>
                    <p class="descripcion-biblioteca">Un lugar para cada historia.</p>
                    <nav aria-label="Navegar por mis listas">
                        <a class="seleccionado" href="#todas-las-listas"><span class="simbolo-menu" aria-hidden="true"></span><span>Todas mis listas</span><b>3</b></a>
                        <a href="#lista-por-leer"><span class="simbolo-menu-cuadrado" aria-hidden="true"></span><span>Por leer</span><b>3</b></a>
                        <a href="#lista-leyendo"><span class="simbolo-menu-cuadrado" aria-hidden="true"></span><span>Leyendo ahora</span><b>2</b></a>
                        <a href="#lista-favoritos"><span class="simbolo-menu-cuadrado" aria-hidden="true"></span><span>Favoritos</span><b>3</b></a>
                    </nav>
                    <div class="invitacion-listas">
                        <span aria-hidden="true"></span>
                        <p>¿Buscas una nueva lectura?</p>
                        <a href="catalogo.php">Explorar el catálogo <span aria-hidden="true"></span></a>
                    </div>
                </aside>

                <section class="contenido-listas" id="todas-las-listas" aria-labelledby="titulo-listas">
                    <div class="encabezado-listas">
                        <div>
                            <p class="rotulo-listas">Tu espacio lector</p>
                            <h1 id="titulo-listas">Mis listas</h1>
                            <small>Organiza tus lecturas y guarda los libros que quieres descubrir.</small>
                        </div>
                        <a class="boton-descubrir-listas" href="catalogo.php"><span aria-hidden="true"></span> Encontrar libros</a>
                    </div>

                    <div class="resumen-listas" aria-label="Resumen de ejemplo">
                        <div><strong>3</strong><span>listas creadas</span></div>
                        <div><strong>8</strong><span>libros guardados</span></div>
                        <div><strong>2</strong><span>lecturas en curso</span></div>
                        <p>Contenido de ejemplo</p>
                    </div>

                    <div class="cuadricula-listas">
                        <article class="tarjeta-lista" id="lista-por-leer">
                            <header class="encabezado-tarjeta-lista">
                                <div>
                                    <span class="etiqueta-lista">Tu próxima aventura</span>
                                    <h2>Por leer</h2>
                                    <p>Historias esperando su turno.</p>
                                </div>
                                <span class="cantidad-lista">3 libros</span>
                            </header>
                            <div class="libros-lista">
                                <a class="libro-lista" href="libro.php#el-nombre-del-viento">
                                    <img class="portada-miniatura" src="assets/img/bv_1_libro_cerrado.svg" alt="">
                                    <span class="datos-miniatura"><strong>El nombre del viento</strong><small>Patrick Rothfuss</small></span>
                                    <span class="flecha-libro-lista" aria-hidden="true">↗</span>
                                </a>
                                <a class="libro-lista" href="libro.php#dracula">
                                    <img class="portada-miniatura" src="assets/img/dracula.webp" alt="">
                                    <span class="datos-miniatura"><strong>Drácula</strong><small>Bram Stoker</small></span>
                                    <span class="flecha-libro-lista" aria-hidden="true">↗</span>
                                </a>
                                <a class="libro-lista" href="libro.php#orgullo-y-prejuicio">
                                    <img class="portada-miniatura" src="assets/img/orgullo-y-prejuicio.webp" alt="">
                                    <span class="datos-miniatura"><strong>Orgullo y prejuicio</strong><small>Jane Austen</small></span>
                                    <span class="flecha-libro-lista" aria-hidden="true">↗</span>
                                </a>
                            </div>
                        </article>

                        <article class="tarjeta-lista" id="lista-leyendo">
                            <header class="encabezado-tarjeta-lista">
                                <div>
                                    <span class="etiqueta-lista etiqueta-leyendo">Entre páginas</span>
                                    <h2>Leyendo ahora</h2>
                                    <p>Una historia para acompañar tus días.</p>
                                </div>
                                <span class="cantidad-lista">2 libros</span>
                            </header>
                            <div class="libros-lista">
                                <a class="libro-lista" href="libro.php#cien-anos-de-soledad">
                                    <img class="portada-miniatura" src="assets/img/cien-anos-de-soledad.webp" alt="">
                                    <span class="datos-miniatura"><strong>Cien años de soledad</strong><small>Gabriel García Márquez</small></span>
                                    <span class="flecha-libro-lista" aria-hidden="true">↗</span>
                                </a>
                                <a class="libro-lista" href="libro.php#el-sabueso-de-los-baskerville">
                                    <img class="portada-miniatura" src="assets/img/el-sabueso-de-los-baskerville.webp" alt="">
                                    <span class="datos-miniatura"><strong>El sabueso de los Baskerville</strong><small>Arthur Conan Doyle</small></span>
                                    <span class="flecha-libro-lista" aria-hidden="true">↗</span>
                                </a>
                            </div>
                        </article>

                        <article class="tarjeta-lista" id="lista-favoritos">
                            <header class="encabezado-tarjeta-lista">
                                <div>
                                    <span class="etiqueta-lista etiqueta-favoritos">Para volver siempre</span>
                                    <h2>Favoritos</h2>
                                    <p>Libros que merecen un lugar especial.</p>
                                </div>
                                <span class="cantidad-lista">3 libros</span>
                            </header>
                            <div class="libros-lista">
                                <a class="libro-lista" href="libro.php#orgullo-y-prejuicio">
                                    <img class="portada-miniatura" src="assets/img/orgullo-y-prejuicio.webp" alt="">
                                    <span class="datos-miniatura"><strong>Orgullo y prejuicio</strong><small>Jane Austen</small></span>
                                    <span class="flecha-libro-lista" aria-hidden="true">↗</span>
                                </a>
                                <a class="libro-lista" href="libro.php#el-sabueso-de-los-baskerville">
                                    <img class="portada-miniatura" src="assets/img/el-sabueso-de-los-baskerville.webp" alt="">
                                    <span class="datos-miniatura"><strong>El sabueso de los Baskerville</strong><small>Arthur Conan Doyle</small></span>
                                    <span class="flecha-libro-lista" aria-hidden="true">↗</span>
                                </a>
                                <a class="libro-lista" href="libro.php#dracula">
                                    <img class="portada-miniatura" src="assets/img/dracula.webp" alt="">
                                    <span class="datos-miniatura"><strong>Drácula</strong><small>Bram Stoker</small></span>
                                    <span class="flecha-libro-lista" aria-hidden="true">↗</span>
                                </a>
                            </div>
                        </article>

                        <aside class="aviso-listas">
                            <span class="icono-aviso-listas" aria-hidden="true"></span>
                            <div>
                                <h2>Tu biblioteca puede seguir creciendo</h2>
                                <p>Explora el catálogo y encuentra nuevas historias para añadir a tus listas.</p>
                            </div>
                            <a href="catalogo.php">Ver catálogo <span aria-hidden="true"></span></a>
                        </aside>
                    </div>
                </section>
            </div>
        </main>
    </section>
</body>
</html>
