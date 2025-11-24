<?php
session_start();
include('../conexao_bd.php');

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
    exit;
}

function val($v) { return isset($v) ? $v : null; }

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID da loja ausente.']);
    exit;
}

// permission check: only users with pode_editar_loja (active) can update
$uid = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
if (!$uid) { echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']); exit; }
$permRes = @mysqli_query($conexao, "SELECT pode_editar_loja FROM equipe WHERE loja_id = $id AND usuario_id = $uid AND status = 'A' LIMIT 1");
if (!($permRes && mysqli_num_rows($permRes) > 0 && (int)mysqli_fetch_assoc($permRes)['pode_editar_loja'] === 1)) {
    echo json_encode(['success' => false, 'message' => 'Você não tem permissão para editar esta loja.']);
    exit;
}

// sanitize inputs
$fields = [
    'nome','razao_social','cnpj','inscricao_estadual','numero','cep','bairro','cidade','estado',
    'telefone_fixo','whatsapp','email_corporativo','site','instagram','facebook','descricao_loja'
];
$updates = [];
foreach ($fields as $f) {
    $v = val($_POST[$f]);
    if ($v !== null) {
        $updates[] = "$f = '" . mysqli_real_escape_string($conexao, $v) . "'";
    }
}

// handle file uploads (logo_local, capa_local)
$uploadBase = realpath(__DIR__ . '/../../img/lojas');
if (!$uploadBase) $uploadBase = __DIR__ . '/../../img/lojas';
$destDir = $uploadBase . '/' . $id;
if (!is_dir($destDir)) @mkdir($destDir, 0755, true);

function move_image_field_local($field, $destDir, $prefix) {
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return '';
    $tmp = $_FILES[$field]['tmp_name'];
    $info = @getimagesize($tmp);
    if (!$info) return '';
    $ext = image_type_to_extension($info[2], false);
    $safe = $prefix . '-' . uniqid() . '.' . $ext;
    $dst = rtrim($destDir, '/') . '/' . $safe;
    if (@move_uploaded_file($tmp, $dst)) return $safe;
    return '';
}

$response = ['success' => false, 'message' => 'Nada a atualizar.'];

if (count($updates) > 0) {
    $sql = "UPDATE lojas SET " . implode(',', $updates) . " WHERE id = " . $id;
    if (!mysqli_query($conexao, $sql)) {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar loja: ' . mysqli_error($conexao)]);
        exit;
    }
    $response = ['success' => true, 'message' => 'Informações atualizadas.'];
}

$logoName = move_image_field_local('logo_local', $destDir, 'logo');
$capaName = move_image_field_local('capa_local', $destDir, 'capa');
$updates2 = [];
if ($logoName) $updates2[] = "logo = '" . mysqli_real_escape_string($conexao, $logoName) . "'";
if ($capaName) $updates2[] = "capa = '" . mysqli_real_escape_string($conexao, $capaName) . "'";
if (count($updates2) > 0) {
    $updSql = "UPDATE lojas SET " . implode(',', $updates2) . " WHERE id = " . $id;
    if (!mysqli_query($conexao, $updSql)) {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar imagens: ' . mysqli_error($conexao)]);
        exit;
    }
    $response = ['success' => true, 'message' => 'Informações e imagens atualizadas.'];
}

// return URLs for previews
if (!empty($logoName)) {
    $response['logo_url'] = '../img/lojas/' . $id . '/' . $logoName;
}
if (!empty($capaName)) {
    $response['capa_url'] = '../img/lojas/' . $id . '/' . $capaName;
}

// process horarios if provided
if (isset($_POST['horarios']) && is_array($_POST['horarios'])) {
    $horarios = [];
    for ($d = 0; $d < 7; $d++) {
        $abre = isset($_POST['horarios'][$d]['abre']) ? trim($_POST['horarios'][$d]['abre']) : '';
        $fecha = isset($_POST['horarios'][$d]['fecha']) ? trim($_POST['horarios'][$d]['fecha']) : '';
        $aberto = isset($_POST['horarios'][$d]['aberto']) ? 1 : 0;
        $horarios[$d] = ['aberto' => $aberto, 'abre' => $abre === '' ? null : $abre, 'fecha' => $fecha === '' ? null : $fecha];
    }
    $hor_json = mysqli_real_escape_string($conexao, json_encode($horarios, JSON_UNESCAPED_UNICODE));
    $hsql = "UPDATE lojas SET horarios = '" . $hor_json . "' WHERE id = " . $id;
    if (!mysqli_query($conexao, $hsql)) {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar horários: ' . mysqli_error($conexao)]);
        exit;
    }
    $response['success'] = true;
    $response['message'] = isset($response['message']) ? $response['message'] . ' Horários atualizados.' : 'Horários atualizados.';
}

echo json_encode($response);
mysqli_close($conexao);
