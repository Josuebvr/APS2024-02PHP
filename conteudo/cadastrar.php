<?php

    include("conexao.php");

    if(isset($_POST['confirmar'])) {    
    
        if(!isset($_SESSION)) 
            session_start();
        
        foreach($_POST as $chave=>&$valor) 
            $_SESSION[$chave] = $mysqli->real_escape_string($valor);

        if(strlen($_SESSION['nome']) == 0)
            $erro[] = "Preencha o nome.";

        if(strlen($_SESSION['sobrenome']) == 0)
            $erro[] = "Preencha o sobrenome.";

        if(substr_count($_SESSION['email'],'@') != 1 || substr_count($_SESSION['email'],'.') < 1 || substr_count($_SESSION['email'],'.') > 2)
            $erro[] = "Preencha o e-mail corretamente.";

        if(strlen($_SESSION['niveldeacesso']) == 0)
            $erro[] = "Preencha o Nível de Acesso.";

        if(strlen($_SESSION['senha']) < 8 || strlen($_SESSION['senha']) > 16)
            $erro[] = "Preencha a senha corretamente.";

        if(strcmp($_SESSION['senha'], $_SESSION['rsenha']) != 0)
            $erro[] = "As senhas não batem.";


        if(count($erro) == 0){

            $slq_code = "INSERT INTO usuario (
                nome, 
                sobrenome, 
                email, 
                senha, 
                sexo,
                niveldeacesso,
                datadecadastro)
                VALUES(
                '$_SESSION[nome]',
                '$_SESSION[sobrenome]',
                '$_SESSION[email]',
                '$_SESSION[senha]',
                '$_SESSION[sexo]',
                '$_SESSION[niveldeacesso]',
                '$_SESSION[datadecadastro]'
                )";

            $confirma = $mysqli->query($slq_code) or die ($mysqli->error);

            if($confirma){

                unset($_SESSION['nome'],
                $_SESSION['sobrenome'],
                $_SESSION['email'],
                $_SESSION['senha'],
                $_SESSION['sexo'],
                $_SESSION['niveldeacesso'],
                $_SESSION['datadecadastro']);

                echo "<script> location.href='index.php?p=inicial'; </script>";

            }else
                $erro[] = "Erro ao cadastrar: " . $mysqli->error;

        }
    }

?>


 
<h1>Cadastrar Usuário</h1>
<?php 
if(count($erro) > 0){ 
    echo "<div class='erro'>"; 
    foreach($erro as $valor) 
        echo "$valor <br>"; 
    echo "</div>";
}
?>
<a href="index.php?p=inicial">< Voltar</a>
<form action="index.php?p=cadastrar" method="POST">

    <label for="nome">Nome</label>
    <input name="nome" value="" required type="text">
    <p class=espaco></p>

    <label for="sobrenome">Sobrenome</label>
    <input name="sobrenome" value="" required type="text">
    <p class=espaco></p>

    <label for="email">E-mail</label>
    <input name="email" value="" required type="email">
    <p class=espaco></p>

    <label for="sexo">Sexo</label>
    <select name="sexo">
        <option value="">Selecione</option>
        <option value="1">Masculino</option>
        <option value="2">Feminino</option>
    </select>
    <p class=espaco></p>

    <label for="niveldeacesso">Nível de Acesso</label>
    <select name="niveldeacesso">
        <option value="">Selecione</option>
        <option value="1">Público</option>
        <option value="2">Diretor de divisão</option>
        <option value="3">Ministro do meio ambiente</option>
    </select>
    <p class=espaco></p>

    <label for="senha">Senha</label>
    A senha deve ter entre 8 e 16 caracteres.
    <input name="senha" value="" required type="password">
    <p class=espaco></p>

    <label for="rsenha">Repita a senha</label>
    <input name="rsenha" value="" required type="password">
    <p class=espaco></p>

    <input value="Salvar" name="confirmar" type="submit">

</form>