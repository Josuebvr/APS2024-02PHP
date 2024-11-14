


<html>
<head>
    <meta charset="utf8">
    <title>Controle de Usuários</title>


    
    


</head>
<body>
    <div class=principal>

    <?php if (isset($_SESSION['niveldeacesso']) && $_SESSION['niveldeacesso'] == 3): ?>
    <a href="index.php?p=cadastrar">Cadastrar um usuário</a>
<?php endif; ?>

        <?php
        
        if (isset($_GET['p'])) {
            $pagina = $_GET['p'] . ".php";
            if (is_file("conteudo/$pagina")) {
                include("conteudo/$pagina");
            } else {
                include("conteudo/404.php");
            }
        } else {
            include("conteudo/inicial.php");
        }
        

        ?>
    </div>

</body>
</html>