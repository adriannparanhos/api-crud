<?php
    
    class UsersController{
        private $users;
    
        public function __construct($users) {
            $this->users = $users;
        }

        public function cadastrar($parametros) {
            $parametrosPost = parametrosJson() + parametrosPost();
            if(!verificarParametrosObrigatorios($parametrosPost, ["username", "password"]));
            $nomeusers = $this->users->listarTodos($parametrosPost["username"]);
            if($nomeusers) outputError(404, "users ja existe");
            $users = $this->users->cadastrar($parametrosPost["username"], $parametrosPost["password"]);
            if(!$users) outputError(500, "Erro interno do servidor");
            output(200, "users cadastrado com sucesso", "OK", $users);
        }

        public function login() {
            $parametrosPost = parametrosJson() + parametrosPost();
            if(!verificarParametrosObrigatorios($parametrosPost, ["username", "password"]));
            $usuario = $this->users->buscarUsersEPassword($parametrosPost["username"], $parametrosPost["password"]);
            if(!$usuario) outputError(401, "username ou senha incorreta");
            $token = Token::criarToken(["id" => $usuario["id"], "username" => $usuario["username"]]);
            $dados = ["token" => $token];
            output(200, "users logado com sucesso", $dados);
        }
        
        public function buscarUsers($parametros) {
            $informacoesToken = validarToken();

            if(!verificarParametrosObrigatorios($parametros, ["id"]));

            $users = $this->users->listar($parametros["id"]);
            if(!$users) outputError(404, "users não encontrado");
            output(200, "users encontrado com sucesso", $users);
        }
        
        public function exibirTodos($parametros){

            validarToken();

            $userss = $this->users->listarTodos();
            if(!$userss) outputError(404, "users não encontrado");
            output(200, "userss encontrados com sucesso!", $userss);

        }

        public function deletarusers($parametros){

            validarToken();

            if(!verificarParametrosObrigatorios($parametros, ["id"]));
            $users = $this->users->delete($parametros["id"]);
            if(!$users) outputError(404, "users não encontrado");
            output(200, "users deletado com sucesso", $users);
        }

        public function atualizarusers($parametros){

            $tokenValidado = validarToken();
            
            $parametrosBody = parametrosJson() + parametrosPut();
            $data_users = strtotime($parametrosBody["data_users"]);
            $data_usersFormatado = date('Y-m-d H:i:s', $data_users);
            if(!verificarParametrosObrigatorios($parametrosBody,["nome","sala","data_users"]));
            if(!verificarParametrosObrigatorios($parametros,["id"]));
            $usersAntigo = $this->users->listarusersUsuario($parametros["id"], $tokenValidado["id"]);
            if ($usersAntigo && $usersAntigo["nome"] == $parametrosBody["nome"] && $usersAntigo["sala"] == $parametrosBody["sala"] && $usersAntigo["data_users"] == $data_usersFormatado) {
                outputError(400, "Nenhum dado foi alterado");
            }
            $users = $this->users->update($parametros["id"], $parametrosBody["nome"], $parametrosBody["sala"], $data_usersFormatado);
            if(!$users) outputError(404, "users não encontrado");
            output(200, "users atualizado com sucesso", $users);
        }
    }

?>