<?php
session_start();
require_once 'config.php'; // Arquivo de configuração com a conexão ao banco

// Função para contar os clientes ativos
function getActiveClients() {
    global $conn;

    $query = "SELECT COUNT(*) FROM cliente WHERE deleted_at IS NULL"; // Clientes ativos, não deletados
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_row();
        return $row[0]; // Retorna o número de clientes ativos
    } else {
        return 0; // Caso ocorra algum erro
    }
}

// Função para contar os quartos ocupados
function getOccupiedRooms() {
    global $conn;

    $query = "SELECT COUNT(*) FROM quarto WHERE status = 'Ocupado'"; // Quartos com status 'Ocupado'
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_row();
        return $row[0]; // Retorna o número de quartos ocupados
    } else {
        return 0; // Caso ocorra algum erro
    }
}

// Função para contar as reservas pendentes
function getPendingReservations() {
    global $conn;

    $query = "SELECT COUNT(*) FROM reserva WHERE status = 'Ativa'"; // Reservas com status 'Ativa'
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_row();
        return $row[0]; // Retorna o número de reservas pendentes
    } else {
        return 0; // Caso ocorra algum erro
    }
}

// Obtendo as estatísticas
$active_clients = getActiveClients();
$occupied_rooms = getOccupiedRooms();
$pending_reservations = getPendingReservations();

// Retorna as estatísticas em formato JSON
echo json_encode([
    'active_clients' => $active_clients,
    'occupied_rooms' => $occupied_rooms,
    'pending_reservations' => $pending_reservations
]);

?>