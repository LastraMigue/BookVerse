<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pagina-inicio">
    <header class="barra-superior barra-inicio navbar navbar-dark">
        <nav class="enlaces-superiores navbar-nav ms-auto" aria-label="Enlaces a las páginas">
            <a class="nav-link" href="inicio.php" aria-current="page">Inicio</a>
            <a class="nav-link" href="catalogo.php">Catálogo</a>
            <a class="nav-link" href="FormularioLibro.php">Formulario</a>
            <details class="ajustes-navbar">
                <summary>Ajustes</summary>
                <div>
                    <a class="nav-link" href="MisListas.php">Listas</a>
                    <a class="nav-link" href="IniciarSesion.php">Login</a>
                </div>
            </details>
        </nav>
    </header>
    <main class="contenido-principal-inicio container py-4">
        <form class="contenedor-busqueda busqueda-inicio mb-4" role="search" action="catalogo.php" method="post">
            <input class="busqueda form-control" name="q" type="search" aria-label="Buscar">
        </form>
        <section class="destacado-del-mes card p-4">
            <h1 class="mb-3" id="home-title"></h1>
            <div class="distribucion-destacado row">
                <div class="detalles-destacado col-12 col-md-8">
                    <p class="rotulo-destacado"></p>
                    <h2></h2>
                    <p class="autor-destacado"></p>
                    <p class="descripcion-destacado"></p>
                    <p class="invitacion-debate"></p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
