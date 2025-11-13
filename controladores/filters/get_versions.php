<?php
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/../conexao_bd.php';
$marca = $_GET['marca'] ?? '';
$modelo = $_GET['modelo'] ?? '';
$marca = trim($marca);
$modelo = trim($modelo);
if ($marca === '' || $modelo === '') { echo json_encode([]); exit; }

$marca_esc = mysqli_real_escape_string($conexao, $marca);
$modelo_esc = mysqli_real_escape_string($conexao, $modelo);
// try to find marca id
$qr = mysqli_query($conexao, "SELECT id FROM marcas WHERE value = '$marca_esc' OR nome = '$marca_esc' LIMIT 1");
$marca_id = null;
if ($qr && mysqli_num_rows($qr) > 0) {
    $r = mysqli_fetch_assoc($qr);
    $marca_id = $r['id'];
}

$where = "";
if ($marca_id) {
    $where = "WHERE carros.marca = $marca_id AND carros.modelo LIKE '%$modelo_esc%' AND carros.ativo = 'A'";
} else {
    $where = "WHERE marcas.nome LIKE '%$marca_esc%' AND carros.modelo LIKE '%$modelo_esc%' AND carros.ativo = 'A'";
}

$sql = "SELECT DISTINCT carros.versao AS versao FROM anuncios_carros carros INNER JOIN marcas ON carros.marca = marcas.id $where ORDER BY carros.versao ASC";
$res = mysqli_query($conexao, $sql);
$out = [];
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        if (trim($row['versao']) !== '') $out[] = $row['versao'];
    }
}

echo json_encode($out);
?>