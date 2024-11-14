
<h1>Bem-vindo à página de Informações sobre o Meio Ambiente!</h1>
<p>Aqui você encontrará diversas informações e notícias sobre preservação ambiental, sustentabilidade, e muito mais.</p>

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

<h1>Informações sobre o Meio Ambiente</h1>

<section>
    <h2>Reflorestamento</h2>
    <p>O reflorestamento é uma prática importante para a recuperação de áreas degradadas, restauração da biodiversidade e combate às mudanças climáticas.</p>
    <img src="imagens/reflorestamento.jpg" alt="Imagem sobre Reflorestamento">
</section>

<?php if ($niveldeacesso >= 2): ?>
<section>
    <h2>Desmatamento</h2>
    <p>O desmatamento é um dos principais problemas ambientais, levando à perda de biodiversidade, mudanças climáticas e degradação dos solos.</p>
    <img src="imagens/desmatamento.jpg" alt="Imagem sobre Desmatamento">
</section>
<?php endif; ?>

<?php if ($niveldeacesso >= 3): ?>
<section>
    <h2>Queimadas</h2>
    <p>As queimadas contribuem para a emissão de gases de efeito estufa, perda de biodiversidade e alterações no clima.</p>
    <img src="imagens/queimadas.jpg" alt="Imagem sobre Queimadas">
</section>
<?php endif; ?>

<?php if ($niveldeacesso == 3): ?>
    <form method="POST">
        <button type="submit" name="redirect">Ir para página inicial</button>
    </form>
<?php endif; ?>
