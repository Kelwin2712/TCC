<?php
session_start();
include('../conexao_bd.php');

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit();
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
action:
$action = isset($_POST['action']) ? $_POST['action'] : 'status';

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit();
}

if ($action === 'status') {
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conexao, $_POST['status']) : 'pendente';
    $sql = "UPDATE reservas SET status = '$status', atualizado_em = NOW() WHERE id = $id";
    if (mysqli_query($conexao, $sql)) {
        $_SESSION['msg_alert'] = ['success', 'Status da reserva atualizado.'];
        echo json_encode(['success' => true, 'message' => 'Status atualizado', 'status' => $status]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conexao)]);
        exit();
    }
} elseif ($action === 'update') {
    // Campos editáveis
    $nome = isset($_POST['nome']) ? mysqli_real_escape_string($conexao, trim($_POST['nome'])) : '';
    // Normalizar telefone: manter apenas dígitos antes de escapar
    $telefone_input = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $telefone_digits = preg_replace('/\D+/', '', $telefone_input);
    $telefone = $telefone_digits !== '' ? mysqli_real_escape_string($conexao, trim($telefone_digits)) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conexao, trim($_POST['email'])) : '';
    $preferencia_contato = isset($_POST['preferencia_contato']) ? mysqli_real_escape_string($conexao, trim($_POST['preferencia_contato'])) : 'telefone';
    $data = isset($_POST['data']) ? mysqli_real_escape_string($conexao, trim($_POST['data'])) : '';
    $hora = isset($_POST['hora']) ? mysqli_real_escape_string($conexao, trim($_POST['hora'])) : '';
    $acompanhantes_qtd = isset($_POST['acompanhantes_qtd']) ? (int) $_POST['acompanhantes_qtd'] : 0;
    $estado = isset($_POST['estado']) ? mysqli_real_escape_string($conexao, trim($_POST['estado'])) : '';
    $cidade = isset($_POST['cidade']) ? mysqli_real_escape_string($conexao, trim($_POST['cidade'])) : '';
    $bairro = isset($_POST['bairro']) ? mysqli_real_escape_string($conexao, trim($_POST['bairro'])) : '';
    $rua = isset($_POST['rua']) ? mysqli_real_escape_string($conexao, trim($_POST['rua'])) : '';
    $numero = isset($_POST['numero']) ? mysqli_real_escape_string($conexao, trim($_POST['numero'])) : '';
    $complemento = isset($_POST['complemento']) ? mysqli_real_escape_string($conexao, trim($_POST['complemento'])) : '';
    $cep = isset($_POST['cep']) ? mysqli_real_escape_string($conexao, trim($_POST['cep'])) : '';
    $observacoes = isset($_POST['observacoes']) ? mysqli_real_escape_string($conexao, trim($_POST['observacoes'])) : '';

    $sql = "UPDATE reservas SET
        nome = '$nome',
        telefone = '$telefone',
        email = '$email',
        preferencia_contato = '$preferencia_contato',
        data = '$data',
        hora = '$hora',
        acompanhantes_qtd = $acompanhantes_qtd,
        estado = '$estado',
        cidade = '$cidade',
        bairro = '$bairro',
        rua = '$rua',
        numero = '$numero',
        complemento = '$complemento',
        cep = '$cep',
        observacoes = '$observacoes',
        atualizado_em = NOW()
        WHERE id = $id";

    if (mysqli_query($conexao, $sql)) {
        $_SESSION['msg_alert'] = ['success', 'Reserva atualizada com sucesso.'];
        echo json_encode(['success' => true, 'message' => 'Reserva atualizada']);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conexao)]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Ação inválida']);
    exit();
}

mysqli_close($conexao);
?>