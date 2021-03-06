<?php

function adicionarCliente() {
    function cadastra ($nome, $email, $idade, $rua, $cidade, $estado, $numero, $senha, $cpf){
        $busca = "SELECT idcliente FROM clientes";
        if (mysqli_fetch_assoc(mysqli_query(conn(), $busca)) == NULL) {
            alert($nome.$email.$idade.$rua.$cidade.$estado.$numero.$senha.$cpf);
            $sql = "INSERT INTO clientes(idcliente, nomecliente, email, idade, rua, cidade, estado, numero, senha, cpf)
        values (1 , '$nome', '$email', '$idade', '$rua', '$cidade', '$estado', '$numero', '$senha', '$cpf')";
        }else{
            $sql = "INSERT INTO clientes(nomecliente, email, idade, rua, cidade, estado, numero, senha, cpf)
        values ('$nome', '$email', '$idade', '$rua', '$cidade', '$estado', '$numero', '$senha', '$cpf')";
        }
        mysqli_query(conn(), $sql);
    }
    foreach ($_POST as $aux){
        $aux = trim(htmlentities($aux));    
    }

    $count = 1;
    if (!empty($_POST)){
        if (empty($_POST["nome"])){
            alert('Preencha o campo Nome!');
            $count += 1;
        }
        if (empty($_POST["senha"]) or strlen($_POST["senha"]) < 8){
            alert('Preencha corretamente o campo Senha!');
            $count += 1;
        }
        if (empty($_POST["email"]) or !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            alert('Preencha o campo E-mail corretamente!');
            $count += 1;
        }
        if (empty($_POST["dia"]) or !filter_var($_POST["dia"], FILTER_VALIDATE_FLOAT) or strlen($_POST["dia"]) < 1){
            alert('Preencha o campo Dia!');
            $count += 1;
        }
        if (empty($_POST["mes"]) or !filter_var($_POST["mes"], FILTER_VALIDATE_FLOAT) or strlen($_POST["mes"]) < 1){
            alert('Preencha o campo Mês!');
            $count += 1;
        }
        if (empty($_POST["ano"]) or !filter_var($_POST["ano"], FILTER_VALIDATE_FLOAT) or strlen($_POST["ano"]) < 4){
            alert('Preencha o campo Ano!');
            $count += 1;
        }
        if (empty($_POST["estado"])){
            alert('Preencha o campo Estado!');
            $count += 1;
        }
        if (empty($_POST["cidade"])){
            alert('Preencha o campo Cidade!');
            $count += 1;
        }
        if (empty($_POST["rua"])){
            alert('Preencha o campo Rua!');
            $count += 1;
        }
        if (empty($_POST["numero"]) or !filter_var($_POST["numero"], FILTER_VALIDATE_FLOAT)){
            alert('Preencha o campo Número com um número inteiro!');
            $count += 1;
        }
        if (empty($_POST["cpf"]) or !filter_var($_POST["cpf"], FILTER_VALIDATE_FLOAT) or strlen($_POST["cpf"]) <> 11){
            alert('Preencha o campo Cpf com números inteiros!');
            $count += 1;
        }
        alert($count);
        if ($count == 1) {
            $nome = $_POST["nome"];
            $email = $_POST["email"];
            $idade = (date("Y") - $_POST["ano"]);
            $rua = $_POST["rua"];
            $cidade = $_POST["cidade"];
            $estado = $_POST["estado"];
            $numero = $_POST["numero"];
            $senha = $_POST["senha"];
            $cpf = $_POST["cpf"];
            alert("passo 2");
            cadastra ($nome, $email, $idade, $rua, $cidade, $estado, $numero, $senha, $cpf);
        }
    }
}

function atualizarCliente($nome, $email, $idade, $endereco, $senha , $cpf, $idcliente) { 
    $comando = "UPDATE clientes
    SET nomecliente = '$nome', email = '$email', idade = '$idade', endereco = '$endereco', senha = '$senha', cpf = '$cpf'
    WHERE idcliente ='$idcliente'";
    mysqli_query(conn(), $comando);

}

function deletarCliente($id) {
    $comando = "DELETE FROM clientes WHERE idcliente = '$id'";
    mysqli_query(conn(), $comando);
}    

function selecionarCliente() {
    @extract($_SESSION["idcliente"], EXTR_OVERWRITE);
    @$sql = "SELECT nomecliente FROM clientes WHERE idcliente='$idcliente'";
    if (isset($_SESSION["idcliente"])) {
        $aux = mysqli_fetch_assoc(mysqli_query(conexao(), $sql));
        extract($aux, EXTR_OVERWRITE);
        $nome = $nomecliente;
    }elseif (@isset($_SESSION["adm"])) {
        $nome = "Admin";
    }else{
        $nome = "Logar";
    }
    return($nome);
}

function login($login, $passwd) {
    function logar($nome, $senha){
        $sql = mysqli_query(conn(), "SELECT * FROM clientes WHERE nomecliente='$nome' AND senha='$senha'");
        if (mysqli_num_rows($sql) != 0) {
            $_SESSION["idcliente"] = mysqli_fetch_assoc($sql);
            extract($_SESSION["idcliente"], EXTR_OVERWRITE);
            $_SESSION["idcliente"]["nomecliente"] = $nome;
        }
    }
    $nome = htmlentities(trim(preg_replace('/[^[:alpha:]_]/', '',$login)));
    $senha = htmlentities(trim($passwd));
    if ($nome == "Administrador"  && $senha == "rodartsinimda") {
        $_SESSION["adm"] = true;
        $_SESSION["idcliente"]["nomecliente"] = $nome;
        authLogin($nome, $senha);
    }else{
        logar($nome, $senha);
    }   
}

function produtosEstoque() {
    $sql = "SELECT * FROM loja_produtos_em_estoque";
    $help = mysqli_query(conn(), $sql);
    while($registro = mysqli_fetch_assoc($help)) {
		$retorno[] = $registro;
	}
    return($retorno);
}

function produtosCategoria(){
    $sql = "SELECT * FROM loja_produtos_por_categoria";
    $help = mysqli_query(conn(), $sql);
    while($registro = mysqli_fetch_assoc($help)) {
		$retorno[] = $registro;
	}
    return($retorno);
}

function pedidosEntreDatas ($data1, $data2) {
    $sql = "CALL pedidos_intervalo_datas('$data1', '$data2')";
    $help = mysqli_query(conn(), $sql);
    while($registro = mysqli_fetch_assoc($help)) {
		$retorno[] = $registro;
	}
    return($retorno);
}

function pedidosMunicipio ($cidade) {
    $sql = "CALL pedidos_municipio('$cidade')";
    $help = mysqli_query(conn(), $sql);
    while($registro = mysqli_fetch_assoc($help)) {
		$retorno[] = $registro;
    }
    return($retorno);
}

function pedidosPeriodo ($data) {
    $sql = "CALL faturamento_periodo('$data')";
    $help = mysqli_query(conn(), $sql);
    while($registro = mysqli_fetch_assoc($help)) {
		$retorno[] = $registro;
	}
    return($retorno);
}
