<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <title>Futebol 12 - O Seu Portal de Futebol</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Bem-vindo ao <span>Futebol 12</span></h1>
            <p class="hero-subtitle">O melhor destino para fãs de futebol</p>
            <div class="hero-buttons">
                <a href="adivinharjogador.php" class="cta-button primary">Começar Jogo <i class="fas fa-play"></i></a>
                <a href="minijogos.php" class="cta-button secondary">Explorar Minijogos <i class="fas fa-gamepad"></i></a>
            </div>
        </div>
        <div class="scroll-down">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Features Grid -->
    <section class="features">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <h3>Notícias</h3>
            <p>Fique atualizado com as últimas novidades do mundo do futebol.</p>
            <a href="noticias.php" class="feature-link">Ler Notícias <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="feature-card highlight">
            <div class="feature-icon">
                <i class="fas fa-futbol"></i>
            </div>
            <h3>Minijogos</h3>
            <p>Diversão garantida com nossos jogos de futebol exclusivos.</p>
            <a href="minijogos.php" class="feature-link">Jogar Agora <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-user"></i>
            </div>
            <h3>Perfil</h3>
            <p>Acesse sua conta para personalizar sua experiência.</p>
            <a href="login.php" class="feature-link">Fazer Login <i class="fas fa-arrow-right"></i></a>
        </div>
    </section>

    <!-- About Section -->
    <section class="about">
        <div class="about-content">
            <h2>Sobre o Futebol 12</h2>
            <p>Somos a plataforma definitiva para fãs de futebol, oferecendo notícias atualizadas em tempo real e uma variedade de minijogos emocionantes para você se divertir a qualquer momento.</p>
            <div class="stats">
        <div class="about-image">
            <img src="imagens/futebol-about.jpg" alt="Sobre o Futebol 12">
        </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter">
        <h2>Receba Novidades</h2>
        <p>Inscreva-se para receber atualizações e novidades exclusivas.</p>
        <form class="newsletter-form">
            <input type="email" placeholder="Seu melhor e-mail">
            <button type="submit">Assinar <i class="fas fa-paper-plane"></i></button>
        </form>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>