<?php
session_start();
session_unset(); // Limpa todos os dados da sessão
session_destroy(); // Finaliza a sessão
header("Location: login.php"); // Redireciona de volta para a página de login
exit();
?>