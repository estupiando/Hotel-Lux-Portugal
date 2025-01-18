<?php
require_once 'config.php'; // Conexão ao banco de dados

if (isset($_GET['id'])) {
    $id_quarto = $_GET['id'];

    // Validação do ID para garantir que é um número
    if (is_numeric($id_quarto)) {
        // Prepara a consulta para excluir o quarto
        $query = "DELETE FROM quarto WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_quarto);

        if ($stmt->execute()) {
            // Redireciona para a lista de quartos com mensagem de sucesso
            $_SESSION['sucesso'] = "Quarto excluído com sucesso!";
            header("Location: quartos.php");
            exit();
        } else {
            // Em caso de erro
            $_SESSION['erro'] = "Erro ao excluir o quarto.";
            header("Location: quartos.php");
            exit();
        }
    } else {
        $_SESSION['erro'] = "ID inválido.";
        header("Location: quartos.php");
        exit();
    }
} else {
    // Caso o ID não seja fornecido
    $_SESSION['erro'] = "Nenhum ID fornecido.";
    header("Location: quartos.php");
    exit();
}
?>