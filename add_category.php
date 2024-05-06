<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db_connection.php';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['category_name']) && !empty($_POST['category_name'])) {
        $category_name = $_POST['category_name'];

        // Inserir a nova categoria no banco de dados
        $sql = "INSERT INTO categories (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $category_name);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: view_expenses.php"); // Redirecionar de volta para a página principal após adicionar a categoria
            exit();
        } else {
            echo "Erro ao adicionar categoria.";
        }
    } else {
        echo "Por favor, preencha o nome da categoria.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Categoria</title>
    <link rel="stylesheet" type="text/css" href="css/style_b.css">
</head>
<body>
    <div class="container">
        <h2>Adicionar Nova Categoria</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="category_name">Nome da Categoria:</label><br>
            <input type="text" id="category_name" name="category_name"><br>
            <input type="submit" value="Adicionar Categoria">
        </form>
    </div>
</body>
</html>
