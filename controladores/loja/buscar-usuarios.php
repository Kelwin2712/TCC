<?php
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/../conexao_bd.php';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$loja_id = isset($_GET['loja_id']) ? (int)$_GET['loja_id'] : 0;
if (mb_strlen($q, 'UTF-8') < 1) {
    echo json_encode([]);
    exit;
}
$q_esc = mysqli_real_escape_string($conexao, $q);
$sql = "SELECT id, nome, email, avatar FROM usuarios WHERE (nome LIKE '%$q_esc%' OR email LIKE '%$q_esc%') LIMIT 30";
$res = mysqli_query($conexao, $sql);
$out = [];
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $avatar = '../img/usuarios/avatares/user.png';
        if (!empty($row['avatar']) && is_file(__DIR__ . '/../../' . $row['avatar'])) {
            $avatar = '../' . $row['avatar'];
        }
        $out[] = ['id' => (int)$row['id'], 'nome' => $row['nome'], 'email' => $row['email'], 'avatar' => $avatar];
    }
}
echo json_encode($out);
?>