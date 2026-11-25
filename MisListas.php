<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pagina-catalogo pagina-listas">
    <header class="barra-superior barra-inicio">
        <nav class="enlaces-superiores" aria-label="Enlaces a las páginas">
            <a href="inicio.php">Inicio</a>
            <a href="catalogo.php">Catálogo</a>
            <a href="FormularioLibro.php">Formulario</a>
            <details class="ajustes-navbar">
                <summary>Ajustes</summary>
                <div>
                    <a href="MisListas.php" aria-current="page">Listas</a>
                    <a href="IniciarSesion.php">Login</a>
                </div>
            </details>
        </nav>
    </header>
    <section class="marco-catalogo marco-listas">
        <main class="panel-catalogo panel-listas">
            <header class="navegacion-catalogo">
                <a class="titulo-catalogo" href="inicio.php" aria-label="">
                    <span></span>
                    <strong></strong>
                </a>
                <form class="busqueda-catalogo" role="search">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.5" cy="10.5" r="6.5"></circle>
                        <path d="M15.5 15.5L20 20"></path>
                    </svg>
                    <input type="search" aria-label="">
                </form>
            </header>
            <div class="cuerpo-listas">
                <aside class="barra-lateral-biblioteca">
                    <p></p>
                    <h2></h2>
                    <nav aria-label="">
                        <a class="seleccionado" href="MisListas.php"><span class="simbolo-menu"></span><b></b></a>
                        <a href="#"><span class="simbolo-menu-cuadrado"></span></a>
                        <a href="#"><span class="simbolo-menu-cuadrado"></span></a>
                    </nav>
                </aside>
                <section class="contenido-listas">
                    <div class="encabezado-listas">
                        <div>
                            <p></p>
                            <h2></h2>
                            <small></small>
                        </div>
                        <a href="FormularioLibro.php"></a>
                    </div>
                    <div class="cuadricula-listas"></div>
                </section>
            </div>
        </main>
    </section>
</body>
</html>
