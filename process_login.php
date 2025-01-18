<?php
session_start();
require_once 'config.php'; // Arquivo de configuração com a conexão ao banco

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['password'];

    // Função para autenticar o cliente
    function loginCliente($email, $senha) {
        global $conn;

        // Consulta SQL para buscar o cliente pelo email
        $query = "SELECT id, password_hash FROM cliente WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Se o cliente for encontrado
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $password_hash);
            $stmt->fetch();

            // Verifica se a senha corresponde ao hash no banco de dados
            if (password_verify($senha, $password_hash)) {
                // Inicia a sessão do cliente
                $_SESSION['cliente_id'] = $id;
                return true; // Login bem-sucedido
            }
        }

        return false; // Se não encontrar ou a senha for incorreta
    }

    // Chama a função de login
    if (loginCliente($email, $senha)) {
        // Redireciona para a página de reservas
        header("Location: reservas_cliente.php"); // Redireciona para a página de reservas
        exit();
    } else {
        // Exibe mensagem de erro caso o login falhe
        $error_message = "E-mail ou senha incorretos.";
    }
}
?>

<!-- Exibindo o formulário de login com mensagem de erro se necessário -->
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel Lux Portugal</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <h2>Login</h2>

    <form id="login-form" method="POST" action="process_login.php">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Entrar">
    </form>

    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <script src="script.js"></script>  <!-- Incluindo o arquivo script.js para validação -->
</body>
</html>

