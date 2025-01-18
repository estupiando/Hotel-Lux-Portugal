<?php
session_start();
require_once 'config.php';

// Verifica se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

// Consulta para pegar todas as reservas
$query = "SELECT reserva.id, quarto.numero_quarto, reserva.data_checkin, reserva.data_checkout, cliente.nome AS cliente_nome, reserva.status 
          FROM reserva 
          INNER JOIN quarto ON reserva.quarto_id = quarto.id 
          INNER JOIN cliente ON reserva.cliente_id = cliente.id";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Reservas - Administrador</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>
    <h1>Gestão de Reservas</h1>
    <nav>
        <ul>
            <li><a href="admin_dashboard.php">Início</a></li>
            <li><a href="reservas_admin.php">Reservas</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Reservas Ativas</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Quarto</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['cliente_nome']) ?></td>
                        <td><?= htmlspecialchars($row['numero_quarto']) ?></td>
                        <td><?= date("d/m/Y", strtotime($row['data_checkin'])) ?></td>
                        <td><?= date("d/m/Y", strtotime($row['data_checkout'])) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <!-- Link para editar ou cancelar a reserva -->
                            <a href="editar_reserva.php?id=<?= $row['id'] ?>">Editar</a> |
                            <a href="cancelar_reserva.php?id=<?= $row['id'] ?>" onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')">Cancelar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma reserva encontrada.</p>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; 2025 Hotel Lux Portugal</p>
</footer>

</body>
</html>