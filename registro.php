<?php
require_once 'db_connection.php';
session_start();
$error = '';
$success = '';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar se o usuário já existe
    $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE utilizador = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error = 'Nome de utilizador já existe.';
    } else {
        // Criptografar a senha
        $senha_encriptada = password_hash($password, PASSWORD_DEFAULT);

        // Inserir o novo usuário
        $stmt = $pdo->prepare("INSERT INTO utilizadores (utilizador, senha) VALUES (:utilizador, :senha)");
        $stmt->bindParam(':utilizador', $username);
        $stmt->bindParam(':senha', $senha_encriptada);

        if ($stmt->execute()) {
            $success = 'Usuário registrado com sucesso! Você pode fazer login agora.';
        } else {
            $error = 'Erro ao registrar o usuário.';
        }
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
    <link rel="stylesheet" href="css/registro.css">
    <title>Registrar</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="content">
        <h1>Registrar Novo Utiizador</h1>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <?php if ($success) echo "<p class='success'>$success</p>"; ?>
        
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
                <input type="submit" value="Registrar">
            </div>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
