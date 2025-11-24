<?php
session_start();
include('../conexao_bd.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit;
}
$loja_id = isset($_POST['loja_id']) ? (int)$_POST['loja_id'] : 0;
$uid = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
if (!$uid || !$loja_id) {
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
    exit;
}

$allow = false;
// try explicit owner_id
$res_owner = @mysqli_query($conexao, "SELECT owner_id FROM lojas WHERE id = $loja_id LIMIT 1");
if ($res_owner && mysqli_num_rows($res_owner) > 0) {
    $row = mysqli_fetch_assoc($res_owner);
    if (isset($row['owner_id']) && (int)$row['owner_id'] === $uid) $allow = true;
}

// fallback: check equipe creator (convidado_por IS NULL)
if (!$allow) {
    $res_creator = @mysqli_query($conexao, "SELECT usuario_id FROM equipe WHERE loja_id = $loja_id AND status='A' AND convidado_por IS NULL LIMIT 1");
    if ($res_creator && mysqli_num_rows($res_creator) > 0) {
        $r = mysqli_fetch_assoc($res_creator);
        if ((int)$r['usuario_id'] === $uid) $allow = true;
    }
}

if (!$allow) {
    echo json_encode(['success' => false, 'message' => 'Você não tem permissão para excluir esta loja.']);
    exit;
}

// delete DB records
@mysqli_query($conexao, "DELETE FROM equipe WHERE loja_id = $loja_id");
@mysqli_query($conexao, "DELETE FROM lojas WHERE id = $loja_id");

// remove images directory if exists
$base = realpath(__DIR__ . '/../../img/lojas');
$dir = $base ? ($base . '/' . $loja_id) : (__DIR__ . '/../../img/lojas/' . $loja_id);

function rrmdir($dir) {
    if (!is_dir($dir)) return;
    $objects = scandir($dir);
    foreach ($objects as $object) {
        if ($object == '.' || $object == '..') continue;
        $path = $dir . '/' . $object;
        if (is_dir($path)) rrmdir($path); else @unlink($path);
    }
    @rmdir($dir);
}

if (is_dir($dir)) rrmdir($dir);

echo json_encode(['success' => true, 'message' => 'Loja removida com sucesso.']);
exit;
?>