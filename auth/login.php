<?php
// BookVerse — Login
require_once __DIR__ . '/../includes/auth.php';

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
            $stmt = $pdo->prepare("SELECT id, nombre, email, password_hash, es_admin, fecha_registro
                                   FROM usuarios
                                   WHERE email = :email
                                   LIMIT 1");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['password_hash'])) {
                iniciarSesionUsuario($usuario);

                if ((int)$usuario['es_admin'] === 1) {
                    header('Location: ../views/administration.php');
                    exit;
                }

                if (!empty($redirect) && strpos($redirect, 'administration.php') === false) {
                    header('Location: ' . $redirect);
                } else {
                    header('Location: ../views/catalog.php');
                }
                exit;
            } else {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/base.css">
</head>
<body class="pagina-acceso">
    <?php include '../includes/navbar.php'; ?>

    <main class="marco-acceso">
        <aside class="panel-bienvenida-acceso">
            <div class="contenido-bienvenida-acceso">
                <p class="subtitulo-acceso">Tu rincón entre páginas</p>
                <h1>Las historias que amas te están esperando.</h1>
                <p class="texto-bienvenida-acceso">Entra en tu cuenta para volver a tus listas y seguir descubriendo nuevas lecturas.</p>
                <img src="../assets/img/bv_2_libro_entreabierto.svg" alt="" aria-hidden="true">
            </div>
            <p class="nota-bienvenida-acceso">Cada libro abre un mundo nuevo.</p>
        </aside>

        <section class="tarjeta-acceso" aria-labelledby="titulo-acceso">
            <a class="enlace-volver-acceso" href="../views/home.php"> Volver a BookVerse</a>
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

            <form class="formulario-acceso" action="login.php" method="post">
                <?php if (!empty($urlRedirect)): ?>
                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($urlRedirect, ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>

                <label class="etiqueta-acceso" for="email">Correo electrónico</label>
                <input class="form-control" id="email" name="email" type="email" autocomplete="email" placeholder="tu@correo.com" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>

                <label class="etiqueta-acceso" for="password">Contraseña</label>
                <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" placeholder="Escribe tu contraseña" required>

                <button class="btn boton-acceso" type="submit">Entrar en mi cuenta <span aria-hidden="true">→</span></button>
            </form>
            <p class="enlace-registro">¿Aún no tienes cuenta? <a href="register.php">Únete a BookVerse</a></p>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="form-validations"></script>
</body>
</html>
