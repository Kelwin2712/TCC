<?php
session_start();
include(__DIR__ . '/../conexao_bd.php');

if (!isset($_SESSION['id'])) {
    $_SESSION['msg_alert'] = ['danger', 'Usuário não autenticado'];
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$uid = (int) $_SESSION['id'];

// Gather values from session (these are set in previous steps: infos/condicao/preco etc.)
$modelo = isset($_SESSION['modelo']) ? mysqli_real_escape_string($conexao, $_SESSION['modelo']) : '';
$marca = isset($_SESSION['marca']) ? (int) $_SESSION['marca'] : 'NULL';
$versao = isset($_SESSION['versao']) ? mysqli_real_escape_string($conexao, $_SESSION['versao']) : '';
$fabr = isset($_SESSION['fabr']) ? (int) $_SESSION['fabr'] : 'NULL';
$ano = isset($_SESSION['ano']) ? (int) $_SESSION['ano'] : 'NULL';
$placa = !empty($_SESSION['placa']) ? mysqli_real_escape_string($conexao, $_SESSION['placa']) : 'NULL';
$cor = isset($_SESSION['cor']) ? (int) $_SESSION['cor'] : 'NULL';
$preco_post = isset($_SESSION['preco']) ? $_SESSION['preco'] : null;
$preco = '0.00';
if ($preco_post) {
    $preco_limpo = str_replace(['R$', '.', ' ', ','], ['', '', '', '.'], $preco_post);
    $preco = number_format((float)$preco_limpo, 2, '.', '');
}
$condicao = isset($_SESSION['condicao']) ? mysqli_real_escape_string($conexao, $_SESSION['condicao']) : '';
$quilometragem = isset($_SESSION['quilometragem']) ? preg_replace('/\D/', '', $_SESSION['quilometragem']) : 0;
$proprietario = isset($_SESSION['proprietario']) ? (int) $_SESSION['proprietario'] : 'NULL';
$revisao = isset($_SESSION['revisao']) ? mysqli_real_escape_string($conexao, $_SESSION['revisao']) : 'NULL';
$vistoria = isset($_SESSION['vistoria']) ? mysqli_real_escape_string($conexao, $_SESSION['vistoria']) : 'NULL';
$sinistro = isset($_SESSION['sinistro']) ? mysqli_real_escape_string($conexao, $_SESSION['sinistro']) : 'NULL';
$ipva = isset($_SESSION['ipva']) ? mysqli_real_escape_string($conexao, $_SESSION['ipva']) : 'NULL';
$licenciamento = isset($_SESSION['licenciamento']) ? mysqli_real_escape_string($conexao, $_SESSION['licenciamento']) : 'NULL';
$consevacao = isset($_SESSION['consevacao']) ? (int) $_SESSION['consevacao'] : 'NULL';
$uso_anterior = isset($_SESSION['uso_anterior']) ? mysqli_real_escape_string($conexao, $_SESSION['uso_anterior']) : 'NULL';
$troca = isset($_SESSION['troca']) ? (int) $_SESSION['troca'] : 0;
$email = isset($_SESSION['email']) ? mysqli_real_escape_string($conexao, $_SESSION['email']) : NULL;
$telefone = isset($_SESSION['telefone']) ? mysqli_real_escape_string($conexao, $_SESSION['telefone']) : NULL;

// Insert anuncio
// Before inserting anuncio, check if there are uploaded files (from this request) or temp files for this user.
$tmp_dir = __DIR__ . '/../../img/anuncios/temp/' . $uid . '/';
$uploaded_count = 0;
$uploaded_files_info = [];
if (isset($_FILES['fotos']) && is_array($_FILES['fotos']['tmp_name'])) {
    $f = $_FILES['fotos'];
    $countf = is_array($f['tmp_name']) ? count($f['tmp_name']) : 0;
    $allowed_mimes = ['image/jpeg','image/png','image/webp','image/gif'];
    for ($i=0;$i<$countf;$i++) {
        if ($f['error'][$i] !== UPLOAD_ERR_OK) continue;
        $tmpname = $f['tmp_name'][$i];
        if (!is_file($tmpname)) continue;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmpname);
        finfo_close($finfo);
        if (!in_array($mime, $allowed_mimes)) continue;
        if (filesize($tmpname) > 5 * 1024 * 1024) continue;
        $uploaded_files_info[] = ['tmp' => $tmpname, 'mime' => $mime];
        $uploaded_count++;
    }
}

// collect temp photos (previously uploaded via temp flow, if any)
$temp_photos = [];
if (isset($_SESSION['tmp_fotos']) && is_array($_SESSION['tmp_fotos']) && count($_SESSION['tmp_fotos'])>0) {
    $temp_photos = $_SESSION['tmp_fotos'];
} elseif (is_dir($tmp_dir)) {
    $files = glob($tmp_dir . '*');
    foreach ($files as $ff) if (is_file($ff)) $temp_photos[] = basename($ff);
}

if (($uploaded_count + count($temp_photos)) < 5) {
    echo json_encode(['success' => false, 'message' => 'É necessário enviar pelo menos 5 fotos antes de finalizar o anúncio.']);
    mysqli_close($conexao);
    exit;
}

// Insert anuncio now that we have enough photos
$sql = "INSERT INTO anuncios_carros(modelo, marca, versao, ano_fabricacao, ano_modelo, placa, cor, id_vendedor, preco, condicao, quilometragem, quant_proprietario, revisao, vistoria, sinistro, ipva, licenciamento, estado_conservacao, uso_anterior, aceita_troca, email, telefone) VALUES ('{$modelo}', {$marca}, '{$versao}', {$fabr}, {$ano}, ".(is_string($placa)?"'{$placa}'":'NULL').", {$cor}, {$uid}, '{$preco}', '{$condicao}', '{$quilometragem}', {$proprietario}, '{$revisao}', '{$vistoria}', '{$sinistro}', '{$ipva}', '{$licenciamento}', {$consevacao}, '{$uso_anterior}', '{$troca}', '{$email}', '{$telefone}')";

if (!mysqli_query($conexao, $sql)) {
    echo json_encode(['success' => false, 'message' => 'Erro ao inserir anúncio']);
    exit;
}

$new_id = mysqli_insert_id($conexao);

// prepare final directory
$final_dir = __DIR__ . '/../../img/anuncios/carros/' . $new_id . '/';
if (!is_dir($final_dir)) mkdir($final_dir, 0755, true);

// insert uploaded files first (respecting client order)
$ord = 0;
if (!empty($uploaded_files_info)) {
    foreach ($uploaded_files_info as $info) {
        $mime = $info['mime'];
        $tmpname = $info['tmp'];
        $ext = '';
        switch ($mime) {
            case 'image/jpeg': $ext = '.jpg'; break;
            case 'image/png': $ext = '.png'; break;
            case 'image/webp': $ext = '.webp'; break;
            case 'image/gif': $ext = '.gif'; break;
        }
        $basename = time() . '_' . bin2hex(random_bytes(6)) . $ext;
        $dest = $final_dir . $basename;
        // move uploaded tmp file into final folder
        if (@move_uploaded_file($tmpname, $dest)) {
            $safe = mysqli_real_escape_string($conexao, $basename);
            mysqli_query($conexao, "INSERT INTO fotos_carros (carro_id, caminho_foto, `ordem`) VALUES ($new_id, '$safe', $ord)");
            $ord++;
        }
    }
}

// then move any temp photos (preserving session order if present)
if (!empty($temp_photos) && is_dir($tmp_dir)) {
    // prefer the session-ordered list if available
    $ordered = [];
    if (isset($_SESSION['tmp_fotos']) && is_array($_SESSION['tmp_fotos']) && count($_SESSION['tmp_fotos'])>0) {
        $ordered = $_SESSION['tmp_fotos'];
    } else {
        $files = glob($tmp_dir . '*');
        foreach ($files as $f) if (is_file($f)) $ordered[] = basename($f);
    }

    foreach ($ordered as $basename) {
        $src = $tmp_dir . $basename;
        if (!is_file($src)) continue;
        // move
        rename($src, $final_dir . $basename);
        // insert DB row with ordem
        $safe = mysqli_real_escape_string($conexao, $basename);
        mysqli_query($conexao, "INSERT INTO fotos_carros (carro_id, caminho_foto, `ordem`) VALUES ($new_id, '$safe', $ord)");
        $ord++;
    }

    // remove temp dir and session list
    @rmdir($tmp_dir);
    if (isset($_SESSION['tmp_fotos'])) unset($_SESSION['tmp_fotos']);
}

$_SESSION['msg_alert'] = ['success', 'Anúncio criado com sucesso.'];

// return JSON with redirect target
echo json_encode(['success' => true, 'message' => 'Anúncio finalizado', 'redirect' => 'pagina-venda.php?id=' . $new_id]);

mysqli_close($conexao);
exit;

?>
