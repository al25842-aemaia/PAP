<?php
session_start();

include_once 'autenticacao.php';
proibirAutenticado();
include 'db_connection.php';

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
        $error = 'Nome de utilizador ou senha incorretos!';
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
    <link rel="stylesheet" href="css/login.css">
    <title>Login - Futebol12</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <section class="login-hero">
        <div class="hero-content">
            <h1>BEM-VINDO DE VOLTA</h1>
            <p>Aceda à sua conta para continuar a sua experiência no Futebol12</p>
        </div>
    </section>

    <main class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2><i class="fas fa-sign-in-alt"></i> INICIAR SESSÃO</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Nome de Utilizador</label>
                    <input type="text" id="username" name="username" required placeholder="Digite seu nome de utilizador">
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Palavra-passe</label>
                    <input type="password" id="password" name="password" required placeholder="Digite sua palavra-passe">
                    <div class="forgot-password">
                        <a href="#">Esqueceu a palavra-passe?</a>
                    </div>
                </div>

                <button type="submit" class="login-button">
                    <i class="fas fa-sign-in-alt"></i> ENTRAR
                </button>
            </form>

            <div class="register-link">
                Ainda não tem uma conta? <a href="registro.php">Registre-se aqui</a>
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