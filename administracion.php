<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pagina-catalogo pagina-administracion">
    <header class="barra-superior barra-inicio">
        <nav class="enlaces-superiores" aria-label="Enlaces a las páginas">
            <a href="inicio.php">Inicio</a>
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
    <section class="marco-catalogo marco-administracion">
        <main class="panel-catalogo panel-administracion">
            <header class="navegacion-catalogo">
                <a class="titulo-catalogo" href="inicio.php" aria-label=""><span></span><strong></strong></a>
                <form class="busqueda-catalogo" role="search">
                   
                    <input type="search" aria-label="">
                </form>
            </header>
            <div class="cuerpo-administracion">
                <aside class="barra-lateral-biblioteca barra-lateral-administracion">
                    <p></p>
                    <h2></h2>
                    <nav aria-label="">
                        <a class="seleccionado" href="administracion.php"><span class="simbolo-menu"></span></a>
                        <a href="#"><span class="simbolo-menu-cuadrado"></span></a>
                        <a href="#"><span class="simbolo-menu-cuadrado"></span></a>
                        <a href="#"><span class="simbolo-menu-cuadrado"></span></a>
                    </nav>
                </aside>
                <section class="contenido-moderacion">
                    <div class="encabezado-moderacion">
                        <div><p></p><h2></h2><small></small></div>
                        <div class="cuenta-pendientes"><strong></strong><span></span></div>
                    </div>
                    <div class="separador-moderacion"></div>
                    <div class="pestanas-reportes" aria-label="">
                        <button class="activo" type="button"><span></span></button>
                        <button type="button"></button>
                    </div>
                    <table class="tabla-reportes">
                        <thead><tr><th></th><th></th><th></th><th></th></tr></thead>
                        <tbody></tbody>
                    </table>
                    <footer class="pie-tabla">
                        <span></span>
                        <nav aria-label="">
                            <button type="button"></button>
                            <button class="activo" type="button"></button>
                            <button type="button"></button>
                            <button type="button"></button>
                            <button type="button"></button>
                        </nav>
                    </footer>
                </section>
            </div>
        </main>
    </section>
</body>
</html>
