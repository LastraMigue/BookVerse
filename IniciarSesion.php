<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inicia sesión en BookVerse y continúa organizando tus próximas lecturas.">
    <title>Iniciar sesión | BookVerse</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pagina-acceso">
    <?php include 'navbar.php'; ?>

    <main class="marco-acceso">
        <aside class="panel-bienvenida-acceso">
            <a class="marca-inicio" href="inicio.php"><span class="icono-marca" aria-hidden="true">B</span><span>Book<span class="marca-resaltada">Verse</span></span></a>
            <div class="contenido-bienvenida-acceso">
                <p class="subtitulo-acceso">Tu rincón entre páginas</p>
                <h1>Las historias que amas te están esperando.</h1>
                <p class="texto-bienvenida-acceso">Entra en tu cuenta para volver a tus listas y seguir descubriendo nuevas lecturas.</p>
                <img src="assets/img/bv_2_libro_entreabierto.svg" alt="" aria-hidden="true">
            </div>
            <p class="nota-bienvenida-acceso">Cada libro abre un mundo nuevo.</p>
        </aside>

        <section class="tarjeta-acceso" aria-labelledby="titulo-acceso">
            <a class="enlace-volver-acceso" href="inicio.php">← Volver a BookVerse</a>
            <p class="subtitulo-acceso">Qué bueno tenerte de vuelta</p>
            <h2 id="titulo-acceso">Inicia sesión</h2>
            <p class="introduccion-acceso">Accede a tu espacio lector y continúa justo donde lo dejaste.</p>
            <form class="formulario-acceso" action="IniciarSesion.php" method="post">
                <label class="etiqueta-acceso" for="email">Correo electrónico</label>
                <input class="form-control" id="email" name="email" type="email" autocomplete="email" placeholder="tu@correo.com" required>
                <label class="etiqueta-acceso" for="password">Contraseña</label>
                <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" placeholder="Escribe tu contraseña" required>
                <button class="btn boton-acceso" type="submit">Entrar en mi cuenta <span aria-hidden="true">→</span></button>
            </form>
            <p class="enlace-registro">¿Aún no tienes cuenta? <a href="CrearCuenta.php">Únete a BookVerse</a></p>
        </section>
    </main>
</body>
</html>
