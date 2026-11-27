<?php
// BookVerse — Lógica de gestión de reseñas

require_once __DIR__ . '/conexion.php';

// 1. Obtiene todas las reseñas asociadas a un libro publicado con sentencias preparadas
function obtenerResenasPorLibro(PDO $pdo, int $libroId): array
{
    try {
        $sql = "SELECT r.id, r.libro_id, r.usuario_id, r.valoracion, r.texto, r.fecha, 
                       u.nombre AS usuario_nombre
                FROM resenas r
                INNER JOIN usuarios u ON r.usuario_id = u.id
                WHERE r.libro_id = :libro_id
                ORDER BY r.fecha DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':libro_id' => $libroId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener reseñas del libro {$libroId}: " . $e->getMessage());
        return [];
    }
}

// 2. Calcula la valoración media en estrellas y el total de reseñas de un libro
function obtenerMediaValoracion(PDO $pdo, int $libroId): array
{
    try {
        $sql = "SELECT COUNT(*) AS total, 
                       ROUND(AVG(valoracion), 1) AS media 
                FROM resenas 
                WHERE libro_id = :libro_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':libro_id' => $libroId]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total' => (int)($resultado['total'] ?? 0),
            'media' => $resultado['media'] !== null ? (float)$resultado['media'] : null
        ];
    } catch (PDOException $e) {
        error_log("Error al calcular media de libro {$libroId}: " . $e->getMessage());
        return ['total' => 0, 'media' => null];
    }
}

// 3. Obtiene la reseña específica de un usuario para un libro concreto (o null si no existe)
function obtenerResenaDeUsuario(PDO $pdo, int $libroId, int $usuarioId): ?array
{
    try {
        $sql = "SELECT id, libro_id, usuario_id, valoracion, texto, fecha 
                FROM resenas 
                WHERE libro_id = :libro_id AND usuario_id = :usuario_id 
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':libro_id'   => $libroId,
            ':usuario_id' => $usuarioId
        ]);
        $resena = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resena ?: null;
    } catch (PDOException $e) {
        error_log("Error al consultar reseña previa: " . $e->getMessage());
        return null;
    }
}

// 4. Comprueba si un usuario ya ha publicado una reseña sobre un libro (restricción UNIQUE)
function usuarioYaReseno(PDO $pdo, int $libroId, int $usuarioId): bool
{
    return obtenerResenaDeUsuario($pdo, $libroId, $usuarioId) !== null;
}

// 5. Valida en servidor los datos brutos recibidos del formulario de reseña
function validarDatosResena($valoracion, $texto): array
{
    $errores = [];

    // Validar tipo y rango numérico (1 a 5 estrellas)
    $valInt = filter_var($valoracion, FILTER_VALIDATE_INT);
    if ($valInt === false || $valInt < 1 || $valInt > 5) {
        $errores[] = "La valoración debe ser un número entero entre 1 y 5 estrellas.";
    }

    // Validar texto no vacío y longitud mínima
    $textoLimpio = trim((string)$texto);
    if ($textoLimpio === '') {
        $errores[] = "El comentario de la reseña es obligatorio.";
    } elseif (mb_strlen($textoLimpio) < 5) {
        $errores[] = "El comentario debe tener al menos 5 caracteres.";
    }

    return [
        'valido'     => empty($errores),
        'errores'    => $errores,
        'valoracion' => (int)$valInt,
        'texto'      => $textoLimpio
    ];
}

// 6. Inserta una nueva reseña o edita la existente si el usuario ya opinó (Upsert seguro)
function guardarOActualizarResena(PDO $pdo, int $libroId, int $usuarioId, int $valoracion, string $texto): array
{
    $validacion = validarDatosResena($valoracion, $texto);
    if (!$validacion['valido']) {
        return [
            'exito'   => false,
            'mensaje' => implode(' ', $validacion['errores'])
        ];
    }

    try {
        // Verificar que el libro existe y está publicado
        $stmtLibro = $pdo->prepare("SELECT id FROM libros WHERE id = :libro_id AND estado = 'publicado'");
        $stmtLibro->execute([':libro_id' => $libroId]);
        if (!$stmtLibro->fetch()) {
            return [
                'exito'   => false,
                'mensaje' => 'Solo se pueden enviar reseñas para libros publicados en el catálogo.'
            ];
        }

        $resenaExistente = obtenerResenaDeUsuario($pdo, $libroId, $usuarioId);

        if ($resenaExistente) {
            // Actualizar reseña existente
            $sql = "UPDATE resenas 
                    SET valoracion = :valoracion, texto = :texto, fecha = CURRENT_TIMESTAMP 
                    WHERE libro_id = :libro_id AND usuario_id = :usuario_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':valoracion' => $validacion['valoracion'],
                ':texto'      => $validacion['texto'],
                ':libro_id'   => $libroId,
                ':usuario_id' => $usuarioId
            ]);

            return [
                'exito'   => true,
                'mensaje' => '¡Tu reseña ha sido actualizada con éxito!'
            ];
        } else {
            // Insertar nueva reseña
            $sql = "INSERT INTO resenas (libro_id, usuario_id, valoracion, texto) 
                    VALUES (:libro_id, :usuario_id, :valoracion, :texto)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':libro_id'   => $libroId,
                ':usuario_id' => $usuarioId,
                ':valoracion' => $validacion['valoracion'],
                ':texto'      => $validacion['texto']
            ]);

            return [
                'exito'   => true,
                'mensaje' => '¡Tu reseña ha sido publicada con éxito!'
            ];
        }
    } catch (PDOException $e) {
        error_log("Error al guardar/actualizar reseña: " . $e->getMessage());
        return [
            'exito'   => false,
            'mensaje' => 'Ocurrió un error en el servidor al procesar tu reseña.'
        ];
    }
}

// 7. Elimina una reseña comprobando estrictamente en el servidor que pertenece al usuario
function eliminarResenaPropia(PDO $pdo, int $resenaId, int $usuarioId): array
{
    try {
        $sql = "DELETE FROM resenas WHERE id = :resena_id AND usuario_id = :usuario_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':resena_id'   => $resenaId,
            ':usuario_id' => $usuarioId
        ]);

        if ($stmt->rowCount() > 0) {
            return [
                'exito'   => true,
                'mensaje' => 'Tu reseña ha sido eliminada correctamente.'
            ];
        }

        return [
            'exito'   => false,
            'mensaje' => 'No se encontró la reseña o no tienes permisos para eliminarla.'
        ];
    } catch (PDOException $e) {
        error_log("Error al eliminar reseña: " . $e->getMessage());
        return [
            'exito'   => false,
            'mensaje' => 'No se pudo eliminar la reseña por un error del servidor.'
        ];
    }
}

// 8. Sanitiza y escapa de forma segura los datos de las reseñas contra ataques XSS
function escaparResenasParaVista(array $resenas): array
{
    return array_map(function ($r) {
        return [
            'id'               => (int)$r['id'],
            'libro_id'         => (int)$r['libro_id'],
            'usuario_id'       => (int)$r['usuario_id'],
            'valoracion'       => (int)$r['valoracion'],
            'usuario_nombre'   => htmlspecialchars((string)($r['usuario_nombre'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'texto'            => htmlspecialchars((string)($r['texto'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'texto_formateado' => nl2br(htmlspecialchars((string)($r['texto'] ?? ''), ENT_QUOTES, 'UTF-8')),
            'fecha'            => htmlspecialchars((string)($r['fecha'] ?? ''), ENT_QUOTES, 'UTF-8')
        ];
    }, $resenas);
}

// 9. Formatea la media de estrellas y total de reseñas con fallback textual seguro
function renderizarTextoMedia(?float $media, int $total): string
{
    if ($total === 0 || $media === null) {
        return "No ratings yet"; // "Sin valoraciones aún"
    }
    $textoValoraciones = ($total === 1) ? "1 valoración" : "{$total} valoraciones";
    return number_format($media, 1, ',', '.') . " de 5 · " . $textoValoraciones;
}

// 10. Genera la representación visual en caracteres de estrellas (ej. ★★★★☆)
function renderizarEstrellasVisuales(?float $media): string
{
    if ($media === null || $media <= 0) {
        return "☆☆☆☆☆";
    }
    $redondeado = (int)round($media);
    $estrellasLlenas = min(5, max(0, $redondeado));
    $estrellasVacias = 5 - $estrellasLlenas;
    return str_repeat("★", $estrellasLlenas) . str_repeat("☆", $estrellasVacias);
}
