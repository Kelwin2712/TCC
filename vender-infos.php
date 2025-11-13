<?php
session_start();
include('controladores/conexao_bd.php');

$_SESSION['placa'] = isset($_POST['placa']) ? $_POST['placa'] : null;
$placa = $_SESSION['placa'];

$sql = "SELECT placa FROM anuncios_carros WHERE placa = '$placa'";
$resultado = mysqli_query($conexao, $sql);

if (mysqli_num_rows($resultado) > 0) {
  $_SESSION['msg_alert'] = ['danger', 'Placa já registrada!'];
  header('Location: vender-placa.php');
  exit;
}

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
            <h3 class="mb-4 fw-bold">Informe os dados do veículo</h3>
            <div class="row w-100 px-5">
              <div class="col-md-6 mb-2">
                <div class="d-flex justify-content-between mb-2 form-text">
                  <label for="marca-select">Marca<sup>*</sup></label>
                  <a href="#" class="link-dark">Não encontrou a marca?</a>
                </div>
                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                  <option value="" selected hidden>Escolha uma marca</option>
                  <?php foreach ($marcas as $marca): ?>
                    <option value="<?= $marca['id'] ?>"><?= $marca['nome'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6 mb-2">
                <div class="d-flex justify-content-between mb-2 form-text">
                  <label for="modelo-input">Modelo<sup>*</sup></label>
                  <a href="#" class="link-dark">Não encontrou o modelo?</a>
                </div>
                <input type="text" autocomplete="off" class="form-control" id="modelo-input" name="modelo" placeholder="Escolha um modelo" required>
              </div>
              <div class="col-sm-6 mb-2">
                <label for="fabr-select" class="form-text mb-2">Ano de fabricação<sup>*</sup></label>
                <select class="form-select shadow-sm" id="fabr-select" aria-label="Default select example" name="fabr" required>
                  <option value="" selected hidden>Escolha um ano</option>
                  <?php
                  $quantidade = date('Y') - 1930;
                  for ($i = 0; $i <= $quantidade; $i++): ?>
                    <option value="<?= date('Y') - $i ?>"><?= date('Y') - $i ?></option>
                  <?php endfor; ?>
                </select>
              </div>
              <div class="col-sm-6 mb-2">
                <label for="ano-select" class="form-text mb-2">Ano do modelo<sup>*</sup></label>
                <select class="form-select shadow-sm" id="ano-select" aria-label="Default select example" name="ano" required>
                  <option value="" selected hidden>Escolha um ano</option>
                  <option value="" disabled>Escolha o ano de fabricação antes</option>
                </select>
              </div>
              <div class="col-xl-6 mb-2">
                <div class="d-flex justify-content-between mb-2 form-text">
                  <label for="versao-input">Versão<sup>*</sup></label>
                  <a href="#" class="link-dark">Não encontrou a versão?</a>
                </div>
                <input type="text" class="form-control" autocomplete="off" id="versao-input" name="versao" placeholder="Escolha uma versão" required>
              </div>
              <div class="col-xl-6 mb-2">
                <div class="d-flex justify-content-between mb-2 form-text">
                  <label for="cor-select">Cor<sup>*</sup></label>
                  <a href="#" class="link-dark">Não encontrou a cor?</a>
                </div>
                <select class="form-select shadow-sm" id="cor-select" aria-label="Default select example" name="cor" required>
                  <option value="" selected hidden>Escolha uma cor</option>
                  <?php foreach ($cores as $cor): ?>
                    <option value="<?= $cor['id'] ?>"><?= $cor['nome'] ?></option>
                  <?php endforeach; ?>
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
  })
</script>

</html>