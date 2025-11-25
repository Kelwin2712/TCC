<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['total' => 0]);
    exit;
}

include('../conexao_bd.php');

$usuario = $_SESSION['id'];

// Contar mensagens não lidas direto da tabela mensagens_chat
// Para o usuário que recebe a mensagem (para_usuario = $usuario)
$sql = "SELECT COUNT(*) AS total FROM mensagens_chat 
        WHERE para_usuario = '$usuario' AND lida = 0";
$resultado = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_assoc($resultado);
$total = (int)$dados['total'];

mysqli_close($conexao);

echo json_encode(['total' => $total]);
?>

