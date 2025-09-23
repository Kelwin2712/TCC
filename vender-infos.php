<?php
session_start();
http_response_code(404);
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
          <div class="card-body p-5 d-flex flex-column justify-content-center align-items-center">
            <h3 class="mb-4 fw-bold">Informe os dados do veículo</h3>
            <div class="row w-100 px-5">
              <div class="col-md-6 mb-2">
                <div class="d-flex justify-content-between mb-2 form-text">
                  <label for="marca-select">Marca<sup>*</sup></label>
                  <a href="#" class="link-dark">Não encontrou a marca?</a>
                </div>
                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" required>
                  <option value="" selected hidden>Escolha uma marca</option>
                  <option value="0">Abarth</option>
                  <option value="0">Alfa Romeo</option>
                  <option value="0">Aston Martin</option>
                  <option value="0">Audi</option>
                  <option value="0">Bentley</option>
                  <option value="0">BMW</option>
                  <option value="0">Bugatti</option>
                  <option value="0">BYD</option>
                  <option value="0">Cadillac</option>
                  <option value="0">Chevrolet</option>
                  <option value="0">Chrysler</option>
                  <option value="0">Citroën</option>
                  <option value="0">Corvette</option>
                  <option value="0">Dacia</option>
                  <option value="0">Dodge</option>
                  <option value="0">Ferrari</option>
                  <option value="0">Fiat</option>
                  <option value="0">Ford</option>
                  <option value="0">Genesis</option>
                  <option value="0">GMC</option>
                  <option value="0">GWM</option>
                  <option value="0">Honda</option>
                  <option value="0">Hummer</option>
                  <option value="0">Hyundai</option>
                  <option value="0">Infiniti</option>
                  <option value="0">JAECOO</option>
                  <option value="0">Jaguar</option>
                  <option value="0">Jeep</option>
                  <option value="0">Kia</option>
                  <option value="0">Koenigsegg</option>
                  <option value="0">Lamborghini</option>
                  <option value="0">Lacia</option>
                  <option value="0">Land Rover</option>
                  <option value="0">Lexus</option>
                  <option value="0">Lincoln</option>
                  <option value="0">Lotus</option>
                  <option value="0">Maserati</option>
                  <option value="0">Mazda</option>
                  <option value="0">McLaren</option>
                  <option value="0">Mercedes-Benz</option>
                  <option value="0">MINI</option>
                  <option value="0">Mitsubishi</option>
                  <option value="0">Nissan</option>
                  <option value="0">Omoda</option>
                  <option value="0">Opel</option>
                  <option value="0">Peugeot</option>
                  <option value="0">Porsche</option>
                  <option value="0">Ram</option>
                  <option value="0">Renault</option>
                  <option value="0">Rolls-Royce</option>
                  <option value="0">Skoda</option>
                  <option value="0">Smart</option>
                  <option value="0">Subaru</option>
                  <option value="0">Suzuki</option>
                  <option value="0">Tesla</option>
                  <option value="0">Toyota</option>
                  <option value="0">Volkswagen</option>
                  <option value="0">Volvo</option>
                </select>
              </div>
              <div class="col-md-6 mb-2">
                <div class="d-flex justify-content-between mb-2 form-text">
                  <label for="modelo-input">Modelo<sup>*</sup></label>
                  <a href="#" class="link-dark">Não encontrou o modelo?</a>
                </div>
                <input type="text" class="form-control" name="modelo-input" id="modelo-input" placeholder="Escolha um modelo">
              </div>
              <div class="col-sm-6 mb-2">
                <label for="ano-select" class="form-text mb-2">Ano do modelo<sup>*</sup></label>
                <select class="form-select shadow-sm" id="ano-select" aria-label="Default select example" required>
                  <option value="" selected hidden>Escolha um ano</option>
                  <?php $quantidade = 97;
                  for ($i = 1; $i <= $quantidade; $i++): ?>
                    <option value="<?= 2027 - $i ?>"><?= 2027 - $i ?></option>
                  <?php endfor; ?>
                </select>
              </div>
              <div class="col-sm-6 mb-2">
                <label for="fabr-select" class="form-text mb-2">Ano de fabricação<sup>*</sup></label>
                <select class="form-select shadow-sm" id="fabr-select" aria-label="Default select example" required>
                  <option value="" selected hidden>Escolha um ano</option>
                  <?php $quantidade = 96;
                  for ($i = 1; $i <= $quantidade; $i++): ?>
                    <option value="<?= 2026 - $i ?>"><?= 2026 - $i ?></option>
                  <?php endfor; ?>
                </select>
              </div>
              <div class="col-xl-6 mb-2">
                <div class="d-flex justify-content-between mb-2 form-text">
                  <label for="versao-input">Versão<sup>*</sup></label>
                  <a href="#" class="link-dark">Não encontrou a versão?</a>
                </div>
                <input type="text" class="form-control" name="versao-input" id="versao-input" placeholder="Escolha uma versão">
              </div>
              <div class="col-xl-6 mb-2">
                <div class="d-flex justify-content-between mb-2 form-text">
                  <label for="cor-select">Cor<sup>*</sup></label>
                  <a href="#" class="link-dark">Não encontrou a cor?</a>
                </div>
                <select class="form-select shadow-sm" id="cor-select" aria-label="Default select example" required>
                  <option value="" selected hidden>Escolha uma cor</option>
                  <option value="branco">Branco</option>
                  <option value="preto">Preto</option>
                  <option value="vermelho">Vermelho</option>
                  <option value="azul">Azul</option>
                  <option value="cinza">Cinza</option>
                  <option value="prata">Prata</option>
                  <option value="vinho">Vinho</option>
                  <option value="marrom">Marrom</option>
                  <option value="laranja">Laranja</option>
                  <option value="amarelo">Amarelo</option>
                  <option value="dourado">Dourado</option>
                  <option value="verde">Verde</option>
                  <option value="bege">Bege</option>
                </select>
              </div>
            </div>
            <div class="row d-flex justify-content-between align-items-center w-100 mt-5">
              <div class="col-auto">
                <a href="vender-placa.php" class="btn text-muted"><i class="bi bi-caret-left"></i>&nbsp;Voltar</a>
              </div>
              <div class="col-auto">
                <a href="vender-infos.php" class="btn btn-dark shadow-sm">Próximo passo&nbsp;<i class="bi bi-caret-right-fill"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="script.js"></script>
<script>
  function formatarPlacaRobusto($input) {
    let texto = $input.val().toUpperCase();
    texto = texto.replace(/[^A-Z0-9]/g, '');

    const pattern = ['L', 'L', 'L', 'N', 'L', 'N', 'N'];
    let resultado = '';
    let i = 0;

    for (let p = 0; p < pattern.length && i < texto.length;) {
      const ch = texto[i];
      if (pattern[p] === 'L') {
        if (/[A-Z]/.test(ch)) {
          resultado += ch;
          p++;
          i++;
        } else {
          i++;
        }
      } else {
        if (/[0-9]/.test(ch)) {
          resultado += ch;
          p++;
          i++;
        } else {
          i++;
        }
      }
    }

    $input.val(resultado);
  }

  $('#placa').on('input', function() {
    formatarPlacaRobusto($(this));
  });
</script>

</html>