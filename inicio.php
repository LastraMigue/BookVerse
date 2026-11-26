<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Descubre tu próxima lectura en BookVerse: explora libros, guarda tus favoritos y comparte tu pasión por las historias.">
    <title>BookVerse | Un universo de historias</title>
    <!-- Bootstrap -->
</head>
<body class="pagina-inicio">
    <?php include 'navbar.php'; ?>

    <main>
        <section class="hero-inicio" aria-labelledby="titulo-inicio">
            <img class="imagen-fondo-hero" src="assets/img/madera-hero.webp" alt="" aria-hidden="true">
            <div class="contenido-hero">
                <p class="rotulo-inicio"><span aria-hidden="true"></span> Tu próxima aventura empieza aquí</p>
                <h1 id="titulo-inicio">Hay historias que te encuentran. <em>Encuentra la tuya.</em></h1>
                <p class="descripcion-hero">Explora nuevos mundos, descubre libros que te emocionen y crea un rincón para cada lectura. Tu próxima historia favorita te está esperando.</p>
                <form class="formulario-busqueda-inicio" role="search" action="catalogo.php" method="post">
                    <label class="visually-hidden" for="busqueda-inicio">Buscar libros por título o autor</label>
                    <input class="form-control" id="busqueda-inicio" name="q" type="search" placeholder="¿Qué historia te apetece hoy?">
                    <button class="btn boton-busqueda-inicio" type="submit" aria-label="Buscar en el catálogo">⌕</button>
                </form>
                <div class="acciones-hero">
                    <a class="btn boton-hero-principal" href="catalogo.php">Explorar el catálogo <span aria-hidden="true"></span></a>
                    <a class="boton-hero-secundario" href="CrearCuenta.php">Únete a BookVerse</a>
                </div>
                <p class="sello-lectura"><span aria-hidden="true"></span> Un lugar para quienes siempre quieren leer una página más</p>
            </div>
        </section>

        <section class="seccion-descubre container" aria-labelledby="titulo-descubre">
            <div class="encabezado-seccion-inicio">
                <p class="rotulo-inicio">Tu espacio lector</p>
                <h2 id="titulo-descubre">Las buenas historias se disfrutan más <em>a tu manera.</em></h2>
                <p>BookVerse te ayuda a convertir cada lectura en parte de tu propia historia.</p>
            </div>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <article class="tarjeta-descubre h-100">
                        <span class="icono-descubre" aria-hidden="true"></span>
                        <h3>Descubre algo nuevo</h3>
                        <p>Déjate llevar por el catálogo y encuentra libros para cada estado de ánimo.</p>
                        <a href="catalogo.php">Explorar libros <span aria-hidden="true"></span></a>
                    </article>
                </div>
                <div class="col-12 col-md-4">
                    <article class="tarjeta-descubre h-100">
                        <span class="icono-descubre" aria-hidden="true"></span>
                        <h3>Haz sitio a tus lecturas</h3>
                        <p>Guarda los títulos que te interesan y organiza lo que quieres leer.</p>
                        <a href="MisListas.php">Ver mis listas <span aria-hidden="true"></span></a>
                    </article>
                </div>
                <div class="col-12 col-md-4">
                    <article class="tarjeta-descubre h-100">
                        <span class="icono-descubre" aria-hidden="true"></span>
                        <h3>Comparte tu pasión</h3>
                        <p>Participa en una comunidad donde cada libro abre una conversación.</p>
                        <a href="CrearCuenta.php">Forma parte <span aria-hidden="true"></span></a>
                    </article>
                </div>
            </div>
        </section>

        <section class="destacado-del-mes container" aria-labelledby="titulo-destacado-inicio">
            <div class="portada-destacada-inicio" aria-hidden="true">
                <span>BOOKVERSE<br>LECTURA DESTACADA</span>
                <strong>El nombre<br>del viento</strong>
                <small>Patrick Rothfuss</small>
                <i></i>
            </div>
            <div class="detalles-destacado">
                <p class="rotulo-inicio">Una historia para perderse</p>
                <h2 id="titulo-destacado-inicio">El nombre del viento</h2>
                <p class="autor-destacado">Patrick Rothfuss <span>·</span> Fantasía épica</p>
                <p class="descripcion-destacado">Magia, música y leyendas se entrelazan en una aventura inolvidable. Una lectura ideal para quienes disfrutan de los mundos llenos de misterio.</p>
                <a class="enlace-destacado-inicio" href="libro.php">Descubrir el libro <span aria-hidden="true"></span></a>
            </div>
        </section>
    </main>

    <footer class="pie-inicio">
        <a class="marca-inicio" href="inicio.php"><span class="icono-marca" aria-hidden="true">B</span><span>Book<span class="marca-resaltada">Verse</span></span></a>
        <p>Un universo de historias, a un libro de distancia.</p>
        <a href="catalogo.php">Encuentra tu próxima lectura <span aria-hidden="true">→</span></a>
    </footer>
</body>
</html>
