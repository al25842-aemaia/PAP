<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <title>Página Principal</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Bem-vindo ao Futebol 12!</h1>
            <p>Explora, O nosso melhor minijogo.</p>
            <a href="adivinharjogador.php" class="cta-button">Começar</a>
        </div>
    </section>

    <!-- About Section -->
    <section class="content">
        <div class="section-header">
            <h2>Sobre Nós</h2>
            <p>Conecte-se com a paixão pelo futebol. Nosso site oferece noticiais atualizadas ao minutos e uma fasta contidade de minijogos para tu jogares sempre que quesseres !</p>
        </div>

        <!-- Features Section -->
        <div class="section-features">
            <h2>O que tem no nosso site</h2>
            <ul>
                <li><a href="login.php">Dar login</a></li>
                <li><a href="noticias.php">ler noticiais</a></li>
                <!-- <li><a href="simulador.php">Simulador de Equipas</a></li> -->
                <li><a href="minijogos.php">MiniGames</a></li>
            </ul>
        </div>

        <!-- Contact Section -->
        <div class="section-contact">
            <h2>Contactos</h2>
            <p>Se você tiver dúvidas ou quiser mais informações, entre em contato conosco pelo e-mail: aemaia.com ou aemaia.com</p>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
