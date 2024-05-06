<?php
session_start();
include 'db_connection.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Evitar ataques de injeção de SQL usando instruções preparadas
$stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    // Verificar a senha usando a função password_verify
    if (password_verify($password, $user['password_hash'])) {
        // Login bem-sucedido
        $_SESSION['username'] = $username;
        header("Location: dashboard.php"); // Redireciona para a página do painel de controle
    } else {
        // Login falhou
        echo "Nome de usuário ou senha incorretos.";
    }
} else {
    // Login falhou
    echo "Nome de usuário ou senha incorretos.";
}

$stmt->close();
$conn->close();
?>
