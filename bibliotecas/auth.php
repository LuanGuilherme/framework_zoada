<?php

define('AUTENTICADOR', true);

function authLogin($login, $passwd) {
    function logar($nome, $senha){
        $sql = mysqli_query(conexao(), "SELECT * FROM clientes WHERE nomecliente='$nome' AND senha='$senha'");
        if (mysqli_num_rows($sql) != 0) {
            $_SESSION["idcliente"] = mysqli_fetch_assoc(mysqli_query(conexao(), "SELECT idcliente FROM clientes WHERE nomecliente='$nome' AND senha='$senha'"));
            extract($_SESSION["idcliente"], EXTR_OVERWRITE);
            header("location:../index.php");
        }else{
            echo "<script> alert('Usuário ou senha invalidos!'); </script>";
            header("refresh:1;url=../login.php");
        }
    }
    $nome = htmlentities(trim(preg_replace('/[^[:alpha:]_]/', '',$_POST["nome"])));
    $senha = htmlentities(trim($_POST["senha"]));
    if ($nome == "Administrador"  && $senha == "rodartsinimda") {
        $_SESSION["adm"] = true;
        header("location:../index.php");
    }else{
        logar($nome, $senha);
    }   
}

function authIsLoggedIn() {
    return isset($_SESSION["auth"]);
}

function authLogout() {
    if (isset($_SESSION["auth"])) {
        $_SESSION["auth"] = null;
        unset($_SESSION["auth"]);
    }
}

function authGetUserRole() {
    if (authIsLoggedIn()) {
        return $_SESSION["auth"]["role"];
    }
}
