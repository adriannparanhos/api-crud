<?php

    class ClientController {
        private $client;
    
        public function __construct($client) {
            $this->client = $client;
        }

        public function cadastrar($parametros) {
            $parametrosPost = parametrosJson() + parametrosPost();
            if(!verificarParametrosObrigatorios($parametrosPost, ["nome", "data_nascimento", "cpf", "rg", "telefone"]));
            $nomeclient = $this->client->listarPorCpf($parametrosPost["cpf"]);
            if($nomeclient) outputError(404, "cliente ja existe");
            $client = $this->client->cadastrar($parametrosPost["nome"], $parametrosPost["data_nascimento"], $parametrosPost["cpf"], $parametrosPost["rg"], $parametrosPost["telefone"]);
            if(!$client) outputError(500, "Erro interno do servidor");
            output(200, "client cadastrado com sucesso", "OK", $client);
        }

        public function buscarTodos() {
            $informacoesToken = validarToken();

            $client = $this->client->listarTodos();
            if(!$client) outputError(404, "cliente não encontrado");
            output(200, "clientes encontrados com sucesso", $client);
        }
    }