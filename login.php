<?php
session_start();
require_once 'config.php'; // Conexão com o banco de dados

// Verifica se o formulário de login foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['password'];

    // Valida se os campos não estão vazios
    if (empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Por favor, preencha todos os campos.";
        header("Location: login.php");
        exit();
    }

    // Verifica as credenciais no banco de dados
    $query = "SELECT id, nome, email, password_hash FROM cliente WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($cliente_id, $nome, $email_db, $password_hash);
        $stmt->fetch();

        // Verifica se a senha está correta
        if (password_verify($senha, $password_hash)) {
            // Sessão autenticada, redireciona para a página de reservas ou dashboard
            $_SESSION['cliente_id'] = $cliente_id;
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email_db;
            header("Location: dashboard_cliente.php"); // ou outra página de destino
            exit();
        } else {
            $_SESSION['erro'] = "E-mail ou senha inválidos.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['erro'] = "E-mail ou senha inválidos.";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel Lux Portugal</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>Hotel Lux Portugal</h1>
    </header>
    
    <main>
        <h2>Login de Cliente</h2>
        <form id="login-form" method="POST" action="process_login.php">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required><br>

            <!-- Mensagem de erro dinâmica (opcional, para feedback) -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid'): ?>
                <p style="color: red;">E-mail ou senha incorretos. Tente novamente.</p>
            <?php endif; ?>

            <input type="submit" value="Entrar">
        </form>
        <p><a href="registo.php">Criar uma conta</a> | <a href="login_admin.php">Login de Administrador</a></p>
    </main>

    <footer>
        <p>&copy; 2025 Hotel Lux Portugal. Todos os direitos reservados.</p>
    </footer>

    <script src="script.js"></script> <!-- Validação opcional de cliente -->
</body>
</html>