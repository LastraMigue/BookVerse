<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pagina-catalogo">
    <header class="barra-superior barra-inicio">
        <nav class="enlaces-superiores" aria-label="Enlaces a las páginas">
            <a href="inicio.php">Inicio</a>
            <a href="catalogo.php" aria-current="page">Catálogo</a>
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
    <section class="marco-catalogo">
        <main class="panel-catalogo">
            <header class="navegacion-catalogo">
                <a class="titulo-catalogo" href="inicio.php" aria-label=""><span></span><strong></strong></a>
                <form class="busqueda-catalogo" role="search">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.5" cy="10.5" r="6.5"></circle>
                        <path d="M15.5 15.5L20 20"></path>
                    </svg>
                    <input type="search" aria-label="">
                </form>
            </header>
            <div class="cuerpo-catalogo">
                <aside class="barra-lateral-catalogo">
                    <div class="encabezado-filtros"><h2></h2><button type="button"></button></div>
                    <fieldset>
                        <legend></legend>
                        <label><input type="checkbox"><span></span><b></b></label>
                        <label><input type="checkbox"><span></span><b></b></label>
                        <label><input type="checkbox"><span></span><b></b></label>
                        <label><input type="checkbox"><span></span><b></b></label>
                        <label><input type="checkbox"><span></span></label>
                        <label><input type="checkbox"><span></span><b></b></label>
                        <label><input type="checkbox"><span></span><b></b></label>
                    </fieldset>
                </aside>
                <section class="resultados-catalogo">
                    <div class="encabezado-resultados">
                        <div><p></p><h2></h2><small></small></div>
                        <label class="selector-orden">
                            <span></span>
                            <select aria-label=""><option></option></select>
                        </label>
                    </div>
                    <div class="cuadricula-libros"></div>
                </section>
            </div>
        </main>
    </section>
</body>
</html>
