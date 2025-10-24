<?php
session_start();
include('../conexao_bd.php');

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit();
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit();
}

$sql = "DELETE FROM reservas WHERE id = $id";
if (mysqli_query($conexao, $sql)) {
    $_SESSION['msg_alert'] = ['success', 'Reserva excluída.'];
    echo json_encode(['success' => true, 'message' => 'Reserva excluída']);
    exit();
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conexao)]);
    exit();
}

mysqli_close($conexao);
?>