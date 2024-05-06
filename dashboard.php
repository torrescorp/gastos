<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Painel de Controle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style_b.css">
</head>
<body>
    <div class="container">
        <h2>Bem-vindo, <?php echo $_SESSION['username']; ?>!</h2>
        <p>Este é o seu painel de controle.</p>
        <div class="menu">
            <ul class="nav">
                <li class="nav-item"><a class="nav-link" href="view_gastos.php">Visualizar Mês</a></li>
                <li class="nav-item"><a class="nav-link" href="add_expense.php">Adicionar Despesa</a></li>
                <li class="nav-item"><a class="nav-link" href="view_expenses.php">Visualizar Despesas</a></li>
                <li class="nav-item"><a class="nav-link" href="add_category.php">Adicionar Categoria</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
