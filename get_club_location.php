<?php
require_once 'db_connection.php';

if (isset($_GET['id_clube'])) {
    $id_clube = $_GET['id_clube'];

    // Busca o local do clube com base no ID do clube
    $sql = "SELECT local_clube FROM clube WHERE id_clube = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_clube]);
    $local_clube = $stmt->fetchColumn();

    if ($local_clube !== false) {
        echo $local_clube; // Retorna o local do clube
    } else {
        echo "Local não encontrado"; // Caso não encontre o clube
    }
}
?>
