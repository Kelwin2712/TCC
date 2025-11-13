<?php
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/../conexao_bd.php';
$marca = $_GET['marca'] ?? '';
$marca = trim($marca);
if ($marca === '') { echo json_encode([]); exit; }

$marca_esc = mysqli_real_escape_string($conexao, $marca);
// try to find marca id by value or name
$qr = mysqli_query($conexao, "SELECT id FROM marcas WHERE value = '$marca_esc' OR nome = '$marca_esc' LIMIT 1");
$marca_id = null;
if ($qr && mysqli_num_rows($qr) > 0) {
    $r = mysqli_fetch_assoc($qr);
    $marca_id = $r['id'];
}

$where = "";
if ($marca_id) {
    $where = "WHERE carros.marca = $marca_id AND carros.ativo = 'A'";
} else {
    // fallback: match by marca name in marcas or in anuncios via join
    $where = "WHERE marcas.nome LIKE '%$marca_esc%' AND carros.ativo = 'A'";
}

$sql = "SELECT DISTINCT carros.modelo AS modelo FROM anuncios_carros carros INNER JOIN marcas ON carros.marca = marcas.id $where ORDER BY carros.modelo ASC";
$res = mysqli_query($conexao, $sql);
$out = [];
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        if (trim($row['modelo']) !== '') $out[] = $row['modelo'];
    }
}

echo json_encode($out);
?>