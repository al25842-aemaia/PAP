<?php session_start(); 
    //proibirNaoAdmin();
    require_once 'db_connection.php';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Notícia</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/adicionar_noticia.css"> <!-- CSS específico para a página -->
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="content">
        <h1>Adicionar Notícia</h1>

        <?php
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'];
            $noticia = $_POST['noticia'];

            try {
                // Inserção no banco de dados
                $stmt = $pdo->prepare('INSERT INTO noticiais (titulo, noticia) VALUES (:titulo, :noticia)');
                $stmt->execute(['titulo' => $titulo, 'noticia' => $noticia]);

                // Redireciona para a página de notícias após a inserção
                header('Location: noticias.php');
                exit;
            } catch (PDOException $e) {
                echo 'Erro ao adicionar notícia: ' . $e->getMessage();
            }
        }
        ?>

        <form action="adicionar_noticia.php" method="POST">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="noticia">Notícia:</label>
            <textarea id="noticia" name="noticia" required></textarea>

            <button type="submit" class="cta-button">Adicionar Notícia</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
