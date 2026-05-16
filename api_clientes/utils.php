<?php
// ============================================================
// utils.php
// Funciones reutilizables para respuestas JSON, lectura del body,
// validaciones y sanitización básica.
// ============================================================

function responder($codigoHttp, $datos) {
    http_response_code($codigoHttp);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function leerJson() {
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);

    if ($input !== '' && $datos === null) {
        responder(400, [
            'ok' => false,
            'mensaje' => 'El cuerpo de la petición no contiene JSON válido'
        ]);
    }

    return $datos ?? [];
}

function limpiarTexto($valor) {
    return trim(strip_tags((string)$valor));
}

function validarCorreo($correo) {
    return filter_var($correo, FILTER_VALIDATE_EMAIL) !== false;
}

function validarEnteroPositivo($valor) {
    return filter_var($valor, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false;
}
?>