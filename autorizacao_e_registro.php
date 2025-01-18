<?php
session_start();
require_once 'config.php'; // Arquivo de conexão ao banco de dados

// Função para registrar um novo cliente
function registrar_cliente($nome, $num_identificacao, $email, $contacto, $senha) {
    global $conn;
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    $query = "INSERT INTO cliente (nome, num_identificacao, email, contacto, password_hash) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $nome, $num_identificacao, $email, $contacto, $senha_hash);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Função para login de clientes
function login_cliente($email, $senha) {
    global $conn;

    $query = "SELECT id, password_hash FROM cliente WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password_hash);
        $stmt->fetch();

        if (password_verify($senha, $password_hash)) {
            $_SESSION['cliente_id'] = $id;
            return true;
        }
    }
    return false;
}

// Função para sair
function logout() {
    session_destroy();
    header("Location: login.php");
    exit();
}

?>