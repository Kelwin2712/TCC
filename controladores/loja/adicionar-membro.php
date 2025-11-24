<?php
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/../conexao_bd.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success'=>false,'message'=>'Método inválido']); exit; }
$loja_id = isset($_POST['loja_id']) ? (int)$_POST['loja_id'] : 0;
$usuario_id = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : 0;
$inviter_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
if ($loja_id <= 0 || $usuario_id <= 0) { echo json_encode(['success'=>false,'message'=>'Dados inválidos']); exit; }
// ensure table equipe exists
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

$p1 = isset($_POST['pode_editar_anuncio']) && $_POST['pode_editar_anuncio'] ? 1 : 0;
$p2 = isset($_POST['pode_responder_mensagem']) && $_POST['pode_responder_mensagem'] ? 1 : 0;
$p3 = isset($_POST['pode_editar_loja']) && $_POST['pode_editar_loja'] ? 1 : 0;
$p4 = isset($_POST['pode_adicionar_membros']) && $_POST['pode_adicionar_membros'] ? 1 : 0;
// detect which permission fields were explicitly provided (for toggle updates)
$providedPerms = [];
foreach (['pode_editar_anuncio','pode_responder_mensagem','pode_editar_loja','pode_adicionar_membros'] as $f) {
    if (array_key_exists($f, $_POST)) $providedPerms[] = $f;
}

// check existing
$check = mysqli_query($conexao, "SELECT id, status FROM equipe WHERE loja_id = " . (int)$loja_id . " AND usuario_id = " . (int)$usuario_id . " LIMIT 1");
if ($check && mysqli_num_rows($check) > 0) {
    $row = mysqli_fetch_assoc($check);
    $st = $row['status'];
    // If caller is trying to update permissions (toggle switches), allow updating existing row regardless of status
    if (count($providedPerms) > 0) {
        $sets = [];
        if (in_array('pode_editar_anuncio', $providedPerms)) $sets[] = "pode_editar_anuncio=$p1";
        if (in_array('pode_responder_mensagem', $providedPerms)) $sets[] = "pode_responder_mensagem=$p2";
        if (in_array('pode_editar_loja', $providedPerms)) $sets[] = "pode_editar_loja=$p3";
        if (in_array('pode_adicionar_membros', $providedPerms)) $sets[] = "pode_adicionar_membros=$p4";
        if (!empty($sets)) {
            $sql = "UPDATE equipe SET " . implode(',', $sets) . ", updated_at = NOW() WHERE id = " . (int)$row['id'];
            if (!mysqli_query($conexao, $sql)) { echo json_encode(['success'=>false,'message'=>'Erro ao atualizar permissões: '.mysqli_error($conexao)]); exit; }
            echo json_encode(['success'=>true,'message'=>'Permissões atualizadas.']); exit;
        }
    }

    if ($st === 'P') { echo json_encode(['success'=>false,'message'=>'Convite já pendente.']); exit; }
    if ($st === 'A') { echo json_encode(['success'=>false,'message'=>'Usuário já é membro ativo.']); exit; }
    // if 'R' or other, re-invite: update row
    $sql = "UPDATE equipe SET status='P', convidado_por=" . (int)$inviter_id . ", pode_editar_anuncio=$p1, pode_responder_mensagem=$p2, pode_editar_loja=$p3, pode_adicionar_membros=$p4, updated_at = NOW() WHERE id = " . (int)$row['id'];
    if (!mysqli_query($conexao, $sql)) { echo json_encode(['success'=>false,'message'=>'Erro ao re-enviar convite: '.mysqli_error($conexao)]); exit; }
    echo json_encode(['success'=>true,'message'=>'Convite reenviado.']); exit;
}

// insert new pending invite
    $sql = "INSERT INTO equipe (loja_id, usuario_id, convidado_por, status, pode_editar_anuncio, pode_responder_mensagem, pode_editar_loja, pode_adicionar_membros) VALUES (".
    (int)$loja_id . "," . (int)$usuario_id . "," . (int)$inviter_id . " ,'P'," . $p1 . "," . $p2 . "," . $p3 . "," . $p4 . ")";
if (!mysqli_query($conexao, $sql)) { echo json_encode(['success'=>false,'message'=>'Erro ao criar convite: '.mysqli_error($conexao)]); exit; }
echo json_encode(['success'=>true,'message'=>'Convite enviado.']);
?>