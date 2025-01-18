<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <h1>Cadastro de Cliente</h1>
    
    <!-- Área para mensagens de erro ou sucesso -->
    <div id="message-area">
        <?php
        session_start();
        if (isset($_SESSION['erro'])) {
            echo "<p style='color: red;'>{$_SESSION['erro']}</p>";
            unset($_SESSION['erro']);
        }
        if (isset($_SESSION['sucesso'])) {
            echo "<p style='color: green;'>{$_SESSION['sucesso']}</p>";
            unset($_SESSION['sucesso']);
        }
        ?>
    </div>

    <form id="register-form" method="POST" action="process_register.php">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" placeholder="Insira o seu nome" required><br>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" placeholder="exemplo@email.com" required><br>

        <label for="num_identificacao">Número de Identificação:</label>
        <input type="text" id="num_identificacao" name="num_identificacao" placeholder="123456789" pattern="\d{9}" required><br>

        <label for="contacto">Contacto:</label>
        <input type="tel" id="contacto" name="contacto" placeholder="912345678" pattern="\d{9}" required><br>

        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" minlength="6" required><br>

        <label for="confirm-password">Confirmar Senha:</label>
        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirme sua senha" required><br>

        <input type="submit" value="Cadastrar">
    </form>

    <script src="script.js"></script>
</body>
</html>