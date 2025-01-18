<?php
session_start();
require_once 'config.php'; // Arquivo de configuração com a conexão ao banco de dados

// Verifica se o usuário está autenticado como cliente
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

// Determina o ID do cliente
$cliente_id = $_SESSION['cliente_id'];

// Consulta para buscar as reservas do cliente
$query_reservas = "SELECT quarto.numero_quarto, reserva.data_checkin, reserva.data_checkout, reserva.status
                   FROM reserva 
                   INNER JOIN quarto ON reserva.quarto_id = quarto.id
                   WHERE reserva.cliente_id = ?";

$stmt_reservas = $conn->prepare($query_reservas);
$stmt_reservas->bind_param("i", $cliente_id);
$stmt_reservas->execute();
$result_reservas = $stmt_reservas->get_result();

// Consulta para buscar os quartos disponíveis
$query_quartos = "SELECT id, numero_quarto, status FROM quarto WHERE status = 'Disponível'";

$stmt_quartos = $conn->prepare($query_quartos);
$stmt_quartos->execute();
$result_quartos = $stmt_quartos->get_result();
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Cliente</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <header>
        <h1>Bem-vindo ao seu Dashboard</h1>
        <nav>
            <ul>
                <li><a href="dashboard_cliente.php">Início</a></li>
                <li><a href="reservas_cliente.php">Minhas Reservas</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Minhas Reservas</h2>
            <?php if ($result_reservas->num_rows > 0): ?>
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
                        <?php
                        // Exibe as reservas do cliente
                        while ($row = $result_reservas->fetch_assoc()) {
                            $numero_quarto = htmlspecialchars($row['numero_quarto']);
                            $data_checkin = date("d/m/Y", strtotime($row['data_checkin']));
                            $data_checkout = date("d/m/Y", strtotime($row['data_checkout']));
                            $status = htmlspecialchars($row['status']);
                            echo "<tr>
                                    <td>{$numero_quarto}</td>
                                    <td>{$data_checkin}</td>
                                    <td>{$data_checkout}</td>
                                    <td>{$status}</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Você não tem reservas ativas no momento.</p>
            <?php endif; ?>
        </section>

        <!-- Seção para fazer uma nova reserva -->
        <section>
            <h2>Faça uma Nova Reserva</h2>
            <form id="reservation-form" method="POST" action="process_reservation.php">
                <label for="quarto">Escolha o Quarto:</label>
                <select id="quarto" name="quarto" required>
                    <option value="">Selecione o Quarto</option>
                    <?php
                    if ($result_quartos->num_rows > 0) {
                        while ($row = $result_quartos->fetch_assoc()) {
                            $quarto_id = $row['id'];
                            $numero_quarto = htmlspecialchars($row['numero_quarto']);
                            echo "<option value='{$quarto_id}'>{$numero_quarto}</option>";
                        }
                    } else {
                        echo "<option value='' disabled>Sem quartos disponíveis</option>";
                    }
                    ?>
                </select><br>

                <label for="checkin">Data de Check-in:</label>
                <input type="date" id="checkin" name="checkin" required><br>

                <label for="checkout">Data de Check-out:</label>
                <input type="date" id="checkout" name="checkout" required><br>

                <input type="submit" value="Reservar">
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Hotel Lux Portugal</p>
    </footer>

</body>
</html>