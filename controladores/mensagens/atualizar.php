<?php
session_start();
include('../conexao_bd.php');

$usuario = $_SESSION['id'];

$sql = "SELECT nao_lidas_comprador, vendedor_id, anuncio_id FROM conversas WHERE comprador_id = '$usuario' AND nao_lidas_comprador > 0";
$resultado = mysqli_query($conexao, $sql);

$conv = [];

while ($linha = mysqli_fetch_assoc($resultado)) {
    $conv[] = $linha;
}

$sql = "SELECT nao_lidas_vendedor, comprador_id, anuncio_id FROM conversas WHERE vendedor_id = '$usuario' AND nao_lidas_vendedor > 0";
$resultado = mysqli_query($conexao, $sql);

while ($linha = mysqli_fetch_assoc($resultado)) {
    $conv[] = $linha;
}

echo json_encode($conv);
?>