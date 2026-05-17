// routes/pedidos.js
// Endpoints para la entidad pedido

const express = require('express');
const router = express.Router();
const pool = require('../db');

const estadosPermitidos = ['pendiente', 'pagado', 'enviado', 'cancelado'];

function esEnteroPositivo(valor) {
    return Number.isInteger(Number(valor)) && Number(valor) > 0;
}

// GET - Listar todos los pedidos
router.get('/', async (req, res) => {
    try {
        const [rows] = await pool.query(
            `SELECT p.id, p.producto, p.cantidad, p.estado, p.creado_en,
                    c.id_cliente AS cliente_id, c.nombre AS cliente
             FROM pedidos p
             INNER JOIN clientes c ON c.id_cliente = p.cliente_id
             ORDER BY p.id DESC`
        );

        res.json({ ok: true, pedidos: rows });
    } catch (error) {
        res.status(500).json({ ok: false, mensaje: 'Error del servidor', detalle: error.message });
    }
});

// POST - Crear pedido
router.post('/', async (req, res) => {
    const { cliente_id, producto, cantidad = 1, estado = 'pendiente' } = req.body;

    if (!esEnteroPositivo(cliente_id)) {
        return res.status(400).json({ ok: false, mensaje: 'cliente_id invalido' });
    }

    if (!producto || producto.trim() === '') {
        return res.status(400).json({ ok: false, mensaje: 'Producto obligatorio' });
    }

    if (!esEnteroPositivo(cantidad)) {
        return res.status(400).json({ ok: false, mensaje: 'Cantidad invalida' });
    }

    if (!estadosPermitidos.includes(estado)) {
        return res.status(400).json({ ok: false, mensaje: 'Estado no permitido' });
    }

    try {
        const [result] = await pool.query(
            'INSERT INTO pedidos (cliente_id, producto, cantidad, estado) VALUES (?, ?, ?, ?)',
            [Number(cliente_id), producto.trim(), Number(cantidad), estado]
        );

        res.status(201).json({
            ok: true,
            mensaje: 'Pedido creado correctamente',
            id: result.insertId
        });
    } catch (error) {
        if (error.code === 'ER_NO_REFERENCED_ROW_2') {
            return res.status(400).json({
                ok: false,
                mensaje: 'No se pudo crear el pedido. Verifique que el cliente exista.'
            });
        }

        res.status(500).json({ ok: false, mensaje: 'Error del servidor', detalle: error.message });
    }
});

module.exports = router;
