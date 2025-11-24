<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit();
}
include_once __DIR__ . '/../conexao_bd.php';

$uid = (int) $_SESSION['id'];
$estado = isset($_POST['estado_local']) ? trim($_POST['estado_local']) : null;
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : null;

// Normalize estado: null or 2-letter uppercase
if ($estado !== null && $estado !== '') {
    $estado = strtoupper(preg_replace('/[^A-Z]/i', '', $estado));
    if (strlen($estado) !== 2) {
        echo json_encode(['success' => false, 'message' => 'Código do estado inválido.']);
        mysqli_close($conexao);
        exit();
    }
} else {
    $estado = null;
}

// Normalize cidade: null or trimmed string (limit 100 chars)
if ($cidade !== null && $cidade !== '') {
    $cidade = trim($cidade);
    if (mb_strlen($cidade) > 100) $cidade = mb_substr($cidade, 0, 100);
} else {
    $cidade = null;
}

// Build UPDATE sets
$sets = [];
if ($estado === null) {
    $sets[] = "estado_local = NULL";
} else {
    $sets[] = "estado_local = '" . mysqli_real_escape_string($conexao, $estado) . "'";
}
if ($cidade === null) {
    $sets[] = "cidade = NULL";
} else {
    $sets[] = "cidade = '" . mysqli_real_escape_string($conexao, $cidade) . "'";
}

$sql = "UPDATE usuarios SET " . implode(', ', $sets) . " WHERE id = " . $uid . " LIMIT 1";

if (!mysqli_query($conexao, $sql)) {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar localização: ' . mysqli_error($conexao)]);
    mysqli_close($conexao);
    exit();
}

mysqli_close($conexao);
echo json_encode(['success' => true, 'message' => 'Localização salva.']);
exit();

?>
