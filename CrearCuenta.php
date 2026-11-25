<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Crea tu cuenta en BookVerse para guardar libros y organizar tus lecturas.">
    <title>Crear cuenta | BookVerse</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pagina-acceso">
    <header class="barra-superior barra-inicio">
        <nav class="enlaces-superiores" aria-label="Enlaces a las páginas">
            <a href="inicio.php">Inicio</a>
            <a href="catalogo.php">Catálogo</a>
            <a href="FormularioLibro.php">Proponer un libro</a>
            <details class="ajustes-navbar">
                <summary>Ajustes</summary>
                <div>
                    <a href="MisListas.php">Mis listas</a>
                    <a href="IniciarSesion.php">Iniciar sesión</a>
                </div>
            </details>
        </nav>
    </header>

    <main class="marco-acceso marco-registro">
        <aside class="panel-bienvenida-acceso">
            <a class="marca-inicio" href="inicio.php"><span class="icono-marca" aria-hidden="true">B</span><span>Book<span class="marca-resaltada">Verse</span></span></a>
            <div class="contenido-bienvenida-acceso">
                <p class="subtitulo-acceso">Un universo de historias</p>
                <h1>Tu próxima lectura favorita empieza contigo.</h1>
                <p class="texto-bienvenida-acceso">Crea tu espacio, guarda los títulos que te llaman y construye una lista a la que siempre quieras volver.</p>
                <img src="assets/img/bv_2_libro_entreabierto.svg" alt="" aria-hidden="true">
            </div>
            <p class="nota-bienvenida-acceso">Una comunidad para lectores curiosos.</p>
        </aside>

        <section class="tarjeta-acceso" aria-labelledby="titulo-registro">
            <a class="enlace-volver-acceso" href="inicio.php">← Volver a BookVerse</a>
            <p class="subtitulo-acceso">Empieza tu próxima aventura</p>
            <h2 id="titulo-registro">Crea tu cuenta</h2>
            <p class="introduccion-acceso">Un espacio para guardar, descubrir y compartir tus lecturas favoritas.</p>
            <form class="formulario-acceso" action="CrearCuenta.php" method="post">
                <label class="etiqueta-acceso" for="nombre">Nombre</label>
                <input class="form-control" id="nombre" name="nombre" type="text" autocomplete="name" placeholder="¿Cómo te llamas?" required>
                <label class="etiqueta-acceso" for="email">Correo electrónico</label>
                <input class="form-control" id="email" name="email" type="email" autocomplete="email" placeholder="tu@correo.com" required>
                <label class="etiqueta-acceso" for="password">Contraseña</label>
                <input class="form-control" id="password" name="password" type="password" autocomplete="new-password" minlength="8" placeholder="Al menos 8 caracteres" required>
                <label class="etiqueta-acceso" for="confirmacion-contrasena">Confirma tu contraseña</label>
                <input class="form-control" id="confirmacion-contrasena" name="confirm_password" type="password" autocomplete="new-password" minlength="8" placeholder="Repite tu contraseña" required>
                <label class="opcion-recordar" for="terms">
                    <input class="form-check-input" id="terms" type="checkbox" name="terms" required>
                    <span>Quiero crear mi espacio lector en BookVerse.</span>
                </label>
                <button class="btn boton-acceso" type="submit">Crear mi cuenta <span aria-hidden="true">→</span></button>
            </form>
            <p class="enlace-registro">¿Ya tienes una cuenta? <a href="IniciarSesion.php">Inicia sesión</a></p>
        </section>
    </main>
</body>
</html>
