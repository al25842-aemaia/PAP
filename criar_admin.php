<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=pap_futebol', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criptografar a senha
    $senha_encriptada = password_hash('1234', PASSWORD_DEFAULT);

    // Inserir o usuário admin
    $stmt = $pdo->prepare("INSERT INTO utilizadores (utilizador, senha) VALUES (:utilizador, :senha)");
    $stmt->bindParam(':utilizador', $utilizador);
    $stmt->bindParam(':senha', $senha_encriptada);

    $utilizador = 'admin'; // nome do usuário
    $stmt->execute();

    echo "Usuário admin criado com sucesso!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
