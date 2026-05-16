<?php
// ============================================================
// pedidos.php
// API REST de pedidos.
// Ejemplos:
// GET  /api_clientes/pedidos.php
// POST /api_clientes/pedidos.php
// ============================================================

require_once 'conexion.php';
require_once 'utils.php';

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $sql = 'SELECT p.id, p.producto, p.cantidad, p.estado, p.creado_en,
                       c.id_cliente AS cliente_id, c.nombre AS cliente
                FROM pedidos p
                INNER JOIN clientes c ON c.id_cliente = p.cliente_id
                ORDER BY p.id DESC';
        $stmt = $pdo->query($sql);
        responder(200, ['ok' => true, 'pedidos' => $stmt->fetchAll()]);
        break;

    case 'POST':
        $datos = leerJson();

        $cliente_id = $datos['cliente_id'] ?? null;
        $producto = limpiarTexto($datos['producto'] ?? '');
        $cantidad = $datos['cantidad'] ?? 1;
        $estado = limpiarTexto($datos['estado'] ?? 'pendiente');

        $estadosPermitidos = ['pendiente', 'pagado', 'enviado', 'cancelado'];

        if (!validarEnteroPositivo($cliente_id)) {
            responder(400, ['ok' => false, 'mensaje' => 'cliente_id inválido']);
        }

        if ($producto === '') {
            responder(400, ['ok' => false, 'mensaje' => 'Producto obligatorio']);
        }

        if (!validarEnteroPositivo($cantidad)) {
            responder(400, ['ok' => false, 'mensaje' => 'Cantidad inválida']);
        }

        if (!in_array($estado, $estadosPermitidos)) {
            responder(400, ['ok' => false, 'mensaje' => 'Estado no permitido']);
        }

        try {
            $stmt = $pdo->prepare(
                'INSERT INTO pedidos (cliente_id, producto, cantidad, estado) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$cliente_id, $producto, $cantidad, $estado]);

            responder(201, [
                'ok' => true,
                'mensaje' => 'Pedido creado correctamente',
                'id' => $pdo->lastInsertId()
            ]);
        } catch (PDOException $e) {
            responder(400, [
                'ok' => false,
                'mensaje' => 'No se pudo crear el pedido. Verifique que el cliente exista.',
                'detalle' => $e->getMessage()
            ]);
        }
        break;

    default:
        responder(405, ['ok' => false, 'mensaje' => 'Método HTTP no permitido']);
}
?>