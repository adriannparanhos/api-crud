<?php
    require_once("config/conexao.php");

    class Client {
        public static function cadastrar($nome, $data_nascimento, $cpf, $rg, $telefone) {
            $sql = "INSERT INTO clientes (nome, data_nascimento, cpf, rg, telefone) VALUES (:nome, :data_nascimento, :cpf, :rg, :telefone)";
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":data_nascimento", $data_nascimento);
            $stmt->bindParam(":cpf", $cpf);
            $stmt->bindParam(":rg", $rg);
            $stmt->bindParam(":telefone", $telefone);
            $stmt->execute();
            return self::listarPorId($conexao->lastInsertId());
        }

        public static function listarPorId($id) {
            $sql = "select * from clientes where id = :id";
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

        public static function listarPorCpf($cpf) {
            $sql = "select * from clientes where cpf = :cpf";
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":cpf", $cpf);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

        public static function listarTodos() {
            $sql = "select * from clientes";
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    
    }