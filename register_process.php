<?php
include 'db_connection.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Verificar se o nome de usuário já existe
$stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Nome de usuário já existe. Por favor, escolha outro.";
} else {
    // Hash da senha
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Inserir novo usuário no banco de dados
    $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password_hash);

    if ($stmt->execute()) {
        echo "Usuário registrado com sucesso.";
    } else {
        echo "Erro ao registrar usuário: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>
