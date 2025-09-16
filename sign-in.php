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
                <div class="col-6 px-5 py-4 d-flex align-items-center">
                  <div class="row w-100">
                    <form action="controladores/login.php" method="post">
                      <h3 class="mb-1 text-center">Login</h3>
                      <p class="mb-5 text-secondary text-center">Utilize o seu email para ter acesso</p>
                      <div class="mb-3">
                        <label for="email-input" class="form-label mb-0">Email</label>
                        <input type="email" class="form-control rounded-3 border-2" id="email-input" name="email" placeholder="Email" required <?php if (isset(
    $_SESSION['email'])) {echo 'value="'.$_SESSION['email'].'"'; unset($_SESSION['email']);}?>>
                      </div>
                      <div class="mb-1">
                        <label for="password-input" class="form-label mb-0">Senha</label>
                        <div class="input-group rounded-3">
                          <input type="password" class="form-control border-2 border-end-0" oninput="showToggleSenha(this, 'password-button')" id="password-input" placeholder="Senha" name="senha" required>
                          <div class="input-group-text border-2 border-start-0 bg-transparent p-0" id="basic-addon1"><button id="password-button" type="button" class="btn px-3 opacity-0" onclick="toggleSenha(this, 'password-input')" disabled><i class="bi bi-eye-slash"></i></button></div>
                        </div>
                      </div>
                      <p class="text-end mb-5"><a href="sign-in-esq-senha.php" class="link-dark link-opacity-75 link-underline-opacity-0 link-underline-opacity-75-hover link-opacity-100-hover">Esqueceu a senha?</a></p>
                      <button type="submit" class="btn btn-dark w-100 mb-3">Entrar</button>
                      <p class="text-center">Não tem uma conta? <a href="sign-up.php" class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover fw-semibold mb-5">Criar</a></p>
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
        <div class="col-12 d-block d-lg-none">
          <div class="row w-100">
            <form action="">
              <h3 class="mb-1 text-center">Login</h3>
              <p class="mb-5 text-secondary text-center">Utilize o seu email para ter acesso</p>
              <div class="mb-3">
                <label for="email-input" class="form-label mb-0">Email</label>
                <input type="email" class="form-control rounded-3 border-2" id="mobile-email-input" placeholder="Email" required>
              </div>
              <div class="mb-1">
                <label for="password-input" class="form-label mb-0">Senha</label>
                <input type="password" class="form-control rounded-3 border-2" id="mobile-password-input" placeholder="Senha" required>
              </div>
              <p class="text-end mb-5"><a href="sign-in-esq-senha.php" class="link-dark link-opacity-75 link-underline-opacity-0 link-underline-opacity-75-hover link-opacity-100-hover">Esqueceu a senha?</a></p>
              <button type="submit" class="btn btn-dark w-100 mb-3">Entrar</button>
              <p class="text-center">Não tem uma conta? <a href="sign-up.php" class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover fw-semibold mb-5">Criar</a></p>
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
<script src="script.js"></script>
</html>