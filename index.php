<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo ao Hotel Lux Portugal</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Inclua seu arquivo CSS para estilos -->
</head>
<body>

<header>
    <h1>Hotel Lux Portugal</h1>
    <nav>
        <ul>
            <li><a href="index.php">Início</a></li>
            <?php if (isset($_SESSION['cliente_id'])): ?>
                <li><a href="reservas_cliente.php">Minhas Reservas</a></li>
                <li><a href="logout.php">Sair</a></li>
            <?php elseif (isset($_SESSION['admin_id'])): ?>
                <li><a href="admin_dashboard.php">Painel de Administração</a></li>
                <li><a href="logout.php">Sair</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="login_admin.php">Login Administrador</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <h2>Descubra a melhor experiência para sua estadia.</h2>
    <p><a href="reservar.php" class="btn">Faça sua reserva agora!</a></p>
</main>

<footer>
    <p>&copy; 2025 Hotel Lux Portugal. Todos os direitos reservados.</p>
</footer>

</body>
</html>