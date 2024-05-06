<?php
include 'db_connection.php'; // Certifique-se de incluir o arquivo de conexão com o banco de dados

// Função para gerar uma data aleatória dentro de um intervalo de tempo especificado
function randomDate($startDate, $endDate) {
    $min = strtotime($startDate);
    $max = strtotime($endDate);
    $randomDate = mt_rand($min, $max);
    return date('Y-m-d', $randomDate);
}

// Função para gerar uma descrição aleatória
function randomDescription() {
    $descriptions = array(
        "Aluguel",
        "Supermercado",
        "Restaurante",
        "Transporte",
        "Conta de Luz",
        "Conta de Água",
        "Gás",
        "Internet",
        "Telefone",
        "Lazer"
    );
    return $descriptions[array_rand($descriptions)];
}

// Função para gerar um valor aleatório entre $min e $max com duas casas decimais
function randomAmount($min, $max) {
    return number_format(mt_rand($min * 100, $max * 100) / 100, 2);
}

// Obter IDs das categorias existentes no banco de dados
$sql_categories = "SELECT id FROM categories";
$result_categories = $conn->query($sql_categories);
$categories = array();
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row['id'];
    }
}

// Obter IDs dos usuários existentes no banco de dados
$sql_users = "SELECT id FROM users";
$result_users = $conn->query($sql_users);
$users = array();
if ($result_users->num_rows > 0) {
    while ($row = $result_users->fetch_assoc()) {
        $users[] = $row['id'];
    }
}

// Gerar despesas de teste
$numExpenses = 20; // Número de despesas a serem geradas

for ($i = 0; $i < $numExpenses; $i++) {
    $date = randomDate("2020-01-01", "2030-12-31"); // Intervalo de datas para o ano de 2022
    $description = randomDescription();
    $amount = randomAmount(10, 200); // Valor mínimo: $10, Valor máximo: $200
    $category_id = $categories[array_rand($categories)]; // Selecionar uma categoria aleatória
    $user_id = $users[array_rand($users)]; // Selecionar um usuário aleatório

    // Inserir a despesa gerada no banco de dados
    $sql = "INSERT INTO expenses (date, description, amount, category_id, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdii", $date, $description, $amount, $category_id, $user_id);
    $stmt->execute();
}

echo "Despesas geradas com sucesso!";
?>
