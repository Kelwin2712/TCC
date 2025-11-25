<?php session_start();
include('controladores/conexao_bd.php');
$filter_check_caminho = 'estruturas/filter/filter-checkbox.php';

$id = $_SESSION['id'] ?? null;

$tipo = $_GET['tipo'] ?? 'carro';
$codicao = $_GET['codicao'] ?? 'usado';
$categoria = $_GET['categoria'] ?? null;
$vendedor_id = isset($_GET['vendedor_id']) ? (int)$_GET['vendedor_id'] : null;

// If vendedor_id is present, fetch vendedor info from database
$vendedor = null;
$vendedor_img = null;
$vendedor_seg = 0;
if ($vendedor_id) {
    $q_vend = mysqli_query($conexao, "SELECT usuarios.nome, usuarios.sobrenome, usuarios.avatar, COALESCE(usuarios.seguidores, 0) AS seguidores FROM usuarios WHERE id = $vendedor_id");
    if ($q_vend && mysqli_num_rows($q_vend) > 0) {
        $vend_row = mysqli_fetch_assoc($q_vend);
        $vendedor = trim($vend_row['nome'] . ' ' . $vend_row['sobrenome']);
        $vendedor_img = $vend_row['avatar'] ?: 'img/usuarios/avatares/user.png';
        $vendedor_seg = (int)$vend_row['seguidores'];
    }
}

// Get URL params for lateral filters (marca, modelo, versao)
$url_marca = $_GET['marca'] ?? '';
$url_modelo = $_GET['modelo'] ?? '';
$url_versao = $_GET['versao'] ?? '';

$page = $_GET['page'] ?? 1;

$quantidade = 3;
$quantidade = $quantidade * 12;

// sorting
$sort = $_GET['sort'] ?? 'relevancia';
$dir = $_GET['dir'] ?? 'desc';
$dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';


$order_sql = '';
if ($sort === 'preco') {
  $order_sql = " ORDER BY carros.preco " . ($dir === 'asc' ? 'ASC' : 'DESC');
} elseif ($sort === 'ano') {
  // order by fabrication year, newer first by default
  $order_sql = " ORDER BY carros.ano_fabricacao " . ($dir === 'asc' ? 'ASC' : 'DESC');
} elseif ($sort === 'km') {
  $order_sql = " ORDER BY carros.quilometragem " . ($dir === 'asc' ? 'ASC' : 'DESC');
}

$whereParts = [];
$whereParts[] = "ativo = 'A'";

// If vendedor_id is set, filter by this vendor
if ($vendedor_id) {
    $whereParts[] = "carros.id_vendedor = $vendedor_id";
}

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
    $codes_esc = array_map(function ($c) use ($conexao) {
      return mysqli_real_escape_string($conexao, $c);
    }, $codes);
    $whereParts[] = "carros.condicao IN ('" . implode("','", $codes_esc) . "')";
  }
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

// search query q: match marca, modelo or 'marca modelo' combined
// Only apply q filter if marca AND modelo are NOT explicitly set (to avoid conflicting with lateral filters)
if (isset($_GET['q']) && trim($_GET['q']) !== '' && !isset($_GET['marca']) && !isset($_GET['modelo'])) {
  $q_raw = $_GET['q'];
  $q_esc = mysqli_real_escape_string($conexao, $q_raw);
  $whereParts[] = "(marcas.nome LIKE '%$q_esc%' OR carros.modelo LIKE '%$q_esc%' OR CONCAT(marcas.nome, ' ', carros.modelo) LIKE '%$q_esc%')";
}

// optional versao filter (match with trim and case-insensitive)
if (isset($_GET['versao']) && trim($_GET['versao']) !== '') {
  $v = mysqli_real_escape_string($conexao, $_GET['versao']);
  $whereParts[] = "TRIM(carros.versao) = TRIM('$v')";
}

// optional estado_local filter (state code, 2 chars)
if (isset($_GET['estado_local']) && trim($_GET['estado_local']) !== '') {
  $estado = mysqli_real_escape_string($conexao, trim($_GET['estado_local']));
  if (strlen($estado) === 2 && ctype_alpha($estado)) {
    $whereParts[] = "carros.estado_local = '" . strtoupper($estado) . "'";
  }
}

// optional cidade filter (city name)
if (isset($_GET['cidade']) && trim($_GET['cidade']) !== '') {
  $cidade = mysqli_real_escape_string($conexao, $_GET['cidade']);
  $whereParts[] = "carros.cidade = '$cidade'";
}

// optional cor filter (accept single or comma-separated color ids)
if (isset($_GET['cor']) && trim($_GET['cor']) !== '') {
  $cor_raw = trim($_GET['cor']);
  $parts_cor = array_filter(array_map('trim', explode(',', $cor_raw)));
  $cor_ids = [];
  foreach ($parts_cor as $c) {
    if (ctype_digit($c)) $cor_ids[] = $c;
  }
  if (count($cor_ids) > 0) {
    $cor_esc = array_map(function ($c) use ($conexao) {
      return mysqli_real_escape_string($conexao, $c);
    }, $cor_ids);
    $whereParts[] = "carros.cor IN ('" . implode("','", $cor_esc) . "')";
  }
}

// optional carroceria filter (accept single or comma-separated carroceria ids)
if (isset($_GET['carroceria']) && trim($_GET['carroceria']) !== '') {
  $carroceria_raw = trim($_GET['carroceria']);
  $parts_car = array_filter(array_map('trim', explode(',', $carroceria_raw)));
  $carroceria_ids = [];
  foreach ($parts_car as $c) {
    if (ctype_digit($c)) $carroceria_ids[] = $c;
  }
  if (count($carroceria_ids) > 0) {
    $carroceria_esc = array_map(function ($c) use ($conexao) {
      return mysqli_real_escape_string($conexao, $c);
    }, $carroceria_ids);
    $whereParts[] = "carros.carroceria IN ('" . implode("','", $carroceria_esc) . "')";
  }
}

// optional preco (price) range filter: accept numeric values for min and/or max
// URL params: preco_min, preco_max (numbers in BRL with optional k/kk suffixes, decimals ignored)
if ((isset($_GET['preco_min']) && trim($_GET['preco_min']) !== '') || (isset($_GET['preco_max']) && trim($_GET['preco_max']) !== '')) {
  // Parse price with k/kk support: "200k" -> 200000, "1.5kk" -> 1500000, "50k" -> 50000
  $parsePriceWithSuffix = function ($s) {
    if (empty($s)) return null;
    $s = strtolower(trim($s));
    $digits_only = preg_replace('/[^0-9,.]/', '', $s);
    $digits_only = str_replace([',', '.'], '', $digits_only); // remove separators
    $num = (int)$digits_only;
    if (strpos($s, 'kk') !== false) $num *= 1000000;
    elseif (strpos($s, 'k') !== false) $num *= 1000;
    return $num;
  };
  $pmin = isset($_GET['preco_min']) ? $parsePriceWithSuffix($_GET['preco_min']) : null;
  $pmax = isset($_GET['preco_max']) ? $parsePriceWithSuffix($_GET['preco_max']) : null;
  if ($pmin !== null && $pmax !== null) {
    if ($pmin > $pmax) {
      $tmp = $pmin;
      $pmin = $pmax;
      $pmax = $tmp;
    }
    $whereParts[] = "carros.preco BETWEEN $pmin AND $pmax";
  } elseif ($pmin !== null) {
    $whereParts[] = "carros.preco >= $pmin";
  } elseif ($pmax !== null) {
    $whereParts[] = "carros.preco <= $pmax";
  }
}

// optional quilometragem (km) range filter: km_min, km_max
if ((isset($_GET['km_min']) && trim($_GET['km_min']) !== '') || (isset($_GET['km_max']) && trim($_GET['km_max']) !== '')) {
  $kmin = isset($_GET['km_min']) ? preg_replace('/\D/', '', $_GET['km_min']) : '';
  $kmax = isset($_GET['km_max']) ? preg_replace('/\D/', '', $_GET['km_max']) : '';
  $kmin = $kmin === '' ? null : (int)$kmin;
  $kmax = $kmax === '' ? null : (int)$kmax;
  if ($kmin !== null && $kmax !== null) {
    if ($kmin > $kmax) {
      $tmp = $kmin;
      $kmin = $kmax;
      $kmax = $tmp;
    }
    $whereParts[] = "carros.quilometragem BETWEEN $kmin AND $kmax";
  } elseif ($kmin !== null) {
    $whereParts[] = "carros.quilometragem >= $kmin";
  } elseif ($kmax !== null) {
    $whereParts[] = "carros.quilometragem <= $kmax";
  }
}

// optional ano (year) range filter applied to ano_fabricacao: ano_min, ano_max
if ((isset($_GET['ano_min']) && trim($_GET['ano_min']) !== '') || (isset($_GET['ano_max']) && trim($_GET['ano_max']) !== '')) {
  $amin = isset($_GET['ano_min']) ? preg_replace('/\D/', '', $_GET['ano_min']) : '';
  $amax = isset($_GET['ano_max']) ? preg_replace('/\D/', '', $_GET['ano_max']) : '';
  $amin = $amin === '' ? null : (int)$amin;
  $amax = $amax === '' ? null : (int)$amax;
  if ($amin !== null && $amax !== null) {
    if ($amin > $amax) {
      $tmp = $amin;
      $amin = $amax;
      $amax = $tmp;
    }
    $whereParts[] = "carros.ano_fabricacao BETWEEN $amin AND $amax";
  } elseif ($amin !== null) {
    $whereParts[] = "carros.ano_fabricacao >= $amin";
  } elseif ($amax !== null) {
    $whereParts[] = "carros.ano_fabricacao <= $amax";
  }
}

// optional cambio filter (Automático / Manual)
if (isset($_GET['cambio']) && trim($_GET['cambio']) !== '') {
  $cambio_raw = trim($_GET['cambio']);
  $parts_cam = array_filter(array_map('trim', explode(',', $cambio_raw)));
  $mapCam = [
    'aut' => 'A',
    'man' => 'M'
  ];
  $camVals = [];
  foreach ($parts_cam as $c) {
    $cl = strtolower($c);
    if (isset($mapCam[$cl])) $camVals[] = $mapCam[$cl];
  }
  if (count($camVals) > 0) {
    $camVals_esc = array_map(function ($c) use ($conexao) {
      return mysqli_real_escape_string($conexao, $c);
    }, $camVals);
    $whereParts[] = "carros.cambio IN ('" . implode("','", $camVals_esc) . "')";
  }
}

// optional blindagem filter (Sim / Não)
if (isset($_GET['blindagem']) && trim($_GET['blindagem']) !== '') {
  $blind_raw = trim($_GET['blindagem']);
  $parts_bli = array_filter(array_map('trim', explode(',', $blind_raw)));
  $mapBli = [
    'bli' => '1',
    'n-bli' => '0'
  ];
  $bliVals = [];
  foreach ($parts_bli as $b) {
    $bl = strtolower($b);
    if (isset($mapBli[$bl])) $bliVals[] = $mapBli[$bl];
  }
  if (count($bliVals) > 0) {
    $bliVals_esc = array_map(function ($c) use ($conexao) {
      return mysqli_real_escape_string($conexao, $c);
    }, $bliVals);
    $whereParts[] = "carros.blindagem IN ('" . implode("','", $bliVals_esc) . "')";
  }
}

// optional propulsao filter (parent categories: comb, hib, ele)
// and optional combustivel filter (subtypes: gas, alc, fle, die, gnv, hev, phe, mhe, ele)
// When both parent(s) and subtype(s) are present, combine them with OR so
// the query returns cars matching either the selected propulsao parents
// or the selected combustivel subtypes (e.g., Elétrico OR Gasolina).

$propClause = '';
$combClause = '';

if (isset($_GET['propulsao']) && trim($_GET['propulsao']) !== '') {
  $parts_prop = array_filter(array_map('trim', explode(',', $_GET['propulsao'])));
  $map = [
    'comb' => 'combustao',
    'hib' => 'hibrido',
    'ele' => 'eletrico'
  ];
  $vals = [];
  foreach ($parts_prop as $p) {
    $pl = strtolower($p);
    if (isset($map[$pl])) $vals[] = $map[$pl];
  }
  if (count($vals) > 0) {
    $vals_esc = array_map(function ($c) use ($conexao) {
      return mysqli_real_escape_string($conexao, $c);
    }, $vals);
    $propClause = "carros.propulsao IN ('" . implode("','", $vals_esc) . "')";
  }
}

if (isset($_GET['combustivel']) && trim($_GET['combustivel']) !== '') {
  $parts_comb = array_filter(array_map('trim', explode(',', $_GET['combustivel'])));
  $mapc = [
    'gas' => 'Gasolina',
    'alc' => 'Álcool',
    'fle' => 'Flex',
    'die' => 'Diesel',
    'gnv' => 'GNV',
    'hev' => 'HEV',
    'phe' => 'PHEV',
    'mhe' => 'MHEV',
    'ele' => 'Elétrico'
  ];
  $cvals = [];
  foreach ($parts_comb as $c) {
    $cl = strtolower($c);
    if (isset($mapc[$cl])) $cvals[] = $mapc[$cl];
  }
  if (count($cvals) > 0) {
    $cvals_esc = array_map(function ($c) use ($conexao) {
      return mysqli_real_escape_string($conexao, $c);
    }, $cvals);
    $combClause = "carros.combustivel IN ('" . implode("','", $cvals_esc) . "')";
  }
}

// Add combined clause: if both exist, wrap with OR to avoid impossible AND
if ($propClause !== '' && $combClause !== '') {
  $whereParts[] = "(" . $propClause . " OR " . $combClause . ")";
} elseif ($propClause !== '') {
  $whereParts[] = $propClause;
} elseif ($combClause !== '') {
  $whereParts[] = $combClause;
}

$where_sql = count($whereParts) ? ' WHERE ' . implode(' AND ', $whereParts) : '';

$countSql = "SELECT COUNT(*) AS total FROM anuncios_carros carros INNER JOIN marcas ON carros.marca = marcas.id" . $where_sql;
$countRes = mysqli_query($conexao, $countSql);
if (!$countRes) {
  die("Erro na contagem de resultados: " . mysqli_error($conexao));
}
$countRow = mysqli_fetch_assoc($countRes);
$total_results = isset($countRow['total']) ? (int)$countRow['total'] : 0;

// pagination calculations
$per_page = (int)$quantidade; // already defined above
$total_pages = $per_page > 0 ? max(1, (int)ceil($total_results / $per_page)) : 1;
$page = max(1, (int)$page);
if ($page > $total_pages) $page = $total_pages;
$offset = ($page - 1) * $per_page;

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
         AND favoritos.usuario_id = '$id'" . $where_sql . $order_sql . "\nLIMIT $offset, $per_page";

$resultado = mysqli_query($conexao, $sql);
if (!$resultado) {
  die("Erro na consulta SQL: " . mysqli_error($conexao));
}

$carros = [];
$qtd_resultados = mysqli_num_rows($resultado) ?? 0;
while ($linha = mysqli_fetch_array($resultado)) {
  $carros[] = $linha;
}

$sql = "SELECT value, nome FROM marcas";
$resultado = mysqli_query($conexao, $sql);

$marcas = [];

while ($linha = mysqli_fetch_array($resultado)) {
  $marcas[] = $linha;
}
// fetch available colors for sidebar filter
$sql = "SELECT id, nome FROM cores";
$resultado = mysqli_query($conexao, $sql);
$cores = [];
if ($resultado) {
  while ($linha = mysqli_fetch_array($resultado)) {
    $cores[] = $linha;
  }
}
// fetch available carrocerias for sidebar filter
$sql = "SELECT id, nome FROM carrocerias";
$resultado = mysqli_query($conexao, $sql);
$carrocerias = [];
if ($resultado) {
  while ($linha = mysqli_fetch_array($resultado)) {
    $carrocerias[] = $linha;
  }
}
// keep DB connection open to fetch fotos for each anuncio below
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="style.css">
  <title>Fahren</title>
</head>
<style>
  .accordion-button:not(.collapsed) {
    background-color: transparent;
    color: #212529;
  }

  .card-compra .carousel {
    z-index: 2;
  }

  .card-compra .favoritar-btn {
    z-index: 3;
  }

  /* Offcanvas full-screen (top to bottom) for mobile filters */
  .offcanvas-top.vh-100 {
    height: 100vh !important;
    max-height: 100vh !important;
    top: 0 !important;
  }

  @media (max-width: 991.98px) {
    .offcanvas-top .offcanvas-body {
      padding: 0.5rem 1rem 2.5rem 1rem;
      overflow: auto;
    }
  }

  /* Pagination disabled look */
  .pagination .page-item.disabled .page-link,
  .pagination .page-link.disabled {
    opacity: 0.45;
    pointer-events: none;
  }

  .pagination .page-link {
    transition: opacity .15s ease-in-out;
  }

  /* No-results responsive container */
  .no-results-container .no-results-inner {
    width: 100%;
    padding: 2rem 1rem;
  }

  @media (min-width: 992px) {

    /* On large screens let the block size naturally so it fits the sidebar
       and leaves space above the footer. Avoid forcing full-viewport height. */
    .no-results-container {
      min-height: auto;
      margin-top: 1rem;
      display: block;
    }
  }

  @media (max-width: 991.98px) {

    /* On small screens ensure the message fits without revealing the footer */
    .no-results-container {
      min-height: auto;
      display: block;
    }

    .no-results-container .no-results-inner {
      padding-top: 1.25rem;
      padding-bottom: 1.25rem;
    }
  }
</style>

<body>
  <?php
  include 'estruturas/top-button/top-button.php' ?>
  <?php
  $float = true;
  include 'estruturas/navbar/navbar-compras.php' ?>
  <main class="bg-body-tertiary fs-nav">
    <div class="container-fluid">
      <div class="row d-flex justify-content-center">
        <div class="col-12 col-lg-10 col-lg-10">
          <div class="row pt-5 pb-3 pb-lg-0">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
              <ol class="breadcrumb" id="dynamic-breadcrumb">
                <li class="breadcrumb-item"><a href="." class="link-dark link-underline-opacity-0 link-underline-opacity-100-hover">Home</a></li>
              </ol>

            </nav>
          </div>
          <div class="row px-3 pt-3 border-top d-flex d-lg-none">
            <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom" class="btn bg-white btn-sm border rounded-pill px-3 w-auto"><i class="bi bi-funnel me-2"></i></i>Filtros</button>
          </div>
          <!-- Offcanvas (mobile) for filters: will receive the existing #filtros-over element at runtime -->
          <div class="offcanvas offcanvas-top vh-100" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
            <div class="offcanvas-header">
              <h5 class="offcanvas-title" id="offcanvasBottomLabel">Filtros</h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
              <!-- filters (moved from sidebar) -->
            </div>
          </div>
          <div class="row g-4">
            <div id="filtros-col" class="col-4 col-xl-3 col-xxl-2 vh-100 position-sticky top-0 pt-4 d-lg-flex flex-column d-none" style="max-height: 100vh;">
              <div id="filtros-over" class="overflow-y-auto rounded-2 border border-opacity-25 shadow-sm" style="max-height: 100%;">
                <div class="accordion w-100" id="accordionPanelsStayOpenExample">
                  <?php if (isset($vendedor)): ?>
                    <!-- Vendedor ⬇️ -->
                    <div class="accordion-item border-0 border-bottom">
                      <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#vendedor" aria-expanded="true" aria-controls="vendedor">
                          Vendedor
                        </button>
                      </h2>
                      <div id="vendedor" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                          <div class="row">
                            <div class="rounded-3 border-2">
                              <div class="row">
                                <div class="col p-2 d-flex align-items-center justify-content-center">
                                  <div class="ratio ratio-1x1">
                                    <img src="<?= $vendedor_img ?>" alt="" class="img-fluid rounded-3 shadow-sm">
                                  </div></i>
                                </div>
                                <div class="col-7 py-2">
                                  <div class="row">
                                    <p class="fw-semibold mb-0"><?= $vendedor ?></p>
                                  </div>
                                  <div class="row">
                                    <small title="Seguidores" class="fw-semibold mb-0"><i class="bi bi-person-fill me-1"></i><?= $vendedor_seg ?></small>
                                  </div>
                                </div>
                                <div class="col-3 d-inline-flex align-items-center text-nowrap">
                                  <small>Aberto <i class="bi bi-circle-fill text-success" style="font-size: 0.5rem !important; vertical-align: middle;"></i></small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                  <!-- Modelo ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#modelo" aria-expanded="true" aria-controls="modelo">
                        Modelo
                      </button>
                    </h2>
                    <div id="modelo" class="accordion-collapse collapse show">
                      <div class="accordion-body">
                        <div class="row px-1 mb-3">
                          <div class="mb-1">
                            <h6>Localização</h6>
                          </div>
                          <div class="row px-2 g-0 gap-2 position-relative">
                            <div class="position-relative w-100">
                              <input type="text" class="form-control" id="localizacao" autocomplete="off" placeholder="Informe o estado ou cidade">
                              <button type="button" class="btn btn-sm position-absolute" id="localizacao-clear" style="right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #999; display: none;">
                                <i class="bi bi-x-circle-fill"></i>
                              </button>
                            </div>
                            <div id="localizacao-suggestions" class="position-absolute w-100" style="top: 100%; left: 16px; right: 16px; background: white; border: 1px solid #dee2e6; border-top: none; border-radius: 0 0 0.375rem 0.375rem; max-height: 300px; overflow-y: auto; z-index: 1000; display: none; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);">
                            </div>
                          </div>
                        </div>
                        <div class="row px-1">
                          <div class="mb-3">
                            <h6>Estado</h6>
                            <div class="row ps-3">
                              <div class="form-check">
                                <input class="form-check-input condicao-input" type="checkbox" id="usados" data-val="usado">
                                <label class="form-check-label" for="usados">
                                  Usados
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input condicao-input" type="checkbox" id="seminovos" data-val="seminovo">
                                <label class="form-check-label" for="seminovos">
                                  Seminovos
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input condicao-input" type="checkbox" id="novos" data-val="novo">
                                <label class="form-check-label" for="novos">
                                  Novos
                                </label>
                              </div>
                            </div>
                          </div>
                          <hr class="mb-4">
                          <div class="row px-1">
                            <div id="marcas-input" class="mb-3">
                              <h6>Marcas</h6>
                              <div class="input-group">
                                <select id="marca-select" name="" class="form-select">
                                  <option value="" selected>Todas as marcas</option>
                                  <?php foreach ($marcas as $marca): ?>
                                    <option value="<?= $marca['value'] ?>" <?php if ($url_marca === $marca['value']) echo 'selected'; ?>><?= $marca['nome'] ?></option>
                                  <?php endforeach; ?>
                                </select>
                                <button class="btn bg-white border <?php echo empty($url_marca) ? 'd-none' : ''; ?>">X</button>
                              </div>
                            </div>
                            <div id="modelos-input" class="mb-3 <?php echo empty($url_marca) ? 'd-none' : ''; ?>">
                              <h6>Modelos</h6>
                              <div class="input-group">
                                <select name="modelo" id="modelo-select" class="form-select">
                                  <option value="" selected>Todos os modelos</option>
                                </select>
                                <button class="btn bg-white border <?php echo empty($url_modelo) ? 'd-none' : ''; ?>">X</button>
                              </div>
                            </div>
                            <div id="versoes-input" class="mb-3 <?php echo empty($url_modelo) ? 'd-none' : ''; ?>">
                              <h6>Versões</h6>
                              <div class="input-group">
                                <select name="versao" id="versao-select" class="form-select">
                                  <option value="" selected>Todas as versões</option>
                                </select>
                                <button class="btn bg-white border <?php echo empty($url_versao) ? 'd-none' : ''; ?>">X</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <!-- Preço ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#preco" aria-expanded="true" aria-controls="preco">
                        Preço
                      </button>
                    </h2>
                    <div id="preco" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row">
                          <div class="mb-1">
                            <h6>Intervalo de preço</h6>
                          </div>
                          <div class="row px-2 g-0 gap-2">
                            <div class="col">
                              <input type="text" class="form-control preco-input-rs" id="preco-min" placeholder="Preço mínimo">
                            </div>
                            <div class="col">
                              <input type="text" class="form-control preco-input-rs" id="preco-max" placeholder="Preço máximo">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <!-- Ano ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ano" aria-expanded="true" aria-controls="ano">
                        Ano
                      </button>
                    </h2>
                    <div id="ano" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row">
                          <div class="mb-1">
                            <h6>Intervalo de tempo</h6>
                          </div>
                          <div class="row px-2 g-0 gap-2">
                            <div class="col">
                              <input type="text" inputmode="numeric" maxlength="4" class="form-control" id="ano-min" placeholder="Ano mínimo">
                            </div>
                            <div class="col">
                              <input type="text" inputmode="numeric" maxlength="4" class="form-control" id="ano-max" placeholder="Ano máximo">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <!-- Quilometragem ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#km" aria-expanded="true" aria-controls="km">
                        Quilometragem
                      </button>
                    </h2>
                    <div id="km" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row">
                          <div class="mb-1">
                            <h6>Intervalo de quilometragem</h6>
                          </div>
                          <div class="row px-2 g-0 gap-2">
                            <div class="col">
                              <input type="text" class="form-control" id="km-min" placeholder="km mínimo">
                            </div>
                            <div class="col">
                              <input type="text" class="form-control" id="km-max" placeholder="km máximo">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <!-- Cor ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cor" aria-expanded="true" aria-controls="cor">
                        Cor
                      </button>
                    </h2>
                    <div id="cor" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row ps-3">
                          <?php if (!empty($cores)): ?>
                            <?php foreach ($cores as $cor): ?>
                              <div class="form-check col-12">
                                <input class="form-check-input cor-input" type="checkbox" id="cor-<?= $cor['id'] ?>" data-val="<?= $cor['id'] ?>">
                                <label class="form-check-label" for="cor-<?= $cor['id'] ?>"><?= htmlspecialchars($cor['nome']) ?></label>
                              </div>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <div class="form-text">Sem cores cadastradas</div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Carroceria ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#carroceria" aria-expanded="false" aria-controls="carroceria">
                        Carroceria
                      </button>
                    </h2>
                    <div id="carroceria" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row ps-3">
                          <?php if (!empty($carrocerias)): ?>
                            <?php foreach ($carrocerias as $carroceria): ?>
                              <div class="form-check col-12">
                                <input class="form-check-input carroceria-input" type="checkbox" id="carroceria-<?= $carroceria['id'] ?>" data-val="<?= $carroceria['id'] ?>">
                                <label class="form-check-label" for="carroceria-<?= $carroceria['id'] ?>"><?= htmlspecialchars($carroceria['nome']) ?></label>
                              </div>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <div class="form-text">Sem carrocerias cadastradas</div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Propulsão ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button<?php if ($categoria != 'ele' and $categoria != 'hib') {
                                                        echo ' collapsed';
                                                      } ?>" type="button" data-bs-toggle="collapse" data-bs-target="#propulsao" aria-expanded="true" aria-controls="propulsao">
                        Propulsão
                      </button>
                    </h2>
                    <div id="propulsao" class="accordion-collapse collapse<?php if ($categoria == 'ele' or $categoria == 'hib') {
                                                                            echo ' show';
                                                                          } ?>">
                      <div class="accordion-body">
                        <div class="row ps-3">
                          <?php
                          $text = 'Combustão';
                          $id = 'comb';
                          include $filter_check_caminho ?>
                          <div id="comb-tipos" class="ps-3">
                            <?php
                            $text = 'Gasolina';
                            $id = 'gas';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'Álcool';
                            $id = 'alc';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'Flex';
                            $id = 'fle';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'Diesel';
                            $id = 'die';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'GNV';
                            $id = 'gnv';
                            include $filter_check_caminho;
                            ?>
                          </div>
                          <?php
                          $text = 'Híbrido';
                          $id = 'hib';
                          include $filter_check_caminho ?>
                          <div id="hib-tipos" class="ps-3">
                            <?php
                            $text = 'HEV';
                            $id = 'hev';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'PHEV';
                            $id = 'phe';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'MHEV';
                            $id = 'mhe';
                            include $filter_check_caminho;
                            ?>
                          </div>
                          <?php
                          $text = 'Elétrico';
                          $id = 'ele';
                          include $filter_check_caminho ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Câmbio ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cambio" aria-expanded="true" aria-controls="cambio">
                        Câmbio
                      </button>
                    </h2>
                    <div id="cambio" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row ps-3">
                          <?php
                          $text = 'Automático';
                          $id = 'aut';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Manual';
                          $id = 'man';
                          include $filter_check_caminho ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Blindagem ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#blindagem" aria-expanded="true" aria-controls="blindagem">
                        Blindagem
                      </button>
                    </h2>
                    <div id="blindagem" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row ps-3">
                          <?php
                          $text = 'Sim';
                          $id = 'bli';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Não';
                          $id = 'n-bli';
                          include $filter_check_caminho ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <?php if (isset($vendedor)): ?>
                <?php
                // try to load loja by name and display its capa if available
                $vendedor_esc = mysqli_real_escape_string($conexao, $vendedor);
                $qr = mysqli_query($conexao, "SELECT id, capa FROM lojas WHERE nome = '$vendedor_esc' LIMIT 1");
                $capaPath = null;
                if ($qr && mysqli_num_rows($qr) > 0) {
                  $row = mysqli_fetch_assoc($qr);
                  if (!empty($row['capa'])) {
                    $possible = 'img/lojas/' . $row['id'] . '/' . $row['capa'];
                    if (is_file(__DIR__ . '/' . $possible)) $capaPath = $possible;
                    else $capaPath = $possible; // keep path even if file not present
                  }
                }
                ?>
                <?php if (isset($capaPath)):?>
                <div class="capa-loja mt-4">
                  <img src="<?= $capaPath?>" class="w-100 border h-auto object-fit-cover rounded-4" style="aspect-ratio: 1/.125;">
                </div>
                <?php endif?>
              <?php endif ?>
              <div class="row pt-4">
                <div class="col-auto me-auto">
                  <div class="fw-semibold small py-3">
                    <?= $qtd_resultados; ?> resultados encontrados
                  </div>
                </div>
                <div class="col-auto d-flex align-items-center mb-3">
                  <button id="ordenar-btn" class="btn btn-light border me-2" disabled><i class="bi bi-filter"></i></button>
                  <div class="small">Ordenar por: </div>
                  <div class="col-auto">
                    <select id="ordenar-input" class="form-select form-select-sm bg-transparent border-0 fw-semibold">
                      <option value="relevancia" <?php if ($sort === 'relevancia') echo 'selected'; ?>>Relevância</option>
                      <option value="preco" <?php if ($sort === 'preco') echo 'selected'; ?>>Preço</option>
                      <option value="ano" <?php if ($sort === 'ano') echo 'selected'; ?>>Ano</option>
                      <option value="km" <?php if ($sort === 'km') echo 'selected'; ?>>KM</option>
                    </select>
                  </div>
                </div>
              </div>
              <div id="area-compra" class="row <?= empty($carros) ? '' : 'row-cols-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-6 g-3 g-lg-2' ?>">
                <?php if (!empty($carros)): ?>
                  <?php
                  foreach ($carros as $carro): ?>
                    <div class="col">
                      <?php
                      $marca = $carro['marca_nome'];
                      $modelo = $carro['modelo'];
                      $versao = $carro['versao'];
                      $preco = $carro['preco'];
                      $ano = $carro['ano_fabricacao'] . '/' . $carro['ano_modelo'];
                      $km = $carro['quilometragem'];
                      $cor = $carro['cor'];
                      $troca = $carro['aceita_troca'];
                      $revisao = $carro['revisao'];
                      $id = $carro['id'];
                      $loc = $carro['cidade'] . ' - '  . $carro['estado_local'];
                      // fetch photos for this anuncio into $imgs array (no predefined img1..img6)
                      $imgs = [];
                      $qr = mysqli_query($conexao, "SELECT caminho_foto FROM fotos_carros WHERE carro_id = $id ORDER BY `ordem` ASC");
                      if ($qr && mysqli_num_rows($qr) > 0) {
                        while ($r = mysqli_fetch_assoc($qr)) {
                          $path = 'img/anuncios/carros/' . $id . '/' . $r['caminho_foto'];
                          $imgs[] = $path;
                        }
                      }
                      $favoritado = $carro['favoritado'];
                      include 'estruturas/card-compra/card-compra.php'; ?>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="col-12 text-muted no-results-container d-flex justify-content-center align-items-center">
                    <div class="no-results-inner">
                      <i class="bi bi-emoji-frown display-4 mb-3"></i>
                      <div class="h5 fw-semibold">Nenhum carro encontrado</div>
                      <div class="small">Tente ajustar os filtros para ampliar sua busca.</div>
                    </div>
                  </div>
                <?php endif ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php if (!empty($carros)): ?>
        <div class="container-fluid mt-5 pb-5">
          <div class="row">
            <div class="col-12 d-flex justify-content-center">
              <nav aria-label="Page navigation example">
                <ul class="pagination pagination-dark">
                  <?php $qs = "&sort=" . urlencode($sort) . "&dir=" . urlencode($dir); ?>
                  <?php
                  // render previous
                  $prev_disabled = $page <= 1;
                  $next_disabled = $page >= $total_pages;
                  ?>
                  <li class="page-item <?= $prev_disabled ? 'disabled' : '' ?>">
                    <a class="page-link <?= $prev_disabled ? 'disabled' : '' ?>" href="<?= $prev_disabled ? '#' : 'compras.php?page=' . ($page - 1) . $qs ?>" aria-disabled="<?= $prev_disabled ? 'true' : 'false' ?>"><i class="bi bi-caret-left-fill"></i></a>
                  </li>

                  <?php
                  // show first page + gap when current page is far
                  if ($total_pages > 5 && $page > 3) {
                    echo '<li class="page-item"><a class="page-link" href="compras.php?page=1' . $qs . '">1</a></li>';
                    if ($page > 4) echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                  }

                  // determine start/end range (show up to 5 pages)
                  $start = max(1, $page - 2);
                  $end = min($total_pages, $page + 2);
                  if ($end - $start < 4) {
                    $start = max(1, $end - 4);
                    $end = min($total_pages, $start + 4);
                  }
                  for ($p = $start; $p <= $end; $p++) {
                    $active = $p == $page ? 'active' : '';
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="compras.php?page=' . $p . $qs . '">' . $p . '</a></li>';
                  }

                  // show gap + last page when far
                  if ($total_pages > 5 && $page < $total_pages - 2) {
                    if ($page < $total_pages - 3) echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                    echo '<li class="page-item"><a class="page-link" href="compras.php?page=' . $total_pages . $qs . '">' . $total_pages . '</a></li>';
                  }

                  // render next
                  ?>
                  <li class="page-item <?= $next_disabled ? 'disabled' : '' ?>">
                    <a class="page-link <?= $next_disabled ? 'disabled' : '' ?>" href="<?= $next_disabled ? '#' : 'compras.php?page=' . ($page + 1) . $qs ?>" aria-disabled="<?= $next_disabled ? 'true' : 'false' ?>"><i class="bi bi-caret-right-fill"></i></a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      <?php endif; ?>
  </main>
  <?php include 'estruturas/footer/footer.php' ?>
</body>
<?php if (isset($conexao)) {
  mysqli_close($conexao);
} ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="script.js"></script>
<script>
  $(function() {
    // Scroll position persistence
    const SCROLL_STORAGE_KEY = 'fahren_scroll_positions';

    // Save scroll positions before page unload (from filter changes)
    function saveScrollPositions() {
      const scrollData = {
        mainScroll: $(window).scrollTop(),
        sidebarScroll: $('#filtros-over').scrollTop() || 0
      };
      sessionStorage.setItem(SCROLL_STORAGE_KEY, JSON.stringify(scrollData));
    }

    // Offcanvas-aware navigation queue: when the filters offcanvas is open,
    // queue navigation requests and only navigate when it is closed.
    window.filterOffcanvasOpen = false;
    window.pendingFilterNavigation = null;

    window.queueOrNavigate = function(url) {
      try {
        if (window.filterOffcanvasOpen) {
          window.pendingFilterNavigation = url.toString();
          // Optionally show a subtle indicator that changes are pending
          const off = document.getElementById('offcanvasBottom');
          if (off) off.classList.add('filters-pending');
          return;
        }
      } catch (e) {
        // fallback to immediate navigation
      }
      saveScrollPositions();
      window.location.href = url.toString();
    };

    window.applyPendingFilterNavigation = function() {
      try {
        // If we have a DOM-based builder, use it to collect all current filter states
        const off = document.getElementById('offcanvasBottom');
        if (typeof window.buildFilterUrlFromDom === 'function') {
          const nav = window.buildFilterUrlFromDom();
          if (off) off.classList.remove('filters-pending');
          saveScrollPositions();
          window.location.href = nav;
          return;
        }
        if (window.pendingFilterNavigation) {
          const nav = window.pendingFilterNavigation;
          window.pendingFilterNavigation = null;
          if (off) off.classList.remove('filters-pending');
          saveScrollPositions();
          window.location.href = nav;
        }
      } catch (e) {
        console.error('Failed to apply pending navigation', e);
      }
    };

    // Restore scroll positions after page load
    function restoreScrollPositions() {
      try {
        const scrollData = JSON.parse(sessionStorage.getItem(SCROLL_STORAGE_KEY));
        if (scrollData) {
          // Restore main scroll
          if (scrollData.mainScroll) {
            $(window).scrollTop(scrollData.mainScroll);
          }
          // Restore sidebar scroll
          if (scrollData.sidebarScroll && $('#filtros-over').length) {
            $('#filtros-over').scrollTop(scrollData.sidebarScroll);
          }
          // Clear the stored data after restoring
          sessionStorage.removeItem(SCROLL_STORAGE_KEY);
        }
      } catch (e) {
        console.error('Error restoring scroll positions:', e);
      }
    }

    // Restore scroll positions on page load with a small delay to ensure DOM is ready
    setTimeout(function() {
      restoreScrollPositions();
    }, 100);

    // Helper to capitalize first letter of each word
    function capitalizeWords(str) {
      return str.replace(/\b\w/g, function(char) {
        return char.toUpperCase();
      });
    }

    // Dynamic breadcrumb builder based on filters
    const FILTER_ORDER = [{
        name: 'codicao',
        label: 'Carros',
        // When all three conditions are selected, return a special token so
        // the breadcrumb renderer can show just the label 'Carros'. Otherwise
        // return the human-readable selected conditions joined with ' e '.
        getValue: function(val) {
          const parts = val.split(',').map(v => {
            const map = {
              'novo': 'Novo',
              'seminovo': 'Seminovo',
              'usado': 'Usado'
            };
            return map[v.toLowerCase()] || v;
          });
          const allSelected = parts.length === 3 && parts.includes('Novo') && parts.includes('Seminovo') && parts.includes('Usado');
          if (allSelected) return '__ALL_COND__';
          return parts.length ? parts.join(' e ') : null;
        }
      },
      {
        name: 'estado_local',
        label: null,
        getValue: (v) => v ? v.toUpperCase() : null
      },
      {
        name: 'cidade',
        label: null,
        getValue: (v) => v ? capitalizeWords(v) : null
      },
      {
        name: 'marca',
        label: null,
        getValue: (v) => v ? capitalizeWords(v) : null
      },
      {
        name: 'modelo',
        label: null,
        getValue: (v) => v ? capitalizeWords(v) : null
      },
      {
        name: 'versao',
        label: null,
        getValue: (v) => v ? capitalizeWords(v) : null
      },
      {
        name: 'ano_min',
        label: null,
        getValue: (v) => v ? capitalizeWords(v) : null
      },
      {
        name: 'ano_max',
        label: null,
        getValue: (v) => v ? capitalizeWords(v) : null
      }
    ];

    function renderBreadcrumb() {
      const url = new URL(window.location.href);
      const params = url.searchParams;
      const breadcrumbItems = [];

      // Build breadcrumb items from active filters
      FILTER_ORDER.forEach(function(filter, index) {
        const val = params.get(filter.name);
        if (val) {
          const displayVal = filter.getValue(val);
          if (displayVal) {
            let displayLabel;
            // Special token for codicao: when all conditions selected, show only 'Carros'
            if (displayVal === '__ALL_COND__' && filter.label) {
              displayLabel = filter.label;
            } else {
              displayLabel = filter.label ? filter.label + ' ' + displayVal : displayVal;
            }
            breadcrumbItems.push({
              label: displayLabel,
              paramName: filter.name,
              index: index
            });
          }
        }
      });

      // Render breadcrumb
      const $breadcrumb = $('#dynamic-breadcrumb');

      // Keep only Home link
      $breadcrumb.find('li').slice(1).remove();

      if (breadcrumbItems.length === 0) {
        return;
      }

      // Add all breadcrumb items
      breadcrumbItems.forEach(function(item, idx) {
        const isLast = idx === breadcrumbItems.length - 1;
        let $li;

        if (isLast) {
          // Last item: not clickable, bold
          $li = $('<li class="breadcrumb-item active text-dark fw-semibold" aria-current="page"></li>');
          $li.text(item.label);
        } else {
          // Previous items: clickable, remove subsequent filters
          $li = $('<li class="breadcrumb-item"></li>');
          const $link = $('<a href="#" class="link-dark link-underline-opacity-0 link-underline-opacity-100-hover"></a>');
          $link.text(item.label);

          $link.on('click', function(e) {
            e.preventDefault();

            // Find all filters that come after this one
            const filtersToRemove = FILTER_ORDER.slice(item.index + 1).map(f => f.name);

            // Build new URL removing filters after current one
            const newUrl = new URL(window.location.href);
            filtersToRemove.forEach(paramName => {
              newUrl.searchParams.delete(paramName);
            });
            newUrl.searchParams.delete('page');

            saveScrollPositions();
            window.location.href = newUrl.toString();
          });

          $li.append($link);
        }

        $breadcrumb.append($li);
      });

      // Update title (no visible title element requested)
      const lastItem = breadcrumbItems[breadcrumbItems.length - 1];
    }

    // Build breadcrumb on page load
    renderBreadcrumb();

    <?php if (isset($_GET['marca'])): ?>
      const initialMarca = <?= json_encode($_GET['marca']) ?>;
    <?php endif; ?>
    <?php if (isset($_GET['modelo'])): ?>
      const initialModelo = <?= json_encode($_GET['modelo']) ?>;
    <?php endif; ?>
    <?php if (isset($_GET['versao'])): ?>
      const initialVersao = <?= json_encode($_GET['versao']) ?>;
    <?php endif; ?>
    <?php if (isset($_GET['q'])): ?>
      const searchQ = <?= json_encode($_GET['q']) ?>;
      $('#navbar-search').val(searchQ);
      $('#global-search').val(searchQ);
    <?php endif; ?>
    // expose searchQ to other handlers (mutable so search bar can update sidebar)
    let pageSearchQ = typeof searchQ !== 'undefined' ? searchQ : null;
    const cards = $('.card-compra');
    cards.each(function() {
      const card = $(this);
      const carousel = card.find('.carousel');
      const items = carousel.find('.carousel-item');
      const quant = Math.max(1, items.length);
      card.data('quant', quant);
      card.find('.max').text(quant);

      // set initial min based on active slide
      let activeIdx = carousel.find('.carousel-item.active').index();
      if (activeIdx === -1) activeIdx = 0;
      card.find('.min').text(activeIdx + 1);

      // update counter after slide finishes to avoid desync with animation
      carousel.on('slid.bs.carousel', function() {
        const idx = $(this).find('.carousel-item.active').index();
        card.find('.min').text(idx + 1);
      });

      // hide controls by default; show on hover only if there are multiple images
      if (quant <= 1) {
        card.find('.carousel-control-prev, .carousel-control-next, #img-quant').hide();
      } else {
        card.find('.carousel-control-prev, .carousel-control-next, #img-quant').hide();
      }

      card.find('.favoritar-btn').hide();

      card.on('mouseenter', function() {
        if (quant > 1) card.find('.carousel-control-prev, .carousel-control-next, #img-quant').stop(true, true).fadeIn(300);
        card.find('.favoritar-btn').stop(true, true).fadeIn(300);
      });

      card.on('mouseleave', function() {
        if (quant > 1) card.find('.carousel-control-prev, .carousel-control-next, #img-quant').stop(true, true).fadeOut(300);
        card.find('.favoritar-btn').stop(true, true).fadeOut(300);
      });
    });

    $("#ele").addClass('propulsao');
    $("#hib").addClass('propulsao');
    $("#comb").addClass('propulsao');

    // Note: do not auto-check children on page load based only on parent state.
    // Children will be initialized from the `combustivel` URL param and parents
    // will be synced from children in `initializePropulsaoCheckboxes`.

    // When a parent (comb, hib, ele) is clicked, toggle all its children to match the parent state.
    // This will be the only place children are auto-toggled; child unchecks will NOT be reverted.
    $('.propulsao').on('change', function() {
      const parentId = $(this).attr('id');
      const container = $('#' + parentId + '-tipos');
      if (container.length) {
        const isParentChecked = $(this).is(':checked');
        container.find('input').prop('checked', isParentChecked);
      }
    });

    // Initialize propulsao checkboxes from URL and wire changes to update search params
    function initializePropulsaoCheckboxes() {
      const url = new URL(window.location.href);
      const propParam = url.searchParams.get('propulsao');
      const combParam = url.searchParams.get('combustivel');
      const parents = propParam ? propParam.split(',').map(v => v.trim()) : [];
      const subs = combParam ? combParam.split(',').map(v => v.trim()) : [];

      ['comb', 'hib', 'ele'].forEach(function(id) {
        const el = $('#' + id);
        if (el.length) el.prop('checked', parents.includes(id));
      });

      // Only check actual subtype checkboxes (gas, alc, fle, die, gnv, hev, phe, mhe)
      // Do NOT check parent-only boxes (ele)
      const subtypeIds = ['gas', 'alc', 'fle', 'die', 'gnv', 'hev', 'phe', 'mhe'];
      subs.forEach(function(s) {
        if (subtypeIds.includes(s)) {
          const el = $('#' + s);
          if (el.length) el.prop('checked', true);
        }
      });
      // If any child subtype is checked, ensure its parent is checked as well
      const parentMap = {
        'comb': '#comb-tipos',
        'hib': '#hib-tipos',
        'ele': null
      };
      Object.keys(parentMap).forEach(function(pid) {
        const container = parentMap[pid];
        if (!container) return;
        if ($(container + ' input:checked').length > 0) {
          const pel = $('#' + pid);
          if (pel.length) pel.prop('checked', true);
        }
      });
    }
    initializePropulsaoCheckboxes();

    // Do not auto-check children on load; children are initialized from the
    // `combustivel` URL parameter and parents are synced from children.

    let propTimer = null;
    const PROP_DEBOUNCE_MS = 600;
    $(document).on('change', '.propulsao, #comb-tipos input, #hib-tipos input', function() {
      if (propTimer) clearTimeout(propTimer);
      propTimer = setTimeout(function() {
        // Sync parent states based on children: if any child is checked, parent must be checked.
        // If all children are unchecked, parent is unchecked.
        const parentContainers = {
          'comb': '#comb-tipos',
          'hib': '#hib-tipos'
        };
        Object.keys(parentContainers).forEach(function(pid) {
          const cont = parentContainers[pid];
          const anyChild = $(cont + ' input:checked').length > 0;
          const pel = $('#' + pid);
          if (pel.length) {
            if (anyChild && !pel.is(':checked')) {
              pel.prop('checked', true);
            } else if (!anyChild && pel.is(':checked')) {
              pel.prop('checked', false);
            }
          }
        });

        // Build lists from current DOM state (do not modify, only read)
        const parents = [];
        $('.propulsao:checked').each(function() {
          parents.push($(this).attr('id'));
        });
        const subs = [];
        $('#comb-tipos input:checked, #hib-tipos input:checked').each(function() {
          subs.push($(this).attr('id'));
        });

        const url = new URL(window.location.href);
        url.searchParams.delete('page');

        if (parents.length === 0) {
          url.searchParams.delete('propulsao');
        } else {
          url.searchParams.set('propulsao', parents.join(','));
        }

        if (subs.length === 0) {
          url.searchParams.delete('combustivel');
        } else {
          url.searchParams.set('combustivel', subs.join(','));
        }

        queueOrNavigate(url);
      }, PROP_DEBOUNCE_MS);
    });

    const order_btn = $('#ordenar-btn');
    const order_i = $(order_btn).find("i");
    // initial sort state from server
    const currentSort = <?= json_encode($sort) ?>;
    const currentDir = <?= json_encode($dir) ?>;

    // reflect current dir in the icon
    if (currentSort === 'relevancia') {
      $(order_i).removeClass('bi-sort-up bi-sort-down').addClass('bi-filter');
      $(order_btn).prop('disabled', true);
    } else {
      $(order_btn).prop('disabled', false);
      $(order_i).removeClass('bi-filter');
      if (currentDir === 'asc') {
        $(order_i).addClass('bi-sort-up');
      } else {
        $(order_i).addClass('bi-sort-down');
      }
    }

    $('#ordenar-input').on('change', function() {
      const val = $(this).val();
      if (val === 'relevancia') {
        // go to relevance
        const url = new URL(window.location.href);
        url.searchParams.set('sort', 'relevancia');
        url.searchParams.delete('dir');
        url.searchParams.delete('page');
        queueOrNavigate(url);
        return;
      }
      // default to desc for new sorts
      const url = new URL(window.location.href);
      url.searchParams.set('sort', val);
      url.searchParams.set('dir', 'desc');
      url.searchParams.delete('page');
      queueOrNavigate(url);
    });

    $(order_btn).on('click', function() {
      // toggle direction for current sort and reload
      const sel = $('#ordenar-input').val();
      if (sel === 'relevancia') return;
      const url = new URL(window.location.href);
      const dirNow = url.searchParams.get('dir') === 'asc' ? 'asc' : 'desc';
      const newDir = dirNow === 'asc' ? 'desc' : 'asc';
      url.searchParams.set('sort', sel);
      url.searchParams.set('dir', newDir);
      url.searchParams.delete('page');
      queueOrNavigate(url);
    });

    $(window).on("scroll", function() {
      const filtrosCol = $("#filtros-col");
      const filtrosOver = $("#filtros-over");

      if (filtrosCol.length === 0 || filtrosOver.length === 0) return;

      const rect = filtrosCol[0].getBoundingClientRect();

      if (rect.top < 0) {
        filtrosOver.addClass("mt-auto");
      } else {
        filtrosOver.removeClass("mt-auto");
      }
    });

    const marcasInput = $("#marcas-input");
    const modelosInput = $("#modelos-input");
    const versoesInput = $("#versoes-input");
    // prepare flags to distinguish programmatic preselection from user actions
    let suppressMarcaNav = false;
    let suppressModeloNav = false;

    // Helper: update button visibility based on select value
    function updateButtonVisibility() {
      const marcaVal = $('#marca-select').val();
      const modeloVal = $('#modelo-select').val();
      const versaoVal = $('#versao-select').val();

      // Show marca button only if marca is selected
      if (marcaVal) {
        marcasInput.find('button').removeClass('d-none');
      } else {
        marcasInput.find('button').addClass('d-none');
      }

      // Show modelo button only if modelo is selected
      if (modeloVal) {
        modelosInput.find('button').removeClass('d-none');
      } else {
        modelosInput.find('button').addClass('d-none');
      }

      // Show versao button only if versao is selected
      if (versaoVal) {
        versoesInput.find('button').removeClass('d-none');
      } else {
        versoesInput.find('button').addClass('d-none');
      }
    }

    // Call on page load
    updateButtonVisibility();

    // Sync: search bar -> sidebar (one-way). Debounced input will try to match a brand in sidebar
    let searchSyncTimer = null;
    const SEARCH_SYNC_MS = 600;

    function handleSearchSync(q) {
      pageSearchQ = q; // update global used by populateModels
      if (!q || q.trim() === '') return;
      const sq = q.toLowerCase();
      let matchedMarca = null;
      $('#marca-select option').each(function() {
        const val = $(this).val();
        const txt = $(this).text().toLowerCase();
        if (val && txt && sq.indexOf(txt) !== -1) {
          matchedMarca = val;
          return false; // break
        }
      });
      if (matchedMarca) {
        // programmatic set: do not navigate, populate models and let populateModels infer model from pageSearchQ
        suppressMarcaNav = true;
        $('#marca-select').val(matchedMarca).trigger('change');
      }
    }

    $('#global-search, #navbar-search').on('input', function() {
      const v = $(this).val() || '';
      if (searchSyncTimer) clearTimeout(searchSyncTimer);
      searchSyncTimer = setTimeout(function() {
        handleSearchSync(v);
      }, SEARCH_SYNC_MS);
    });

    // helper: populate models for a marca and optionally preselect modelo (does not navigate)
    function populateModels(marcaVal, preselectModelo) {
      if (!marcaVal) return;
      console.log('[populateModels] marcaVal=', marcaVal, 'preselectModelo=', preselectModelo);
      modelosInput.removeClass('d-none');
      marcasInput.find('button').removeClass('d-none');
      const $modelo = $('#modelo-select');
      $modelo.empty();
      $modelo.append($('<option>', {
        value: '',
        text: '',
        hidden: true,
        selected: true
      }));
      $.getJSON('controladores/filters/get_models.php', {
          marca: marcaVal
        })
        .done(function(data) {
          $modelo.empty();
          $modelo.append($('<option>', {
            value: '',
            text: 'Todos os modelos',
            hidden: true,
            selected: true
          }));
          if (Array.isArray(data) && data.length) {
            data.forEach(function(m) {
              $modelo.append($('<option>', {
                value: m,
                text: m
              }));
            });
          }
          modelosInput.removeClass('d-none');
          marcasInput.find('button').removeClass('d-none');
          modelosInput.find('button').removeClass('d-none');
          if (preselectModelo) {
            suppressModeloNav = true;
            $modelo.val(preselectModelo).trigger('change');
          } else if (pageSearchQ) {
            // try to infer model from q
            const sq = pageSearchQ.toLowerCase();
            let matched = null;
            $modelo.find('option').each(function() {
              const t = $(this).text().toLowerCase();
              if (t !== '' && sq.indexOf(t) !== -1) {
                matched = $(this).val();
                return false;
              }
            });
            if (matched) {
              suppressModeloNav = true;
              $modelo.val(matched).trigger('change');
            }
          }
        })
        .fail(function() {
          // on error show the modelos block so user can retry
          modelosInput.removeClass('d-none');
          marcasInput.find('button').removeClass('d-none');
        });
    }

    // helper: populate versions for marca+modelo and optionally preselect versao (does not navigate)
    function populateVersions(marcaVal, modeloVal, preselectVersao) {
      if (!marcaVal || !modeloVal) return;
      console.log('[populateVersions] marca=', marcaVal, 'modelo=', modeloVal, 'preselectVersao=', preselectVersao);
      const $versao = $('#versao-select');
      $versao.empty();
      $versao.append($('<option>', {
        value: '',
        text: 'Carregando versões...',
        hidden: true,
        selected: true
      }));
      versoesInput.removeClass('d-none');
      modelosInput.find('button').removeClass('d-none');
      $.getJSON('controladores/filters/get_versions.php', {
          marca: marcaVal,
          modelo: modeloVal
        })
        .done(function(data) {
          $versao.empty();
          $versao.append($('<option>', {
            value: '',
            text: 'Todas as versões',
            hidden: true,
            selected: true
          }));
          if (Array.isArray(data) && data.length) {
            data.forEach(function(v) {
              $versao.append($('<option>', {
                value: v,
                text: v
              }));
            });
            versoesInput.removeClass('d-none');
            versoesInput.find('button').removeClass('d-none');
          }
          if (preselectVersao) {
            $versao.val(preselectVersao);
            versoesInput.removeClass('d-none');
            versoesInput.find('button').removeClass('d-none');
          }
          modelosInput.removeClass('d-none');
          modelosInput.find('button').removeClass('d-none');
        })
        .fail(function() {
          modelosInput.removeClass('d-none');
          modelosInput.find('button').removeClass('d-none');
        });
    }

    // if the page was loaded with an explicit marca param, prepopulate models (no navigation)
    if (typeof initialMarca !== 'undefined' && initialMarca) {
      console.log('[init] initialMarca=', initialMarca, 'initialModelo=', typeof initialModelo !== 'undefined' ? initialModelo : null);
      suppressMarcaNav = true; // mark this as programmatic
      populateModels(initialMarca, typeof initialModelo !== 'undefined' ? initialModelo : null);
      setTimeout(updateButtonVisibility, 100);
    }

    // if the page search contains a brand name, try to preselect the matching brand (don't navigate)
    if (pageSearchQ) {
      const sq = pageSearchQ.toLowerCase();
      $('#marca-select option').each(function() {
        const val = $(this).val();
        const txt = $(this).text().toLowerCase();
        if (val && txt && sq.indexOf(txt) !== -1) {
          console.log('[init] pageSearchQ matched marca option', txt, val);
          suppressMarcaNav = true;
          $('#marca-select').val(val);
          populateModels(val, null);
          return false; // break loop
        }
      });
    }

    // when brand changes, fetch models for that brand and populate the modelo-select
    marcasInput.find("select").on("change", function(e) {
      const isUser = !!e.originalEvent; // true when event came from a real user action
      const marcaVal = $(this).val();
      if (!marcaVal) {
        // clear models and versions
        modelosInput.addClass("d-none");
        modelosInput.find("select").val("");
        marcasInput.find("button").addClass("d-none");
        versoesInput.addClass("d-none");
        versoesInput.find("select").val("");
        // navigate clearing marca filters when the user clicked the clear (or user manually cleared)
        if (isUser) {
          const url = new URL(window.location.href);
          url.searchParams.delete('marca');
          url.searchParams.delete('modelo');
          url.searchParams.delete('versao');
          url.searchParams.delete('q');
          url.searchParams.delete('page');
          queueOrNavigate(url);
        } else {
          // programmatic clear (from our code) — only reset suppression
          suppressMarcaNav = false;
        }
        return;
      }
      // If this change came from a real user action, always treat it as user selection and navigate immediately.
      if (isUser) {
        const url = new URL(window.location.href);
        url.searchParams.set('marca', marcaVal);
        url.searchParams.delete('modelo');
        url.searchParams.delete('versao');
        // do not update search input when selecting marca in sidebar
        url.searchParams.delete('page');
        queueOrNavigate(url);
        return;
      }

      // otherwise it was programmatic (preselection) — if suppression active, populate models without navigating
      if (suppressMarcaNav) {
        modelosInput.removeClass("d-none");
        marcasInput.find("button").removeClass("d-none");
        suppressMarcaNav = false; // clear suppression immediately so next user action behaves normally
        populateModels(marcaVal, typeof initialModelo !== 'undefined' ? initialModelo : null);
      } else {
        // defensive fallback: navigate to apply marca filter
        const url = new URL(window.location.href);
        url.searchParams.set('marca', marcaVal);
        url.searchParams.delete('modelo');
        url.searchParams.delete('versao');
        // do not update search input when navigating for marca
        url.searchParams.delete('page');
        queueOrNavigate(url);
        return;
      }

      // Mark button visibility
      if (!isUser) {
        updateButtonVisibility();
      }
    });

    // when model changes, fetch versions for selected brand+model
    modelosInput.find("select").on("change", function(e) {
      const isUser = !!e.originalEvent;
      const modeloVal = $(this).val();
      const marcaVal = $('#marca-select').val();
      if (!modeloVal) {
        versoesInput.addClass("d-none");
        versoesInput.find("select").val("");
        modelosInput.find("button").addClass("d-none");
        if (isUser) {
          // user cleared modelo: navigate and keep marca
          const url = new URL(window.location.href);
          url.searchParams.delete('modelo');
          url.searchParams.delete('versao');
          url.searchParams.delete('q');
          url.searchParams.delete('page');
          queueOrNavigate(url);
        } else {
          // programmatic clear
          suppressModeloNav = false;
        }
        return;
      }

      if (isUser) {
        // user-driven selection: navigate to apply marca+modelo filter
        const url = new URL(window.location.href);
        url.searchParams.set('marca', marcaVal);
        url.searchParams.set('modelo', modeloVal);
        // do not update search input when selecting modelo in sidebar
        url.searchParams.delete('versao');
        url.searchParams.delete('page');
        queueOrNavigate(url);
        return;
      }

      // programmatic change: populate versions without navigating
      if (suppressModeloNav) {
        modelosInput.removeClass("d-none");
        marcasInput.find("button").removeClass("d-none");
        suppressModeloNav = false; // clear suppression immediately
        populateVersions(marcaVal, modeloVal, typeof initialVersao !== 'undefined' ? initialVersao : null);
        updateButtonVisibility();
      } else {
        // defensive fallback: navigate
        const url = new URL(window.location.href);
        url.searchParams.set('marca', marcaVal);
        url.searchParams.set('modelo', modeloVal);
        // do not update search input when navigating for modelo
        url.searchParams.delete('versao');
        url.searchParams.delete('page');
        queueOrNavigate(url);
      }
    });

    versoesInput.find("select").on("change", function(e) {
      const isUser = !!e.originalEvent;
      const versaoVal = $(this).val();
      if (versaoVal) {
        // navigate to apply version filter
        const marcaVal = $('#marca-select').val();
        const modeloVal = $('#modelo-select').val();
        const url = new URL(window.location.href);
        if (marcaVal) url.searchParams.set('marca', marcaVal);
        if (modeloVal) url.searchParams.set('modelo', modeloVal);
        url.searchParams.set('versao', versaoVal);
        // do not update search input when selecting versao in sidebar
        url.searchParams.delete('page');
        queueOrNavigate(url);
      } else if (!isUser) {
        updateButtonVisibility();
      }
    });

    marcasInput.find("button").on("click", function() {
      // user clicked clear on marca: remove marca/modelo/versao from URL and reload
      // (don't manipulate selects; let PHP do the rendering on reload)
      const url = new URL(window.location.href);
      url.searchParams.delete('marca');
      url.searchParams.delete('modelo');
      url.searchParams.delete('versao');
      url.searchParams.delete('q');
      url.searchParams.delete('page');
      queueOrNavigate(url);
    });

    modelosInput.find("button").on("click", function() {
      // user clicked clear on modelo: remove modelo/versao from URL and reload
      // (don't manipulate selects; let PHP do the rendering on reload)
      const url = new URL(window.location.href);
      url.searchParams.delete('modelo');
      url.searchParams.delete('versao');
      url.searchParams.delete('q');
      url.searchParams.delete('page');
      queueOrNavigate(url);
    });

    versoesInput.find("button").on("click", function() {
      // user clicked clear on versao: remove versao from URL and reload
      // (don't manipulate selects; let PHP do the rendering on reload)
      const url = new URL(window.location.href);
      url.searchParams.delete('versao');
      url.searchParams.delete('q');
      url.searchParams.delete('page');
      queueOrNavigate(url);
    });

    // Condição checkboxes (usado / seminovo / novo)
    // Initialize checkboxes based on URL param (not server-side)
    function initializeCondicaoCheckboxes() {
      const url = new URL(window.location.href);
      const codicaoParam = url.searchParams.get('codicao') || '';
      const values = codicaoParam ? codicaoParam.split(',').map(v => v.trim()) : [];

      // If no codicao param present, default to all three conditions checked
      if (values.length === 0) {
        $('.condicao-input').each(function() {
          $(this).prop('checked', true);
        });
        return;
      }

      $('.condicao-input').each(function() {
        const val = $(this).attr('data-val');
        const isChecked = values.includes(val);
        $(this).prop('checked', isChecked);
      });
    }

    // Initialize on page load
    initializeCondicaoCheckboxes();

    // If no `codicao` URL parameter was present, update the URL to explicitly
    // include all three conditions so links/share include the current state.
    (function ensureCodicaoParam() {
      try {
        const u = new URL(window.location.href);
        const existing = u.searchParams.get('codicao');
        if (!existing || existing.trim() === '') {
          u.searchParams.set('codicao', 'usado,seminovo,novo');
          history.replaceState(null, '', u.toString());
          // re-render breadcrumb to reflect updated params
          if (typeof renderBreadcrumb === 'function') renderBreadcrumb();
        }
      } catch (e) {
        // ignore URL errors on older browsers
      }
    })();

    // Location state tracking
    let localizacaoEstado = null;
    let localizacaoCidade = null;

    // Function to show/hide clear button based on selection
    function updateLocalizacaoClearButton() {
      if (localizacaoEstado || localizacaoCidade) {
        $('#localizacao-clear').show();
      } else {
        $('#localizacao-clear').hide();
      }
    }

    // Initialize location from URL on page load
    function initializeLocalizacao() {
      const url = new URL(window.location.href);
      const estadoParam = url.searchParams.get('estado_local');
      const cidadeParam = url.searchParams.get('cidade');

      if (estadoParam || cidadeParam) {
        localizacaoEstado = estadoParam || null;
        localizacaoCidade = cidadeParam || null;

        // Build display text
        let displayText = '';
        if (cidadeParam) {
          displayText = cidadeParam + (estadoParam ? ' - ' + estadoParam : '');
        } else if (estadoParam) {
          // Fetch state name from hidden data or just show UF
          displayText = estadoParam;
        }

        $('#localizacao').val(displayText);
        updateLocalizacaoClearButton();
      }
    }

    initializeLocalizacao();

    // Clear location button handler
    $('#localizacao-clear').on('click', function(e) {
      e.preventDefault();
      e.stopPropagation();

      localizacaoEstado = null;
      localizacaoCidade = null;
      $('#localizacao').val('').focus();
      $('#localizacao-suggestions').hide();
      updateLocalizacaoClearButton();

      // Remove filters from URL
      const url = new URL(window.location.href);
      url.searchParams.delete('estado_local');
      url.searchParams.delete('cidade');
      url.searchParams.delete('page');
      queueOrNavigate(url);
    });

    // Location autocomplete initialization
    let localizacaoTimer = null;
    const LOCALIZACAO_DEBOUNCE_MS = 300;

    $('#localizacao').on('input', function() {
      const val = $(this).val().trim();

      // If input is cleared, clear the stored state
      if (val === '') {
        localizacaoEstado = null;
        localizacaoCidade = null;
        updateLocalizacaoClearButton();
      }

      if (val.length < 2) {
        $('#localizacao-suggestions').hide();
        return;
      }

      if (localizacaoTimer) clearTimeout(localizacaoTimer);
      localizacaoTimer = setTimeout(function() {
        $.getJSON('controladores/get_locations.php', {
            q: val
          })
          .done(function(data) {
            const $suggestions = $('#localizacao-suggestions');
            $suggestions.empty();

            if (data.states && data.states.length > 0) {
              $suggestions.append('<div class="px-2 py-2 border-bottom"><small class="text-muted">Estados</small></div>');
              data.states.forEach(function(state) {
                const $item = $('<div class="px-3 py-2" style="cursor: pointer; border-bottom: 1px solid #f0f0f0;">')
                  .text(state.label)
                  .addClass('location-suggestion')
                  .data('type', 'estado')
                  .data('value', state.value)
                  .data('name', state.name)
                  .on('click', function() {
                    localizacaoEstado = state.value;
                    localizacaoCidade = null;
                    $('#localizacao').val(state.name);
                    $suggestions.hide();
                    updateLocalizacaoClearButton();
                    // Persist selection asynchronously (do not block navigation)
                    try {
                      $.post('controladores/usuarios/atualizar-localizacao.php', {
                        estado_local: state.value,
                        cidade: ''
                      }).fail(function() {
                        // ignore errors silently; do not block UX
                      });
                    } catch (e) {}
                    // Filter by state
                    const url = new URL(window.location.href);
                    url.searchParams.set('estado_local', state.value);
                    url.searchParams.delete('cidade');
                    url.searchParams.delete('page');
                    queueOrNavigate(url);
                  })
                  .on('mouseenter', function() {
                    $(this).css('background-color', '#f8f9fa');
                  })
                  .on('mouseleave', function() {
                    $(this).css('background-color', '');
                  });
                $suggestions.append($item);
              });
            }

            if (data.cities && data.cities.length > 0) {
              if (data.states && data.states.length > 0) {
                $suggestions.append('<div class="px-2 py-2 border-bottom"><small class="text-muted">Cidades</small></div>');
              }
              data.cities.forEach(function(city) {
                const $item = $('<div class="px-3 py-2" style="cursor: pointer; border-bottom: 1px solid #f0f0f0;">')
                  .text(city.label)
                  .addClass('location-suggestion')
                  .data('type', 'cidade')
                  .data('cidade', city.cidade)
                  .data('estado', city.estado)
                  .on('click', function() {
                    localizacaoEstado = city.estado;
                    localizacaoCidade = city.cidade;
                    $('#localizacao').val(city.label);
                    $suggestions.hide();
                    updateLocalizacaoClearButton();
                    // Persist selection asynchronously (do not block navigation)
                    try {
                      $.post('controladores/usuarios/atualizar-localizacao.php', {
                        estado_local: city.estado,
                        cidade: city.cidade
                      }).fail(function() {
                        // ignore errors silently; do not block UX
                      });
                    } catch (e) {}
                    // Filter by state and city
                    const url = new URL(window.location.href);
                    url.searchParams.set('estado_local', city.estado);
                    url.searchParams.set('cidade', city.cidade);
                    url.searchParams.delete('page');
                    queueOrNavigate(url);
                  })
                  .on('mouseenter', function() {
                    $(this).css('background-color', '#f8f9fa');
                  })
                  .on('mouseleave', function() {
                    $(this).css('background-color', '');
                  });
                $suggestions.append($item);
              });
            }

            if ((data.states && data.states.length > 0) || (data.cities && data.cities.length > 0)) {
              $suggestions.show();
            } else {
              $suggestions.hide();
            }
          })
          .fail(function() {
            $('#localizacao-suggestions').hide();
          });
      }, LOCALIZACAO_DEBOUNCE_MS);
    });

    // Hide suggestions when clicking outside
    $(document).on('click', function(e) {
      if (!$(e.target).closest('#localizacao, #localizacao-suggestions, #localizacao-clear').length) {
        $('#localizacao-suggestions').hide();
      }
    });


    // Categoria filter initialization + handler
    function initializeCategoriaCheckboxes() {
      const url = new URL(window.location.href);
      const categoriaParam = url.searchParams.get('categoria') || '';
      const values = categoriaParam ? categoriaParam.split(',').map(v => v.trim()) : [];
      // Os checkboxes de categoria usam .form-check-input e id igual ao value
      values.forEach(function(val) {
        // Tenta marcar o checkbox pelo id
        $(".form-check-input[id='" + val + "']").prop('checked', true);
      });
    }
    initializeCategoriaCheckboxes();

    // Colors filter initialization + handler
    function initializeCorCheckboxes() {
      const url = new URL(window.location.href);
      const corParam = url.searchParams.get('cor') || '';
      const values = corParam ? corParam.split(',').map(v => v.trim()) : [];
      $('.cor-input').each(function() {
        const val = $(this).attr('data-val');
        const isChecked = values.includes(val);
        $(this).prop('checked', isChecked);
      });
    }
    initializeCorCheckboxes();

    let corTimer = null;
    const COR_DEBOUNCE_MS = 800;
    $('.cor-input').on('change', function() {
      // collect checked colors
      const checked = $('.cor-input:checked').length;
      // allow zero selection meaning 'all' (no filter)
      if (corTimer) clearTimeout(corTimer);
      corTimer = setTimeout(function() {
        const vals = [];
        $('.cor-input:checked').each(function() {
          const v = $(this).attr('data-val');
          if (v) vals.push(v);
        });
        const url = new URL(window.location.href);
        url.searchParams.delete('page');
        if (vals.length === 0) {
          url.searchParams.delete('cor');
        } else if (vals.length === 1) {
          url.searchParams.set('cor', vals[0]);
        } else {
          url.searchParams.set('cor', vals.join(','));
        }
        queueOrNavigate(url);
      }, COR_DEBOUNCE_MS);
    });

    // Carroceria filter initialization and handler
    function initializeCarroceriaCheckboxes() {
      const url = new URL(window.location.href);
      const carroceriaParam = url.searchParams.get('carroceria');
      const values = carroceriaParam ? carroceriaParam.split(',').map(v => v.trim()) : [];
      $('.carroceria-input').each(function() {
        const val = $(this).attr('data-val');
        const isChecked = values.includes(val);
        $(this).prop('checked', isChecked);
      });
    }
    initializeCarroceriaCheckboxes();

    let carroceriaTimer = null;
    const CARROCERIA_DEBOUNCE_MS = 800;
    $('.carroceria-input').on('change', function() {
      if (carroceriaTimer) clearTimeout(carroceriaTimer);
      carroceriaTimer = setTimeout(function() {
        const vals = [];
        $('.carroceria-input:checked').each(function() {
          const v = $(this).attr('data-val');
          if (v) vals.push(v);
        });
        const url = new URL(window.location.href);
        url.searchParams.delete('page');
        if (vals.length === 0) {
          url.searchParams.delete('carroceria');
        } else if (vals.length === 1) {
          url.searchParams.set('carroceria', vals[0]);
        } else {
          url.searchParams.set('carroceria', vals.join(','));
        }
        queueOrNavigate(url);
      }, CARROCERIA_DEBOUNCE_MS);
    });

    // Debounce changes so user can toggle multiple boxes before navigation
    let condicaoTimer = null;
    const CONDICAO_DEBOUNCE_MS = 450; // 1.5 seconds

    $('.condicao-input').on('change', function() {
      // Prevent unchecking if it's the last one checked
      const checkedCount = $('.condicao-input:checked').length;
      if (checkedCount === 0) {
        // Revert this checkbox back to checked
        $(this).prop('checked', true);
        return;
      }

      // clear any previous timer
      if (condicaoTimer) clearTimeout(condicaoTimer);
      condicaoTimer = setTimeout(function() {
        // collect checked condicao values
        const vals = [];
        $('.condicao-input:checked').each(function() {
          const v = $(this).attr('data-val');
          if (v) vals.push(v);
        });

        const url = new URL(window.location.href);
        // remove page when changing filters
        url.searchParams.delete('page');

        if (vals.length === 1) {
          url.searchParams.set('codicao', vals[0]);
        } else if (vals.length > 1) {
          // multiple values: join with comma
          url.searchParams.set('codicao', vals.join(','));
        }

        queueOrNavigate(url);
      }, CONDICAO_DEBOUNCE_MS);
    });

    // Accordion state persistence: store open/close state in URL as string like "AAAFF"
    // Get all accordion-collapse IDs in order
    const accordionIds = [];
    $('#filtros-over .accordion-collapse').each(function() {
      accordionIds.push($(this).attr('id'));
    });

    // Load accordion state from URL or initialize with default (only "modelo" open)
    function getAccordionStateFromURL() {
      const url = new URL(window.location.href);
      const accParam = url.searchParams.get('acc') || '';
      if (accParam.length === accordionIds.length && /^[AF]+$/.test(accParam)) {
        return accParam.split('');
      }
      // Default: only "modelo" open, all others closed
      return accordionIds.map(function(id) {
        return id === 'modelo' ? 'A' : 'F';
      });
    }

    // Apply accordion state from saved string
    function applyAccordionState(stateArray) {
      stateArray.forEach(function(state, idx) {
        if (idx < accordionIds.length) {
          const $acc = $('#' + accordionIds[idx]);
          const $button = $acc.prev().find('button');
          if (state === 'A') {
            $acc.addClass('show');
            $button.removeClass('collapsed').attr('aria-expanded', 'true');
          } else {
            $acc.removeClass('show');
            $button.addClass('collapsed').attr('aria-expanded', 'false');
          }
        }
      });
    }

    // Get current accordion state from DOM
    function getCurrentAccordionState() {
      return accordionIds.map(function(id) {
        const $acc = $('#' + id);
        return $acc.hasClass('show') ? 'A' : 'F';
      }).join('');
    }

    // Initialize accordion state from URL on page load
    const initialAccordionState = getAccordionStateFromURL();
    applyAccordionState(initialAccordionState);

    // If vendedor_id is present, force the vendedor accordion to stay open
    <?php if ($vendedor_id): ?>
      const vendedorAccordion = $('#vendedor');
      if (vendedorAccordion.length) {
        vendedorAccordion.addClass('show');
        vendedorAccordion.prev().find('button').removeClass('collapsed').attr('aria-expanded', 'true');
        // Update state in URL to reflect vendedor accordion open
        setTimeout(function() {
          const newState = getCurrentAccordionState();
          const url = new URL(window.location.href);
          url.searchParams.set('acc', newState);
          window.history.replaceState({}, '', url.toString());
        }, 50);
      }
    <?php endif; ?>

    // Listen to accordion button clicks to update URL
    $('#filtros-over .accordion-button').on('click', function(e) {
      setTimeout(function() {
        const newState = getCurrentAccordionState();
        const url = new URL(window.location.href);
        url.searchParams.set('acc', newState);
        // Use replaceState to avoid reloading the page
        window.history.replaceState({}, '', url.toString());
      }, 10);
    });

    // Auto-expand/collapse accordions based on active filters (generic)
    let accordionTimer = null;
    const ACCORDION_DEBOUNCE_MS = 500;

    function updateAccordionState() {
      // This function now ONLY detects filter state, doesn't close accordions automatically
      // Accordions stay open/closed based on URL state, not filter content
    }

    // Initialize on page load (after individual input initializers)
    updateAccordionState();

    // Update when filters change (no longer auto-closing accordions)
    $(document).on('change input', '.form-check-input, .form-select, input[type=text], input[type=number], textarea', function() {
      if (accordionTimer) clearTimeout(accordionTimer);
      accordionTimer = setTimeout(function() {
        updateAccordionState();
      }, ACCORDION_DEBOUNCE_MS);
    });

    // Initialize cambio and blindagem checkboxes from URL and wire changes to update search params
    function initializeCambioCheckboxes() {
      const url = new URL(window.location.href);
      const cambioParam = url.searchParams.get('cambio') || '';
      const values = cambioParam ? cambioParam.split(',').map(v => v.trim()) : [];
      $('#cambio .form-check-input').each(function() {
        const val = $(this).attr('data-val');
        const isChecked = values.includes(val);
        $(this).prop('checked', isChecked);
      });
    }
    initializeCambioCheckboxes();

    let cambioTimer = null;
    const CAMBIO_DEBOUNCE_MS = 600;
    $('#cambio .form-check-input').on('change', function() {
      if (cambioTimer) clearTimeout(cambioTimer);
      cambioTimer = setTimeout(function() {
        const vals = [];
        $('#cambio .form-check-input:checked').each(function() {
          vals.push($(this).attr('data-val'));
        });
        const url = new URL(window.location.href);
        url.searchParams.delete('page');
        if (vals.length === 0) {
          url.searchParams.delete('cambio');
        } else if (vals.length === 1) {
          url.searchParams.set('cambio', vals[0]);
        } else {
          url.searchParams.set('cambio', vals.join(','));
        }
        queueOrNavigate(url);
      }, CAMBIO_DEBOUNCE_MS);
    });

    function initializeBlindagemCheckboxes() {
      const url = new URL(window.location.href);
      const bliParam = url.searchParams.get('blindagem') || '';
      const values = bliParam ? bliParam.split(',').map(v => v.trim()) : [];
      $('#blindagem .form-check-input').each(function() {
        const val = $(this).attr('data-val');
        const isChecked = values.includes(val);
        $(this).prop('checked', isChecked);
      });
    }
    initializeBlindagemCheckboxes();

    let blindagemTimer = null;
    const BLINDAGEM_DEBOUNCE_MS = 600;
    $('#blindagem .form-check-input').on('change', function() {
      if (blindagemTimer) clearTimeout(blindagemTimer);
      blindagemTimer = setTimeout(function() {
        const vals = [];
        $('#blindagem .form-check-input:checked').each(function() {
          vals.push($(this).attr('data-val'));
        });
        const url = new URL(window.location.href);
        url.searchParams.delete('page');
        if (vals.length === 0) {
          url.searchParams.delete('blindagem');
        } else if (vals.length === 1) {
          url.searchParams.set('blindagem', vals[0]);
        } else {
          url.searchParams.set('blindagem', vals.join(','));
        }
        queueOrNavigate(url);
      }, BLINDAGEM_DEBOUNCE_MS);
    });

    // helpers for numeric sanitization and BRL formatting
    const toNum = (s) => {
      if (!s) return '';
      const cleaned = String(s).replace(/\D/g, '');
      return cleaned === '' ? '' : cleaned;
    };

    const formatBRL = (s) => {
      if (!s) return '';
      const digits = String(s).replace(/\D/g, '');
      if (digits === '') return '';
      return 'R$ ' + digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };

    // format kilometers like "1.234 km"
    const formatKM = (s) => {
      if (!s) return '';
      const digits = String(s).replace(/\D/g, '');
      if (digits === '') return '';
      return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' km';
    };

    // Initialize price/km/ano inputs from URL (format price as BRL)
    function initializeRangeInputs() {
      const url = new URL(window.location.href);
      const pmin = url.searchParams.get('preco_min');
      const pmax = url.searchParams.get('preco_max');
      const kmin = url.searchParams.get('km_min');
      const kmax = url.searchParams.get('km_max');
      const amin = url.searchParams.get('ano_min');
      const amax = url.searchParams.get('ano_max');

      if (pmin) $('#preco-min').val(formatBRL(pmin));
      else $('#preco-min').val('');
      // preço máximo: show when provided in URL, otherwise start empty
      if (pmax) $('#preco-max').val(formatBRL(pmax));
      else $('#preco-max').val('');

      if (kmin) $('#km-min').val(formatKM(kmin));
      else $('#km-min').val('');
      if (kmax) $('#km-max').val(formatKM(kmax));
      else $('#km-max').val('');
      if (amin) $('#ano-min').val(amin);
      else $('#ano-min').val('');
      if (amax) $('#ano-max').val(amax);
      else $('#ano-max').val('');
    }
    initializeRangeInputs();

    // Build a full URL from the current state of all filter inputs in the DOM.
    window.buildFilterUrlFromDom = function() {
      try {
        const url = new URL(window.location.href);
        // Remove page
        url.searchParams.delete('page');

        // marca / modelo / versao
        const marcaVal = $('#marca-select').val();
        const modeloVal = $('#modelo-select').val();
        const versaoVal = $('#versao-select').val();
        if (marcaVal) url.searchParams.set('marca', marcaVal);
        else {
          url.searchParams.delete('marca');
          url.searchParams.delete('modelo');
          url.searchParams.delete('versao');
        }
        if (modeloVal) url.searchParams.set('modelo', modeloVal);
        else {
          url.searchParams.delete('modelo');
          url.searchParams.delete('versao');
        }
        if (versaoVal) url.searchParams.set('versao', versaoVal);
        else url.searchParams.delete('versao');

        // condicao
        const conds = [];
        $('.condicao-input:checked').each(function() {
          conds.push($(this).attr('data-val'));
        });
        if (conds.length === 0) url.searchParams.delete('codicao');
        else url.searchParams.set('codicao', conds.join(','));

        // localizacao (use vars if present)
        if (typeof localizacaoEstado !== 'undefined' && localizacaoEstado) url.searchParams.set('estado_local', localizacaoEstado);
        else url.searchParams.delete('estado_local');
        if (typeof localizacaoCidade !== 'undefined' && localizacaoCidade) url.searchParams.set('cidade', localizacaoCidade);
        else url.searchParams.delete('cidade');

        // cor
        const cors = [];
        $('.cor-input:checked').each(function() {
          cors.push($(this).attr('data-val'));
        });
        if (cors.length === 0) url.searchParams.delete('cor');
        else url.searchParams.set('cor', cors.join(','));

        // carroceria
        const carrocerias = [];
        $('.carroceria-input:checked').each(function() {
          carrocerias.push($(this).attr('data-val'));
        });
        if (carrocerias.length === 0) url.searchParams.delete('carroceria');
        else url.searchParams.set('carroceria', carrocerias.join(','));

        // propulsao parents and combustivel subtypes
        const parents = [];
        $('.propulsao:checked').each(function() {
          parents.push($(this).attr('id'));
        });
        if (parents.length === 0) url.searchParams.delete('propulsao');
        else url.searchParams.set('propulsao', parents.join(','));
        const subs = [];
        $('#comb-tipos input:checked, #hib-tipos input:checked').each(function() {
          subs.push($(this).attr('id'));
        });
        if (subs.length === 0) url.searchParams.delete('combustivel');
        else url.searchParams.set('combustivel', subs.join(','));

        // cambio
        const cambios = [];
        $('#cambio .form-check-input:checked').each(function() {
          cambios.push($(this).attr('data-val'));
        });
        if (cambios.length === 0) url.searchParams.delete('cambio');
        else url.searchParams.set('cambio', cambios.join(','));

        // blindagem
        const blis = [];
        $('#blindagem .form-check-input:checked').each(function() {
          blis.push($(this).attr('data-val'));
        });
        if (blis.length === 0) url.searchParams.delete('blindagem');
        else url.searchParams.set('blindagem', blis.join(','));

        // preco, km, ano (use helper toNum if present)
        const pmin = (typeof toNum === 'function') ? toNum($('#preco-min').val()) : ($('#preco-min').val() || '').replace(/\D/g, '');
        const pmax = (typeof toNum === 'function') ? toNum($('#preco-max').val()) : ($('#preco-max').val() || '').replace(/\D/g, '');
        if (!pmin && !pmax) {
          url.searchParams.delete('preco_min');
          url.searchParams.delete('preco_max');
        } else {
          if (pmin) url.searchParams.set('preco_min', pmin);
          else url.searchParams.delete('preco_min');
          if (pmax) url.searchParams.set('preco_max', pmax);
          else url.searchParams.delete('preco_max');
        }

        const kmin = ($('#km-min').val() || '').replace(/\D/g, '');
        const kmax = ($('#km-max').val() || '').replace(/\D/g, '');
        if (!kmin && !kmax) {
          url.searchParams.delete('km_min');
          url.searchParams.delete('km_max');
        } else {
          if (kmin) url.searchParams.set('km_min', kmin);
          else url.searchParams.delete('km_min');
          if (kmax) url.searchParams.set('km_max', kmax);
          else url.searchParams.delete('km_max');
        }

        const amin = ($('#ano-min').val() || '').replace(/\D/g, '');
        const amax = ($('#ano-max').val() || '').replace(/\D/g, '');
        if (!amin && !amax) {
          url.searchParams.delete('ano_min');
          url.searchParams.delete('ano_max');
        } else {
          if (amin) url.searchParams.set('ano_min', amin);
          else url.searchParams.delete('ano_min');
          if (amax) url.searchParams.set('ano_max', amax);
          else url.searchParams.delete('ano_max');
        }

        // search query (global/navbar)
        const qv = ($('#global-search').val() || $('#navbar-search').val() || (typeof pageSearchQ !== 'undefined' ? pageSearchQ : '')).trim();
        if (qv) url.searchParams.set('q', qv);
        else url.searchParams.delete('q');

        return url.toString();
      } catch (e) {
        console.error('buildFilterUrlFromDom failed', e);
        return window.location.href;
      }
    };

    // After all initializers, ensure accordions reflect current state
    updateAccordionState();

    // Handler to update range filters (price, km, ano) with debounce
    let rangeTimer = null;
    const RANGE_DEBOUNCE_MS = 700;
    $(document).on('input change', '#preco-min, #preco-max, #km-min, #km-max, #ano-min, #ano-max', function() {
      if (rangeTimer) clearTimeout(rangeTimer);
      rangeTimer = setTimeout(function() {
        const pmin = toNum($('#preco-min').val());
        const pmax = toNum($('#preco-max').val());
        const kmin = toNum($('#km-min').val());
        const kmax = toNum($('#km-max').val());
        const amin = toNum($('#ano-min').val());
        const amax = toNum($('#ano-max').val());

        const url = new URL(window.location.href);
        url.searchParams.delete('page');

        // price
        if (!pmin && !pmax) {
          url.searchParams.delete('preco_min');
          url.searchParams.delete('preco_max');
        } else {
          if (pmin) url.searchParams.set('preco_min', pmin);
          else url.searchParams.delete('preco_min');
          if (pmax) url.searchParams.set('preco_max', pmax);
          else url.searchParams.delete('preco_max');
        }

        // km
        if (!kmin && !kmax) {
          url.searchParams.delete('km_min');
          url.searchParams.delete('km_max');
        } else {
          if (kmin) url.searchParams.set('km_min', kmin);
          else url.searchParams.delete('km_min');
          if (kmax) url.searchParams.set('km_max', kmax);
          else url.searchParams.delete('km_max');
        }

        // ano
        if (!amin && !amax) {
          url.searchParams.delete('ano_min');
          url.searchParams.delete('ano_max');
        } else {
          if (amin) url.searchParams.set('ano_min', amin);
          else url.searchParams.delete('ano_min');
          if (amax) url.searchParams.set('ano_max', amax);
          else url.searchParams.delete('ano_max');
        }

        queueOrNavigate(url);
      }, RANGE_DEBOUNCE_MS);
    });

    // Format price inputs on blur for better UX (show BRL formatting)
    $(document).on('blur', '#preco-min, #preco-max', function() {
      const raw = $(this).val();
      const digits = toNum(raw);
      $(this).val(digits ? formatBRL(digits) : '');
    });

    // Un-format price inputs on focus so the user can edit/clear them
    $(document).on('focus', '#preco-min, #preco-max', function() {
      const raw = $(this).val();
      const digits = toNum(raw);
      // If empty string from toNum, leave field empty (allows clearing)
      $(this).val(digits === '' ? '' : digits);
    });

    // Live-format KM inputs as user types (display with thousands and ' km')
    $(document).on('input', '#km-min, #km-max', function() {
      const $el = $(this);
      const digits = toNum($el.val());
      $el.val(digits ? formatKM(digits) : '');
    });

    // Ensure year inputs accept only up to 4 digits
    $(document).on('input', '#ano-min, #ano-max', function() {
      const $el = $(this);
      let digits = String($el.val()).replace(/\D/g, '');
      if (digits.length > 4) digits = digits.slice(0, 4);
      $el.val(digits);
    });

  });

  (function() {
    const currentUser = <?= isset($_SESSION['id']) ? json_encode($_SESSION['id']) : 'null' ?>;

    $(document).on('click', 'button.favoritar', function() {
      let anuncioID = $(this).data('anuncio');
      if (!currentUser) {
        // not logged in: redirect to sign-in or show message
        window.location.href = 'sign-in.php';
        return;
      }

      $.post('controladores/veiculos/favoritar-veiculo.php', {
        usuario: currentUser,
        anuncio: anuncioID
      }, function(resposta) {
        console.log("Resposta do servidor:", resposta);
      }, 'json');
    });
  })();
</script>

<script>
  (function() {
    // Move the existing sidebar filters into the offcanvas on small screens
    const offEl = document.getElementById('offcanvasBottom');
    const filtrosOver = document.getElementById('filtros-over');
    const filtrosCol = document.getElementById('filtros-col');

    if (!offEl || !filtrosOver || !filtrosCol) return;

    offEl.addEventListener('show.bs.offcanvas', function() {
      // Only move on small screens (Bootstrap lg breakpoint = 992px)
      if (window.innerWidth >= 992) return;
      // append filtrosOver into offcanvas body (moves node, preserving event handlers)
      const body = offEl.querySelector('.offcanvas-body');
      if (body && filtrosOver.parentElement !== body) {
        body.appendChild(filtrosOver);
      }
      // mark open state for navigation queuing
      window.filterOffcanvasOpen = true;
    });

    offEl.addEventListener('hidden.bs.offcanvas', function() {
      // Move back into sidebar column when offcanvas closes
      if (filtrosCol && filtrosOver.parentElement !== filtrosCol) {
        filtrosCol.appendChild(filtrosOver);
      }
      // offcanvas closed: clear open flag and apply queued navigation if any
      window.filterOffcanvasOpen = false;
      if (typeof window.applyPendingFilterNavigation === 'function') {
        window.applyPendingFilterNavigation();
      }
    });

    // If user resizes from large -> small while offcanvas open, ensure it contains the filters
    window.addEventListener('resize', function() {
      if (!offEl || !filtrosOver || !filtrosCol) return;
      if (window.innerWidth < 992) {
        if (offEl && filtrosOver.parentElement !== offEl.querySelector('.offcanvas-body')) {
          offEl.querySelector('.offcanvas-body').appendChild(filtrosOver);
        }
      } else {
        if (filtrosCol && filtrosOver.parentElement !== filtrosCol) {
          filtrosCol.appendChild(filtrosOver);
        }
      }
    });
  })();
</script>

</html>