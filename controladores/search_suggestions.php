<?php
header('Content-Type: application/json; charset=utf-8');

include_once __DIR__ . '/conexao_bd.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if (mb_strlen($q, 'UTF-8') < 2) {
    echo json_encode(['marcas' => [], 'modelos' => []]);
    exit;
}

$q_like = mysqli_real_escape_string($conexao, $q);

// Fetch matching brands
$marcas = [];
$marca_sql = "SELECT nome FROM marcas WHERE nome LIKE '%$q_like%' COLLATE utf8_general_ci";
$mr = mysqli_query($conexao, $marca_sql);
if ($mr) {
    while ($row = mysqli_fetch_assoc($mr)) {
        $marcas[] = $row['nome'];
    }
}

// Fetch matching models with brand
$modelos = [];
$model_sql = "SELECT DISTINCT carros.modelo AS modelo, marcas.nome AS marca
FROM anuncios_carros carros
INNER JOIN marcas ON carros.marca = marcas.id
WHERE CONCAT(marcas.nome, ' ', carros.modelo) LIKE '%$q_like%'
    OR carros.modelo LIKE '%$q_like%'
    OR marcas.nome LIKE '%$q_like%'
LIMIT 200";
$mr2 = mysqli_query($conexao, $model_sql);
if ($mr2) {
    while ($row = mysqli_fetch_assoc($mr2)) {
        $modelos[] = ['modelo' => $row['modelo'], 'marca' => $row['marca']];
    }
}

// Ordering rules
// Brands: prefix matches (stripos === 0) come first, then alphabetical
// Order marcas by position of match (prefix -> position 0 first), then alphabetically
$lcq = mb_strtolower($q, 'UTF-8');
usort($marcas, function($a, $b) use ($lcq) {
    $apos = mb_stripos($a, $lcq, 0, 'UTF-8');
    $bpos = mb_stripos($b, $lcq, 0, 'UTF-8');
    $an = ($apos === false) ? 9999 : $apos;
    $bn = ($bpos === false) ? 9999 : $bpos;
    if ($an !== $bn) return $an - $bn; // smaller position first (prefix = 0)
    return strcasecmp($a, $b);
});

// Models: primary: model prefix match -> top. Within same group, sort by model then brand.
// Order modelos by position of match in modelo (prefix -> position 0 first), then by modelo alphabetically, then marca
usort($modelos, function($x, $y) use ($lcq) {
    $xm = isset($x['modelo']) ? $x['modelo'] : '';
    $ym = isset($y['modelo']) ? $y['modelo'] : '';
    $xpos = mb_stripos($xm, $lcq, 0, 'UTF-8');
    $ypos = mb_stripos($ym, $lcq, 0, 'UTF-8');
    $xn = ($xpos === false) ? 9999 : $xpos;
    $yn = ($ypos === false) ? 9999 : $ypos;
    if ($xn !== $yn) return $xn - $yn; // smaller pos first
    $cmp = strcasecmp($xm, $ym);
    if ($cmp !== 0) return $cmp;
    return strcasecmp($x['marca'], $y['marca']);
});

// Trim results to reasonable size
$marcas = array_slice($marcas, 0, 20);
$modelos = array_slice($modelos, 0, 50);

echo json_encode(['marcas' => $marcas, 'modelos' => $modelos]);

?>