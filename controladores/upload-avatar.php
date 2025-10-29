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

// detect mime more robustly (getimagesize preferred for images)
$imgInfo = @getimagesize($tmp);
if ($imgInfo && isset($imgInfo['mime'])) {
    $mime = $imgInfo['mime'];
} else {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmp);
    finfo_close($finfo);
}

// accept common jpeg synonyms and typical image types
$allowed = ['image/jpeg', 'image/pjpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
// allow up to 5MB for camera photos (resize later)
$maxSize = 5 * 1024 * 1024;
if (!in_array(strtolower($mime), $allowed) || $size > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'Arquivo inválido ou maior que 5MB']);
    exit;
}

$target_dir = __DIR__ . '/../img/usuarios/avatares/';
if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

$timestamp = time();
$baseName = 'usuario_' . $uid . '_' . $timestamp;

// remove existing files for this user (any extension)
$existing = glob($target_dir . 'usuario_' . $uid . '_*.*');
if ($existing && is_array($existing)) {
    foreach ($existing as $ex) {
        if (is_file($ex)) @unlink($ex);
    }
}
// map mime to extension
$ext_map = [
    'image/jpeg' => 'jpg',
    'image/pjpeg' => 'jpg',
    'image/jpg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp'
];
$orig_ext = isset($ext_map[strtolower($mime)]) ? $ext_map[strtolower($mime)] : 'png';

$avatar_path = null;
// default destination (if we convert to PNG)
$filename = $baseName . '.png';
$dest = $target_dir . $filename;
// Try GD conversion with several fallbacks for better JPG/WebP support
// try to build image resource
$im = false;
// Preferred: imagecreatefromstring which accepts many formats
if (function_exists('imagecreatefromstring')) {
    $data = @file_get_contents($tmp);
    if ($data !== false) {
        $im = @imagecreatefromstring($data);
    }
}

// Fallback to format-specific creators if string method failed
if ($im === false) {
    switch ($mime) {
        case 'image/jpeg':
            if (function_exists('imagecreatefromjpeg')) $im = @imagecreatefromjpeg($tmp);
            break;
        case 'image/png':
            if (function_exists('imagecreatefrompng')) $im = @imagecreatefrompng($tmp);
            break;
        case 'image/gif':
            if (function_exists('imagecreatefromgif')) $im = @imagecreatefromgif($tmp);
            break;
        case 'image/webp':
            if (function_exists('imagecreatefromwebp')) $im = @imagecreatefromwebp($tmp);
            break;
    }
}

// If we have an image resource, save as PNG
if ($im !== false) {
    // fix EXIF orientation for JPEGs if present
    if (stripos($mime, 'jpeg') !== false || stripos($mime, 'jpg') !== false) {
        if (function_exists('exif_read_data')) {
            try {
                $exif = @exif_read_data($tmp);
                if (!empty($exif['Orientation'])) {
                    $orient = $exif['Orientation'];
                    switch ($orient) {
                        case 3: $im = imagerotate($im, 180, 0); break;
                        case 6: $im = imagerotate($im, -90, 0); break;
                        case 8: $im = imagerotate($im, 90, 0); break;
                    }
                }
            } catch (Exception $e) {
                // ignore EXIF parse errors
            }
        }
    }
    // optional: limit size to avoid memory issues (resize if too large)
    $maxDim = 2500; // pixels
    $w = imagesx($im);
    $h = imagesy($im);
    if ($w > $maxDim || $h > $maxDim) {
        $ratio = $w / $h;
        if ($w > $h) {
            $nw = $maxDim;
            $nh = (int) round($maxDim / $ratio);
        } else {
            $nh = $maxDim;
            $nw = (int) round($maxDim * $ratio);
        }
        $tmpImg = imagecreatetruecolor($nw, $nh);
        // preserve transparency for PNG/GIF/WebP
        imagealphablending($tmpImg, false);
        imagesavealpha($tmpImg, true);
        imagecopyresampled($tmpImg, $im, 0, 0, 0, 0, $nw, $nh, $w, $h);
        imagedestroy($im);
        $im = $tmpImg;
    }

    if (imagepng($im, $dest, 6)) {
        imagedestroy($im);
        $avatar_path = 'img/usuarios/avatares/' . $filename;
    } else {
        imagedestroy($im);
    }
} else {
    // If we couldn't build an image resource (likely GD missing or unsupported),
    // try to accept the original uploaded file as-is (preserving extension).
    $fallbackFilename = $baseName . '.' . $orig_ext;
    $fallbackDest = $target_dir . $fallbackFilename;
    if (is_uploaded_file($tmp) && move_uploaded_file($tmp, $fallbackDest)) {
        $avatar_path = 'img/usuarios/avatares/' . $fallbackFilename;
    }
}

if (!$avatar_path) {
    error_log('upload-avatar: failed to process image. mime=' . $mime . ' tmp=' . $tmp . ' uid=' . $uid);
    // Provide a slightly more informative message for debugging on client side
    $userMsg = 'Falha ao processar imagem. Verifique o tipo do arquivo e se a extensão GD do PHP está habilitada.';
    echo json_encode(['success' => false, 'message' => $userMsg]);
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
