<?php
include_once 'autenticacao.php';
?>
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="index.php">⚽ Futebol12</a>
        </div>
        
        <ul class="navbar-menu">
            <li><a href="index.php" class="nav-link"><i class="fas fa-home"></i> Página Principal</a></li>
            <li><a href="minijogos.php" class="nav-link"><i class="fas fa-gamepad"></i> Minijogos</a></li>
            <li><a href="noticias.php" class="nav-link"><i class="fas fa-newspaper"></i> Notícias</a></li>

            <?php if(!estaAutenticado()) { ?>
                <li><a href="registro.php" class="nav-link"><i class="fas fa-user-plus"></i> Registo</a></li>
                <li><a href="login.php" class="nav-link"><i class="fas fa-sign-in-alt"></i> Login</a></li>
            <?php } ?>

            <?php if(eAdmin()) { ?>
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle"><i class="fas fa-cog"></i> Admin <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="adicionar_noticia.php"><i class="fas fa-plus-circle"></i> Adicionar Notícias</a></li>
                        <li><a href="questionario_jogador.php"><i class="fas fa-user-plus"></i> Adicionar Jogadores</a></li>
                        <li><a href="questionario_clube.php"><i class="fas fa-tshirt"></i> Adicionar Clubes</a></li>
                        <li><a href="questionario_nacionalidade.php"><i class="fas fa-flag"></i> Adicionar Nacionalidades</a></li>
                        <li><a href="questionario_posicao.php"><i class="fas fa-map-marker-alt"></i> Adicionar Posições</a></li>
                    </ul>
                </li>
            <?php } ?>

            <?php if(estaAutenticado()) { ?>
                <li><a href="logout.php" class="nav-link logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            <?php } ?>
        </ul>
        
        <div class="mobile-menu-toggle">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</nav>