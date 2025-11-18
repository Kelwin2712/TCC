<?php
session_start();
// Accept data posted from vender-infos.php and persist into session
// Keep only required DB queries for rendering selects (marcas, cores, carrocerias)
include('controladores/conexao_bd.php');

// Merge incoming POST values into session (only expected fields)
$_SESSION['modelo'] = isset($_POST['modelo']) ? strtolower($_POST['modelo']) : (isset($_SESSION['modelo']) ? $_SESSION['modelo'] : null);
$_SESSION['marca'] = isset($_POST['marca']) ? $_POST['marca'] : (isset($_SESSION['marca']) ? $_SESSION['marca'] : null);
$_SESSION['versao'] = isset($_POST['versao']) ? $_POST['versao'] : (isset($_SESSION['versao']) ? $_SESSION['versao'] : null);
$_SESSION['cor'] = isset($_POST['cor']) ? $_POST['cor'] : (isset($_SESSION['cor']) ? $_SESSION['cor'] : null);
$_SESSION['ano'] = isset($_POST['ano']) ? $_POST['ano'] : (isset($_SESSION['ano']) ? $_SESSION['ano'] : null);
$_SESSION['fabr'] = isset($_POST['fabr']) ? $_POST['fabr'] : (isset($_SESSION['fabr']) ? $_SESSION['fabr'] : null);
$_SESSION['carroceria'] = isset($_POST['carroceria']) ? $_POST['carroceria'] : (isset($_SESSION['carroceria']) ? $_SESSION['carroceria'] : null);
// placa may be set earlier; preserve if provided by POST
if (isset($_POST['placa'])) {
  $_SESSION['placa'] = $_POST['placa'];
}

// check duplicate placa (if present)
$placa = isset($_SESSION['placa']) ? mysqli_real_escape_string($conexao, $_SESSION['placa']) : null;
if (!empty($placa)) {
  $sqlp = "SELECT placa FROM anuncios_carros WHERE placa = '$placa'";
  $resP = mysqli_query($conexao, $sqlp);
  if ($resP && mysqli_num_rows($resP) > 0) {
    $_SESSION['msg_alert'] = ['danger', 'Placa já registrada!'];
    header('Location: vender-placa.php');
    exit;
  }
}

// load select options
$sql = "SELECT * FROM marcas";
$resultado = mysqli_query($conexao, $sql);
$marcas = [];
while ($linha = mysqli_fetch_array($resultado)) {
  $marcas[] = $linha;
}

$sql = "SELECT * FROM cores";
$resultado = mysqli_query($conexao, $sql);
$cores = [];
while ($linha = mysqli_fetch_array($resultado)) {
  $cores[] = $linha;
}

$sql = "SELECT * FROM carrocerias ORDER BY id ASC";
$resultado = mysqli_query($conexao, $sql);
$carrocerias = [];
while ($linha = mysqli_fetch_array($resultado)) {
  $carrocerias[] = $linha;
}

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <title>Fahren</title>
  <style>
    input[name="carroceria"]+label {
      color: var(--bs-secondary-color);
      cursor: pointer;
      border: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color);
      transition: background-color 0.2s, border-color 0.2s;
    }

    input[name="carroceria"]+label img {
      opacity: 0.7;
    }

    input[name="carroceria"]:checked+label {
      --bs-text-opacity: 1;
      --bs-border-opacity: 1;
      background-color: var(--bs-primary-bg-subtle);
      border-color: rgba(var(--bs-primary-rgb), var(--bs-border-opacity));
      color: rgba(var(--bs-primary-rgb), var(--bs-text-opacity));
    }
  </style>
</head>

<body>
  <div class="vh-100 d-flex flex-column">
    <?php include 'estruturas/alert/alert.php' ?>
    <?php include 'estruturas/navbar/navbar-default.php' ?>
    <main class="bg-body-tertiary fs-nav flex-grow-1 d-flex justify-content-center align-items-center">
      <div class="container h-100">
        <div class="position-relative my-5 mx-auto" style="max-width: 75vw;">
          <div class="progress" role="progressbar" aria-label="Progress" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="height: 1px;">
            <div class="progress-bar bg-dark" style="width: 0%"></div>
          </div>
          <button type="button" class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-dark rounded-pill" style="width: 2rem; height:2rem;">1</button>
          <button type="button" class="position-absolute top-0 start-50 translate-middle btn btn-sm bg-body-secondary rounded-pill" style="width: 2rem; height:2rem;">2</button>
          <button type="button" class="position-absolute top-0 start-100 translate-middle btn btn-sm bg-body-secondary rounded-pill" style="width: 2rem; height:2rem;">3</button>
        </div>
        <div class="card">
          <form method="post" action="vender-condicao.php" class="card-body p-5 d-flex flex-column justify-content-center align-items-center">
            <h3 class="mb-4 fw-bold">Informações adicionais</h3>
            <div class="row row-cols-1 row-cols-md-2 w-100 px-5 g-4">
              <div class="col">
                <label for="propulsao-select" class="form-text mb-2">Propulsão<sup>*</sup></label>
                <select class="form-select shadow-sm" id="propulsao-select" aria-label="Default select example" name="propulsao" required>
                  <option value="" selected hidden>Selecione a propulsão</option>
                  <option value="eletrico">Elétrico</option>
                  <option value="combustao">Combustão</option>
                  <option value="hibrido">Híbrido</option>
                </select>
              </div>
              <div class="col">
                <label for="combustivel-select" class="form-text mb-2">Combustível<sup>*</sup></label>
                <select class="form-select shadow-sm" id="combustivel-select" aria-label="Default select example" name="combustivel" required>
                  <option value="" selected hidden>Selecione o combustível</option>
                </select>
              </div>
              <div class="col">
                <label for="cambio-select" class="form-text mb-2">Câmbio<sup>*</sup></label>
                <select class="form-select shadow-sm" id="cambio-select" aria-label="Default select example" name="cambio" required>
                  <option value="" selected hidden>Selecione o câmbio</option>
                  <option value="A">Automático</option>
                  <option value="M">Manual</option>
                </select>
              </div>
              <div class="col">
                <label for="blindagem-select" class="form-text mb-2">Blindagem<sup>*</sup></label>
                <select class="form-select shadow-sm" id="blindagem-select" aria-label="Default select example" name="blindagem" required>
                  <option value="0" selected>Sem blindagem</option>
                  <option value="1">Com blindagem</option>
                </select>
              </div>
              <div class="col">
                <label for="portas-select" class="form-text mb-2">Quantidade de portas<sup>*</sup></label>
                <select class="form-select shadow-sm" id="portas-select" aria-label="Default select example" name="portas_qtd" required>
                  <option value="" selected hidden>Selecione a quantidade</option>
                  <option value="1">1 porta</option>
                  <option value="2">2 portas</option>
                  <option value="3">3 portas</option>
                  <option value="4">4 portas</option>
                  <option value="5">Mais de 4 portas</option>
                </select>
              </div>
              <div class="col">
                <label for="assentos-select" class="form-text mb-2">Quantidade de assentos<sup>*</sup></label>
                <select class="form-select shadow-sm" id="assentos-select" aria-label="Default select example" name="assentos_qtd" required>
                  <option value="" selected hidden>Selecione a quantidade</option>
                  <option value="1">1 assento</option>
                  <option value="2">2 assentos</option>
                  <option value="3">3 assentos</option>
                  <option value="4">4 assentos</option>
                  <option value="5">5 assentos</option>
                  <option value="6">6 assentos</option>
                  <option value="7">7 assentos</option>
                  <option value="8">8 ou mais assentos</option>
                </select>
              </div>
            </div>
            <div class="row d-flex justify-content-between align-items-center w-100 mt-5">
              <div class="col-auto">
                <a href="vender-placa.php" class="btn text-muted"><i class="bi bi-caret-left"></i>&nbsp;Voltar</a>
              </div>
              <div class="col-auto">
                <button type="submit" class="btn btn-dark shadow-sm">Próximo passo&nbsp;<i class="bi bi-caret-right-fill"></i></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="script.js"></script>
<script>
  $(function() {
    $('#fabr-select').on('change', function() {
      const fabrModelo = parseInt($(this).val());
      const anoSelect = $('#ano-select');

      anoSelect.empty('option[selected="false"]');

      for (let year = fabrModelo + 1; year >= fabrModelo; year--) {
        if (fabrModelo == year) {
          anoSelect.append('<option value="' + year + '" selected>' + year + '</option>');
        } else {
          anoSelect.append('<option value="' + year + '">' + year + '</option>');
        }
      }
    });

    $('#placa').on('input', function() {
      formatarPlacaRobusto($(this));
    });

    // Propulsão field dynamic loader
    const propulsaoSelect = $('#propulsao-select');
    const combustivelSelect = $('#combustivel-select');
    
    const propulsaoConfig = {
        'eletrico': { combustivel: 'Elétrico', opcoes: ['Elétrico'] },
        'combustao': { combustivel: 'Gasolina', opcoes: ['Gasolina', 'Álcool', 'Flex', 'Diesel', 'GNV'] },
        'hibrido': { combustivel: 'HEV', opcoes: ['HEV', 'PHEV'] }
    };
    
    function updateCombustivelOptions() {
        const selectedValue = propulsaoSelect.val();
        combustivelSelect.empty();
        combustivelSelect.append('<option value="" selected hidden>Selecione o combustível</option>');
        
        if (selectedValue && propulsaoConfig[selectedValue]) {
            const opcoes = propulsaoConfig[selectedValue].opcoes;
            opcoes.forEach(function(opcao) {
                combustivelSelect.append('<option value="' + opcao + '">' + opcao + '</option>');
            });
        }
    }
    
    // Update when propulsão changes
    propulsaoSelect.on('change', updateCombustivelOptions);
  })
</script>

</html>