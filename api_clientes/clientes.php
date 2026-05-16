<?php
// ============================================================
// clientes.php
// API REST de clientes compatible con XAMPP.
// Métodos disponibles:
// GET    /api_clientes/clientes.php
// GET    /api_clientes/clientes.php?id=1
// POST   /api_clientes/clientes.php
// PUT    /api_clientes/clientes.php?id=1
// DELETE /api_clientes/clientes.php?id=1
// ============================================================

require_once 'conexion.php';
require_once 'utils.php';

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {

    case 'GET':
        // READ: listar todos los clientes o buscar uno por id.
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            if (!validarEnteroPositivo($id)) {
                responder(400, ['ok' => false, 'mensaje' => 'ID inválido']);
            }

            $stmt = $pdo->prepare('SELECT * FROM clientes WHERE id = ?');
            $stmt->execute([$id]);
            $cliente = $stmt->fetch();

            if (!$cliente) {
                responder(404, ['ok' => false, 'mensaje' => 'Cliente no encontrado']);
            }

            responder(200, ['ok' => true, 'cliente' => $cliente]);
        }

        $stmt = $pdo->query('SELECT * FROM clientes ORDER BY id DESC');
        responder(200, ['ok' => true, 'clientes' => $stmt->fetchAll()]);
        break;

    case 'POST':
        // CREATE: crear un cliente nuevo.
        $datos = leerJson();

        $nombre = limpiarTexto($datos['nombre'] ?? '');
        $correo = limpiarTexto($datos['correo'] ?? '');
        $telefono = limpiarTexto($datos['telefono'] ?? '');

        if ($nombre === '' || $correo === '') {
            responder(400, ['ok' => false, 'mensaje' => 'Nombre y correo son obligatorios']);
        }

        if (!validarCorreo($correo)) {
            responder(400, ['ok' => false, 'mensaje' => 'Correo inválido']);
        }

        try {
            $stmt = $pdo->prepare('INSERT INTO clientes (nombre, correo, telefono) VALUES (?, ?, ?)');
            $stmt->execute([$nombre, $correo, $telefono]);

            responder(201, [
                'ok' => true,
                'mensaje' => 'Cliente creado correctamente',
                'id' => $pdo->lastInsertId()
            ]);
        } catch (PDOException $e) {
            responder(409, [
                'ok' => false,
                'mensaje' => 'No se pudo crear el cliente. Puede que el correo ya exista.',
                'detalle' => $e->getMessage()
            ]);
        }
        break;

    case 'PUT':
        // UPDATE: actualizar un cliente existente.
        $id = $_GET['id'] ?? null;

        if (!validarEnteroPositivo($id)) {
            responder(400, ['ok' => false, 'mensaje' => 'ID inválido']);
        }

        $datos = leerJson();

        $nombre = limpiarTexto($datos['nombre'] ?? '');
        $correo = limpiarTexto($datos['correo'] ?? '');
        $telefono = limpiarTexto($datos['telefono'] ?? '');

        if ($nombre === '' || $correo === '') {
            responder(400, ['ok' => false, 'mensaje' => 'Nombre y correo son obligatorios']);
        }

        if (!validarCorreo($correo)) {
            responder(400, ['ok' => false, 'mensaje' => 'Correo inválido']);
        }

        $stmt = $pdo->prepare('UPDATE clientes SET nombre = ?, correo = ?, telefono = ? WHERE id = ?');
        $stmt->execute([$nombre, $correo, $telefono, $id]);

        if ($stmt->rowCount() === 0) {
            responder(404, [
                'ok' => false,
                'mensaje' => 'No se actualizó ningún registro. Verifique si el cliente existe.'
            ]);
        }

        responder(200, ['ok' => true, 'mensaje' => 'Cliente actualizado correctamente']);
        break;

    case 'DELETE':
        // DELETE: eliminar un cliente por id.
        $id = $_GET['id'] ?? null;

        if (!validarEnteroPositivo($id)) {
            responder(400, ['ok' => false, 'mensaje' => 'ID inválido']);
        }

        $stmt = $pdo->prepare('DELETE FROM clientes WHERE id = ?');
        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) {
            responder(404, ['ok' => false, 'mensaje' => 'Cliente no encontrado']);
        }

        responder(200, ['ok' => true, 'mensaje' => 'Cliente eliminado correctamente']);
        break;

    default:
        responder(405, ['ok' => false, 'mensaje' => 'Método HTTP no permitido']);
}
?>