<?php
session_start();
if (!isset($_SESSION['id'])) {
  header('Location: sign-in.php');
  exit();
}
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
          <form action="vender-infos.php" method="post" class="card-body p-5 d-flex flex-column justify-content-center align-items-center">
            <h3 class="mb-4 fw-bold">Informe a placa do veículo</h3>
            <div class="d-flex flex-column">
              <div class="position-relative mb-2">
                <img src="img/placa-veiculo.png" alt="" class="img-fluid" style="max-height: 60vh;">
                <div class="position-absolute start-50 translate-middle-x text-center" style="width: 80%; height: 60%; bottom: 10%;">
                  <input
                    type="text"
                    class="form-control w-100 h-100 bg-transparent text-center text-uppercase border-0"
                    name="placa"
                    id="placa"
                    pattern="[A-Z]{3}[0-9][A-Z][0-9]{2}"
                    title="Formato: 3 letras + 1 número + 1 letra + 2 números (ex: ABC1D23)"
                    placeholder="ABC1D23"
                    style="font-size: calc(300% + 1.25vw); line-height: 1; border: none !important; outline: none !important; box-shadow: none !important;background: transparent !important;" maxlength="7" required>
                </div>
              </div>
              <p>Não tem a placa do veículo no momento? Sem problemas, <a href="vender-infos.php" class="link-dark fw-semibold">clique aqui</a></p>
            </div>
            <div class="row d-flex justify-content-between align-items-center w-100 mt-4">
              <div class="col-auto">
                <a href="." class="btn text-muted"><i class="bi bi-caret-left"></i>&nbsp;Cancelar</a>
              </div>
              <div class="col-auto">
                <button type="submit" class="btn btn-dark shadow-sm" disabled>Próximo passo&nbsp;<i class="bi bi-caret-right-fill"></i></button>
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

  const proxBtn = $('button[type="submit"]');
  const placaInput = $('#placa');

  placaInput.on('input', function() {
    formatarPlacaRobusto($(this));

    if ($(this).val().length === 7) {
      proxBtn.prop('disabled', false);
    } else {
      proxBtn.prop('disabled', true);
    }
  });
</script>

</html>