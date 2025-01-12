<!DOCTYPE html>
<html lang="pt">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/menu.css"> <!-- Caminho para o seu CSS -->
</head>
<body>

<div class="topnav">
    <a href="pagina_principal.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'pagina_principal.php' ? 'active' : ''; ?>">Página Principal</a>
    <a href="noticias.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'noticias.php' ? 'active' : ''; ?>">Notícias</a>    
    <a href="minijogos.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'minijogos.php' ? 'active' : ''; ?>">Minijogos</a>
    <a href="adicionar_noticia.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'adicionar_noticia.php' ? 'active' : ''; ?>">Adicionar Notícias</a>
    <a href="questionario_jogador.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'questionario_jogador.php' ? 'active' : ''; ?>">Questionário Jogador</a>
    <a href="questionario_clube.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'questionario_clube.php' ? 'active' : ''; ?>">Questionário Clube</a>
    <a href="questionario_nacionalidade.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'questionario_nacionalidade.php' ? 'active' : ''; ?>">Questionário Nacionalidade</a>
    <a href="questionario_posicao.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'questionario_posicao.php' ? 'active' : ''; ?>">Questionário Posição</a>
    <a href="registro.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'registro.php' ? 'active' : ''; ?>">Registrar</a>
    <a href="login.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>">Login</a>

</div>

</body>
</html>
