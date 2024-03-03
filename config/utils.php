<?php

function parametrosValidos($valores, $lista) {
    $obtidos = array_keys($valores);
    $nao_encontrados = array_diff($lista, $obtidos);
    if (empty($nao_encontrados)) {
        foreach ($lista as $p) {
            if (empty(trim($valores[$p]))) {
                return false;
            }
        }
        return true;
    }
    return false;
}

function verificarParametrosObrigatorios($params, $parametrosObrigatorios) {
    foreach($parametrosObrigatorios as $parametro) {
        if (!isset($params[$parametro])) {
            outputError(404, "O parâmetro '$parametro' é obrigatório.");
        }
        if (empty($params[$parametro])) {
            outputError(400, "O parâmetro '$parametro' não pode estar vazio.");
        }
    }
}

function outputError($statusCode, $mensagem) {
    http_response_code($statusCode);
    $response = array("status" => "ERRO", "mensagem" => $mensagem);
    echo json_encode($response);
    exit;
}
function output($codigo, $msg, $resultado = null) {
    http_response_code($codigo);
    echo json_encode([
        "status" => "OK",
        "msg" => $msg,
        "resultado" => $resultado
    ]);
    exit;
}

function parametrosJson() {
    $postData = json_decode(file_get_contents('php://input'), true);
    return !empty($postData) ? $postData : [];
}

function parametrosGet() {
    return $_GET;
}

function parametrosPost() {
    return $_POST;
}

function parametrosPut() {
    $_PUT = array();
    return $_PUT;
}

function validarToken() {
    $authorizationHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null;
    if($authorizationHeader == null) outputError(401, "Cabecalho de autenticacao nao encontrado!");
    
    if (isset($authorizationHeader) && strpos($authorizationHeader, 'Bearer ') === 0) {
        $token = substr($authorizationHeader, 7);
        $cargaDados = Token::verificarToken($token);
        if(!$cargaDados) outputError(401, "Token invalido");
    } else {
        outputError(401, "Para usar nossa API é necessario utilizar Bearer Token, por favor tente novamente!");
    }
    return $cargaDados;
}
