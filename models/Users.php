<?php
    require_once("config/conexao.php");

    class Users {
        
        public static function cadastrar($username, $password) {
            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password);
            $stmt->execute();
            return [];
        }
        
        public static function listarTodos($username) {
            $sql = "SELECT username FROM users WHERE username = :username";
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function buscarUsersEPassword($username, $password) {
            $sql = "SELECT id, username, password FROM users WHERE username = :username AND password = :password";
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        
        
        
    }