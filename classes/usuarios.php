<?php

class usuario
{
    public $msgErro;
    private $pdo;
   

    public function conectar($nome, $host, $usuario, $senha){
        global $pdo;
        global $msgErro;
        try {
            $pdo = new PDO("mysql:dbname=".$nome.";host".$host, $usuario, $senha);
        } catch (PDOException $e) {
           $msgErro = $e-> getMessage();
        }
        
    }
    public function cadastrar($nome, $telefone, $email, $senha){
        global $pdo;
        $sql = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :e");
        $sql->bindValue(":e",$email);
        $sql->execute();
        if($sql->rowCount() > 0){
            return false;
        }
        else
        {
            $sql = $pdo->prepare("INSERT INTO usuarios(nome, telefone, email, senha) VALUES (:n, :t, :e, :s )");
            $sql->bindValue(":n",$nome);
            $sql->bindValue(":t",$telefone);
            $sql->bindValue(":e",$email);
            $sql->bindValue(":s",md5($senha));
            $sql->execute();
            return true;
        }
    
    }
    public function logar($email, $senha){
        global $pdo;
        global $sql;

        $sql = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email= :e AND senha = :s");
        $sql->bindValue(":e",$email);
        $sql->bindValue(":s",md5($senha));
        $sql->execute();
        if($sql->rowCount()>0){
            $dado = $sql->fetch();
            session_start();
            $_SESSION['id_usuario'] = $dado['id_usuario'];
            return true;
        }
        else    
        {
            return false;
        }
    }

            public function postar($titulo,$autor,$categorias,$artigo,$data){
        
            global $poster;
            global $pdo;
            
            $id_usuario = $_SESSION["usuario" ][0];
           
            $sql = $pdo->prepare("INSERT INTO posts(titulo, autor, categorias, artigo, data, usuario_id) VALUES (:t, :a, :c, :r, :d, :u)");
            $sql->bindValue(":t",$titulo);
            $sql->bindValue(":a",$autor);
            $sql->bindValue(":c",$categorias);
            $sql->bindValue(":r",$artigo);
            $sql->bindValue(":d",$data);
            $sql->bindValue(":u", $id_usuario);
            $sql->execute();
                    
        }
}
