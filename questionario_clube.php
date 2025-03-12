<?php session_start(); 
    //proibirNaoAdmin();
    require_once 'db_connection.php';
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_clube = $_POST['nome_clube'];
    $local_clube = $_POST['local_clube'];

    // Lógica de upload de imagem
    $target_dir = "imagens_clube/"; // Alterado para o diretório correto
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
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

    // Verifique se $uploadOk é 1, e tente fazer o upload
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            
            // Inserindo o clube na tabela clube
            $sql = "INSERT INTO clube (nome_clube, local_clube, imagem_clube) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome_clube, $local_clube, $target_file]);

            echo "Clube cadastrado com sucesso!";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/questionario.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <title>Questionário de Clube</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <form method="POST" action="questionario_clube.php" enctype="multipart/form-data">
        <label for="nome_clube">Nome do Clube:</label>
        <input type="text" id="nome_clube" name="nome_clube" required><br>

        <label for="local_clube">Local do Clube:</label>
        <input type="text" id="local_clube" name="local_clube" required><br>

        <label for="fileToUpload">Imagem do Clube:</label>
        <input type="file" id="fileToUpload" name="fileToUpload" accept="image/*" required><br>

        <input type="submit" name="submit" value="Enviar">
    </form>

    <?php include 'footer.php'; ?>
</body>
</html>
