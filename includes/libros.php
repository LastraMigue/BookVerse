<?php
// BookVerse — Lógica de gestión de libros (consulta y propuesta de nuevos títulos)

require_once __DIR__ . '/conexion.php';

// 1. Obtiene todos los géneros literarios ordenados alfabéticamente
function obtenerGeneros(PDO $pdo): array
{
    try {
        $sql = "SELECT id, nombre FROM generos ORDER BY nombre ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener géneros: " . $e->getMessage());
        return [];
    }
}

// 2. Comprueba si un género existe en la base de datos
function existeGenero(PDO $pdo, int $generoId): bool
{
    try {
        $stmt = $pdo->prepare("SELECT 1 FROM generos WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $generoId]);
        return (bool)$stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error al comprobar género {$generoId}: " . $e->getMessage());
        return false;
    }
}

// 3. Comprueba si ya existe un libro registrado con ese ISBN
function existeIsbn(PDO $pdo, string $isbn): bool
{
    try {
        $stmt = $pdo->prepare("SELECT 1 FROM libros WHERE isbn = :isbn LIMIT 1");
        $stmt->execute([':isbn' => $isbn]);
        return (bool)$stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error al comprobar ISBN {$isbn}: " . $e->getMessage());
        return false;
    }
}

// 4. Valida en servidor los datos de una propuesta de libro
function validarDatosLibro(PDO $pdo, string $titulo, string $autor, string $isbn, $generoId, string $descripcion): array
{
    $errores = [];

    $tituloLimpio = trim($titulo);
    $autorLimpio = trim($autor);
    $isbnLimpio = trim($isbn);
    $descripcionLimpia = trim($descripcion);
    $generoInt = filter_var($generoId, FILTER_VALIDATE_INT);

    if ($tituloLimpio === '') {
        $errores[] = "El título del libro es obligatorio.";
    } elseif (mb_strlen($tituloLimpio) > 255) {
        $errores[] = "El título no puede superar los 255 caracteres.";
    }

    if ($autorLimpio === '') {
        $errores[] = "El autor o autora es obligatorio.";
    } elseif (mb_strlen($autorLimpio) > 150) {
        $errores[] = "El nombre del autor no puede superar los 150 caracteres.";
    }

    if ($isbnLimpio === '') {
        $errores[] = "El ISBN es obligatorio.";
    } elseif (mb_strlen($isbnLimpio) > 20) {
        $errores[] = "El ISBN no puede superar los 20 caracteres.";
    } elseif (existeIsbn($pdo, $isbnLimpio)) {
        $errores[] = "Ya existe un libro registrado con ese ISBN.";
    }

    if ($generoInt === false || $generoInt <= 0 || !existeGenero($pdo, (int)$generoInt)) {
        $errores[] = "Debes seleccionar un género válido.";
    }

    if ($descripcionLimpia === '') {
        $errores[] = "La descripción del libro es obligatoria.";
    }

    return [
        'valido'      => empty($errores),
        'errores'     => $errores,
        'titulo'      => $tituloLimpio,
        'autor'       => $autorLimpio,
        'isbn'        => $isbnLimpio,
        'genero_id'   => (int)$generoInt,
        'descripcion' => $descripcionLimpia
    ];
}

// 5. Inserta un libro propuesto con estado 'pendiente' forzado en el servidor
function proponerLibro(PDO $pdo, array $datos, int $usuarioId): array
{
    $validacion = validarDatosLibro(
        $pdo,
        (string)($datos['titulo'] ?? ''),
        (string)($datos['autor'] ?? ''),
        (string)($datos['isbn'] ?? ''),
        $datos['genero_id'] ?? 0,
        (string)($datos['descripcion'] ?? '')
    );

    if (!$validacion['valido']) {
        return [
            'exito'   => false,
            'errores' => $validacion['errores']
        ];
    }

    try {
        // SEGURIDAD: el estado siempre se fija a 'pendiente' en el servidor, nunca desde el formulario
        $sql = "INSERT INTO libros (titulo, autor, isbn, descripcion, genero_id, creado_por, estado)
                VALUES (:titulo, :autor, :isbn, :descripcion, :genero_id, :creado_por, 'pendiente')";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titulo'      => $validacion['titulo'],
            ':autor'       => $validacion['autor'],
            ':isbn'        => $validacion['isbn'],
            ':descripcion' => $validacion['descripcion'],
            ':genero_id'   => $validacion['genero_id'],
            ':creado_por'  => $usuarioId
        ]);

        return [
            'exito'   => true,
            'errores' => []
        ];
    } catch (PDOException $e) {
        error_log("Error al proponer libro: " . $e->getMessage());
        return [
            'exito'   => false,
            'errores' => ["No se pudo guardar la propuesta debido a un error del servidor."]
        ];
    }
}

// 6. Obtiene únicamente los libros publicados para el catálogo público
function obtenerLibrosPublicados(PDO $pdo, ?string $busqueda = null, ?int $generoId = null): array
{
    try {
        $sql = "SELECT l.id, l.titulo, l.autor, l.isbn, l.descripcion, l.genero_id, l.fecha_envio,
                       g.nombre AS genero_nombre
                FROM libros l
                INNER JOIN generos g ON l.genero_id = g.id
                WHERE l.estado = 'publicado'";

        $params = [];

        if ($busqueda !== null && trim($busqueda) !== '') {
            $sql .= " AND (l.titulo LIKE :busqueda OR l.autor LIKE :busqueda)";
            $params[':busqueda'] = '%' . trim($busqueda) . '%';
        }

        if ($generoId !== null && $generoId > 0) {
            $sql .= " AND l.genero_id = :genero_id";
            $params[':genero_id'] = $generoId;
        }

        $sql .= " ORDER BY l.fecha_envio DESC, l.id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener libros publicados: " . $e->getMessage());
        return [];
    }
}

// 7. Obtiene el detalle de un libro únicamente si está publicado
function obtenerLibroPublicadoPorId(PDO $pdo, int $libroId): ?array
{
    try {
        $sql = "SELECT l.id, l.titulo, l.autor, l.isbn, l.descripcion, l.genero_id, l.fecha_envio,
                       g.nombre AS genero_nombre
                FROM libros l
                INNER JOIN generos g ON l.genero_id = g.id
                WHERE l.id = :id AND l.estado = 'publicado'
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $libroId]);
        $libro = $stmt->fetch(PDO::FETCH_ASSOC);

        return $libro ?: null;
    } catch (PDOException $e) {
        error_log("Error al obtener detalle del libro {$libroId}: " . $e->getMessage());
        return null;
    }
}
