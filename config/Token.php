<?php
require_once("vendor/autoload.php");
use \Firebase\JWT\JWT;

class Token {
    private static $chavesRevogadas = [];

    public static function criarToken($dados, $chave = null, $tempoExpiracaoSegundos = null) {
        if ($chave === null) $chave = '9lolEmelhorQueDota9';
        if ($tempoExpiracaoSegundos === null) $tempoExpiracaoSegundos = 60 * 60 * 24;

        $token = [
            "iat" => time(),
            "exp" => time() + $tempoExpiracaoSegundos,
            "data" => $dados
        ];

        return JWT::encode($token, $chave);
    }

    public static function verificarToken($token, $chave = null) {
        if ($chave === null) $chave = '9lolEmelhorQueDota9';

        if (in_array($chave, self::$chavesRevogadas)) {
            outputError(401, "Chave JWT revogada");
        }

        try {
            $decoded = JWT::decode($token, $chave, ['HS256']);
            return (array) $decoded->data;
        } catch (\Firebase\JWT\ExpiredException $e) {
            self::$chavesRevogadas[] = $chave;
            outputError(404, "Token expirado");
        } catch (\Exception $e) {
            outputError(404, "Token inválido");
        }
    }

    public static function logout($chave) {
        self::$chavesRevogadas[] = $chave;
    }

}
