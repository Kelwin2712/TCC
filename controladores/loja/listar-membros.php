<?php
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/../conexao_bd.php';
$loja_id = isset($_GET['loja_id']) ? (int)$_GET['loja_id'] : 0;
if ($loja_id <= 0) { echo json_encode([]); exit; }
// ensure equipe table exists
mysqli_query($conexao, "CREATE TABLE IF NOT EXISTS equipe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    loja_id INT NOT NULL,
    usuario_id INT NOT NULL,
    convidado_por INT DEFAULT NULL,
    status CHAR(1) DEFAULT 'P',
    pode_editar_anuncio TINYINT(1) DEFAULT 0,
    pode_responder_mensagem TINYINT(1) DEFAULT 0,
    pode_editar_loja TINYINT(1) DEFAULT 0,
    pode_adicionar_membros TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY(loja_id, usuario_id)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
);

$sql = "SELECT e.usuario_id AS id, u.nome, u.email, u.avatar, e.pode_editar_anuncio, e.pode_responder_mensagem, e.pode_editar_loja, e.pode_adicionar_membros, e.status, e.convidado_por
FROM equipe e
LEFT JOIN usuarios u ON u.id = e.usuario_id
WHERE e.loja_id = " . (int)$loja_id . " ORDER BY e.status ASC, u.nome ASC";
$res = mysqli_query($conexao, $sql);
$out = [];
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $avatar = '../img/usuarios/avatares/user.png';
        if (!empty($row['avatar']) && is_file(__DIR__ . '/../../' . $row['avatar'])) {
            $avatar = '../' . $row['avatar'];
        }
        $inviter_name = null;
        if (!empty($row['convidado_por'])) {
            $q = mysqli_query($conexao, "SELECT nome FROM usuarios WHERE id = " . (int)$row['convidado_por'] . " LIMIT 1");
            if ($q && mysqli_num_rows($q)) { $r2 = mysqli_fetch_assoc($q); $inviter_name = $r2['nome']; }
        }
        $out[] = [
            'id' => (int)$row['id'],
            'nome' => $row['nome'],
            'email' => $row['email'],
            'avatar' => $avatar,
            'pode_editar_anuncio' => (int)$row['pode_editar_anuncio'],
            'pode_responder_mensagem' => (int)$row['pode_responder_mensagem'],
            'pode_editar_loja' => (int)$row['pode_editar_loja'],
            'pode_adicionar_membros' => (int)$row['pode_adicionar_membros'],
            'status' => $row['status'],
            'convidado_por_nome' => $inviter_name
        ];
    }
}

echo json_encode($out);
?>