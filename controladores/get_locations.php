<?php
// Fetch states and cities matching a search query
// Returns JSON with grouped results: states and cities

header('Content-Type: application/json');

include('conexao_bd.php');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($q) < 2) {
  echo json_encode(['states' => [], 'cities' => []]);
  exit;
}

$q_esc = mysqli_real_escape_string($conexao, $q);
$q_lower = strtolower($q);

// Search states (case-insensitive)
$sql_states = "SELECT DISTINCT uf, nome FROM estados WHERE LOWER(nome) LIKE '%$q_lower%' OR LOWER(uf) LIKE '%$q_lower%' ORDER BY nome ASC LIMIT 10";
$result_states = mysqli_query($conexao, $sql_states);
$states = [];
if ($result_states) {
  while ($row = mysqli_fetch_assoc($result_states)) {
    $states[] = [
      'type' => 'estado',
      'label' => $row['nome'] . ' (' . $row['uf'] . ')',
      'value' => $row['uf'],
      'name' => $row['nome']
    ];
  }
}

// Search cities with active cars (case-insensitive)
// Only return cities that have at least one active car
$sql_cities = "
  SELECT DISTINCT ac.cidade, ac.estado_local, e.nome as estado_nome
  FROM anuncios_carros ac
  LEFT JOIN estados e ON ac.estado_local = e.uf
  WHERE ac.ativo = 'A' 
    AND ac.cidade IS NOT NULL 
    AND ac.estado_local IS NOT NULL
    AND (LOWER(ac.cidade) LIKE '%$q_lower%' OR LOWER(e.nome) LIKE '%$q_lower%')
  ORDER BY ac.cidade ASC
  LIMIT 10
";
$result_cities = mysqli_query($conexao, $sql_cities);
$cities = [];
if ($result_cities) {
  while ($row = mysqli_fetch_assoc($result_cities)) {
    $label = $row['cidade'] . ' - ' . $row['estado_local'];
    if ($row['estado_nome']) {
      $label = $row['cidade'] . ' - ' . $row['estado_nome'];
    }
    $cities[] = [
      'type' => 'cidade',
      'label' => $label,
      'value' => $row['estado_local'] . '|' . $row['cidade'],
      'cidade' => $row['cidade'],
      'estado' => $row['estado_local']
    ];
  }
}

echo json_encode([
  'states' => $states,
  'cities' => $cities
]);

mysqli_close($conexao);
?>
