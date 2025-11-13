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
$preco = 0;
if ($preco_post) {
    // remove qualquer caractere não numérico (p.ex. pontos de milhar) e mantenha o inteiro
    $preco = (int) preg_replace('/\D/', '', $preco_post);
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
// optional location saved during flow
$estado_local = isset($_SESSION['estado_local']) ? mysqli_real_escape_string($conexao, $_SESSION['estado_local']) : '';
$cidade = isset($_SESSION['cidade']) ? mysqli_real_escape_string($conexao, $_SESSION['cidade']) : '';
// tipo_vendedor: expect 'pf' or 'pj' in session (from form). Map to 0 = Particular, 1 = Loja
$tipo_vendedor = 0;
$loja_id = null;
if (isset($_SESSION['tipo_vendedor'])) {
    $tipo_v = $_SESSION['tipo_vendedor'];
    $tipo_vendedor = ($tipo_v === 'pj') ? 1 : 0;
}
if (isset($_SESSION['loja_id']) && (int)$_SESSION['loja_id'] > 0) {
    $loja_id = (int) $_SESSION['loja_id'];
}

// If seller is a loja, prefer loja's contact info (email_corporativo / whatsapp or telefone_fixo)
if ($tipo_vendedor === 1 && $loja_id) {
    $resL = mysqli_query($conexao, "SELECT email_corporativo, whatsapp, telefone_fixo FROM lojas WHERE id = " . $loja_id . " LIMIT 1");
    if ($resL && mysqli_num_rows($resL) > 0) {
        $rl = mysqli_fetch_assoc($resL);
        if (!empty($rl['email_corporativo'])) $email = mysqli_real_escape_string($conexao, $rl['email_corporativo']);
        // prefer whatsapp, then telefone_fixo
        $tel = !empty($rl['whatsapp']) ? $rl['whatsapp'] : $rl['telefone_fixo'];
        if (!empty($tel)) $telefone = mysqli_real_escape_string($conexao, $tel);
    }
}

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
// Description: accept from POST (client) — sanitize and enforce length limits
$descricao = '';
if (isset($_POST['descricao'])) {
    $descricao = mysqli_real_escape_string($conexao, trim($_POST['descricao']));
    // enforce limits server-side: description is optional, but if provided must be at most 1000 chars
    if (mb_strlen($descricao) > 1000) {
        echo json_encode(['success' => false, 'message' => 'A descrição deve ter no máximo 1000 caracteres.']);
        mysqli_close($conexao);
        exit;
    }
}

$cols = [
    'modelo','marca','versao','ano_fabricacao','ano_modelo','placa','cor','descricao','id_vendedor','preco','condicao','quilometragem','quant_proprietario','revisao','vistoria','sinistro','ipva','licenciamento','estado_conservacao','uso_anterior','aceita_troca','email','telefone'
];

$values = array();
$values[] = "'" . $modelo . "'";
$values[] = (int) $marca;
$values[] = "'" . $versao . "'";
$values[] = (int) $fabr;
$values[] = (int) $ano;
$values[] = is_string($placa) ? "'" . $placa . "'" : 'NULL';
$values[] = (int) $cor;
$values[] = "'" . $descricao . "'";
$values[] = (int) $uid;
$values[] = (int) $preco;
$values[] = "'" . $condicao . "'";
$values[] = "'" . $quilometragem . "'";
$values[] = (int) $proprietario;
$values[] = "'" . $revisao . "'";
$values[] = "'" . $vistoria . "'";
$values[] = "'" . $sinistro . "'";
$values[] = "'" . $ipva . "'";
$values[] = "'" . $licenciamento . "'";
$values[] = (int) $consevacao;
$values[] = "'" . $uso_anterior . "'";
$values[] = "'" . $troca . "'";
$values[] = ($email === NULL ? 'NULL' : "'" . $email . "'");
$values[] = ($telefone === NULL ? 'NULL' : "'" . $telefone . "'");
// include location if present (anuncios_carros may have columns estado_local and cidade)
$r_tmp = mysqli_query($conexao, "SHOW COLUMNS FROM anuncios_carros LIKE 'estado_local'");
if ($r_tmp && mysqli_num_rows($r_tmp) > 0) {
    $cols[] = 'estado_local';
    $values[] = ($estado_local === '' ? 'NULL' : "'" . $estado_local . "'");
}
$r_tmp = mysqli_query($conexao, "SHOW COLUMNS FROM anuncios_carros LIKE 'cidade'");
if ($r_tmp && mysqli_num_rows($r_tmp) > 0) {
    $cols[] = 'cidade';
    $values[] = ($cidade === '' ? 'NULL' : "'" . $cidade . "'");
}

// Check if database has tipo_vendedor and loja_id columns; include only if present to remain backward-compatible
$has_tipo = false;
$has_loja = false;
$r = mysqli_query($conexao, "SHOW COLUMNS FROM anuncios_carros LIKE 'tipo_vendedor'");
if ($r && mysqli_num_rows($r) > 0) $has_tipo = true;
$r = mysqli_query($conexao, "SHOW COLUMNS FROM anuncios_carros LIKE 'loja_id'");
if ($r && mysqli_num_rows($r) > 0) $has_loja = true;

if ($has_tipo) {
    $cols[] = 'tipo_vendedor';
    $values[] = $tipo_vendedor;
}
if ($has_loja) {
    $cols[] = 'loja_id';
    $values[] = ($loja_id ? $loja_id : 'NULL');
}

$sql = "INSERT INTO anuncios_carros(" . implode(',', $cols) . ") VALUES (" . implode(',', $values) . ")";

if (!mysqli_query($conexao, $sql)) {
    $err = mysqli_error($conexao);
    // return informative JSON for debugging
    echo json_encode(['success' => false, 'message' => 'Erro ao inserir anúncio: ' . $err]);
    mysqli_close($conexao);
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
