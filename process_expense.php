<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Verificar se os dados foram enviados via POST e estão válidos
if(isset($_POST['date'], $_POST['description'], $_POST['amount'], $_POST['category'], $_POST['user_id'])) {
    // Sanitize e validar os dados do formulário
    $date = $_POST['date'];
    $description = htmlspecialchars($_POST['description']);
    $amount = floatval($_POST['amount']);
    $category = htmlspecialchars($_POST['category']);
    $user_id = intval($_POST['user_id']);

    // Consultar o ID da categoria no banco de dados
    $sql_category = "SELECT id FROM categories WHERE name = ?";
    $stmt_category = $conn->prepare($sql_category);
    $stmt_category->bind_param("s", $category);
    $stmt_category->execute();
    $result_category = $stmt_category->get_result();

    if ($result_category->num_rows > 0) {
        $row_category = $result_category->fetch_assoc();
        $category_id = $row_category['id'];

        // Inserir a despesa com o category_id e user_id corretos
        $sql_expense = "INSERT INTO expenses (date, description, amount, category_id, user_id)
                        VALUES (?, ?, ?, ?, ?)";
        $stmt_expense = $conn->prepare($sql_expense);
        $stmt_expense->bind_param("ssdii", $date, $description, $amount, $category_id, $user_id);

        if ($stmt_expense->execute()) {
            // Redirecionar de volta ao dashboard após adicionar a despesa
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Erro ao adicionar despesa: " . $stmt_expense->error;
        }
    } else {
        echo "Categoria não encontrada.";
    }

    // Fechar declarações preparadas
    $stmt_category->close();
    $stmt_expense->close();
} else {
    echo "Por favor, preencha todos os campos do formulário.";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
