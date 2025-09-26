<?php
session_start();

$_SESSION['modelo'] = isset($_POST['modelo']) ? strtolower($_POST['modelo']) : $_SESSION['modelo'];
$_SESSION['marca'] = isset($_POST['marca']) ? $_POST['marca'] : $_SESSION['marca'];
$_SESSION['versao'] = isset($_POST['versao']) ? $_POST['versao'] : $_SESSION['versao'];
$_SESSION['cor'] = isset($_POST['cor']) ? $_POST['cor'] : $_SESSION['cor'];
$_SESSION['ano'] = isset($_POST['ano']) ? $_POST['ano'] : $_SESSION['ano'];
$_SESSION['fabr'] = isset($_POST['fabr']) ? $_POST['fabr'] : $_SESSION['fabr'];
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
          <form method="post" action="vender-preco.php" class="card-body p-5 d-flex flex-column justify-content-center align-items-center">
            <h3 class="mb-4 fw-bold">Condição e uso</h3>
            <div class="row row-cols-1 row-cols-md-2 w-100 g-4">
              <div class="col">
                <label for="quilometragem-input" class="form-text mb-2">Quilometragem<sup>*</sup></label>
                <input type="text" class="form-control" id="quilometragem-input" value="0 km" name="quilometragem" placeholder="Informe a quilometragem do veículo" required>
              </div>
              <div class="col">
                <label for="proprietario-select" class="form-text mb-2">Quantidade de proprietários<sup>*</sup></label>
                <select class="form-select shadow-sm" id="proprietario-select" aria-label="Default select example" name="proprietario" required>
                  <option value="" selected hidden>Informe quantos proprietários o veículo já teve</option>
                  <option value="1">1° proprietário</option>
                  <option value="2">2° proprietário</option>
                  <option value="3">3° proprietário</option>
                  <option value="4">4° proprietário</option>
                  <option value="5">5° proprietário ou mais</option>
                </select>
              </div>
              <div class="col">
                <label for="revisao-select" class="form-text mb-2">Revisão (Últimos 12 meses)<sup>*</sup></label>
                <select class="form-select shadow-sm" id="revisao-select" aria-label="Default select example" name="revisao" required>
                  <option value="" selected hidden>Informe quantas revisões foram feitas<i class="fa fa-sort-amount-asc" aria-hidden="true"></i></option>
                  <option value="0">Nenhuma</option>
                  <option value="1">1 revisão</option>
                  <option value="2">2 revisões</option>
                  <option value="3">3 revisões</option>
                  <option value="4">4 revisões</option>
                  <option value="5">5 revisões ou mais</option>
                </select>
              </div>
              <div class="col">
                <label for="vistoria-select" class="form-text mb-2">Vistoria<sup>*</sup></label>
                <select class="form-select shadow-sm" id="vistoria-select" aria-label="Default select example" name="vistoria" required>
                  <option value="" selected hidden>Informe a situação da vistoria<i class="fa fa-sort-amount-asc" aria-hidden="true"></i></option>
                  <option value="F">Feita</option>
                  <option value="V">Vencida</option>
                </select>
              </div>
              <div class="col">
                <div class="form-text mb-2 d-flex justify-content-between">
                  <label for="sinistro-select">Histórico de sinistro<sup>*</sup></label>
                  <button class="btn btn-sm border-0 p-0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Registro de ocorrências graves como acidentes, inundações ou incêndios que o veículo sofreu. Afeta o valor e a segurança."><i class="bi bi-question-circle"></i></button>
                </div>
                <select class="form-select shadow-sm" id="sinistro-select" aria-label="Default select example" name="sinistro" required>
                  <option value="" selected hidden>Informe as ocorrências graves sofridas pelo veículo<i class="fa fa-sort-amount-asc" aria-hidden="true"></i></option>
                  <option value="0">🟢</span> Nenhum</option>
                  <option value="L">🟡</span> Leve</option>
                  <option value="M">🟠</span> Moderado</option>
                  <option value="G">🔴</span> Grave</option>
                </select>
              </div>
              <div class="col">
                <label for="ipva-select" class="form-text mb-2">IPVA Pago<sup>*</sup></label>
                <select class="form-select shadow-sm" id="ipva-select" aria-label="Default select example" name="ipva" required>
                  <option value="" selected hidden>Informe a situação do IPVA<i class="fa fa-sort-amount-asc" aria-hidden="true"></i></option>
                  <option value="D">Em dia</option>
                  <option value="A">Atrasado</option>
                  <option value="I">Isento</option>
                </select>
              </div>
              <div class="col">
                <label for="licenciamento-select" class="form-text mb-2">Licenciamento<sup>*</sup></label>
                <select class="form-select shadow-sm" id="licenciamento-select" aria-label="Default select example" name="licenciamento" required>
                  <option value="" selected hidden>Informe a situação do licenciamento<i class="fa fa-sort-amount-asc" aria-hidden="true"></i></option>
                  <option value="D">Em dia</option>
                  <option value="V">Vencido</option>
                  <option value="T">Em processo de transferência</option>
                </select>
              </div>
              <div class="col">
                <label for="consevacao-select" class="form-text mb-2">Estado de conservação<sup>*</sup></label>
                <select class="form-select shadow-sm" id="consevacao-select" aria-label="Default select example" name="consevacao" required>
                  <option value="" selected hidden>Informe o estado de conservação<i class="fa fa-sort-amount-asc" aria-hidden="true"></i></option>
                  <option value="4">Excelente</option>
                  <option value="3">Bom</option>
                  <option value="2">Regular</option>
                  <option value="1">Ruim</option>
                </select>
              </div>
            </div>
            <div class="row w-100 mt-4" id="uso-row" style="display: none;">
              <div class="col">
                <label for="uso-select" class="form-text mb-2">Uso anterior<sup>*</sup></label>
                <select class="form-select shadow-sm" id="uso-select" aria-label="Default select example" name="uso_anterior">
                  <option value="" selected hidden>Informe para que o veículo era usado<i class="fa fa-sort-amount-asc" aria-hidden="true"></i></option>
                  <option value="P">Uso particular</option>
                  <option value="A">Carro de aluguel</option>
                  <option value="T">Trasporte de passageiros</option>
                  <option value="E">Frota empresarial</option>
                  <option value="O">Outro</option>
                </select>
              </div>
            </div>
            <div class="row d-flex justify-content-between align-items-center w-100 mt-5">
              <div class="col-auto">
                <a href="vender-infos.php" class="btn text-muted"><i class="bi bi-caret-left"></i>&nbsp;Voltar</a>
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
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    const kmInput = $('#quilometragem-input');

    kmInput.on('focus', function() {
      kmInput.prop('required', false);
      if ($(this).val() === '0 km') {
        $(this).val('');
      } else {
        const currentValue = $(this).val().replace(' km', '').replace(/\./g, '');
        $(this).val(currentValue);
      }
    });

    kmInput.on('blur', function() {
      kmInput.prop('required', true);
      if ($(this).val() === '') {
        $(this).val('0 km');
      } else {
        let numericValue = $(this).val().replace(/\D/g, '');
        if (numericValue === '') {
          numericValue = '0';
        }
        if (numericValue > 0) {
          $('#uso-row').slideDown(150);
          $('#uso-select').prop('required', true);
        } else {
          $('#uso-row').slideUp(100);
          $('#uso-select').prop('required', false);
          $('#uso-select').val('');
        }
        const formattedValue = parseInt(numericValue, 10).toLocaleString('pt-BR') + ' km';
        $(this).val(formattedValue);
      }
    });

    $('#placa').on('input', function() {
      formatarPlacaRobusto($(this));
    });
  })
</script>

</html>