<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include 'db_connection.php';

// Obtém o ID do usuário atual
$username = $_SESSION['username'];
$user_id_query = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($user_id_query);
$stmt->bind_param("s", $username);
$stmt->execute();
$user_id_result = $stmt->get_result();

if ($user_id_result->num_rows > 0) {
    $row = $user_id_result->fetch_assoc();
    $user_id = $row['id'];

    // Obtém o ano e o mês atual
    $current_year = date('Y');
    $current_month = date('m');

    // Consulta para obter os gastos do usuário no mês atual
    $sql_expenses = "SELECT date, description, amount 
                     FROM expenses 
                     WHERE user_id = ? 
                     AND YEAR(date) = ? 
                     AND MONTH(date) = ?
                     ORDER BY date";
    $stmt = $conn->prepare($sql_expenses);
    $stmt->bind_param("iii", $user_id, $current_year, $current_month);
    $stmt->execute();
    $result_expenses = $stmt->get_result();

    if ($result_expenses->num_rows > 0) {
        ?>
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Gastos do Mês Atual</title>
            <link rel="stylesheet" type="text/css" href="css/style_b.css">
        </head>
        <body>
            <div class="container">
                <h2>Bem-vindo, <?php echo htmlentities($username); ?>!</h2>
                <p>Este é o seu painel de controle.</p>
                <div class="menu">
                    <ul>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="add_expense.php">Adicionar Despesa</a></li>
                        <li><a href="view_expenses.php">Visualizar Despesas</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
                <h2>Gastos do Mês Atual</h2>
                <table border='1'>
                    <tr><th>Data</th><th>Descrição</th><th>Valor</th></tr>
                    <?php while($row_expense = $result_expenses->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($row_expense['date'])); ?></td>
                            <td><?php echo htmlentities($row_expense['description']); ?></td>
                            <td>R$ <?php echo number_format($row_expense['amount'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "<p>Nenhum gasto registrado no mês atual.</p>";
    }
} else {
    echo "<p>Erro ao recuperar o ID do usuário.</p>";
}

$stmt->close();
$conn->close();
?>
