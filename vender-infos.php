<?php
session_start();

$_SESSION['placa'] = isset($_POST['placa']) ? $_POST['placa'] : null;
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
                  <option value="abarth">Abarth</option>
                  <option value="alfa">Alfa Romeo</option>
                  <option value="aston">Aston Martin</option>
                  <option value="audi">Audi</option>
                  <option value="bentley">Bentley</option>
                  <option value="bmw">BMW</option>
                  <option value="bugatti">Bugatti</option>
                  <option value="byd">BYD</option>
                  <option value="cadillac">Cadillac</option>
                  <option value="chevrolet">Chevrolet</option>
                  <option value="chrysler">Chrysler</option>
                  <option value="citroen">Citroën</option>
                  <option value="corvette">Corvette</option>
                  <option value="dacia">Dacia</option>
                  <option value="dodge">Dodge</option>
                  <option value="ferrari">Ferrari</option>
                  <option value="fiat">Fiat</option>
                  <option value="ford">Ford</option>
                  <option value="genesis">Genesis</option>
                  <option value="gmc">GMC</option>
                  <option value="gwm">GWM</option>
                  <option value="honda">Honda</option>
                  <option value="hummer">Hummer</option>
                  <option value="hyundai">Hyundai</option>
                  <option value="infiniti">Infiniti</option>
                  <option value="jaecoo">JAECOO</option>
                  <option value="jaguar">Jaguar</option>
                  <option value="jeep">Jeep</option>
                  <option value="kia">Kia</option>
                  <option value="koenigsegg">Koenigsegg</option>
                  <option value="lamborghini">Lamborghini</option>
                  <option value="lancia">Lancia</option>
                  <option value="land">Land Rover</option>
                  <option value="lexus">Lexus</option>
                  <option value="lincoln">Lincoln</option>
                  <option value="lotus">Lotus</option>
                  <option value="maserati">Maserati</option>
                  <option value="mazda">Mazda</option>
                  <option value="mclaren">McLaren</option>
                  <option value="mercedes">Mercedes-Benz</option>
                  <option value="mini">MINI</option>
                  <option value="mitsubishi">Mitsubishi</option>
                  <option value="nissan">Nissan</option>
                  <option value="omoda">Omoda</option>
                  <option value="opel">Opel</option>
                  <option value="peugeot">Peugeot</option>
                  <option value="porsche">Porsche</option>
                  <option value="ram">Ram</option>
                  <option value="renault">Renault</option>
                  <option value="rolls">Rolls-Royce</option>
                  <option value="skoda">Skoda</option>
                  <option value="smart">Smart</option>
                  <option value="subaru">Subaru</option>
                  <option value="suzuki">Suzuki</option>
                  <option value="tesla">Tesla</option>
                  <option value="toyota">Toyota</option>
                  <option value="volkswagen">Volkswagen</option>
                  <option value="volvo">Volvo</option>
                </select>
              </div>
              <div class="col-md-6 mb-2">
                <div class="d-flex justify-content-between mb-2 form-text">
                  <label for="modelo-input">Modelo<sup>*</sup></label>
                  <a href="#" class="link-dark">Não encontrou o modelo?</a>
                </div>
                <input type="text" class="form-control" id="modelo-input" name="modelo" placeholder="Escolha um modelo" required>
              </div>
              <div class="col-sm-6 mb-2">
                <label for="ano-select" class="form-text mb-2">Ano do modelo<sup>*</sup></label>
                <select class="form-select shadow-sm" id="ano-select" aria-label="Default select example" name="ano" required>
                  <option value="" selected hidden>Escolha um ano</option>
                  <?php $quantidade = 97;
                  for ($i = 1; $i <= $quantidade; $i++): ?>
                    <option value="<?= 2027 - $i ?>"><?= 2027 - $i ?></option>
                  <?php endfor; ?>
                </select>
              </div>
              <div class="col-sm-6 mb-2">
                <label for="fabr-select" class="form-text mb-2">Ano de fabricação<sup>*</sup></label>
                <select class="form-select shadow-sm" id="fabr-select" aria-label="Default select example" name="fabr" required>
                  <option value="" selected hidden>Escolha um ano</option>
                  <option value="" disabled>Escolha o ano do modelo antes</option>
                </select>
              </div>
              <div class="col-xl-6 mb-2">
                <div class="d-flex justify-content-between mb-2 form-text">
                  <label for="versao-input">Versão<sup>*</sup></label>
                  <a href="#" class="link-dark">Não encontrou a versão?</a>
                </div>
                <input type="text" class="form-control" id="versao-input" name="versao" placeholder="Escolha uma versão" required>
              </div>
              <div class="col-xl-6 mb-2">
                <div class="d-flex justify-content-between mb-2 form-text">
                  <label for="cor-select">Cor<sup>*</sup></label>
                  <a href="#" class="link-dark">Não encontrou a cor?</a>
                </div>
                <select class="form-select shadow-sm" id="cor-select" aria-label="Default select example" name="cor" required>
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
  $(function(){
  $('#ano-select').on('change', function() {
    const anoModelo = parseInt($(this).val());
    const fabrSelect = $('#fabr-select');

    fabrSelect.empty('option[selected="false"]');

    for (let year = anoModelo; year >= anoModelo-1; year--) {
      fabrSelect.append('<option value="' + year + '">' + year + '</option>');
    }
  });

  $('#placa').on('input', function() {
    formatarPlacaRobusto($(this));
  });
  })
</script>

</html>