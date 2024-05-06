<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db_connection.php';

// Consulta para obter o ID do usuário atual
$username = $_SESSION['username'];
$sql_user_id = "SELECT id FROM users WHERE username = ?";
$stmt_user_id = $conn->prepare($sql_user_id);
$stmt_user_id->bind_param("s", $username);
$stmt_user_id->execute();
$user_id_result = $stmt_user_id->get_result();

if ($user_id_result->num_rows > 0) {
    $row = $user_id_result->fetch_assoc();
    $user_id = $row['id'];

    // Consulta para selecionar todas as categorias
    $sql_categories = "SELECT * FROM categories";
    $result_categories = $conn->query($sql_categories);

    // Array para armazenar as categorias
    $categories = array();

    if ($result_categories->num_rows > 0) {
        while($row = $result_categories->fetch_assoc()) {
            $categories[] = $row['name'];
        }
    }
} else {
    echo "Erro ao recuperar o ID do usuário.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Despesa</title>    
    <link rel="stylesheet" type="text/css" href="css/style_b.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<body>
    <div class="container">
        <h2>Bem-vindo, <?php echo $_SESSION['username']; ?>!</h2>
        <p>Este é o seu painel de controle.</p>
        <div class="menu">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="add_expense.php">Adicionar Despesa</a></li>
                <li><a href="view_expenses.php">Visualizar Despesas</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="add-expense-form">
            <h2>Adicionar Despesa</h2>
            <form action="process_expense.php" method="post">
                <label for="date">Data:</label><br>
                <input type="date" id="date" name="date"><br>
                <label for="description">Descrição:</label><br>
                <input type="text" id="description" name="description"><br>
                <label for="amount">Valor:</label><br>
                <input type="number" step="0.01" id="amount" name="amount"><br>
                <label for="category">Categoria:</label><br>
                <select id="category" name="category">
                    <?php
                    // Exibir as opções do menu suspenso com as categorias
                    foreach ($categories as $category) {
                        echo "<option value='$category'>$category</option>";
                    }
                    ?>
                </select><br>
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"> <!-- Adicionando o ID do usuário como um campo oculto -->
                <input type="submit" value="Adicionar Despesa">
            </form>
        </div>
    </div>
</body>
</html>