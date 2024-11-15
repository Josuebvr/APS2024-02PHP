


            <!-- tem que arrumar essa pagina inteira --> 

<?php
session_start();
if (!isset($_SESSION['niveldeacesso'])) {
    echo "Você precisa estar logado para acessar esta página.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redirect'])) {
    header('Location: inicial.php');
    exit();
}


$niveldeacesso = $_SESSION['niveldeacesso'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações sobre o Meio Ambiente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Bem-vindo à página de Informações sobre o Meio Ambiente!</h1>
    <p>Aqui você encontrará diversas informações e notícias sobre preservação ambiental, sustentabilidade, e muito mais.</p>
    
<div class="card">
        <h2>Reflorestamento</h2>
        <img src="../imagens/reflorestamento.jpg" alt="Reflorestamento">
        <p>O reflorestamento é uma prática importante para a recuperação de áreas degradadas, restauração da biodiversidade e combate às mudanças climáticas.</p>
    </div>

<?php if ($niveldeacesso >= 2): ?>
    <div class="card">
        <h2>Desmatamento</h2>
        <img src="../imagens/desmatamento.jpg" alt="Desmatamento">
        <p>O desmatamento é um dos principais problemas ambientais, levando à perda de biodiversidade, mudanças climáticas e degradação dos solos.</p>
    </div>
<?php endif; ?>

<?php if ($niveldeacesso >= 3): ?>
    <div class="card">
        <h2>Queimadas</h2>
        <img src="../imagens/queimadas.jpg" alt="Queimadas">
        <p>As queimadas contribuem para a emissão de gases de efeito estufa, perda de biodiversidade e alterações no clima.</p>
    </div>
<?php endif; ?>

<?php if ($niveldeacesso == 3): ?>
    <form method="POST">
        <button type="submit" name="redirect">Ir para página inicial</button>
    </form>
    </body>
</html>
<?php endif; ?>
