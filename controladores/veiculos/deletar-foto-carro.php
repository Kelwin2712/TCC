<?php
session_start();
include(__DIR__ . '/../conexao_bd.php');
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$uid = (int) $_SESSION['id'];
// support two modes:
// 1) DB-backed: receive 'id' => delete DB row and file under img/anuncios/carros/{carro_id}/{filename}
// 2) Temp mode: receive 'filename' => delete file under img/anuncios/temp/{uid}/{filename}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$filename_post = isset($_POST['filename']) ? $_POST['filename'] : null;

if ($id > 0) {
    $res = mysqli_query($conexao, "SELECT carro_id, caminho_foto FROM fotos_carros WHERE id = $id LIMIT 1");
    if (!$res || mysqli_num_rows($res) === 0) {
        echo json_encode(['success' => false, 'message' => 'Registro não encontrado']);
        exit;
    }
    $row = mysqli_fetch_assoc($res);
    $carro_id = (int) $row['carro_id'];
    $filename = $row['caminho_foto'];

    // check ownership
    $r2 = mysqli_query($conexao, "SELECT id_vendedor FROM anuncios_carros WHERE id = $carro_id LIMIT 1");
    if (!$r2 || mysqli_num_rows($r2) === 0) {
        echo json_encode(['success' => false, 'message' => 'Anúncio não encontrado']);
        exit;
    }
    $r2row = mysqli_fetch_assoc($r2);
    if ((int)$r2row['id_vendedor'] !== $uid) {
        echo json_encode(['success' => false, 'message' => 'Permissão negada']);
        exit;
    }

    $path = __DIR__ . '/../../img/anuncios/carros/' . $carro_id . '/' . $filename;
    $deleted = false;
    if (is_file($path)) { $deleted = @unlink($path); }

    $sql = "DELETE FROM fotos_carros WHERE id = $id";
    if (!mysqli_query($conexao, $sql)) {
        echo json_encode(['success' => false, 'message' => 'Erro ao remover registro DB']);
        exit;
    }

    $_SESSION['msg_alert'] = ['success', 'Imagem removida com sucesso.'];
    echo json_encode(['success' => true, 'deleted_file' => $deleted, 'message' => 'Imagem removida com sucesso.']);
    mysqli_close($conexao);
    exit;

} elseif ($filename_post) {
    // sanitize filename (allow only basename)
    $filename = basename($filename_post);
    $path = __DIR__ . '/../../img/anuncios/temp/' . $uid . '/' . $filename;
    $deleted = false;
    if (is_file($path)) { $deleted = @unlink($path); }
    // also remove from session ordered list if present
    if (isset($_SESSION['tmp_fotos']) && is_array($_SESSION['tmp_fotos'])) {
        $idx = array_search($filename, $_SESSION['tmp_fotos']);
        if ($idx !== false) array_splice($_SESSION['tmp_fotos'], $idx, 1);
    }
    if ($deleted) {
        echo json_encode(['success' => true, 'deleted_file' => true, 'message' => 'Imagem temporária removida.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Arquivo não encontrado.']);
    }
    mysqli_close($conexao);
    exit;

} else {
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
    mysqli_close($conexao);
    exit;
}
mysqli_close($conexao);
exit;
?>
