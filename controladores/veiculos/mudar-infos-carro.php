<?php
session_start();
include('../conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_veiculo = $_SESSION['id_veiculo_edit'];

    $estado_local = $_POST['estado_local'] ?? 'SP';
    $cidade = $_POST['cidade'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $versao = $_POST['versao'] ?? '';
    $carroceria = $_POST['carroceria'] ?? '';
    $preco_raw = $_POST['preco'] ?? '';
    // Normalize price sent in BR format (e.g. "1.234,56" or "1234,56") to SQL decimal format ("1234.56").
    $preco = $preco_raw;
    if ($preco !== '') {
        // Remove spaces
        $preco = str_replace(' ', '', $preco);
        // If contains comma, assume BR format: remove dot thousands separators and convert comma to dot
        if (strpos($preco, ',') !== false) {
            $preco = str_replace('.', '', $preco);
            $preco = str_replace(',', '.', $preco);
        } else {
            // No comma: remove any non-digit or dot, keep dot as decimal separator
            $preco = preg_replace('/[^0-9.]/', '', $preco);
        }
        // Ensure decimal part with two decimals
        if (strpos($preco, '.') === false) {
            $preco = $preco . '.00';
        } else {
            $preco = number_format((float)$preco, 2, '.', '');
        }
    } else {
        $preco = '0.00';
    }

    // Read quilometragem from POST (submitted by the edit form). The form may contain formatted text like "0 km" or "12.345 km".
    $quilometragem_raw = $_POST['quilometragem'] ?? ($_SESSION['quilometragem'] ?? '');
    // Keep only digits (store as integer kilometers). If empty, default to 0.
    $quilometragem = preg_replace('/\D/', '', $quilometragem_raw);
    if ($quilometragem === null || $quilometragem === '') {
        $quilometragem = '0';
    }
    $ano_fabricacao = $_POST['ano_fabricacao'] ?? '';
    $condicao = $_POST['condicao'] ?? '';
    $ano_modelo = $_POST['ano_modelo'] ?? '';
    $propulsao = $_POST['propulsao'] ?? '';
    $combustivel = $_POST['combustivel'] ?? '';
    $blindagem = $_POST['blindagem'] ?? '';
    $portas_qtd = $_POST['portas_qtd'] ?? '';
    $assentos_qtd = $_POST['assentos_qtd'] ?? '';
    $placa = $_POST['placa'] ?? '';
    $cor = $_POST['cor'] ?? '';
    $proprietario = $_POST['proprietario'] ?? '';
    $revisao = $_POST['revisao'] ?? '';
    $vistoria = $_POST['vistoria'] ?? '';
    $sinistro = $_POST['sinistro'] ?? '';
    $ipva = $_POST['ipva'] ?? '';
    $licenciamento = $_POST['licenciamento'] ?? '';
    $conservacao = $_POST['conservacao'] ?? '';
    $uso = $_POST['uso'] ?? '';
    $aceita_troca = $_POST['troca'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $garantia = $_POST['garantia'] ?? '';
    $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : null;

    $sql = "UPDATE anuncios_carros SET 
        estado_local='$estado_local', cidade='$cidade', marca='$marca', modelo='$modelo', versao='$versao', carroceria='$carroceria', preco='$preco', quilometragem='$quilometragem', 
        ano_fabricacao='$ano_fabricacao', ano_modelo='$ano_modelo', propulsao='$propulsao', combustivel='$combustivel', blindagem='$blindagem', portas_qtd='$portas_qtd', assentos_qtd='$assentos_qtd', 
    placa='$placa', cor='$cor', quant_proprietario='$proprietario', revisao='$revisao', vistoria='$vistoria', sinistro='$sinistro', ipva='$ipva', licenciamento='$licenciamento', 
    condicao='$condicao', estado_conservacao='$conservacao', uso_anterior='$uso', aceita_troca='$aceita_troca', email='$email', telefone='$telefone', garantia='$garantia' 
        WHERE id='$id_veiculo'";
    // include descricao if provided
    if ($descricao !== null) {
        $safe_desc = mysqli_real_escape_string($conexao, $descricao);
        // basic server-side enforce 100-1000 chars (if not empty)
        if ($safe_desc !== '' && (mb_strlen($descricao) < 100 || mb_strlen($descricao) > 1000)) {
            // respond with error for AJAX or redirect with message
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'A descrição deve conter entre 100 e 1000 caracteres.']);
                mysqli_close($conexao);
                exit();
            }
            $_SESSION['msg_alert'] = ['danger', 'A descrição deve conter entre 100 e 1000 caracteres.'];
            header('Location: ../../menu/editar-anuncio.php?id=' . $id_veiculo);
            mysqli_close($conexao);
            exit();
        }
        // append to update
        $sql = str_replace(" WHERE id='$id_veiculo'", ", descricao='$safe_desc' WHERE id='$id_veiculo'", $sql);
    }

    if (!mysqli_query($conexao, $sql)) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar anúncio: ' . mysqli_error($conexao)]);
            mysqli_close($conexao);
            exit();
        }
        $_SESSION['msg_alert'] = ['danger', 'Erro ao atualizar anúncio.'];
        header('Location: ../../menu/anuncios.php');
        mysqli_close($conexao);
        exit();
    }

    // process deletions (only apply when user clicked save and sent delete_photos[])
    if (isset($_POST['delete_photos']) && is_array($_POST['delete_photos'])) {
        $del_list = $_POST['delete_photos'];
        $img_dir = __DIR__ . '/../../img/anuncios/carros/' . $id_veiculo . '/';
        foreach ($del_list as $del) {
            $del_safe = mysqli_real_escape_string($conexao, $del);
            // remove DB row
            mysqli_query($conexao, "DELETE FROM fotos_carros WHERE carro_id = $id_veiculo AND caminho_foto = '$del_safe' LIMIT 1");
            // remove file if exists
            $path = $img_dir . $del;
            if (is_file($path)) @unlink($path);
        }
    }

    // process new uploaded photos (new_fotos[*]) - supports associative keys from client
    $new_map = []; // tmpKey => final basename
    if (isset($_FILES['new_fotos']) && is_array($_FILES['new_fotos']['tmp_name'])) {
        $f = $_FILES['new_fotos'];
        $allowed_mimes = ['image/jpeg','image/png','image/webp','image/gif'];
        $final_dir = __DIR__ . '/../../img/anuncios/carros/' . $id_veiculo . '/';
        if (!is_dir($final_dir)) mkdir($final_dir, 0755, true);
        // fetch current max ordem
        $res = mysqli_query($conexao, "SELECT COALESCE(MAX(`ordem`), -1) as m FROM fotos_carros WHERE carro_id = $id_veiculo");
        $row = mysqli_fetch_assoc($res);
        $ord = isset($row['m']) ? ((int)$row['m'] + 1) : 0;
        foreach ($f['tmp_name'] as $key => $tmpname) {
            if (!isset($f['error'][$key]) || $f['error'][$key] !== UPLOAD_ERR_OK) continue;
            if (!is_file($tmpname)) continue;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmpname);
            finfo_close($finfo);
            if (!in_array($mime, $allowed_mimes)) continue;
            if (filesize($tmpname) > 5 * 1024 * 1024) continue;
            $ext = '';
            switch ($mime) {
                case 'image/jpeg': $ext = '.jpg'; break;
                case 'image/png': $ext = '.png'; break;
                case 'image/webp': $ext = '.webp'; break;
                case 'image/gif': $ext = '.gif'; break;
            }
            $basename = time() . '_' . bin2hex(random_bytes(6)) . $ext;
            $dest = $final_dir . $basename;
            if (@move_uploaded_file($tmpname, $dest)) {
                $safe = mysqli_real_escape_string($conexao, $basename);
                mysqli_query($conexao, "INSERT INTO fotos_carros (carro_id, caminho_foto, `ordem`) VALUES ($id_veiculo, '$safe', $ord)");
                $ord++;
                $new_map[$key] = $basename;
            }
        }
    }

    $_SESSION['msg_alert'] = ['success', 'Alterações feitas com sucesso!'];
    // if ajax, return JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json; charset=utf-8');
        // if photo_order provided, update ordem accordingly
        if (isset($_POST['photo_order']) && is_array($_POST['photo_order'])) {
            $orderList = $_POST['photo_order'];
            // build map filename => id
            $r2 = mysqli_query($conexao, "SELECT id, caminho_foto FROM fotos_carros WHERE carro_id = $id_veiculo");
            $idByName = [];
            while ($rr = mysqli_fetch_assoc($r2)) $idByName[$rr['caminho_foto']] = $rr['id'];
            $pos = 0;
            foreach ($orderList as $entry) {
                // if entry matches a new_map key, replace with generated basename
                if (isset($new_map[$entry])) $filename = $new_map[$entry];
                else $filename = $entry;
                if (isset($idByName[$filename])) {
                    $fid = (int)$idByName[$filename];
                    mysqli_query($conexao, "UPDATE fotos_carros SET `ordem` = $pos WHERE id = $fid LIMIT 1");
                    $pos++;
                }
            }
        }

        echo json_encode(['success' => true, 'message' => 'Alterações salvas', 'redirect' => '../../menu/anuncios.php']);
        mysqli_close($conexao);
        exit();
    }
    header('Location: ../../menu/anuncios.php');
    exit();
} else {
    header('Location: ../../index.php');
    exit();
}

mysqli_close($conexao);
