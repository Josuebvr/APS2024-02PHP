<?php

    include('../conexao.php');

    $sql_code = "SELECT * FROM usuario";
    $sql_query = $mysqli->query($sql_code) or die ($mysqli->error);
    $linha = $sql_query->fetch_assoc();

    $sexo[1] = "Masculino";
    $sexo[2] = "Feminino";

    $niveldeacesso[1] = "Público";
    $niveldeacesso[2] = "Diretor de divisão";
    $niveldeacesso[3] = "Ministro do meio ambiente";

    
    
    
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['id_usuario'])) {
        echo "<script> alert('Por favor, faça login para acessar esta página.'); location.href='login'; </script>";
        exit;
    }
    
    
?>

<h1>Usuários</h1>
<a href="logout">Logout</a>
<a href="cadastrar">Cadastrar um usuário</a>
<p class=espaco></p>
<table border=1 cellpadding=10>

    <tr class=titulo>
        <td>Imagem</td>
        <td>Nome</td>
        <td>Sobrenome</td>
        <td>Sexo</td>
        <td>E-mail</td>
        <td>Nível de Acesso</td>
        <td>Data de cadastro</td>
        <td>Ação</td>
    </tr>


    <?php
do {
?>
    <tr>
        <!-- Coluna de Imagem -->
        <td>
            <?php if (!empty($linha['imagem']) && file_exists($linha['imagem'])): ?>
                <a href="<?php echo $linha['imagem']; ?>">
                    <img src="<?php echo $linha['imagem']; ?>" alt="Imagem do usuário" width="50" height="50">
                </a>
            <?php else: ?>
                <p>Sem imagem</p>
            <?php endif; ?>
        </td>

        <!-- Coluna de Nome -->
        <td><?php echo $linha['nome'] ?? 'Nome não disponível'; ?></td>
        
        <!-- Colunas restantes -->
        <td><?php echo $linha['sobrenome'] ?? 'Sobrenome não disponível'; ?></td>
        <td><?php echo $sexo[$linha['sexo']] ?? 'Não especificado'; ?></td>
        <td><?php echo $linha['email'] ?? 'E-mail não disponível'; ?></td>
        <td><?php echo $niveldeacesso[$linha['niveldeacesso']] ?? 'Nível não especificado'; ?></td>
        <td>
            <?php 
            if (!empty($linha['datadecadastro'])) {
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $linha['datadecadastro']);
                echo $date ? $date->format('d/m/Y \à\s H:i:s') : "Data inválida";
            } else {
                echo "Data não disponível";
            }
            ?>
        </td>
        <td>
            <a href="editar?usuario=<?php echo $linha['codigo'] ?? ''; ?>">Editar</a> |
            <a href="javascript: if(confirm('Tem certeza que deseja deletar o usuário <?php echo $linha['nome'] ?? 'usuário'; ?>?'))
            location.href='deletar?usuario=<?php echo $linha['codigo'] ?? ''; ?>';">Deletar</a>
        </td>
    </tr>
<?php 
} while ($linha = $sql_query->fetch_assoc()); 
?>

</table>