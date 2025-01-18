<?php
session_start();
require_once 'config.php'; // Arquivo de configuração com a conexão ao banco

// Verifica se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php"); // Redireciona para a página de login se não estiver logado
    exit();
}

// Funções para obter estatísticas
function getActiveClients() {
    global $conn;
    $query = "SELECT COUNT(*) FROM cliente WHERE created_at IS NULL"; // Exemplo de soft delete
    $result = $conn->query($query);
    $row = $result->fetch_row();
    return $row[0];
}

function getOccupiedRooms() {
    global $conn;
    $query = "SELECT COUNT(*) FROM quarto WHERE status = 'Ocupado'";
    $result = $conn->query($query);
    $row = $result->fetch_row();
    return $row[0];
}

function getPendingReservations() {
    global $conn;
    $query = "SELECT COUNT(*) FROM reserva WHERE status = 'Ativa'";
    $result = $conn->query($query);
    $row = $result->fetch_row();
    return $row[0];
}

// Obtendo as estatísticas
$active_clients = getActiveClients();
$occupied_rooms = getOccupiedRooms();
$pending_reservations = getPendingReservations();

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <h1>Painel de Administração - Hotel Lux Portugal</h1>
    <nav>
        <ul>
            <li><a href="admin_dashboard.php">Início</a></li>
            <li><a href="clientes.php">Gerenciar Clientes</a></li>
            <li><a href="quartos.php">Gerenciar Quartos</a></li>
            <li><a href="reservas_admin.php">Gerenciar Reservas</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
</header>

<main>
    <section id="overview">
        <h2>Visão Geral do Hotel</h2>
        <p>Bem-vindo, Administrador! Aqui você pode acompanhar as estatísticas do hotel e gerenciar as operações.</p>
    </section>

    <section id="statistics">
        <h3>Estatísticas do Hotel</h3>
        <ul>
            <li><strong>Clientes Ativos:</strong> <?php echo $active_clients; ?></li>
            <li><strong>Quartos Ocupados:</strong> <?php echo $occupied_rooms; ?></li>
            <li><strong>Reservas Pendentes:</strong> <?php echo $pending_reservations; ?></li>
        </ul>
    </section>

    <section id="actions">
        <a href="clientes.php" class="button">Gerenciar Clientes</a>
        <a href="quartos.php" class="button">Gerenciar Quartos</a>
        <a href="reservas_admin.php" class="button">Gerenciar Reservas</a>
    </section>
</main>

<footer>
    <p>&copy; 2025 Hotel Lux Portugal. Todos os direitos reservados.</p>
</footer>

</body>
</html>