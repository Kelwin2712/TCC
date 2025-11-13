<?php
session_start();

$_SESSION['condicao'] = isset($_POST['condicao']) ? $_POST['condicao'] : null;
$_SESSION['quilometragem'] = isset($_POST['quilometragem']) ? $_POST['quilometragem'] : null;
$_SESSION['proprietario'] = isset($_POST['proprietario']) ? $_POST['proprietario'] : null;
$_SESSION['revisao'] = isset($_POST['revisao']) ? $_POST['revisao'] : null;
$_SESSION['vistoria'] = isset($_POST['vistoria']) ? $_POST['vistoria'] : null;
$_SESSION['sinistro'] = isset($_POST['sinistro']) ? $_POST['sinistro'] : null;
$_SESSION['ipva'] = isset($_POST['ipva']) ? $_POST['ipva'] : null;
$_SESSION['licenciamento'] = isset($_POST['licenciamento']) ? $_POST['licenciamento'] : null;
$_SESSION['consevacao'] = isset($_POST['consevacao']) ? $_POST['consevacao'] : null;
$_SESSION['uso_anterior'] = isset($_POST['uso_anterior']) ? $_POST['uso_anterior'] : null;
// carregar lista de lojas para o select (se disponível)
include_once 'controladores/conexao_bd.php';
$lojas = [];
$res_lojas = mysqli_query($conexao, "SELECT id, nome, cidade FROM lojas ORDER BY nome ASC");
if ($res_lojas) {
  while ($l = mysqli_fetch_assoc($res_lojas)) $lojas[] = $l;
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
    .vendedor-card {
    position: relative;
}

.vendedor-radio {
    position: absolute;
    opacity: 0;
}

.vendedor-option {
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
    cursor: pointer;
}

.vendedor-option:hover {
    border-color: #adb5bd;
    background-color: #f8f9fa;
}

.vendedor-radio:checked + .vendedor-option {
    border-color: #0d6efd !important;
    background-color: #f0f8ff;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.radio-indicator {
    width: 20px;
    height: 20px;
    border: 2px solid #dee2e6;
    border-radius: 50%;
    position: relative;
    transition: all 0.3s ease;
}

.vendedor-radio:checked + .vendedor-option .radio-indicator {
    border-color: #0d6efd;
    background-color: #0d6efd;
}

.vendedor-radio:checked + .vendedor-option .radio-indicator::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background: white;
    border-radius: 50%;
}

.cursor-pointer {
    cursor: pointer;
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
          <form method="post" action="vender-fotos.php" class="card-body p-5 d-flex flex-column justify-content-center align-items-center">
            <h3 class="mb-4 fw-bold">Preço e negociação</h3>
            <div class="row row-cols-1 row-cols-md-2 w-100 g-4 mb-4">
              <div class="col">
                <label for="preco-input" class="form-text mb-2">Preço<sup>*</sup></label>
                <input type="text" class="form-control preco-input-rs" id="preco-input" value="0" name="preco" placeholder="Informe o preço do veículo" required>
              </div>
              <div class="col">
                <label for="troca-select" class="form-text mb-2">Aceita troca<sup>*</sup></label>
                <select class="form-select shadow-sm" id="troca-select" aria-label="Default select example" name="troca" required>
                  <option value="0" selected>Não</option>
                  <option value="1">Sim</option>
                </select>
              </div>
            </div>
            <div class="row d-flex w-100">
              <div class="col d-flex flex-column gap-3">
                <p class="form-text mb-0">Tipo de Vendedor</p>

                <!-- Particular -->
                <div class="vendedor-card">
                  <input type="radio" name="tipo_vendedor" value="pf" id="vendedor-pf" class="vendedor-radio">
                  <label for="vendedor-pf" class="vendedor-option d-flex justify-content-between w-100 border py-2 px-3 rounded-3 cursor-pointer">
                    <div class="d-flex flex-column py-2 w-100">
                      <p class="h6 fw-semibold text-dark mb-1">Particular</p>
                      <p class="text-muted mb-0 small">Venda direta do proprietário do veículo</p>

                      <!-- Campos do Particular (inicialmente escondidos) -->
                      <div class="campos-particular mt-3 me-5" style="display: none;">
                        <div class="d-flex w-100 gap-3">
                          <div class="col">
                            <label for="email-input" class="form-text mb-2">Email de contato<sup>*</sup></label>
                            <input type="email" class="form-control" id="email-input" name="email" placeholder="Informe o email de contato" value="<?= $_SESSION['email'] ?>" required>
                          </div>
                          <div class="col">
                            <label for="telefone-input" class="form-text mb-2">Telefone de contato<sup>*</sup></label>
                            <input type="text" class="form-control telefone-mask" id="telefone-input" name="telefone" maxlength="15" minlength="14" value="<?= isset($_SESSION['telefone']) ? $_SESSION['telefone'] : '' ?>" placeholder="Informe o telefone de contato" oninput="handlePhone(event)" required>
                          </div>
                        </div>
                        <div class="row mt-2">
                          <div class="col">
                            <label class="form-text mb-2">Estado</label>
                            <select class="form-select" id="particular-estado" name="estado">
                              <option value="">Selecione um estado...</option>
                            </select>
                          </div>
                          <div class="col">
                            <label class="form-text mb-2">Cidade</label>
                            <select class="form-select" id="particular-cidade" name="cidade">
                              <option value="">Selecione um município...</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-flex align-items-center">
                      <div class="radio-indicator"></div>
                    </div>
                  </label>
                </div>

                <!-- Loja -->
                <div class="vendedor-card">
                  <input type="radio" name="tipo_vendedor" value="pj" id="vendedor-pj" class="vendedor-radio">
                  <label for="vendedor-pj" class="vendedor-option d-flex justify-content-between w-100 border py-2 px-3 rounded-3 cursor-pointer">
                    <div class="d-flex flex-column py-2 w-100">
                      <p class="h6 fw-semibold text-dark mb-1">Loja</p>
                      <p class="text-muted mb-0 small">Venda por concessionária ou revendedora autorizada</p>

                      <!-- Select da Loja (inicialmente escondido) -->
                      <div class="campos-loja mt-3 me-5" style="display: none;">
                        <div class="w-100">
                          <label for="loja-select" class="form-text mb-2">Selecionar Loja<sup>*</sup></label>
                          <select class="form-select" id="loja-select" name="loja_id" required>
                            <option value="">Selecione uma loja</option>
                            <?php foreach ($lojas as $loja): ?>
                              <option value="<?= $loja['id'] ?>"><?= htmlspecialchars($loja['nome']) ?> - <?= htmlspecialchars($loja['cidade']) ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="d-flex align-items-center">
                      <div class="radio-indicator"></div>
                    </div>
                  </label>
                </div>
              </div>
            </div>
            <div class="row d-flex justify-content-between align-items-center w-100 mt-5">
              <div class="col-auto">
                <a href="vender-condicao.php" class="btn text-muted"><i class="bi bi-caret-left"></i>&nbsp;Voltar</a>
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
  })

  $(document).ready(function() {
    // Esconde todos os campos inicialmente
    $('.campos-particular, .campos-loja').hide();
    
    // Quando um radio é selecionado
    $('.vendedor-radio').on('change', function() {
        // Remove required de todos os campos primeiro
        $('#email-input, #telefone-input, #loja-select').prop('required', false);
        
        // Esconde todos os campos
        $('.campos-particular, .campos-loja').slideUp(300);
        
        // Mostra os campos correspondentes ao tipo selecionado
        if ($(this).val() === 'pf') {
            $('.campos-particular').slideDown(300);
            $('#email-input, #telefone-input').prop('required', true);
        } else if ($(this).val() === 'pj') {
            $('.campos-loja').slideDown(300);
            $('#loja-select').prop('required', true);
        }
    });
    
    // Trigger change no carregamento se algum já estiver selecionado
    $('.vendedor-radio:checked').trigger('change');
});
</script>

<script>
// IBGE: popular selects de estado/municipio para o bloco Particular
$(function() {
  const ibgeEstadosUrl = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';

  function populateEstadosParticular() {
    $.getJSON(ibgeEstadosUrl, function(estados) {
      estados.sort((a,b) => a.nome.localeCompare(b.nome));
      const $est = $('#particular-estado');
      const $mun = $('#particular-cidade');
      $est.empty().append('<option value="">Selecione um estado...</option>');
      $mun.empty().append('<option value="">Selecione um município...</option>');
      estados.forEach(function(e) {
        const opt = $('<option/>').attr('value', e.sigla).attr('data-id', e.id).text(e.nome + ' (' + e.sigla + ')');
        $est.append(opt);
      });
    });
  }

  function loadMunicipiosForParticular() {
    const $est = $('#particular-estado');
    const $mun = $('#particular-cidade');
    const estadoId = $est.find('option:selected').data('id');
    if (!estadoId) { $mun.html('<option value="">Selecione um estado primeiro...</option>'); return; }
    const url = `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estadoId}/municipios`;
    $mun.html('<option>Carregando municípios...</option>');
    $.getJSON(url, function(list) {
      list.sort((a,b) => a.nome.localeCompare(b.nome));
      $mun.empty().append('<option value="">Selecione um município...</option>');
      list.forEach(function(m) { $mun.append($('<option/>').attr('value', m.nome).text(m.nome)); });
    });
  }

  $(document).on('change', '#particular-estado', loadMunicipiosForParticular);
  populateEstadosParticular();
});
</script>

</html>