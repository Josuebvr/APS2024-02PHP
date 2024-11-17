


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
    <title>Impacto dos Agrotóxicos no Meio Ambiente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="meio-ambiente">
    <div class="container">
        <h1>Impacto dos Agrotóxicos nas Propriedades Rurais</h1>
        <p>O uso de agrotóxicos proibidos em propriedades rurais tem gerado sérias preocupações ambientais, especialmente pelos danos causados aos lençóis freáticos, rios e mares. A contaminação das águas por esses produtos químicos representa uma ameaça para a biodiversidade e para a saúde humana.</p>
        
        <div class="card">
            <h2>Agrotóxicos Proibidos</h2>
            <img src="..\imagens\AGROTOXICO.jpg" alt="Uso de Agrotóxicos">
            <p>Esses produtos químicos, que são proibidos devido ao seu potencial de causar danos ao meio ambiente, continuam sendo utilizados por algumas propriedades rurais. Sua aplicação indevida pode resultar na contaminação do solo e das águas subterrâneas, afetando a qualidade da água em diversas regiões.</p>
        </div>

    <?php if ($niveldeacesso >= 2): ?>
        <div class="card">
            <h2>Impacto nos Lençóis Freáticos</h2>
            <img src="..\imagens\lençol freatio.jpg" alt="Contaminação dos Lençóis Freáticos">
            <p>Quando os agrotóxicos infiltram-se no solo, eles podem alcançar os lençóis freáticos, contaminando fontes de água subterrânea. Esse problema afeta o abastecimento de água potável e pode ser extremamente difícil de remediar, além de ter um impacto duradouro na saúde pública.</p>
        </div>
    <?php endif; ?>

    <?php if ($niveldeacesso >= 3): ?>
        <div class="card">
            <h2>Consequências para Rios e Mares</h2>
            <img src="..\imagens\agua.jpg" alt="Contaminação de Rios e Mares">
            <p>A contaminação dos rios e mares ocorre principalmente quando os agrotóxicos são levados pela chuva para os cursos d'água. Esse processo prejudica a fauna e a flora aquáticas e contribui para a degradação dos ecossistemas marinhos, afetando a pesca e o equilíbrio ecológico.</p>
        </div>
    <?php endif; ?>

    <?php if ($niveldeacesso == 3): ?>
        <form method="POST">
            <button type="submit" name="redirect">Ir para página inicial</button>
        </form>
</div>
</body>
</html>
<?php endif; ?>