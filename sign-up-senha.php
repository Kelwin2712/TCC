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
  <?php include 'estruturas/navbar/navbar-no-login.php'?>
  <main style="min-height: calc(100vh - 56px);" class="d-flex align-items-center">
    <div class="container h-100">
      <div class="row h-100 g-0 d-flex align-items-center justify-content-center">
        <div class="col-xl-10 col d-none d-lg-block">
          <div class="card shadow border-0 h-100">
            <div class="card-body p-0 h-100">
              <div class="row h-100 g-0">
                <div class="col-6">
                  <div id="images" class="carousel slide h-100" data-bs-ride="carousel">
                    <div class="carousel-inner h-100">
                      <div class="carousel-item h-100 active" data-bs-interval="5000">
                        <img src="img/login/1.jpg" class="d-block w-100 rounded-start-1 h-100" alt="g63">
                      </div>
                      <div class="carousel-item h-100" data-bs-interval="5000">
                        <img src="img/login/2.jpg" class="d-block w-100 rounded-start-1 h-100" alt="revuelto">
                      </div>
                      <div class="carousel-item h-100" data-bs-interval="5000">
                        <img src="img/login/3.jpg" class="d-block w-100 rounded-start-1 h-100" alt="cullinan">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6 px-5 py-4 d-flex align-items-center">
                  <div class="row w-100">
                    <form id="form-senha" action="sign-in.php">
                      <h3 class="mb-5 text-center">Defina sua senha</h3>
                      <div class="mb-3">
                        <label for="senha-input" class="form-label mb-0">Senha</label>
                        <input type="password" class="form-control rounded-3 border-2" id="senha-input" placeholder="Senha" required>
                      </div>
                      <div class="mb-1">
                        <label for="confirmar-senha-input" class="form-label mb-0">Confirmar senha</label>
                        <input type="password" class="form-control rounded-3 border-2" id="confirmar-senha-input" placeholder="Confirmar senha" required>
                      </div>
                      <div class="mb-4 d-flex flex-column">
                        <small id="regra-senha-1" class="text-secondary"><i class="bi bi-dash-circle"></i> Senhas coincidem</small>
                        <small id="regra-senha-2" class="text-secondary"><i class="bi bi-dash-circle"></i> Mínimo 8 caracteres</small>
                        <small id="regra-senha-3" class="text-secondary"><i class="bi bi-dash-circle"></i> Pelo menos um número</small>
                        <small id="regra-senha-4" class="text-secondary"><i class="bi bi-dash-circle"></i> Pelo menos uma letra maiúscula</small>
                      </div>
                      <button type="submit" class="btn btn-dark w-100 mb-3">Definir senha</button>
                      <p class="text-center"><a href="sign-up.php" class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover fw-semibold mb-5">Voltar</a></p>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 d-block d-lg-none">
          <div class="row w-100">
            <form id="form-senha" action="sign-in.php">
              <h3 class="mb-5 text-center">Defina sua senha</h3>
              <div class="mb-3">
                <label for="senha-input" class="form-label mb-0">Senha</label>
                <input type="password" class="form-control rounded-3 border-2" id="senha-input" placeholder="Senha" required>
              </div>
              <div class="mb-1">
                <label for="confirmar-senha-input" class="form-label mb-0">Confirmar senha</label>
                <input type="password" class="form-control rounded-3 border-2" id="confirmar-senha-input" placeholder="Confirmar senha" required>
              </div>  
      </div>
    </div>
  </main>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script>
  const senhaInput = document.getElementById('senha-input');
const confirmarSenhaInput = document.getElementById('confirmar-senha-input');
const regraSenha1 = document.getElementById('regra-senha-1');
const regraSenha2 = document.getElementById('regra-senha-2');
const regraSenha3 = document.getElementById('regra-senha-3');
const regraSenha4 = document.getElementById('regra-senha-4');
const form = document.getElementById('form-senha');

function validar(submit = false) {
  const senha = senhaInput.value;
  const confirmarSenha = confirmarSenhaInput.value;

  let valido = true;
  let senha1Ok = false;
  let senha2Ok = false;
  let senha3Ok = false;
  let senha4Ok = false;

  if (senha === confirmarSenha && senha !== '') {
    senha1Ok = true;
    regraSenha1.className = 'text-success';
    regraSenha1.innerHTML = '<i class="bi bi-check-circle"></i> Senhas coincidem';
  } else {
    senha1Ok = false;
    if (submit) {
      regraSenha1.className = 'text-danger';
      regraSenha1.innerHTML = '<i class="bi bi-exclamation-circle"></i> Senhas coincidem';
    } else {
      regraSenha1.className = 'text-secondary';
      regraSenha1.innerHTML = '<i class="bi bi-dash-circle"></i> Senhas coincidem';
    }
    valido = false;
  }

  if (senha.length >= 8) {
    senha2Ok = true;
    regraSenha2.className = 'text-success';
    regraSenha2.innerHTML = '<i class="bi bi-check-circle"></i> Mínimo 8 caracteres';
  } else {
    senha2Ok = false;
    if (submit) {
      regraSenha2.className = 'text-danger';
      regraSenha2.innerHTML = '<i class="bi bi-exclamation-circle"></i> Mínimo 8 caracteres';
    } else {
      regraSenha2.className = 'text-secondary';
      regraSenha2.innerHTML = '<i class="bi bi-dash-circle"></i> Mínimo 8 caracteres';
    }
    valido = false;
  }

  if (/\d/.test(senha)) {
    senha3Ok = true;
    regraSenha3.className = 'text-success';
    regraSenha3.innerHTML = '<i class="bi bi-check-circle"></i> Pelo menos um número';
  } else {
    senha3Ok = false;
    if (submit) {
      regraSenha3.className = 'text-danger';
      regraSenha3.innerHTML = '<i class="bi bi-exclamation-circle"></i> Pelo menos um número';
    } else {
      regraSenha3.className = 'text-secondary';
      regraSenha3.innerHTML = '<i class="bi bi-dash-circle"></i> Pelo menos um número';
    }
    valido = false;
  }

  if (/[A-Z]/.test(senha)) {
    senha4Ok = true;
    regraSenha4.className = 'text-success';
    regraSenha4.innerHTML = '<i class="bi bi-check-circle"></i> Pelo menos uma letra maiúscula';
  } else {
    senha4Ok = false;
    if (submit) {
      regraSenha4.className = 'text-danger';
      regraSenha4.innerHTML = '<i class="bi bi-exclamation-circle"></i> Pelo menos uma letra maiúscula';
    } else {
      regraSenha4.className = 'text-secondary';
      regraSenha4.innerHTML = '<i class="bi bi-dash-circle"></i> Pelo menos uma letra maiúscula';
    }
    valido = false;
  }

  if (senha1Ok) {
    confirmarSenhaInput.classList.remove('is-invalid');
    confirmarSenhaInput.classList.add('is-valid');
  } else {
    confirmarSenhaInput.classList.remove('is-valid');
    if (submit || confirmarSenha !== '') confirmarSenhaInput.classList.add('is-invalid');
  }

  if (senha2Ok && senha3Ok && senha4Ok) {
    senhaInput.classList.remove('is-invalid');
    senhaInput.classList.add('is-valid');
  } else {
    senhaInput.classList.remove('is-valid');
    if (submit || senha !== '') senhaInput.classList.add('is-invalid');
  }

  return valido;
}

form.addEventListener('submit', function(e) {
  if (!validar(true)) {
    e.preventDefault();
  } else {
    form.reset();
  }
});
senhaInput.addEventListener('input', () => validar(false));
confirmarSenhaInput.addEventListener('input', () => validar(false));

</script>
<script src="script.js"></script>

</html>