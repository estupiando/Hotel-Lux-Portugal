<?php
session_start();
require_once 'config.php'; // Arquivo de conexão ao banco de dados

// Verifica se o ID do quarto foi passado pela URL
if (isset($_GET['id'])) {
    $id_quarto = $_GET['id'];

    // Valida se o ID é numérico
    if (!is_numeric($id_quarto)) {
        $_SESSION['erro'] = "ID do quarto inválido.";
        header("Location: quartos.php");
        exit();
    }

    // Consulta para buscar os dados do quarto
    $query = "SELECT * FROM quarto WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_quarto);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o quarto existe
    if ($result->num_rows == 0) {
        $_SESSION['erro'] = "Quarto não encontrado.";
        header("Location: quartos.php");
        exit();
    }

    // Obtém os dados do quarto
    $row = $result->fetch_assoc();
} else {
    $_SESSION['erro'] = "ID do quarto não especificado.";
    header("Location: quartos.php");
    exit();
}

// Processa o envio do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $numero_quarto = trim($_POST['numero_quarto']);
    $tipo = trim($_POST['tipo']);
    $preco = $_POST['preco'];
    $status = $_POST['status'];
    $descricao = trim($_POST['descricao']);

    // Valida os dados
    if (empty($numero_quarto) || empty($tipo) || empty($preco)) {
        $_SESSION['erro'] = "Todos os campos obrigatórios precisam ser preenchidos.";
        header("Location: editar_quarto.php?id=" . $id_quarto);
        exit();
    }

    // Atualiza os dados do quarto no banco de dados
    $query = "UPDATE quarto SET numero_quarto = ?, tipo = ?, preco = ?, status = ?, descricao = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssisi", $numero_quarto, $tipo, $preco, $status, $descricao, $id_quarto);

    if ($stmt->execute()) {
        $_SESSION['sucesso'] = "Quarto atualizado com sucesso!";
        header("Location: quartos.php");
    } else {
        $_SESSION['erro'] = "Erro ao atualizar o quarto.";
        header("Location: editar_quarto.php?id=" . $id_quarto);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Quarto - Hotel Lux Portugal</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>Editar Quarto</h1>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="clientes.php">Clientes</a></li>
                <li><a href="quartos.php">Quartos</a></li>
                <li><a href="reservas_admin.php">Reservas</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php if (isset($_SESSION['erro'])): ?>
            <div class="erro">
                <p><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="sucesso">
                <p><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></p>
            </div>
        <?php endif; ?>

        <form action="editar_quarto.php?id=<?= $row['id'] ?>" method="POST">
            <label for="numero_quarto">Número do Quarto:</label>
            <input type="text" id="numero_quarto" name="numero_quarto" value="<?= $row['numero_quarto'] ?>" required>

            <label for="tipo">Tipo:</label>
            <input type="text" id="tipo" name="tipo" value="<?= $row['tipo'] ?>" required>

            <label for="preco">Preço:</label>
            <input type="number" id="preco" name="preco" value="<?= $row['preco'] ?>" step="0.01" required>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="Disponível" <?= ($row['status'] == 'Disponível') ? 'selected' : '' ?>>Disponível</option>
                <option value="Ocupado" <?= ($row['status'] == 'Ocupado') ? 'selected' : '' ?>>Ocupado</option>
                <option value="Reservado" <?= ($row['status'] == 'Reservado') ? 'selected' : '' ?>>Reservado</option>
            </select>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" required><?= $row['descricao'] ?></textarea>

            <button type="submit">Atualizar Quarto</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Hotel Lux Portugal. Todos os direitos reservados.</p>
    </footer>
</body>
</html>