// routes/clientes.js
// Endpoints CRUD para la entidad cliente

const express = require('express');
const router = express.Router();
const pool = require('../db');

// GET - Listar todos los clientes
router.get('/', async (req, res) => {
    try {
        const [rows] = await pool.query('SELECT * FROM clientes ORDER BY id_cliente DESC');
        res.json({ ok: true, clientes: rows });
    } catch (error) {
        res.status(500).json({ ok: false, mensaje: 'Error del servidor', detalle: error.message });
    }
});

// GET - Buscar cliente por ID
router.get('/:id', async (req, res) => {
    const id = req.params.id;
    if (isNaN(id) || id <= 0) {
        return res.status(400).json({ ok: false, mensaje: 'ID inválido' });
    }
    try {
        const [rows] = await pool.query('SELECT * FROM clientes WHERE id_cliente = ?', [id]);
        if (rows.length === 0) {
            return res.status(404).json({ ok: false, mensaje: 'Cliente no encontrado' });
        }
        res.json({ ok: true, cliente: rows[0] });
    } catch (error) {
        res.status(500).json({ ok: false, mensaje: 'Error del servidor', detalle: error.message });
    }
});

// POST - Crear cliente
router.post('/', async (req, res) => {
    const { nombre, email, telefono } = req.body;

    if (!nombre || !email) {
        return res.status(400).json({ ok: false, mensaje: 'Nombre y email son obligatorios' });
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        return res.status(400).json({ ok: false, mensaje: 'Email inválido' });
    }

    try {
        const [result] = await pool.query(
            'INSERT INTO clientes (nombre, email, telefono) VALUES (?, ?, ?)',
            [nombre, email, telefono || null]
        );
        res.status(201).json({ ok: true, mensaje: 'Cliente creado correctamente', id_cliente: result.insertId });
    } catch (error) {
        if (error.code === 'ER_DUP_ENTRY') {
            return res.status(409).json({ ok: false, mensaje: 'El email ya está registrado' });
        }
        res.status(500).json({ ok: false, mensaje: 'Error del servidor', detalle: error.message });
    }
});

// PUT - Actualizar cliente
router.put('/:id', async (req, res) => {
    const id = req.params.id;
    if (isNaN(id) || id <= 0) {
        return res.status(400).json({ ok: false, mensaje: 'ID inválido' });
    }

    const { nombre, email, telefono } = req.body;

    if (!nombre || !email) {
        return res.status(400).json({ ok: false, mensaje: 'Nombre y email son obligatorios' });
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        return res.status(400).json({ ok: false, mensaje: 'Email inválido' });
    }

    try {
        const [result] = await pool.query(
            'UPDATE clientes SET nombre = ?, email = ?, telefono = ? WHERE id_cliente = ?',
            [nombre, email, telefono || null, id]
        );
        if (result.affectedRows === 0) {
            return res.status(404).json({ ok: false, mensaje: 'Cliente no encontrado' });
        }
        res.json({ ok: true, mensaje: 'Cliente actualizado correctamente' });
    } catch (error) {
        res.status(500).json({ ok: false, mensaje: 'Error del servidor', detalle: error.message });
    }
});

// DELETE - Eliminar cliente
router.delete('/:id', async (req, res) => {
    const id = req.params.id;
    if (isNaN(id) || id <= 0) {
        return res.status(400).json({ ok: false, mensaje: 'ID inválido' });
    }

    try {
        const [result] = await pool.query('DELETE FROM clientes WHERE id_cliente = ?', [id]);
        if (result.affectedRows === 0) {
            return res.status(404).json({ ok: false, mensaje: 'Cliente no encontrado' });
        }
        res.json({ ok: true, mensaje: 'Cliente eliminado correctamente' });
    } catch (error) {
        res.status(500).json({ ok: false, mensaje: 'Error del servidor', detalle: error.message });
    }
});

module.exports = router;
