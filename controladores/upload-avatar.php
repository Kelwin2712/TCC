<?php
session_start();
include('conexao_bd.php');

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$uid = (int) $_SESSION['id'];

if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Nenhum arquivo enviado']);
    exit;
}

$tmp = $_FILES['avatar']['tmp_name'];
$size = $_FILES['avatar']['size'];

// basic mime check
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $tmp);
finfo_close($finfo);
$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

if (!in_array($mime, $allowed) || $size > 2 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'Arquivo inválido ou maior que 2MB']);
    exit;
}

$target_dir = __DIR__ . '/../img/usuarios/avatares/';
if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

$timestamp = time();
$filename = 'usuario_' . $uid . '_' . $timestamp . '.png';
$dest = $target_dir . $filename;

// remove existing files for this user
$existing = glob($target_dir . 'usuario_' . $uid . '_*.png');
if ($existing && is_array($existing)) {
    foreach ($existing as $ex) {
        if (is_file($ex)) @unlink($ex);
    }
}

$avatar_path = null;
// Try GD conversion if available
if (function_exists('imagecreatefromstring')) {
    $data = @file_get_contents($tmp);
    if ($data !== false) {
        $im = @imagecreatefromstring($data);
        if ($im !== false) {
            if (imagepng($im, $dest, 6)) {
                imagedestroy($im);
                $avatar_path = 'img/usuarios/avatares/' . $filename;
            } else {
                imagedestroy($im);
            }
        }
    }
} else {
    // fallback: accept only PNGs and move
    if ($mime === 'image/png') {
        if (move_uploaded_file($tmp, $dest)) {
            $avatar_path = 'img/usuarios/avatares/' . $filename;
        }
    }
}

if (!$avatar_path) {
    echo json_encode(['success' => false, 'message' => 'Falha ao processar imagem']);
    exit;
}

// update DB
$sql = "UPDATE usuarios SET avatar = '$avatar_path' WHERE id = $uid";
if (!mysqli_query($conexao, $sql)) {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar DB']);
    exit;
}

// update session
$_SESSION['avatar'] = $avatar_path;

$_SESSION['msg_alert'] = ['success', 'Foto de perfil atualizada com sucesso.'];

// Return JSON with a human message so AJAX callers can show instant alerts
echo json_encode(['success' => true, 'avatar' => $avatar_path, 'message' => 'Foto de perfil atualizada com sucesso.']);

mysqli_close($conexao);
exit;

?>
