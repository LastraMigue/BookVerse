<?php
// BookVerse — Registro de Nuevo Usuario
// Archivo: CrearCuenta.php
// Rol: Integrante 1 (Backend núcleo, base de datos y Git)

require_once __DIR__ . '/includes/auth.php';

// Si ya tiene sesión, redirigir según su rol
redirigirSiAutenticado();

$errores = [];
$nombre = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']);

    // 1. Validaciones en servidor
    if (empty($nombre)) {
        $errores[] = "Por favor, introduce tu nombre.";
    } elseif (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 100) {
        $errores[] = "El nombre debe tener entre 2 y 100 caracteres.";
    }

    if (empty($email)) {
        $errores[] = "El correo electrónico es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del correo electrónico no es válido.";
    } elseif (mb_strlen($email) > 150) {
        $errores[] = "El correo electrónico no puede superar los 150 caracteres.";
    }

    if (empty($password)) {
        $errores[] = "La contraseña es obligatoria.";
    } elseif (mb_strlen($password) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    }

    if ($password !== $confirmPassword) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    if (!$terms) {
        $errores[] = "Debes aceptar los términos para crear tu cuenta.";
    }

    // 2. Comprobar unicidad de email en la base de datos
    if (empty($errores)) {
        try {
            $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
            $stmtCheck->execute([':email' => $email]);
            if ($stmtCheck->fetch()) {
                $errores[] = "Ya existe una cuenta registrada con este correo electrónico.";
            }
        } catch (PDOException $e) {
            error_log("Error al verificar email duplicado: " . $e->getMessage());
            $errores[] = "Error temporal del servidor. Inténtalo más tarde.";
        }
    }

    // 3. Inserción segura con password_hash y es_admin forzado a 0
    if (empty($errores)) {
        try {
            // Cifrado de contraseña con BCRYPT
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            // SEGURIDAD: es_admin se fuerza explícitamente a 0 para impedir cualquier elevación de privilegios
            $sql = "INSERT INTO usuarios (nombre, email, password_hash, es_admin, fecha_registro) 
                    VALUES (:nombre, :email, :password_hash, 0, CURRENT_TIMESTAMP)";
            
            $stmtInsert = $pdo->prepare($sql);
            $stmtInsert->execute([
                ':nombre'        => $nombre,
                ':email'         => $email,
                ':password_hash' => $passwordHash
            ]);

            // Redirección con mensaje de éxito hacia el login
            header('Location: IniciarSesion.php?registro=exito');
            exit;
        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            $errores[] = "No se pudo crear la cuenta debido a un error del servidor.";
        }
    }
}
?>
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
    <?php include 'navbar.php'; ?>

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

            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form class="formulario-acceso" action="CrearCuenta.php" method="post">
                <label class="etiqueta-acceso" for="nombre">Nombre</label>
                <input class="form-control" id="nombre" name="nombre" type="text" autocomplete="name" placeholder="¿Cómo te llamas?" value="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>" required>

                <label class="etiqueta-acceso" for="email">Correo electrónico</label>
                <input class="form-control" id="email" name="email" type="email" autocomplete="email" placeholder="tu@correo.com" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>

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
