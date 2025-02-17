<?php
include_once 'autenticacao.php';
?>
<nav>
    <ul class="menu">
        <li><a href="index.php">Página Principal</a></li>
        <li><a href="minijogos.php">Minijogos</a></li>
        <li><a href="noticias.php">Noticiais</a></li>

        <?php if(!estaAutenticado()) { ?>
            <li><a href="registro.php">Registo</a></li>
            <li><a href="login.php">Login</a></li>
        <?php } ?>

        <?php if(eAdmin()) { ?>
            <li><a href="adicionar_noticia.php">Adicionar Noticias</a></li>
            <li><a href="questionario_jogador.php">Adicionar Jogadores</a></li>
            <li><a href="questionario_clube.php">Adicionar Clubes</a></li>
            <li><a href="questionario_nacionalidade.php">Adicionar nacionalidades</a></li>
            <li><a href="questionario_posicao.php">Adicionar Posições</a></li>
        <?php } ?>

        <?php if(estaAutenticado()) { ?>
            <li><a href="logout.php">Logout</a></li>
        <?php } ?>
    </ul>
</nav>

