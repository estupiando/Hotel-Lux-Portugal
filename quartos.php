<?php
session_start();
require_once 'config.php';

// Verificar se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

// Consultar todos os quartos (removido o filtro de status para teste)
$query = "SELECT * FROM quarto";  // Sem filtro de status
$result = $conn->query($query);

// Verificar se houve erro na consulta
if (!$result) {
    die('Erro na consulta: ' . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Quartos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>Gestão de Quartos</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Início</a></li>
                <li><a href="quartos.php">Quartos</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Tipo</th>
                    <th>Preço</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['numero_quarto']}</td>
                                <td>{$row['tipo']}</td>
                                <td>€{$row['preco']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                    <a href='editar_quarto.php?id={$row['id']}'>Editar</a> | 
                                    <a href='excluir_quarto.php?id={$row['id']}' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Excluir</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Nenhum quarto encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2025 Hotel Lux Portugal. Todos os direitos reservados.</p>
    </footer>
</body>
</html>