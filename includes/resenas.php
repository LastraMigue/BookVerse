<?php
/**
 * BookVerse — Lógica de gestión de reseñas
 */

require_once __DIR__ . '/conexion.php';

/**
 * 1. Obtiene todas las reseñas asociadas a un libro publicado con sentencias preparadas.
 */
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

/**
 * 2. Calcula la valoración media en estrellas (AVG) y el total de reseñas de un libro.
 */
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

/**
 * 3. Comprueba si un usuario ya ha publicado una reseña sobre un libro (restricción UNIQUE).
 */
function usuarioYaReseno(PDO $pdo, int $libroId, int $usuarioId): bool
{
    try {
        $sql = "SELECT 1 FROM resenas WHERE libro_id = :libro_id AND usuario_id = :usuario_id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':libro_id'   => $libroId,
            ':usuario_id' => $usuarioId
        ]);
        return (bool)$stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error al verificar reseña previa: " . $e->getMessage());
        return false;
    }
}

/**
 * 4. Valida en servidor los datos brutos recibidos del formulario de reseña.
 */
function validarDatosResena($valoracion, $texto): array
{
    $errores = [];

    // Validar tipo y rango numérico (1 a 5 estrellas)
    $valInt = filter_var($valoracion, FILTER_VALIDATE_INT);
    if ($valInt === false || $valInt < 1 || $valInt > 5) {
        $errores[] = "La valoración debe ser un valor entero entre 1 y 5 estrellas.";
    }

    // Validar texto no vacío y longitud mínima
    $textoLimpio = trim((string)$texto);
    if ($textoLimpio === '') {
        $errores[] = "El comentario de la reseña es obligatorio.";
    } elseif (mb_strlen($textoLimpio) < 5) {
        $errores[] = "El comentario debe tener una longitud mínima de 5 caracteres.";
    }

    return [
        'valido'     => empty($errores),
        'errores'    => $errores,
        'valoracion' => (int)$valInt,
        'texto'      => $textoLimpio
    ];
}

/**
 * 5. Inserta una nueva reseña de forma segura con sentencias preparadas.
 */
function insertarResena(PDO $pdo, int $libroId, int $usuarioId, int $valoracion, string $texto): array
{
    // Validar datos de entrada
    $validacion = validarDatosResena($valoracion, $texto);
    if (!$validacion['valido']) {
        return [
            'exito'   => false,
            'mensaje' => implode(' ', $validacion['errores'])
        ];
    }

    try {
        // Verificar que el libro existe y está publicado en el catálogo
        $stmtLibro = $pdo->prepare("SELECT id FROM libros WHERE id = :libro_id AND estado = 'publicado'");
        $stmtLibro->execute([':libro_id' => $libroId]);
        if (!$stmtLibro->fetch()) {
            return [
                'exito'   => false,
                'mensaje' => 'Solo se pueden enviar reseñas para libros publicados.'
            ];
        }

        // Verificar que el usuario no haya publicado ya para este libro
        if (usuarioYaReseno($pdo, $libroId, $usuarioId)) {
            return [
                'exito'   => false,
                'mensaje' => 'Ya has publicado una reseña para este libro.'
            ];
        }

        // Inserción segura mediante sentencia preparada
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
            'mensaje' => 'Reseña guardada correctamente.'
        ];
    } catch (PDOException $e) {
        error_log("Error al insertar reseña: " . $e->getMessage());
        return [
            'exito'   => false,
            'mensaje' => 'Error de servidor al guardar la reseña.'
        ];
    }
}

/**
 * 6. Sanitiza y escapa de forma segura los datos de las reseñas contra ataques XSS
 * para que el integrante encargado del marcado HTML pueda renderizarlas de forma segura.
 */
function escaparResenasParaVista(array $resenas): array
{
    return array_map(function ($r) {
        return [
            'id'             => (int)$r['id'],
            'libro_id'       => (int)$r['libro_id'],
            'usuario_id'     => (int)$r['usuario_id'],
            'valoracion'     => (int)$r['valoracion'],
            'usuario_nombre' => htmlspecialchars((string)($r['usuario_nombre'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'texto'          => htmlspecialchars((string)($r['texto'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'texto_formateado' => nl2br(htmlspecialchars((string)($r['texto'] ?? ''), ENT_QUOTES, 'UTF-8')),
            'fecha'          => htmlspecialchars((string)($r['fecha'] ?? ''), ENT_QUOTES, 'UTF-8')
        ];
    }, $resenas);
}

/**
 * 7. Ejecuta la redirección automática tras guardar una reseña.
 */
function redirigirTrasGuardarResena(int $libroId, bool $guardadoÉxito = true): void
{
    $parametro = $guardadoÉxito ? 'guardado=1' : 'error=1';
    // Redireccionar a la pantalla que corresponda (index.php como ejemplo, revisarlo más adelante)
    header("Location: index.php?id={$libroId}&{$parametro}");
    exit;
}
