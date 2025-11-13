<?php
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/conexao_bd.php';

// total active anuncios
$total = 0;
$qr = mysqli_query($conexao, "SELECT COUNT(*) as total FROM anuncios_carros WHERE ativo = 'A'");
if ($qr) {
    $r = mysqli_fetch_assoc($qr);
    $total = (int)$r['total'];
}

// marcas
$marcas = [];
$mr = mysqli_query($conexao, "SELECT DISTINCT nome FROM marcas ORDER BY nome ASC");
if ($mr) {
    while ($row = mysqli_fetch_assoc($mr)) {
        $marcas[] = $row['nome'];
    }
}

// modelos with brand
$modelos = [];
$mr2 = mysqli_query($conexao, "SELECT DISTINCT carros.modelo AS modelo, marcas.nome AS marca
    FROM anuncios_carros carros
    INNER JOIN marcas ON carros.marca = marcas.id
    ORDER BY carros.modelo ASC, marcas.nome ASC");
if ($mr2) {
    while ($row = mysqli_fetch_assoc($mr2)) {
        $modelos[] = ['modelo' => $row['modelo'], 'marca' => $row['marca']];
    }
}

echo json_encode(['total' => $total, 'marcas' => $marcas, 'modelos' => $modelos]);

?>