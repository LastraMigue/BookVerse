<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Descubre tu próxima lectura en BookVerse: explora libros, guarda tus favoritos y comparte tu pasión por las historias.">
    <title>BookVerse | Un universo de historias</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/base.css">
</head>
<body class="pagina-inicio">
    <?php include '../includes/navbar.php'; ?>

    <main>
        <section class="hero-inicio" aria-labelledby="titulo-inicio">
            <img class="imagen-fondo-hero" src="../assets/img/madera-hero.webp" alt="" aria-hidden="true">
            <div class="contenido-hero">
                <p class="rotulo-inicio"><span aria-hidden="true"></span> Tu próxima aventura empieza aquí</p>
                <h1 id="titulo-inicio">Hay historias que te encuentran. <em>Encuentra la tuya.</em></h1>
                <p class="descripcion-hero">Explora nuevos mundos, descubre libros que te emocionen y crea un rincón para cada lectura. Tu próxima historia favorita te está esperando.</p>
                <form class="formulario-busqueda-inicio" role="search" action="catalog.php" method="post">
                    <label class="visually-hidden" for="busqueda-inicio">Buscar libros por título o autor</label>
                    <input class="form-control" id="busqueda-inicio" name="q" type="search" placeholder="¿Qué historia te apetece hoy?">
                    <button class="btn boton-busqueda-inicio" type="submit" aria-label="Buscar en el catálogo">⌕</button>
                </form>
                <div class="acciones-hero">
                    <a class="btn boton-hero-principal" href="catalog.php">Explorar el catálogo <span aria-hidden="true"></span></a>
                    <a class="boton-hero-secundario" href="../auth/register.php">Únete a BookVerse</a>
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
                        <a href="catalog.php">Explorar libros <span aria-hidden="true"></span></a>
                    </article>
                </div>
                <div class="col-12 col-md-4">
                    <article class="tarjeta-descubre h-100">
                        <span class="icono-descubre" aria-hidden="true"></span>
                        <h3>Haz sitio a tus lecturas</h3>
                        <p>Guarda los títulos que te interesan y organiza lo que quieres leer.</p>
                        <a href="my-lists.php">Ver mis listas <span aria-hidden="true"></span></a>
                    </article>
                </div>
                <div class="col-12 col-md-4">
                    <article class="tarjeta-descubre h-100">
                        <span class="icono-descubre" aria-hidden="true"></span>
                        <h3>Comparte tu pasión</h3>
                        <p>Participa en una comunidad donde cada libro abre una conversación.</p>
                        <a href="../auth/register.php">Forma parte <span aria-hidden="true"></span></a>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <footer class="pie-inicio">
        <a class="marca-inicio" href="home.php"><span class="icono-marca" aria-hidden="true">B</span><span>Book<span class="marca-resaltada">Verse</span></span></a>
        <p>Un universo de historias, a un libro de distancia.</p>
        <a href="catalog.php">Encuentra tu próxima lectura <span aria-hidden="true">→</span></a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
