# API REST Clientes y Pedidos

Este proyecto corresponde a una API REST desarrollada con Node.js, Express y MySQL. Permite probar endpoints para clientes y pedidos, usando una base de datos relacional y una coleccion de Postman incluida en el repositorio.

## Requisitos

- Node.js instalado
- MySQL activo, por ejemplo usando XAMPP
- Base de datos creada/importada en MySQL

## Instalacion

Desde la carpeta raiz del proyecto, entrar a la carpeta de la API Node:

```bash
cd api_clientes_node
```

Instalar las dependencias:

```bash
npm install
```

## Configuracion

Crear o revisar el archivo `.env` dentro de `api_clientes_node` con los datos de conexion a MySQL:

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=api_clientes_db
PORT=3000
```

Si se usa XAMPP, normalmente el usuario es `root` y la clave queda vacia.

## Ejecutar el proyecto

Con MySQL iniciado, ejecutar:

```bash
npm start
```

La API quedara disponible en:

```text
http://localhost:3000
```

## Endpoints principales

```text
GET    /api/clientes
GET    /api/clientes/1
POST   /api/clientes
PUT    /api/clientes/1
DELETE /api/clientes/3
GET    /api/pedidos
POST   /api/pedidos
```

Tambien se incluye una coleccion de Postman en la carpeta `postman` para probar los endpoints de forma mas rapida.
