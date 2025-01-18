<?php
session_start();
require_once 'config.php';

// Verifica se o cliente está autenticado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

// Recebe os dados do formulário
$quarto_id = $_POST['quarto_id'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$cliente_id = $_SESSION['cliente_id'];

// Verifica se as datas são válidas
if (strtotime($checkin) >= strtotime($checkout)) {
    $_SESSION['erro'] = "A data de check-out deve ser posterior à data de check-in.";
    header("Location: reservar.php");
    exit();
}

// Verifica a disponibilidade do quarto para as datas selecionadas
$query = "SELECT id FROM reserva WHERE quarto_id = ? AND 
          ((data_checkin <= ? AND data_checkout >= ?) OR 
           (data_checkin <= ? AND data_checkout >= ?))";
$stmt = $conn->prepare($query);
$stmt->bind_param("issss", $quarto_id, $checkin, $checkin, $checkout, $checkout);
$stmt->execute();
$stmt->store_result();

// Se o quarto já estiver reservado, redireciona com erro
if ($stmt->num_rows > 0) {
    $_SESSION['erro'] = "Este quarto já está reservado para as datas selecionadas.";
    header("Location: reservar.php");
    exit();
}

// Insere a reserva no banco de dados
$query = "INSERT INTO reserva (cliente_id, quarto_id, data_checkin, data_checkout) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiss", $cliente_id, $quarto_id, $checkin, $checkout);
$stmt->execute();

// Atualiza o status do quarto para 'Reservado'
$query = "UPDATE quarto SET status = 'Reservado' WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $quarto_id);
$stmt->execute();

// Redireciona para a página de confirmação ou para as reservas do cliente
$_SESSION['sucesso'] = "Reserva confirmada com sucesso!";
header("Location: reservas_cliente.php");
exit();
?>