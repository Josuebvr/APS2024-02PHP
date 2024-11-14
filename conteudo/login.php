<?php
include('../conexao.php');

if (!isset($_SESSION)) {
    session_start();
}

// Inicialize a variável $erro como array vazio
$erro = [];

if (isset($_POST['login'])) {
    $email = $mysqli->real_escape_string($_POST['email']);
    $senha = md5(md5($_POST['senha']));

    // Verifique se o usuário existe no banco de dados
    $sql_code = "SELECT * FROM usuario WHERE email = '$email' AND senha = '$senha'";
    $sql_query = $mysqli->query($sql_code) or die($mysqli->error);

    // Verifica se a consulta retornou um resultado
    if ($sql_query->num_rows == 1) {
        $usuario = $sql_query->fetch_assoc();
        
        // Armazena as informações do usuário na sessão
        $_SESSION['usuario'] = $usuario['nome'];
        $_SESSION['niveldeacesso'] = $usuario['niveldeacesso'];
        $_SESSION['id_usuario'] = $usuario['codigo'];

        // Redireciona para a página inicial
       // echo "<script> location.href='index.php?p=inicial'; </script>";
       echo "<script> location.href='index.php?p=conteudo/identificacao'; </script>";
    } else {
        $erro[] = "E-mail ou senha incorretos.";
    }
}
?>

<h1>Login</h1>
<?php 
if (count($erro) > 0) { 
    echo "<div class='erro'>"; 
    foreach ($erro as $valor) 
        echo "$valor <br>"; 
    echo "</div>";
}
?>
<form action="index.php?p=login" method="POST">
    <label for="email">E-mail</label>
    <input name="email" required type="email">
    <p class="espaco"></p>

    <label for="senha">Senha</label>
    <input name="senha" required type="password">
    <p class="espaco"></p>











    











    <input value="Entrar" name="login" type="submit">
</form>
