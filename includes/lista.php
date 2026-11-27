<?php
// BookVerse — Lógica de gestión de listas de lectura (MVP)
// Funciones CRUD sobre la tabla 'lista_lecturas' -> Listar, añadir, editar y eliminar.

require_once __DIR__ . '/conexion.php';

// 1. Seleccionar todos los libros de la lista de un usuario
function obtenerListaUsuario(PDO $pdo, int $usuarioId): array
{
    try {
        $sql = "SELECT l.id AS libro_id, l.titulo, l.autor, g.nombre AS genero, ll.estado, ll.fecha
                FROM lista_lecturas ll
                INNER JOIN libros l ON ll.libro_id = l.id
                LEFT JOIN generos g ON l.genero_id = g.id
                WHERE ll.usuario_id = :usuario_id
                ORDER BY ll.fecha DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener lista de lecturas: " . $e->getMessage());
        return [];
    }
}

// 2. Añadir libro a la lista del usuario (comprobando existencia y duplicados)
function anadirLibroLista(PDO $pdo, int $usuarioId, int $libroId, string $estado = 'quiero_leer'): array
{
    $estadosValidos = ['quiero_leer', 'leyendo', 'leido'];
    if (!in_array($estado, $estadosValidos, true)) {
        return ['exito' => false, 'mensaje' => 'Estado de lectura inválido.'];
    }

    try {
        // Comprobar que el libro existe en el catálogo general
        $checkLibro = $pdo->prepare("SELECT id FROM libros WHERE id = :libro_id");
        $checkLibro->execute([':libro_id' => $libroId]);
        if (!$checkLibro->fetch()) {
            return ['exito' => false, 'mensaje' => "El libro con ID $libroId no existe en la base de datos."];
        }

        // Comprobar que el usuario no lo tiene ya en su lista
        $checkDuplicado = $pdo->prepare("SELECT 1 FROM lista_lecturas WHERE usuario_id = :usuario_id AND libro_id = :libro_id");
        $checkDuplicado->execute([':usuario_id' => $usuarioId, ':libro_id' => $libroId]);
        if ($checkDuplicado->fetch()) {
            return ['exito' => false, 'mensaje' => 'Este libro ya está en tu lista de lecturas.'];
        }

        // Insertar en la lista
        $sql = "INSERT INTO lista_lecturas (usuario_id, libro_id, estado) VALUES (:usuario_id, :libro_id, :estado)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':libro_id'   => $libroId,
            ':estado'     => $estado
        ]);

        return ['exito' => true, 'mensaje' => 'Libro añadido correctamente a tu lista.'];
    } catch (PDOException $e) {
        error_log("Error al añadir libro: " . $e->getMessage());
        return ['exito' => false, 'mensaje' => 'No se pudo añadir el libro a la lista.'];
    }
}

// 3. Editar estado de un libro en la lista del usuario
function editarEstadoLibro(PDO $pdo, int $usuarioId, int $libroId, string $nuevoEstado): array
{
    $estadosValidos = ['quiero_leer', 'leyendo', 'leido'];
    if (!in_array($nuevoEstado, $estadosValidos, true)) {
        return ['exito' => false, 'mensaje' => 'Estado no válido. Opciones: quiero_leer, leyendo, leido.'];
    }

    try {
        $sql = "UPDATE lista_lecturas SET estado = :estado WHERE usuario_id = :usuario_id AND libro_id = :libro_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':estado'     => $nuevoEstado,
            ':usuario_id' => $usuarioId,
            ':libro_id'   => $libroId
        ]);

        if ($stmt->rowCount() > 0) {
            return ['exito' => true, 'mensaje' => 'Estado actualizado correctamente.'];
        }

        return ['exito' => false, 'mensaje' => 'No se encontró el libro en tu lista o el estado ya era el seleccionado.'];
    } catch (PDOException $e) {
        error_log("Error al actualizar estado: " . $e->getMessage());
        return ['exito' => false, 'mensaje' => 'No se pudo actualizar el estado del libro.'];
    }
}

// 4. Eliminar un libro de la lista del usuario
function eliminarLibroLista(PDO $pdo, int $usuarioId, int $libroId): array
{
    try {
        $sql = "DELETE FROM lista_lecturas WHERE usuario_id = :usuario_id AND libro_id = :libro_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':libro_id'   => $libroId
        ]);

        if ($stmt->rowCount() > 0) {
            return ['exito' => true, 'mensaje' => 'Libro eliminado de tu lista correctamente.'];
        }

        return ['exito' => false, 'mensaje' => 'El libro no se encontraba en tu lista.'];
    } catch (PDOException $e) {
        error_log("Error al eliminar libro: " . $e->getMessage());
        return ['exito' => false, 'mensaje' => 'No se pudo eliminar el libro de la lista.'];
    }
}
