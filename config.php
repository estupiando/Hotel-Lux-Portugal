<?php
// Arquivo de configuração do banco de dados

$host = 'localhost';  // Ou o IP do seu servidor de banco de dados
$dbname = 'hotel_management';
$username = 'root';  // Seu nome de usuário do MySQL
$password = '';  // Sua senha do MySQL

// Conexão ao banco de dados
$conn = new mysqli($host, $username, $password, $dbname);

// Verificando se houve erro na conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>