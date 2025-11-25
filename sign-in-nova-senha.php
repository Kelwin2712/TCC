<?php 
session_start();

if (isset($_SESSION['id'])) {
  header('Location: index.php');
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
  <?php include 'estruturas/alert/alert.php' ?>
  <?php include 'estruturas/navbar/navbar-no-login.php' ?>
  <main style="min-height: calc(100vh - 56px);" class="d-flex align-items-center">
    <div class="container h-100">
      <div class="row h-100 g-0 d-flex align-items-center justify-content-center">
        <!-- PC -->
        <div class="col-xl-10 col d-none d-lg-block">
          <div class="card shadow border-0 h-100">
            <div class="card-body p-0 h-100">
              <div class="row h-100 g-0">
                <div class="col-6 px-5 py-4">
                  <div class="row w-100 h-100 d-flex align-items-center">
                    <form action="controladores/recuperacao-senha/atualizar-senha.php" method="POST" class="w-100 h-100 d-flex flex-column justify-content-between">
                      <div class="row mt-5">
                        <h3 class="mb-1 text-center">Defina uma nova senha</h3>
                        <p class="mb-5 text-secondary text-center">Escolha uma senha forte e segura</p>
                      </div>
                      <div class="mb-4">
                        <label for="senha-input" class="form-label mb-0">Nova senha</label>
                        <input type="password" class="form-control rounded-3 border-2" id="senha-input" name="senha" placeholder="Digite uma senha" required>
                      </div>
                      <div class="mb-1">
                        <label for="confirma-senha-input" class="form-label mb-0">Confirme a senha</label>
                        <input type="password" class="form-control rounded-3 border-2" id="confirma-senha-input" name="confirma_senha" placeholder="Confirme a senha" required>
                      </div>
                      <div class="mb-5 d-flex flex-column">
                        <small id="regra-senha-1" class="text-secondary"><i class="bi bi-dash-circle"></i> Senhas coincidem</small>
                        <small id="regra-senha-2" class="text-secondary"><i class="bi bi-dash-circle"></i> Mínimo 8 caracteres</small>
                        <small id="regra-senha-3" class="text-secondary"><i class="bi bi-dash-circle"></i> Pelo menos um número</small>
                        <small id="regra-senha-4" class="text-secondary"><i class="bi bi-dash-circle"></i> Pelo menos uma letra maiúscula</small>
                      </div>
                      <div class="row">
                        <div class="col">
                          <a href="sign-in.php" class="btn w-auto mb-3">
                            < Voltar</a>
                        </div>
                        <div class="col">
                          <button type="submit" class="btn btn-dark w-auto mb-3 float-end">Atualizar senha</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="col-6">
                  <div id="images" class="carousel slide h-100" data-bs-ride="carousel">
                    <div class="carousel-inner h-100">
                      <div class="carousel-item h-100 active" data-bs-interval="5000">
                        <img src="img/login/1.jpg" class="d-block w-100 rounded-end-1 h-100" alt="g63">
                      </div>
                      <div class="carousel-item h-100" data-bs-interval="5000">
                        <img src="img/login/2.jpg" class="d-block w-100 rounded-end-1 h-100" alt="revuelto">
                      </div>
                      <div class="carousel-item h-100" data-bs-interval="5000">
                        <img src="img/login/3.jpg" class="d-block w-100 rounded-end-1 h-100" alt="cullinan">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Mobile -->
        <div class="col-12 d-lg-none d-block">
          <div class="row w-100">
            <form action="controladores/recuperacao-senha/atualizar-senha.php" method="POST">
              <h3 class="mb-1 text-center">Defina uma nova senha</h3>
              <p class="mb-5 text-secondary text-center">Escolha uma senha forte e segura</p>
              <div class="mb-4">
                <label for="mobile-senha-input" class="form-label mb-0">Nova senha</label>
                <input type="password" class="form-control rounded-3 border-2" id="mobile-senha-input" name="senha" placeholder="Digite uma senha" required>
              </div>
              <div class="mb-1">
                <label for="mobile-confirma-senha-input" class="form-label mb-0">Confirme a senha</label>
                <input type="password" class="form-control rounded-3 border-2" id="mobile-confirma-senha-input" name="confirma_senha" placeholder="Confirme a senha" required>
              </div>
              <div class="mb-5 d-flex flex-column">
                <small id="mobile-regra-senha-1" class="text-secondary"><i class="bi bi-dash-circle"></i> Senhas coincidem</small>
                <small id="mobile-regra-senha-2" class="text-secondary"><i class="bi bi-dash-circle"></i> Mínimo 8 caracteres</small>
                <small id="mobile-regra-senha-3" class="text-secondary"><i class="bi bi-dash-circle"></i> Pelo menos um número</small>
                <small id="mobile-regra-senha-4" class="text-secondary"><i class="bi bi-dash-circle"></i> Pelo menos uma letra maiúscula</small>
              </div>
              <div class="row">
                <div class="col">
                  <a href="sign-in.php" class="btn w-auto mb-3">
                    < Voltar</a>
                </div>
                <div class="col">
                  <button type="submit" class="btn btn-dark w-auto mb-3 float-end">Atualizar senha</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
  <?php include 'estruturas/footer/footer.php' ?>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script>
  // PC
  const senhaInput = document.getElementById('senha-input');
  const confirmarSenhaInput = document.getElementById('confirma-senha-input');
  const regraSenha1 = document.getElementById('regra-senha-1');
  const regraSenha2 = document.getElementById('regra-senha-2');
  const regraSenha3 = document.getElementById('regra-senha-3');
  const regraSenha4 = document.getElementById('regra-senha-4');
  const formPC = document.querySelector('.col-6 form');

  // Mobile
  const mobileSenhaInput = document.getElementById('mobile-senha-input');
  const mobileConfirmarSenhaInput = document.getElementById('mobile-confirma-senha-input');
  const mobileRegraSenha1 = document.getElementById('mobile-regra-senha-1');
  const mobileRegraSenha2 = document.getElementById('mobile-regra-senha-2');
  const mobileRegraSenha3 = document.getElementById('mobile-regra-senha-3');
  const mobileRegraSenha4 = document.getElementById('mobile-regra-senha-4');
  const formMobile = document.querySelector('.col-12.d-lg-none form');

  function validarSenha(senhaEl, confirmarEl, regra1, regra2, regra3, regra4, submit = false) {
    const senha = senhaEl.value;
    const confirmarSenha = confirmarEl.value;

    let valido = true;
    let senha1Ok = false;
    let senha2Ok = false;
    let senha3Ok = false;
    let senha4Ok = false;

    if (senha === confirmarSenha && senha !== '') {
      senha1Ok = true;
      regra1.className = 'text-success';
      regra1.innerHTML = '<i class="bi bi-check-circle"></i> Senhas coincidem';
    } else {
      senha1Ok = false;
      if (submit) {
        regra1.className = 'text-danger';
        regra1.innerHTML = '<i class="bi bi-exclamation-circle"></i> Senhas coincidem';
      } else {
        regra1.className = 'text-secondary';
        regra1.innerHTML = '<i class="bi bi-dash-circle"></i> Senhas coincidem';
      }
      valido = false;
    }

    if (senha.length >= 8) {
      senha2Ok = true;
      regra2.className = 'text-success';
      regra2.innerHTML = '<i class="bi bi-check-circle"></i> Mínimo 8 caracteres';
    } else {
      senha2Ok = false;
      if (submit) {
        regra2.className = 'text-danger';
        regra2.innerHTML = '<i class="bi bi-exclamation-circle"></i> Mínimo 8 caracteres';
      } else {
        regra2.className = 'text-secondary';
        regra2.innerHTML = '<i class="bi bi-dash-circle"></i> Mínimo 8 caracteres';
      }
      valido = false;
    }

    if (/\d/.test(senha)) {
      senha3Ok = true;
      regra3.className = 'text-success';
      regra3.innerHTML = '<i class="bi bi-check-circle"></i> Pelo menos um número';
    } else {
      senha3Ok = false;
      if (submit) {
        regra3.className = 'text-danger';
        regra3.innerHTML = '<i class="bi bi-exclamation-circle"></i> Pelo menos um número';
      } else {
        regra3.className = 'text-secondary';
        regra3.innerHTML = '<i class="bi bi-dash-circle"></i> Pelo menos um número';
      }
      valido = false;
    }

    if (/[A-Z]/.test(senha)) {
      senha4Ok = true;
      regra4.className = 'text-success';
      regra4.innerHTML = '<i class="bi bi-check-circle"></i> Pelo menos uma letra maiúscula';
    } else {
      senha4Ok = false;
      if (submit) {
        regra4.className = 'text-danger';
        regra4.innerHTML = '<i class="bi bi-exclamation-circle"></i> Pelo menos uma letra maiúscula';
      } else {
        regra4.className = 'text-secondary';
        regra4.innerHTML = '<i class="bi bi-dash-circle"></i> Pelo menos uma letra maiúscula';
      }
      valido = false;
    }

    if (senha1Ok) {
      confirmarEl.classList.remove('is-invalid');
      confirmarEl.classList.add('is-valid');
    } else {
      confirmarEl.classList.remove('is-valid');
      if (submit || confirmarSenha !== '') confirmarEl.classList.add('is-invalid');
    }

    if (senha2Ok && senha3Ok && senha4Ok) {
      senhaEl.classList.remove('is-invalid');
      senhaEl.classList.add('is-valid');
    } else {
      senhaEl.classList.remove('is-valid');
      if (submit || senha !== '') senhaEl.classList.add('is-invalid');
    }

    return valido;
  }

  if (formPC) {
    formPC.addEventListener('submit', function(e) {
      if (!validarSenha(senhaInput, confirmarSenhaInput, regraSenha1, regraSenha2, regraSenha3, regraSenha4, true)) {
        e.preventDefault();
      }
    });
    senhaInput.addEventListener('input', () => validarSenha(senhaInput, confirmarSenhaInput, regraSenha1, regraSenha2, regraSenha3, regraSenha4, false));
    confirmarSenhaInput.addEventListener('input', () => validarSenha(senhaInput, confirmarSenhaInput, regraSenha1, regraSenha2, regraSenha3, regraSenha4, false));
  }

  if (formMobile) {
    formMobile.addEventListener('submit', function(e) {
      if (!validarSenha(mobileSenhaInput, mobileConfirmarSenhaInput, mobileRegraSenha1, mobileRegraSenha2, mobileRegraSenha3, mobileRegraSenha4, true)) {
        e.preventDefault();
      }
    });
    mobileSenhaInput.addEventListener('input', () => validarSenha(mobileSenhaInput, mobileConfirmarSenhaInput, mobileRegraSenha1, mobileRegraSenha2, mobileRegraSenha3, mobileRegraSenha4, false));
    mobileConfirmarSenhaInput.addEventListener('input', () => validarSenha(mobileSenhaInput, mobileConfirmarSenhaInput, mobileRegraSenha1, mobileRegraSenha2, mobileRegraSenha3, mobileRegraSenha4, false));
  }
</script>
<script src="script.js"></script>
</html>
