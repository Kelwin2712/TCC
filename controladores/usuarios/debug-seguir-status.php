<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/../conexao_bd.php';

$user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
$seguido_id = isset($_GET['seguido']) ? (int)$_GET['seguido'] : 0;

$response = [
    'user_id' => $user_id,
    'seguido_id' => $seguido_id,
    'seguindo' => 0,
    'usuario_seguidores' => null,
    'loja_seguidores' => null,
    'debug' => []
];

if (!$user_id || !$seguido_id) {
    $response['error'] = 'IDs inválidos';
    echo json_encode($response);
    exit;
}

// Criar tabela
$create = "CREATE TABLE IF NOT EXISTS seguidores (
  id INT NOT NULL AUTO_INCREMENT,
  seguidor_id INT NOT NULL,
  seguido_id INT NOT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_seg (seguidor_id, seguido_id),
  KEY idx_seguido (seguido_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
mysqli_query($conexao, $create);

// Verificar relação
$check = mysqli_query($conexao, "SELECT id FROM seguidores WHERE seguidor_id = $user_id AND seguido_id = $seguido_id LIMIT 1");
if ($check && mysqli_num_rows($check) > 0) {
    $response['seguindo'] = 1;
    $response['debug'][] = "Encontrou relação em seguidores";
}

// Verificar se é usuário ou loja
$check_user = mysqli_query($conexao, "SELECT seguidores FROM usuarios WHERE id = $seguido_id LIMIT 1");
if ($check_user && mysqli_num_rows($check_user) > 0) {
    $row = mysqli_fetch_assoc($check_user);
    $response['usuario_seguidores'] = (int)$row['seguidores'];
    $response['debug'][] = "É um usuário com {$row['seguidores']} seguidores";
}

$check_loja = mysqli_query($conexao, "SELECT seguidores FROM lojas WHERE id = $seguido_id LIMIT 1");
if ($check_loja && mysqli_num_rows($check_loja) > 0) {
    $row = mysqli_fetch_assoc($check_loja);
    $response['loja_seguidores'] = (int)$row['seguidores'];
    $response['debug'][] = "É uma loja com {$row['seguidores']} seguidores";
}

echo json_encode($response, JSON_PRETTY_PRINT);
mysqli_close($conexao);
?>
