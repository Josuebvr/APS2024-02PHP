<?php
include("conexao.php");

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    echo "<script> alert('Por favor, faça login para acessar esta página.'); location.href='index.php?p=login'; </script>";
    exit;
}

if(!isset($_SESSION)) 
    session_start();

// Inicialize a variável $erro como array vazio
$erro = [];

if(isset($_POST['confirmar'])) {    
    
    // Armazena os dados do formulário na sessão
    foreach($_POST as $chave => &$valor) 
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
        // Criptografa a senha
        $senha = md5(md5($_SESSION['senha']));

        $imagem = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $nome_imagem = uniqid() . '-' . basename($_FILES['imagem']['name']);
        $caminho_imagem = 'uploads/' . $nome_imagem;
    
            // Mover a imagem para a pasta "uploads"
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem)) {
            $imagem = $caminho_imagem; // Caminho relativo da imagem para armazenar no banco
        } else {
            echo "Erro ao fazer upload da imagem.";
        }
    }

        $sql_code = "INSERT INTO usuario (
            nome, 
            sobrenome, 
            email, 
            senha, 
            sexo,
            niveldeacesso,
            datadecadastro,
            imagem)
            VALUES(
            '$_SESSION[nome]',
            '$_SESSION[sobrenome]',
            '$_SESSION[email]',
            '$senha',  
            '$_SESSION[sexo]',
            '$_SESSION[niveldeacesso]',
            NOW(),
            '$_SESSION[imagem]'
            )";

        $confirma = $mysqli->query($sql_code) or die ($mysqli->error);

        if($confirma){
            unset($_SESSION['nome'], 
                  $_SESSION['sobrenome'], 
                  $_SESSION['email'], 
                  $_SESSION['senha'], 
                  $_SESSION['sexo'], 
                  $_SESSION['niveldeacesso'], 
                  $_SESSION['datadecadastro'],
                  $_SESSION['imagem']);
            
            echo "<script> location.href='index.php?p=inicial'; </script>";
        } else {
            $erro[] = "Erro ao cadastrar: " . $mysqli->error;
        }
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

<form action="index.php?p=cadastrar" method="POST" enctype="multipart/form-data">

    <label for="nome">Nome</label>
    <input name="nome" value="<?php echo isset($_POST['nome']) ? $_POST['nome'] : ''; ?>" required type="text">
    <p class=espaco></p>

    <label for="sobrenome">Sobrenome</label>
    <input name="sobrenome" value="<?php echo isset($_POST['sobrenome']) ? $_POST['sobrenome'] : ''; ?>" required type="text">
    <p class=espaco></p>

    <label for="email">E-mail</label>
    <input name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required type="email">
    <p class=espaco></p>

    <label for="sexo">Sexo</label>
    <select name="sexo">
        <option value="">Selecione</option>
        <option value="1" <?php echo (isset($_POST['sexo']) && $_POST['sexo'] == 1) ? 'selected' : ''; ?>>Masculino</option>
        <option value="2" <?php echo (isset($_POST['sexo']) && $_POST['sexo'] == 2) ? 'selected' : ''; ?>>Feminino</option>
    </select>
    <p class=espaco></p>

    <label for="niveldeacesso">Nível de Acesso</label>
    <select name="niveldeacesso">
        <option value="">Selecione</option>
        <option value="1" <?php echo (isset($_POST['niveldeacesso']) && $_POST['niveldeacesso'] == 1) ? 'selected' : ''; ?>>Público</option>
        <option value="2" <?php echo (isset($_POST['niveldeacesso']) && $_POST['niveldeacesso'] == 2) ? 'selected' : ''; ?>>Diretor de divisão</option>
        <option value="3" <?php echo (isset($_POST['niveldeacesso']) && $_POST['niveldeacesso'] == 3) ? 'selected' : ''; ?>>Ministro do meio ambiente</option>
    </select>
    <p class=espaco></p>

    <label for="senha">Senha</label>
    A senha deve ter entre 8 e 16 caracteres.
    <input name="senha" value="" required type="password">
    <p class=espaco></p>

    <label for="rsenha">Repita a senha</label>
    <input name="rsenha" value="" required type="password">
    <p class=espaco></p>

    <label for="imagem">Foto do Usuário:</label>
    <input type="file" name="imagem" id="imagem" accept="image/*">
    <p class=espaco></p>

    <input value="Salvar" name="confirmar" type="submit">
</form>
