<?php
session_start();
include(__DIR__ . '/../conexao_bd.php');
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$uid = (int) $_SESSION['id'];
// carro_id is optional. If provided (>0) we store directly under that anuncio and insert DB rows.
$carro_id = isset($_POST['carro_id']) ? (int) $_POST['carro_id'] : 0;
// if carro_id > 0 verify ownership
if ($carro_id > 0) {
    $res = mysqli_query($conexao, "SELECT id_vendedor FROM anuncios_carros WHERE id = $carro_id LIMIT 1");
    if (!$res || mysqli_num_rows($res) === 0) {
        echo json_encode(['success' => false, 'message' => 'Anúncio não encontrado']);
        exit;
    }
    $row = mysqli_fetch_assoc($res);
    if ((int)$row['id_vendedor'] !== $uid) {
        echo json_encode(['success' => false, 'message' => 'Permissão negada']);
        exit;
    }
}

if (!isset($_FILES['fotos'])) {
    echo json_encode(['success' => false, 'message' => 'Nenhuma imagem enviada']);
    exit;
}

$allowed = ['image/jpeg','image/png','image/webp','image/gif'];
$target_base = __DIR__ . '/../../img/anuncios/carros/';
if (!is_dir($target_base)) mkdir($target_base, 0755, true);
if ($carro_id > 0) {
    $target_dir = $target_base . $carro_id . '/';
    if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
} else {
    // temporary folder per user session/user id
    $tmp_base = __DIR__ . '/../../img/anuncios/temp/';
    if (!is_dir($tmp_base)) mkdir($tmp_base, 0755, true);
    $target_dir = $tmp_base . $uid . '/';
    if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
}

$inserted = [];
$files = $_FILES['fotos'];
$count = is_array($files['name']) ? count($files['name']) : 0;
for ($i = 0; $i < $count; $i++) {
    if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
    $tmp = $files['tmp_name'][$i];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmp);
    finfo_close($finfo);
    if (!in_array($mime, $allowed)) continue;
    if (filesize($tmp) > 5 * 1024 * 1024) continue; // skip >5MB

    // build safe filename
    $ext = '';
    switch ($mime) {
        case 'image/jpeg': $ext = '.jpg'; break;
        case 'image/png': $ext = '.png'; break;
        case 'image/webp': $ext = '.webp'; break;
        case 'image/gif': $ext = '.gif'; break;
    }
    $basename = time() . '_' . bin2hex(random_bytes(6)) . $ext;
    $dest = $target_dir . $basename;

    if (!move_uploaded_file($tmp, $dest)) continue;

    if ($carro_id > 0) {
        // determine starting ordem (0 if none, else max+1)
        $ord_res = mysqli_query($conexao, "SELECT MAX(`ordem`) as mx FROM fotos_carros WHERE carro_id = $carro_id");
        $start_ord = 0;
        if ($ord_res && mysqli_num_rows($ord_res) > 0) {
            $rord = mysqli_fetch_assoc($ord_res);
            if ($rord && $rord['mx'] !== null) $start_ord = (int)$rord['mx'] + 1;
        }
        // use incremental ordem for this batch
        $ord = $start_ord + count($inserted);

        // insert DB (store only filename) with ordem
        $safe = mysqli_real_escape_string($conexao, $basename);
        $sql = "INSERT INTO fotos_carros (carro_id, caminho_foto, `ordem`) VALUES ($carro_id, '$safe', $ord)";
        if (mysqli_query($conexao, $sql)) {
            $inserted_id = mysqli_insert_id($conexao);
            $inserted[] = ['id' => $inserted_id, 'filename' => $basename];
        } else {
            // on DB fail, delete file
            @unlink($dest);
        }
    } else {
        // temp mode: return filenames only (no DB insert)
        $inserted[] = ['filename' => $basename];
        // keep an ordered list in session so finalize can respect selection order
        if (!isset($_SESSION['tmp_fotos']) || !is_array($_SESSION['tmp_fotos'])) $_SESSION['tmp_fotos'] = [];
        $_SESSION['tmp_fotos'][] = $basename;
    }
}

if (empty($inserted)) {
    echo json_encode(['success' => false, 'message' => 'Nenhuma imagem processada']);
    exit;
}

// Note: in temp mode we don't set session msg_alert to avoid confusing the later flow
if ($carro_id > 0) $_SESSION['msg_alert'] = ['success', 'Imagens enviadas com sucesso.'];
echo json_encode(['success' => true, 'message' => 'Imagens enviadas com sucesso.', 'inserted' => $inserted]);
mysqli_close($conexao);
exit;

?>
