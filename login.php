<?php
require_once 'db_connection.php';

session_start();
$error = '';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar credenciais
    $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE utilizador = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar a senha
        if (password_verify($password, $user['senha'])) {
            $_SESSION['loggedin'] = true;
            header('Location: pagina_principal.php');
            exit;
        } else {
            $error = 'Nome de utilizador ou senha inválidos.';
        }
    } else {
        $error = 'Nome de utilizador ou senha inválidos.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/PAP/css/menu.css">
    <link rel="stylesheet" href="http://localhost/PAP/css/footer.css">
    <link rel="stylesheet" href="http://localhost/PAP/css/login.css">

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
