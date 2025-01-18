<?php
// Conexão com o banco de dados
require_once 'config.php';

session_start();
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

// Consulta para buscar os quartos disponíveis
$query_quartos = "SELECT id, numero_quarto FROM quarto WHERE status = 'Disponível'";

$stmt_quartos = $conn->prepare($query_quartos);
$stmt_quartos->execute();
$result_quartos = $stmt_quartos->get_result();
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fazer Reserva</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>Fazer Reserva</h1>
        <nav>
            <ul>
                <li><a href="dashboard_cliente.php">Dashboard</a></li>
                <li><a href="reservas_cliente.php">Minhas Reservas</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>Escolha o Quarto e Faça a Sua Reserva</h2>
            
            <?php
            if (isset($_SESSION['erro'])) {
                echo "<div class='alert error'>{$_SESSION['erro']}</div>";
                unset($_SESSION['erro']);
            }
            if (isset($_SESSION['sucesso'])) {
                echo "<div class='alert success'>{$_SESSION['sucesso']}</div>";
                unset($_SESSION['sucesso']);
            }
            ?>
            
            <form action="process_reservation.php" method="POST">
                <label for="quarto_id">Selecione o Quarto:</label>
                <select name="quarto_id" id="quarto_id" required>
                    <option value="">Selecione o Quarto</option>
                    <!-- Opções de quartos disponíveis dinâmicamente -->
                    <?php
                    // Consulta para buscar os quartos disponíveis
                    $query = "SELECT id, numero_quarto FROM quarto WHERE status = 'Disponível'";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>Quarto {$row['numero_quarto']}</option>";
                    }
                    ?>
                </select><br>

                <label for="checkin">Data de Check-in:</label>
                <input type="date" name="checkin" required><br>

                <label for="checkout">Data de Check-out:</label>
                <input type="date" name="checkout" required><br>

                <button type="submit">Confirmar Reserva</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Hotel Lux Portugal</p>
    </footer>
</body>
</html>