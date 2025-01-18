<?php
session_start();
require_once 'config.php'; // Arquivo de configuração com a conexão ao banco

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Função para autenticar o administrador
    function loginAdmin($username, $password) {
        global $conn;

        // Consulta SQL para buscar o administrador pelo nome de usuário
        $query = "SELECT id, password_hash FROM admin WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Se o administrador for encontrado
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $password_hash);
            $stmt->fetch();

            // Verifica se a senha corresponde ao hash no banco de dados
            if (password_verify($password, $password_hash)) {
                // Inicia a sessão do administrador
                $_SESSION['admin_id'] = $id;
                return true; // Login bem-sucedido
            }
        }

        return false; // Se não encontrar ou a senha for incorreta
    }

    // Chama a função de login
    if (loginAdmin($username, $password)) {
        // Redireciona para a página de administração
        header("Location: admin_dashboard.php"); // Ajuste o redirecionamento conforme necessário
        exit();
    } else {
        // Exibe mensagem de erro caso o login falhe
        $error_message = "Nome de usuário ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <header>
        <h1>Login Administrador</h1>
    </header>

    <main>
        <form action="login_admin.php" method="POST">
            <label for="username">Nome de usuário:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>

            <?php if (isset($error_message)): ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <button type="submit">Entrar</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Hotel Lux Portugal. Todos os direitos reservados.</p>
    </footer>

</body>
</html>