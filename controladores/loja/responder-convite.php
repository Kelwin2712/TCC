<?php
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/../conexao_bd.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success'=>false,'message'=>'Método inválido']); exit; }
$loja_id = isset($_POST['loja_id']) ? (int)$_POST['loja_id'] : 0;
$usuario_id = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
if ($loja_id <= 0 || $usuario_id <= 0 || empty($action)) { echo json_encode(['success'=>false,'message'=>'Dados inválidos']); exit; }

if ($action === 'accept') {
    // ensure the session user is the invitee
    $session_uid = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
    if ($session_uid !== (int)$usuario_id) { echo json_encode(['success'=>false,'message'=>'Ação não autorizada']); exit; }

    // set status to A
    $sql = "UPDATE equipe SET status = 'A', updated_at = NOW() WHERE loja_id = " . (int)$loja_id . " AND usuario_id = " . (int)$usuario_id . " LIMIT 1";
    if (!mysqli_query($conexao, $sql)) { echo json_encode(['success'=>false,'message'=>'Erro ao aceitar convite: '.mysqli_error($conexao)]); exit; }
    if (mysqli_affected_rows($conexao) <= 0) { echo json_encode(['success'=>false,'message'=>'Convite não encontrado']); exit; }

    // fetch loja details to return for inline insertion
    $lres = mysqli_query($conexao, "SELECT id, nome, logo FROM lojas WHERE id = " . (int)$loja_id . " LIMIT 1");
    $loja = null;
    if ($lres && mysqli_num_rows($lres) > 0) {
        $lr = mysqli_fetch_assoc($lres);
        $logo_url = '../img/logo-fahren-bg.jpg';
        $possible = __DIR__ . '/../../img/lojas/' . $lr['id'] . '/';
        if (!empty($lr['logo']) && file_exists($possible . $lr['logo'])) {
            $logo_url = '../img/lojas/' . $lr['id'] . '/' . $lr['logo'];
        }
        $loja = ['id' => (int)$lr['id'], 'nome' => $lr['nome'], 'logo_url' => $logo_url];
    }

    // return remaining pending count for the user
    $cntRes = mysqli_query($conexao, "SELECT COUNT(*) AS c FROM equipe WHERE usuario_id = " . (int)$usuario_id . " AND status = 'P'");
    $pending = 0;
    if ($cntRes && mysqli_num_rows($cntRes) > 0) { $pending = (int)mysqli_fetch_assoc($cntRes)['c']; }

    echo json_encode(['success'=>true,'message'=>'Convite aceito.','loja'=>$loja,'pending_count'=>$pending]); exit;
} elseif ($action === 'decline') {
    // ensure the session user is the invitee
    $session_uid = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
    if ($session_uid !== (int)$usuario_id) { echo json_encode(['success'=>false,'message'=>'Ação não autorizada']); exit; }

    // delete the invite
    $sql = "DELETE FROM equipe WHERE loja_id = " . (int)$loja_id . " AND usuario_id = " . (int)$usuario_id . " LIMIT 1";
    if (!mysqli_query($conexao, $sql)) { echo json_encode(['success'=>false,'message'=>'Erro ao recusar convite: '.mysqli_error($conexao)]); exit; }

    // remaining pending count
    $cntRes = mysqli_query($conexao, "SELECT COUNT(*) AS c FROM equipe WHERE usuario_id = " . (int)$usuario_id . " AND status = 'P'");
    $pending = 0;
    if ($cntRes && mysqli_num_rows($cntRes) > 0) { $pending = (int)mysqli_fetch_assoc($cntRes)['c']; }

    echo json_encode(['success'=>true,'message'=>'Convite recusado.','pending_count'=>$pending]); exit;
} else {
    echo json_encode(['success'=>false,'message'=>'Ação inválida']); exit;
}

?>
