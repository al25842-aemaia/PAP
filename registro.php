<?php
require_once 'db_connection.php';
session_start();
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE utilizador = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error = 'Nome de utilizador já existe.';
    } else {
        $senha_encriptada = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO utilizadores (utilizador, senha) VALUES (:utilizador, :senha)");
        $stmt->bindParam(':utilizador', $username);
        $stmt->bindParam(':senha', $senha_encriptada);

        if ($stmt->execute()) {
            $success = 'Registo concluído com sucesso! Pode fazer login agora.';
        } else {
            $error = 'Erro ao registar o utilizador.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/registro.css">
    <title>Registar - Futebol12</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <section class="register-hero">
        <div class="hero-content">
            <h1>JUNTE-SE À NOSSA COMUNIDADE</h1>
            <p>Crie sua conta e aceda a todos os recursos do Futebol12</p>
        </div>
    </section>

    <main class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h2><i class="fas fa-user-plus"></i> CRIAR CONTA</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="register-form">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Nome de Utilizador</label>
                    <input type="text" id="username" name="username" required placeholder="Digite seu nome de utilizador">
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Palavra-passe</label>
                    <input type="password" id="password" name="password" required placeholder="Crie uma palavra-passe segura">
                    <div class="password-strength"></div>
                </div>

                <button type="submit" class="register-button">
                    <i class="fas fa-user-edit"></i> REGISTAR
                </button>
            </form>

            <div class="login-link">
                Já tem uma conta? <a href="login.php">Faça login aqui</a>
            </div>
        </div>

        <div class="about-card">
            <h3><i class="fas fa-futbol"></i> FUTEBOL12</h3>
            <p>O melhor portal sobre futebol que existe. Notícias atualizadas ao minuto, análises detalhadas e estatísticas completas.</p>
            
            <div class="contact-info">
                <h4><i class="fas fa-envelope"></i> CONTACTOS</h4>
                <p><i class="fas fa-at"></i> al23682@aemaia.com</p>
                <p><i class="fas fa-at"></i> al25842@aemaia.com</p>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>