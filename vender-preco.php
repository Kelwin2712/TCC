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
          <form method="post" action="vender-fotos.php" class="card-body p-5 d-flex flex-column justify-content-center align-items-center">
            <h3 class="mb-4 fw-bold">Preço e negociação</h3>
            <div class="row row-cols-1 row-cols-md-2 w-100 g-4">
              <div class="col">
                <label for="preco-input" class="form-text mb-2">Preço<sup>*</sup></label>
                <div class="position-relative">
                  <span class="position-absolute translate-middle-y top-50" style="margin-left: .75rem;">R$</span>
                  <input type="text" class="form-control preco-input" style="padding-left: 2.25rem" id="preco-input" value="0" name="preco" placeholder="Informe o preço do veículo" required>
                </div>
              </div>
              <div class="col">
                <label for="troca-select" class="form-text mb-2">Aceita troca<sup>*</sup></label>
                <select class="form-select shadow-sm" id="troca-select" aria-label="Default select example" name="troca" required>
                  <option value="0" selected>Não</option>
                  <option value="1">Sim</option>
                </select>
              </div>
              <div class="col">
                <label for="email-input" class="form-text mb-2">Email de contato<sup>*</sup></label>
                <input type="email" class="form-control" id="email-input" name="email" placeholder="Informe o email de contato" required>
              </div>
              <div class="col">
                <label for="telefone-input" class="form-text mb-2">Telefone de contato<sup>*</sup></label>
                <input type="text" class="form-control" id="telefone-input" name="telefone" maxlength="15" minlength="14" placeholder="Informe o telefone de contato" oninput="handlePhone(event)" required>
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
</script>

</html>