<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gastos Registrados</title>
    <link rel="stylesheet" type="text/css" href="css/style_b.css">
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

        <?php
        // Define o local para português
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');

        // Obter o ID do usuário atual
        $username = $_SESSION['username'];
        $user_id_query = "SELECT id FROM users WHERE username = '$username'";
        $user_id_result = $conn->query($user_id_query);

        if ($user_id_result->num_rows > 0) {
            $row = $user_id_result->fetch_assoc();
            $user_id = $row['id'];

            // Consulta para obter os gastos agrupados por categoria e mês
            $sql_category = "SELECT categories.name, MONTH(expenses.date) AS month, YEAR(expenses.date) AS year, SUM(expenses.amount) AS total_amount 
                             FROM expenses 
                             INNER JOIN categories ON expenses.category_id = categories.id 
                             WHERE expenses.user_id = $user_id
                             GROUP BY categories.name, YEAR(expenses.date), MONTH(expenses.date)
                             ORDER BY YEAR(expenses.date) ASC, MONTH(expenses.date) ASC"; // Ordena por ano crescente e mês crescente
            $result_category = $conn->query($sql_category);

            if ($result_category->num_rows > 0) {
                echo "<h2>Gastos por Categoria</h2>";
                echo "<table border='1'>";
                echo "<tr><th>Categoria</th><th>Mês</th><th>Ano</th><th>Total</th></tr>";
                while($row_category = $result_category->fetch_assoc()) {
                    $category_name = $row_category['name'];
                    $month = $row_category['month'];
                    $year = $row_category['year'];
                    $total_amount = $row_category['total_amount'];
                    echo "<tr><td>".$category_name."</td><td>".strftime('%B', mktime(0, 0, 0, $month, 1))."</td><td>".$year."</td><td>".$total_amount."</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Nenhum gasto registrado por este usuário.</p>";
            }

            // Consulta para obter os gastos agrupados por mês
            $sql_monthly = "SELECT MONTH(date) AS month, YEAR(date) AS year, SUM(amount) AS total_amount 
                            FROM expenses 
                            WHERE user_id = $user_id
                            GROUP BY YEAR(date), MONTH(date)
                            ORDER BY YEAR(date) ASC, MONTH(date) ASC"; // Ordena por ano crescente e mês crescente
            $result_monthly = $conn->query($sql_monthly);

            if ($result_monthly->num_rows > 0) {
                echo "<h2>Gastos por Mês</h2>";
                echo "<table border='1'>";
                echo "<tr><th>Mês</th><th>Ano</th><th>Total</th></tr>";
                while($row_monthly = $result_monthly->fetch_assoc()) {
                    $month = $row_monthly['month'];
                    $year = $row_monthly['year'];
                    $total_amount = $row_monthly['total_amount'];
                    echo "<tr><td>".strftime('%B', mktime(0, 0, 0, $month, 1))."</td><td>".$year."</td><td>".$total_amount."</td></tr>";
                }
                echo "</table>";
                
            } else {
                echo "<p>Nenhum gasto registrado por este usuário.</p>";
            }
        } else {
            echo "<p>Erro ao recuperar o ID do usuário.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
