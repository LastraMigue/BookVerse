<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pagina-acceso">
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
    <main class="tarjeta-acceso">
        <a class="enlace-volver-acceso" href="IniciarSesion.php"></a>
        <p class="subtitulo-acceso"></p>
        <h1></h1>
        <p class="introduccion-acceso"></p>
        <form class="formulario-acceso" action="CrearCuenta.php" method="post">
            <label for="nombre"></label>
            <input id="nombre" name="nombre" type="text" autocomplete="name" required>
            <label for="email"></label>
            <input id="email" name="email" type="email" autocomplete="email" required>
            <label for="password"></label>
            <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
            <label for="confirm-password"></label>
            <input id="confirm-password" name="confirm_password" type="password" autocomplete="new-password" minlength="8" required>
            <label class="opcion-recordar"><input type="checkbox" name="terms" required><span></span></label>
            <button type="submit"></button>
        </form>
        <p class="enlace-registro"><a href="IniciarSesion.php"></a></p>
    </main>
</body>
</html>
