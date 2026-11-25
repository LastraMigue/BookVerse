<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pagina-inicio">
    <header class="barra-superior barra-inicio">
        <nav class="enlaces-superiores" aria-label="Enlaces a las páginas">
            <a href="inicio.php" aria-current="page">Inicio</a>
            <a href="catalogo.php">Catálogo</a>
            <a href="FormularioLibro.php">Formulario</a>
            <details class="ajustes-navbar">
                <summary>Ajustes</summary>
                <div>
                    <a href="MisListas.php">Listas</a>
                    <a href="IniciarSesion.php">Login</a>
                </div>
            </details>
        </nav>
    </header>
    <main class="contenido-principal-inicio">
        <form class="contenedor-busqueda busqueda-inicio" role="search" action="catalogo.php" method="post">
            <input class="busqueda" name="q" type="search" aria-label="">
        </form>
        <section class="destacado-del-mes">
            <h1 id="home-title"></h1>
            <div class="distribucion-destacado">
                <div class="detalles-destacado">
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
