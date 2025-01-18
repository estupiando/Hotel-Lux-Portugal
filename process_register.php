<?php
session_start();
require_once 'config.php'; // Arquivo de configuração com a conexão ao banco

// Função para registrar um novo cliente
function registrar_cliente($nome, $num_identificacao, $email, $contacto, $senha) {
    global $conn;

    // Verifica se já existe um cliente com o mesmo e-mail ou número de identificação
    $query = "SELECT COUNT(*) FROM cliente WHERE email = ? OR num_identificacao = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $num_identificacao);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    
    // Libere os resultados da consulta anterior
    $stmt->free_result(); // Libera o resultado da consulta SELECT COUNT(*)

    if ($count > 0) {
        return "E-mail ou número de identificação já cadastrado."; // Retorna mensagem de erro
    }

    // Criptografa a senha
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    // Insere o novo cliente no banco de dados
    $query = "INSERT INTO cliente (nome, num_identificacao, email, contacto, password_hash) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $nome, $num_identificacao, $email, $contacto, $senha_hash);

    if ($stmt->execute()) {
        return true; // Sucesso
    } else {
        return "Erro ao registrar cliente."; // Retorna mensagem de erro
    }
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $nome = trim($_POST['nome']);
    $num_identificacao = trim($_POST['num_identificacao']);
    $email = trim($_POST['email']);
    $contacto = trim($_POST['contacto']);
    $senha = $_POST['password']; // A senha original do usuário
    $confirmar_senha = $_POST['confirm-password']; // A senha confirmada

    // Valida os dados
    if (empty($nome) || empty($num_identificacao) || empty($email) || empty($contacto) || empty($senha) || empty($confirmar_senha)) {
        $_SESSION['erro'] = "Todos os campos são obrigatórios.";
        header("Location: registo.php");
        exit();
    }

    if ($senha !== $confirmar_senha) {
        $_SESSION['erro'] = "As senhas não coincidem.";
        header("Location: registo.php");
        exit();
    }

    // Chama a função para registrar o cliente
    $resultado = registrar_cliente($nome, $num_identificacao, $email, $contacto, $senha);

    if ($resultado === true) {
        // Sucesso, redireciona para a página de login
        $_SESSION['sucesso'] = "Cliente registrado com sucesso! Faça o login.";
        header("Location: login.php");
    } else {
        // Falha, retorna a mensagem de erro
        $_SESSION['erro'] = $resultado;
        header("Location: registo.php");
    }
    exit();
}
?>