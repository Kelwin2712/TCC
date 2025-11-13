<?php
// Simular exatamente a URL que o usuário testou
$_GET['tipo'] = 'carro';
$_GET['codicao'] = 'usado,seminovo,novo';
$_GET['marca'] = 'bmw';
$_GET['q'] = 'BMW 320i 2.0 16V TURBO FLEX SPORT GP AUTOMÁTICO';
$_GET['modelo'] = '320i';
$_GET['versao'] = '2.0 16V TURBO FLEX SPORT GP AUTOMÁTICO';

include('controladores/conexao_bd.php');

$id = $_SESSION['id'] ?? null;
$quantidade = 36;

$whereParts = [];
$whereParts[] = "ativo = 'A'";

// optional codicao filter (accept single or comma-separated values: novo,seminovo,usado)
if (isset($_GET['codicao']) && trim($_GET['codicao']) !== '') {
  $cod_raw = trim($_GET['codicao']);
  $cod_map = [
    'novo' => 'N',
    'seminovo' => 'S',
    'usado' => 'U'
  ];
  $parts = array_filter(array_map('trim', explode(',', $cod_raw)));
  $codes = [];
  foreach ($parts as $p) {
    $pl = strtolower($p);
    if (isset($cod_map[$pl])) $codes[] = $cod_map[$pl];
  }
  if (count($codes) > 0) {
    $codes_esc = array_map(function($c) use ($conexao){ return mysqli_real_escape_string($conexao, $c); }, $codes);
    $whereParts[] = "carros.condicao IN ('" . implode("','", $codes_esc) . "')";
  }
}

// search query q: match marca, modelo or 'marca modelo' combined
if (isset($_GET['q']) && trim($_GET['q']) !== '') {
  $q_raw = $_GET['q'];
  $q_esc = mysqli_real_escape_string($conexao, $q_raw);
  $whereParts[] = "(marcas.nome LIKE '%$q_esc%' OR carros.modelo LIKE '%$q_esc%' OR CONCAT(marcas.nome, ' ', carros.modelo) LIKE '%$q_esc%')";
}

// optional exact marca filter (accept both name and value)
if (isset($_GET['marca']) && trim($_GET['marca']) !== '') {
  $m = mysqli_real_escape_string($conexao, $_GET['marca']);
  $whereParts[] = "(marcas.nome = '$m' OR marcas.value = '$m')";
}

// optional modelo filter (partial match)
if (isset($_GET['modelo']) && trim($_GET['modelo']) !== '') {
  $mo = mysqli_real_escape_string($conexao, $_GET['modelo']);
  $whereParts[] = "carros.modelo LIKE '%$mo%'";
}

// optional versao filter (match with trim and case-insensitive)
if (isset($_GET['versao']) && trim($_GET['versao']) !== '') {
  $v = mysqli_real_escape_string($conexao, $_GET['versao']);
  $whereParts[] = "TRIM(carros.versao) = TRIM('$v')";
}

$where_sql = count($whereParts) ? ' WHERE ' . implode(' AND ', $whereParts) : '';

echo "<h2>Simulação da URL de teste</h2>";
echo "<p><strong>GET params:</strong></p>";
echo "<pre>";
var_dump($_GET);
echo "</pre>";

echo "<p><strong>WHERE Parts:</strong></p>";
echo "<pre>";
var_dump($whereParts);
echo "</pre>";

$sql = "SELECT 
  carros.*, 
  marcas.nome AS marca_nome,
  IF(favoritos.id IS NULL, 0, 1) AS favoritado
FROM 
  anuncios_carros carros
INNER JOIN 
  marcas ON carros.marca = marcas.id
LEFT JOIN 
  favoritos ON favoritos.anuncio_id = carros.id 
         AND favoritos.usuario_id = '$id'
$where_sql
LIMIT $quantidade";

echo "<p><strong>SQL Executada:</strong></p>";
echo "<pre>" . htmlspecialchars($sql) . "</pre>";

$resultado = mysqli_query($conexao, $sql);

if (!$resultado) {
  echo "<p style='color:red;'><strong>Erro na consulta:</strong> " . mysqli_error($conexao) . "</p>";
} else {
  $count = mysqli_num_rows($resultado);
  echo "<p style='color: " . ($count > 0 ? 'green' : 'red') . ";'><strong>Encontrados: $count carros</strong></p>";
  
  while ($row = mysqli_fetch_assoc($resultado)) {
    echo "<p>ID: " . $row['id'] . " | Modelo: " . $row['modelo'] . " | Versão: '" . $row['versao'] . "' | Condição: " . $row['condicao'] . "</p>";
  }
}

mysqli_close($conexao);
?>
