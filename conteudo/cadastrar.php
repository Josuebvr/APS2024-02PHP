<?php
include('../conexao.php');

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    echo "<script> alert('Por favor, faça login para acessar esta página.'); location.href='login'; </script>";
    exit;
}

if(!isset($_SESSION)) 
    session_start();

$erro = [];

if(isset($_POST['confirmar'])) {    
    
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

       if (count($erro) == 0) {

     //parte da imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $imagem = $_FILES['imagem'];
            
            
            if ($imagem["size"] > 2097152) {
                $erro[] = "Imagem muito grande! Max: 2MB";
            } else {
                $pasta = "../uploads/";
                $nomedaimagem = uniqid() . '.' . strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));

             
                if (move_uploaded_file($imagem["tmp_name"], $pasta . $nomedaimagem)) {
                    $path = $pasta . $nomedaimagem;
                } else {
                    $erro[] = "Falha ao mover o arquivo de imagem.";
                }
            }
        } else {
            $path = ''; 
        }
    }

    if(count($erro) == 0){
        
        $senha = md5(md5($_SESSION['senha']));

        //banco de dados
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
            '$path'
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
                  $path);
            
            echo "<script> location.href='inicial'; </script>";
        } else {
            $erro[] = "Erro ao cadastrar: " . $mysqli->error;
        }
    }
}
?>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<h1>Cadastrar Usuário</h1>

<?php 
if(count($erro) > 0){ 
    echo "<div class='erro'>"; 
    foreach($erro as $valor) 
        echo "$valor <br>"; 
    echo "</div>";
}
?>

<a href="inicial">< Voltar</a>

<form action="cadastrar" method="POST" enctype="multipart/form-data">

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

    <!-- parte da foto 2 --> 
<?php 
include('../conexao.php');

if(isset($_FILES['imagem'])) {
    $imagem = $_FILES['imagem'];
     if($imagem['error'])
          die("Falha ao enviar a imagem");
    
    if($imagem["size"] > 2097152)
        die("Imagem muito grande! Max: 2MB");

    $pasta = "../uploads/";
    $nomedaimagem = $imagem['name'];
    $novonomedaimagem = uniqid();
    $extensao = strtolower (pathinfo($nomedaimagem, PATHINFO_EXTENSION));

    if($extensao != "jpg" && $extensao != 'png')
        die("Tipo de imagem não aceito.");

    $path = $pasta . $novonomedaimagem . "." . $extensao;
    $deu_certo = move_uploaded_file($imagem["tmp_name"], $path);
    if ($deu_certo) {
        $mysqli->query("INSERT INTO usuario (imagem) VALUES('$path')") or die ($mysqli->error);
        echo "<p>Imagem enviada com sucesso! </p>";
    } else
        echo "<p>Falha ao enviar imagem</p>";
    
}

?>

    <p><label for ="">Selecione a imagem</label>
    <input name="imagem" type="file"></p>
    
    <p class=espaco></p>

    <input value="Salvar" name="confirmar" type="submit">
</form>

</html>