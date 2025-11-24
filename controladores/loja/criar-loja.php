<?php
session_start();
include('../conexao_bd.php');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header('Location: ../../index.php');
  exit();
}

// basic sanitization helper
function val($v) { return isset($v) ? $v : ''; }

$nome = mysqli_real_escape_string($conexao, val($_POST['nome']));
$razao_social = mysqli_real_escape_string($conexao, val($_POST['razao_social']));
$cnpj = mysqli_real_escape_string($conexao, val($_POST['cnpj']));
$inscricao_estadual = mysqli_real_escape_string($conexao, val($_POST['inscricao_estadual']));
// endereco removed from form
$endereco = '';
$numero = mysqli_real_escape_string($conexao, val($_POST['numero']));
$cep = mysqli_real_escape_string($conexao, val($_POST['cep']));
$bairro = mysqli_real_escape_string($conexao, val($_POST['bairro']));
$cidade = mysqli_real_escape_string($conexao, val($_POST['cidade']));
$estado = mysqli_real_escape_string($conexao, val($_POST['estado']));
$telefone_fixo = mysqli_real_escape_string($conexao, val($_POST['telefone_fixo']));
$whatsapp = mysqli_real_escape_string($conexao, val($_POST['whatsapp']));
$email_corporativo = mysqli_real_escape_string($conexao, val($_POST['email_corporativo']));
$site = mysqli_real_escape_string($conexao, val($_POST['site']));
$instagram = mysqli_real_escape_string($conexao, val($_POST['instagram']));
$facebook = mysqli_real_escape_string($conexao, val($_POST['facebook']));
$descricao_loja = mysqli_real_escape_string($conexao, val($_POST['descricao_loja']));
$hora_abre = mysqli_real_escape_string($conexao, val($_POST['hora_abre']));
$hora_fecha = mysqli_real_escape_string($conexao, val($_POST['hora_fecha']));
$dias_funcionamento = isset($_POST['dias_funcionamento']) ? mysqli_real_escape_string($conexao, implode(',', $_POST['dias_funcionamento'])) : '';

// advanced horarios
$horarios_json = 'NULL';
if (isset($_POST['horarios_avancado']) && isset($_POST['horarios']) && is_array($_POST['horarios'])) {
  $horarios = [];
  for ($d = 0; $d < 7; $d++) {
    $abre = isset($_POST['horarios'][$d]['abre']) ? trim($_POST['horarios'][$d]['abre']) : '';
    $fecha = isset($_POST['horarios'][$d]['fecha']) ? trim($_POST['horarios'][$d]['fecha']) : '';
    $aberto = isset($_POST['horarios'][$d]['aberto']) ? 1 : 0;
    $horarios[$d] = ['aberto' => $aberto, 'abre' => $abre === '' ? null : $abre, 'fecha' => $fecha === '' ? null : $fecha];
  }
  $horarios_json = "'" . mysqli_real_escape_string($conexao, json_encode($horarios, JSON_UNESCAPED_UNICODE)) . "'";
}

// insert record (logo/capa set after moving uploads)
$sql = "INSERT INTO lojas (
  nome, razao_social, cnpj, inscricao_estadual, endereco, numero, cep, bairro, cidade, estado,
  telefone_fixo, whatsapp, email_corporativo, site, instagram, facebook, logo, capa, descricao_loja,
  hora_abre, hora_fecha, dias_funcionamento, horarios
) VALUES (
  '$nome', '$razao_social', '$cnpj', '$inscricao_estadual', '$endereco', '$numero', '$cep', '$bairro', '$cidade', '$estado',
  '$telefone_fixo', '$whatsapp', '$email_corporativo', '$site', '$instagram', '$facebook', '', '', '$descricao_loja',
  '$hora_abre', '$hora_fecha', '$dias_funcionamento', " . $horarios_json . ")";

if (!mysqli_query($conexao, $sql)) {
  $_SESSION['msg_alert'] = ['danger', 'Erro ao criar loja: ' . mysqli_error($conexao)];
  header('Location: ../sign-up-senha.php');
  exit();
}

$loja_id = mysqli_insert_id($conexao);

// handle uploads
$uploadBase = realpath(__DIR__ . '/../../img/lojas');
if (!$uploadBase) $uploadBase = __DIR__ . '/../../img/lojas';
$destDir = $uploadBase . '/' . $loja_id;
if (!is_dir($destDir)) @mkdir($destDir, 0755, true);

$updates = [];

function move_image_field($field, $destDir, $prefix) {
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

$logoName = move_image_field('logo', $destDir, 'logo');
if ($logoName) $updates[] = "logo = '" . mysqli_real_escape_string($conexao, $logoName) . "'";
$capaName = move_image_field('capa', $destDir, 'capa');
if ($capaName) $updates[] = "capa = '" . mysqli_real_escape_string($conexao, $capaName) . "'";

if (count($updates) > 0) {
  $updSql = "UPDATE lojas SET " . implode(',', $updates) . " WHERE id = " . (int)$loja_id;
  mysqli_query($conexao, $updSql);
}

// add creator as active member in equipe so they see/manage the loja
if (isset($_SESSION['id']) && (int)$_SESSION['id'] > 0) {
    $creator = (int)$_SESSION['id'];
    // ensure equipe table exists (idempotent)
    mysqli_query($conexao, "CREATE TABLE IF NOT EXISTS equipe (
        id INT AUTO_INCREMENT PRIMARY KEY,
        loja_id INT NOT NULL,
        usuario_id INT NOT NULL,
        convidado_por INT DEFAULT NULL,
        status CHAR(1) DEFAULT 'P',
        pode_editar_anuncio TINYINT(1) DEFAULT 0,
        pode_responder_mensagem TINYINT(1) DEFAULT 0,
        pode_editar_loja TINYINT(1) DEFAULT 0,
        pode_adicionar_membros TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY(loja_id, usuario_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    // insert active membership for creator (all perms enabled)
    $ins = "INSERT INTO equipe (loja_id, usuario_id, convidado_por, status, pode_editar_anuncio, pode_responder_mensagem, pode_editar_loja, pode_adicionar_membros) VALUES (" . (int)$loja_id . ", " . $creator . ", NULL, 'A', 1, 1, 1, 1)";
    @mysqli_query($conexao, $ins);

    // set owner_id on lojas if column exists (backwards-compatible)
    $colCheck = @mysqli_query($conexao, "SHOW COLUMNS FROM lojas LIKE 'owner_id'");
    if ($colCheck && mysqli_num_rows($colCheck) > 0) {
      @mysqli_query($conexao, "UPDATE lojas SET owner_id = $creator WHERE id = " . (int)$loja_id . " AND (owner_id IS NULL OR owner_id = 0) LIMIT 1");
    }
}

$_SESSION['msg_alert'] = ['success', 'Loja criada com sucesso.'];
header('Location: ../../index.php');
exit();

mysqli_close($conexao);
?>