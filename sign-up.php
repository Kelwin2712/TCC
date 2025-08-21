<?php session_start(); ?>
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
  <?php include 'estruturas/navbar/navbar-no-login.php' ?>
  <main style="min-height: calc(100vh - 56px);" class="d-flex align-items-center">
    <div class="container h-100">
      <div class="row h-100 g-0 d-flex align-items-center justify-content-center">
        <!-- PC -->
        <div class="col-xl-10 col d-none d-lg-block">
          <div class="card shadow border-0 h-100">
            <div class="card-body p-0 h-100">
              <div class="row h-100 g-0">
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
                <div class="col-6 px-5 py-4 d-flex align-items-center">
                  <div class="row w-100">
                    <form action="sign-up-senha.php" method="post">
                      <h3 class="mb-1 text-center">Cadastre-se</h3>
                      <p class="mb-5 text-secondary text-center">Utilize o seu email para ter acesso</p>
                      <div class="mb-3">
                        <label for="email-input" class="form-label mb-0">Nome completo</label>
                        <input type="text" class="form-control rounded-3 border-2" id="nome-input" name="nome" placeholder="Nome completo" required>
                      </div>
                      <div class="mb-5">
                        <label for="email-input" class="form-label mb-0">Email</label>
                        <input type="email" class="form-control rounded-3 border-2" id="email-input" name="email" placeholder="Email" required>
                      </div>
                      <button type="submit" class="btn btn-dark w-100 mb-3">Cadastrar-se</button>
                      <p class="text-center">Já tem uma conta? <a href="sign-in.php" class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover fw-semibold mb-5">Entrar</a></p>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Mobile -->
        <div class="col-12 d-lg-none d-block">
          <div class="row w-100">
            <form action="sign-up-senha.php">
              <h3 class="mb-1 text-center">Cadastre-se</h3>
              <p class="mb-5 text-secondary text-center">Utilize o seu email para ter acesso</p>
              <div class="mb-3">
                <label for="email-input" class="form-label mb-0">Nome completo</label>
                <input type="text" class="form-control rounded-3 border-2" id="nome-input" placeholder="Nome completo" required>
              </div>
              <div class="mb-5">
                <label for="email-input" class="form-label mb-0">Email</label>
                <input type="email" class="form-control rounded-3 border-2" id="email-input" placeholder="Email" required>
              </div>
              <button type="submit" class="btn btn-dark w-100 mb-3">Cadastrar-se</button>
              <p class="text-center">Já tem uma conta? <a href="sign-in.php" class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover fw-semibold mb-5">Entrar</a></p>
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