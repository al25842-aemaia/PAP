<?php
session_start();

include_once 'autenticacao.php';

proibirAutenticado();

include 'db_connection.php';  // Conexão com o banco de dados

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $utilizador = $_POST['username'];
    $senha = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE utilizador = ?");
    $stmt->execute([$utilizador]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['id'] = $user['id_utilizador'];
        $_SESSION['utilizador'] = $user['utilizador'];
        $_SESSION['tipo'] = $user['tipo'];

        header('Location: index.php');
        exit;
    } else {
        $error = "<p style='color: red;'>Email ou senha incorretos!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/login.css">

    <title>Login</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="content">
        <h1>Login</h1>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST">
            <div class="input-group">
                <label for="username">Nome do Utilizador:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="input-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="submit-group">
                <input type="submit" value="Entrar">
            </div>
        </form>

        <p>Ainda não tem uma conta? <a href="registro.php">Registrar-se aqui</a>.</p>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
