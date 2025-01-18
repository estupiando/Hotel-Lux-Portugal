<?php
session_start();
require_once 'config.php';

// Verifica se o administrador está autenticado
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Clientes</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>Gestão de Clientes</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Início</a></li>
                <li><a href="clientes.php">Clientes</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Lista de Clientes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Contacto</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Exemplo de consulta de clientes (modifique conforme necessário)
                $query = "SELECT * FROM cliente";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nome'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['contacto'] . "</td>";
                    echo "<td><a href='editar_cliente.php?id=" . $row['id'] . "'>Editar</a> | <a href='excluir_cliente.php?id=" . $row['id'] . "'>Excluir</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2025 Hotel Lux Portugal</p>
    </footer>
</body>
</html>
