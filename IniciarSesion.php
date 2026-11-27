<?php
// BookVerse — Inicio de Sesión
// Archivo: IniciarSesion.php
// Rol: Integrante 1 (Backend núcleo, base de datos y Git)

require_once __DIR__ . '/includes/auth.php';

// Si el usuario ya está autenticado, redirigir según su rol
redirigirSiAutenticado();

$errores = [];
$email = '';
$mensajeExito = '';

if (isset($_GET['registro']) && $_GET['registro'] === 'exito') {
    $mensajeExito = "¡Tu cuenta ha sido creada con éxito! Inicia sesión para comenzar.";
} elseif (isset($_GET['mensaje']) && $_GET['mensaje'] === 'sesion_cerrada') {
    $mensajeExito = "Has cerrado sesión correctamente. ¡Hasta pronto!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirect = filter_input(INPUT_POST, 'redirect', FILTER_SANITIZE_URL);

    if (empty($email) || empty($password)) {
        $errores[] = "Por favor, introduce tu correo electrónico y tu contraseña.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del correo electrónico no es válido.";
    } else {
        try {
            // Consulta preparada para buscar el usuario por email
            $stmt = $pdo->prepare("SELECT id, nombre, email, password_hash, es_admin, fecha_registro 
                                   FROM usuarios 
                                   WHERE email = :email 
                                   LIMIT 1");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificación de credenciales con password_verify
            if ($usuario && password_verify($password, $usuario['password_hash'])) {
                // Iniciar sesión y regenerar session_id
                iniciarSesionUsuario($usuario);

                // Redirección según el rol de la cuenta
                if ((int)$usuario['es_admin'] === 1) {
                    // El administrador va directamente a su panel de moderación
                    header('Location: administracion.php');
                    exit;
                }

                // Usuario estándar: ir a URL solicitada o al catálogo
                if (!empty($redirect) && strpos($redirect, 'administracion.php') === false) {
                    header('Location: ' . $redirect);
                } else {
                    header('Location: catalogo.php');
                }
                exit;
            } else {
                // Mensaje genérico para evitar enumeración de usuarios
                $errores[] = "El correo electrónico o la contraseña no son correctos.";
            }
        } catch (PDOException $e) {
            error_log("Error en autenticación: " . $e->getMessage());
            $errores[] = "Ocurrió un error en el servidor. Inténtalo de nuevo más tarde.";
        }
    }
}

$urlRedirect = filter_input(INPUT_GET, 'redirect', FILTER_SANITIZE_URL);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inicia sesión en BookVerse y continúa organizando tus próximas lecturas.">
    <title>Iniciar sesión | BookVerse</title>
    <!-- Bootstrap -->
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

            <?php if (!empty($mensajeExito)): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($mensajeExito, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form class="formulario-acceso" action="IniciarSesion.php" method="post">
                <?php if (!empty($urlRedirect)): ?>
                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($urlRedirect, ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>

                <label class="etiqueta-acceso" for="email">Correo electrónico</label>
                <input class="form-control" id="email" name="email" type="email" autocomplete="email" placeholder="tu@correo.com" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>

                <label class="etiqueta-acceso" for="password">Contraseña</label>
                <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" placeholder="Escribe tu contraseña" required>

                <button class="btn boton-acceso" type="submit">Entrar en mi cuenta <span aria-hidden="true">→</span></button>
            </form>
            <p class="enlace-registro">¿Aún no tienes cuenta? <a href="CrearCuenta.php">Únete a BookVerse</a></p>
        </section>
    </main>
</body>
</html>
