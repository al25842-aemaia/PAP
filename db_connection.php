<?php
$host = 'localhost';
$dbname = 'pap_futebol'; // Nome correto do banco de dados
$user = 'root';           // Usuário root do MySQL
$password = '';           // Senha vazia

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
?>
