<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit();
}
include_once __DIR__ . '/../conexao_bd.php';

$seguidor = (int) $_SESSION['id'];
$seguido = isset($_POST['seguido']) ? (int) $_POST['seguido'] : 0;

if ($seguido <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido.']);
    exit();
}

if ($seguidor === $seguido) {
    echo json_encode(['success' => false, 'message' => 'Você não pode seguir a si mesmo.']);
    exit();
}

// Ensure seguidores table exists
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

// Check if relationship exists
$check = mysqli_query($conexao, "SELECT id FROM seguidores WHERE seguidor_id = $seguidor AND seguido_id = $seguido LIMIT 1");
if ($check && mysqli_num_rows($check) > 0) {
    // currently following -> unfollow
    $row = mysqli_fetch_assoc($check);
    $del = mysqli_query($conexao, "DELETE FROM seguidores WHERE id = " . (int)$row['id'] . " LIMIT 1");
    if ($del) {
        // decrement seguidores count (safely). Determine whether seguido is a user or a loja.
        $isLoja = false;
        $rq = mysqli_query($conexao, "SELECT id FROM lojas WHERE id = $seguido LIMIT 1");
        if ($rq && mysqli_num_rows($rq) > 0) $isLoja = true;
        if ($isLoja) {
            mysqli_query($conexao, "UPDATE lojas SET seguidores = GREATEST(0, COALESCE(seguidores,0) - 1) WHERE id = $seguido LIMIT 1");
            $res = mysqli_query($conexao, "SELECT COALESCE(seguidores,0) as s FROM lojas WHERE id = $seguido LIMIT 1");
        } else {
            mysqli_query($conexao, "UPDATE usuarios SET seguidores = GREATEST(0, COALESCE(seguidores,0) - 1) WHERE id = $seguido LIMIT 1");
            $res = mysqli_query($conexao, "SELECT COALESCE(seguidores,0) as s FROM usuarios WHERE id = $seguido LIMIT 1");
        }
        $count = ($res && mysqli_num_rows($res)) ? (int) mysqli_fetch_assoc($res)['s'] : 0;
        echo json_encode(['success' => true, 'action' => 'unfollow', 'seguidores' => $count]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao deixar de seguir.']);
        exit();
    }
} else {
    // follow
    $ins = mysqli_query($conexao, "INSERT INTO seguidores (seguidor_id, seguido_id) VALUES ($seguidor, $seguido)");
    if ($ins) {
        // If seguido corresponds to a loja id, update lojas.seguidores, else update usuarios.seguidores
        $isLoja = false;
        $rq = mysqli_query($conexao, "SELECT id FROM lojas WHERE id = $seguido LIMIT 1");
        if ($rq && mysqli_num_rows($rq) > 0) $isLoja = true;
        if ($isLoja) {
            mysqli_query($conexao, "UPDATE lojas SET seguidores = COALESCE(seguidores,0) + 1 WHERE id = $seguido LIMIT 1");
            $res = mysqli_query($conexao, "SELECT COALESCE(seguidores,0) as s FROM lojas WHERE id = $seguido LIMIT 1");
        } else {
            mysqli_query($conexao, "UPDATE usuarios SET seguidores = COALESCE(seguidores,0) + 1 WHERE id = $seguido LIMIT 1");
            $res = mysqli_query($conexao, "SELECT COALESCE(seguidores,0) as s FROM usuarios WHERE id = $seguido LIMIT 1");
        }
        $count = ($res && mysqli_num_rows($res)) ? (int) mysqli_fetch_assoc($res)['s'] : 0;
        echo json_encode(['success' => true, 'action' => 'follow', 'seguidores' => $count]);
        exit();
    } else {
        // possible duplicate key race etc
        echo json_encode(['success' => false, 'message' => 'Erro ao seguir.']);
        exit();
    }
}

?>
