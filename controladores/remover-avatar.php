<?php
session_start();
include('conexao_bd.php');

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$uid = (int) $_SESSION['id'];

// fetch current avatar path
$res = mysqli_query($conexao, "SELECT avatar FROM usuarios WHERE id = $uid");
if ($res && mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    $avatar = $row['avatar'];
} else {
    $avatar = null;
}

$target_dir = __DIR__ . '/../img/usuarios/avatares/';

// remove files matching usuario_{id}_*.png
$deleted = 0;
if (is_dir($target_dir)) {
    $files = glob($target_dir . 'usuario_' . $uid . '_*.png');
    if ($files && is_array($files)) {
        foreach ($files as $f) {
            if (is_file($f)) {
                if (@unlink($f)) $deleted++;
            }
        }
    }
}

// update DB to NULL avatar
$sql = "UPDATE usuarios SET avatar = NULL WHERE id = $uid";
if (!mysqli_query($conexao, $sql)) {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar DB']);
    exit;
}

// unset session avatar if present
if (isset($_SESSION['avatar'])) unset($_SESSION['avatar']);

$_SESSION['msg_alert'] = ['success', 'Foto de perfil removida com sucesso.'];

// Return JSON with a human message so AJAX callers can show instant alerts
echo json_encode(['success' => true, 'deleted' => $deleted, 'message' => 'Foto de perfil removida com sucesso.']);
mysqli_close($conexao);
exit;

?>
