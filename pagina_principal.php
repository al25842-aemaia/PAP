<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/pagina_principal.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <title>Página Principal</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Bem-vindo ao Mundo do Futebol!</h1>
            <p>Explore, registre e gerencie seu time de futebol de maneira simples e dinâmica.</p>
            <a href="questionario.php" class="cta-button">Começar</a>
        </div>
    </section>

    <!-- About Section -->
    <section class="content">
        <div class="section-header">
            <h2>Sobre Nós</h2>
            <p>Conecte-se com a paixão pelo futebol. Nosso site oferece ferramentas para registrar jogadores, clubes e muito mais!</p>
        </div>

        <!-- Features Section -->
        <div class="section-features">
            <h2>Nossos Questionários</h2>
            <ul>
                <li><a href="questionario_jogador.php">Cadastrar Jogadores</a></li>
                <li><a href="adicionar_noticia.php">Adicionar Notícias</a></li>
                <li><a href="#">Simulador de Equipas</a></li>
                <li><a href="#">MiniGames</a></li>
            </ul>
        </div>

        <!-- Contact Section -->
        <div class="section-contact">
            <h2>Contato</h2>
            <p>Se você tiver dúvidas ou quiser mais informações, entre em contato conosco pelo e-mail: <a href="mailto:contato@futebol.com">contato@futebol.com</a></p>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
