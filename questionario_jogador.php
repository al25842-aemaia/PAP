<?php session_start(); 
    //proibirNaoAdmin();
    require_once 'db_connection.php';
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_jogador = $_POST['nome_jogador'];
    $aposentado = $_POST['aposentado'];
    $numero_camisola = $_POST['numero_camisola'];
    $imagem_jogador = $_FILES['fileToUpload']['name'];
    $id_clube = $_POST['id_clube'];
    $id_nacionalidade = $_POST['id_nacionalidade'];
    $posicoes = $_POST['posicoes']; // Este campo será um array.

    // Lógica de upload de imagem
    $target_dir = "imagens_jogador/";
    $target_file = $target_dir . basename($imagem_jogador);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Verifique se $uploadOk é 1 e tente fazer o upload
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            
            // Inserindo o jogador na tabela jogador
            $sql = "INSERT INTO jogador (nome_jogador, aposentado, numero_camisola, imagem_jogador, id_clube, id_nacionalidade) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome_jogador, $aposentado, $numero_camisola, $target_file, $id_clube, $id_nacionalidade]);

            // Obtendo o ID do jogador recém-inserido
            $id_jogador = $pdo->lastInsertId();

            // Inserindo as posições do jogador na tabela jogador_posicoes
            foreach ($posicoes as $id_posicao) {
                $sql_posicao = "INSERT INTO jogador_posicoes (id_jogador, id_posicao) VALUES (?, ?)";
                $stmt_posicao = $pdo->prepare($sql_posicao);
                $stmt_posicao->execute([$id_jogador, $id_posicao]);
            }

            echo "Jogador cadastrado com sucesso!";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Buscando clubes e nacionalidades para preencher os campos
$clubes = $pdo->query("SELECT id_clube, nome_clube FROM clube")->fetchAll(PDO::FETCH_ASSOC);
$nacionalidades = $pdo->query("SELECT id_nacionalidade, nacionalidade FROM nacionalidade")->fetchAll(PDO::FETCH_ASSOC);
$posicoes = $pdo->query("SELECT id_posicao, nome_posicao FROM posicoes")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/questionario.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <title>Questionário de Jogador</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <form method="POST" action="questionario_jogador.php" enctype="multipart/form-data">
        <label for="nome_jogador">Nome do Jogador:</label>
        <input type="text" id="nome_jogador" name="nome_jogador" required><br>

        <label for="aposentado">Aposentado:</label>
        <select id="aposentado" name="aposentado" required>
            <option value="0">Não</option>
            <option value="1">Sim</option>
        </select><br>

        <label for="numero_camisola">Número da Camisola:</label>
        <input type="number" id="numero_camisola" name="numero_camisola" min="1" max="99" required><br>

        <label for="fileToUpload">Imagem do Jogador:</label>
        <input type="file" id="fileToUpload" name="fileToUpload" accept="image/*" required><br>

        <label for="id_clube">Clube:</label>
        <select id="id_clube" name="id_clube" required>
            <?php foreach ($clubes as $clube): ?>
                <option value="<?= $clube['id_clube'] ?>"><?= $clube['nome_clube'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="id_nacionalidade">Nacionalidade:</label>
        <select id="id_nacionalidade" name="id_nacionalidade" required>
            <?php foreach ($nacionalidades as $nacionalidade): ?>
                <option value="<?= $nacionalidade['id_nacionalidade'] ?>"><?= $nacionalidade['nacionalidade'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="posicoes">Posições:</label>
        <select id="posicoes" name="posicoes[]" multiple required>
            <?php foreach ($posicoes as $posicao): ?>
                <option value="<?= $posicao['id_posicao'] ?>"><?= $posicao['nome_posicao'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <input type="submit" name="submit" value="Enviar">
    </form>

    <?php include 'footer.php'; ?>
</body>
</html>
