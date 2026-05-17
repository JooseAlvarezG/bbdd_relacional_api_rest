// index.js
// Servidor principal Express

const express = require('express');
const app = express();
require('dotenv').config();

const clientesRouter = require('./routes/clientes');

app.use(express.json());

app.get('/', (req, res) => {
    res.json({
        ok: true,
        mensaje: 'API REST de clientes funcionando',
        endpoints: {
            listarClientes: 'GET /api/clientes',
            buscarCliente: 'GET /api/clientes/:id',
            crearCliente: 'POST /api/clientes',
            actualizarCliente: 'PUT /api/clientes/:id',
            eliminarCliente: 'DELETE /api/clientes/:id'
        }
    });
});

app.use('/api/clientes', clientesRouter);

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Servidor corriendo en http://localhost:${PORT}`);
});
