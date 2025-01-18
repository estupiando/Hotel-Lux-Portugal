<?php
session_start();
require_once 'config.php';

// Verifica se o usuário está autenticado como cliente
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

// Determina o ID do cliente
$cliente_id = $_SESSION['cliente_id'];

// Consulta para buscar as reservas do cliente
$query = "SELECT quarto.numero_quarto, reserva.data_checkin, reserva.data_checkout, reserva.status
          FROM reserva 
          INNER JOIN quarto ON reserva.quarto_id = quarto.id
          WHERE reserva.cliente_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Reservas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <h1>Minhas Reservas</h1>
    <nav>
        <ul>
            <li><a href="dashboard_cliente.php">Início</a></li>
            <li><a href="reservas_cliente.php">Minhas Reservas</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Minhas Reservas</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Quarto</th>
                    <th>Data de Check-in</th>
                    <th>Data de Check-out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['numero_quarto']) ?></td>
                        <td><?= date("d/m/Y", strtotime($row['data_checkin'])) ?></td>
                        <td><?= date("d/m/Y", strtotime($row['data_checkout'])) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Você não tem reservas ativas no momento.</p>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; 2025 Hotel Lux Portugal</p>
</footer>

</body>
</html>