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
  <nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm sticky-top" data-bs-theme="light">
    <div class="container-xxl">
      <a class="navbar-brand" href="index.php">
        <img src="./img/logo-fahren.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top" style="filter: invert(1);">
        Fahren
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Comprar
            </a>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="#">
                  <i class="bi bi-car-front"></i>
                  Carros usados
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#">
                  <i class="bi bi-car-front-fill"></i>
                  Carros novos
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item" href="#">
                  <i class="bi bi-bicycle"></i>
                  Motos usadas
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#">
                  <i class="bi bi-bicycle"></i>
                  Motos novas
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Vender
            </a>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="#">
                  <i class="bi bi-car-front-fill"></i>
                  Vender carro
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#">
                  <i class="bi bi-bicycle"></i>
                  Vender moto
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <main style="min-height: calc(100vh - 56px);" class="d-flex align-items-center">
    <div class="container h-100">
      <div class="row h-100 g-0 d-flex align-items-center justify-content-center">
        <div class="col-xl-10 col d-none d-lg-block">
          <div class="card shadow border-0 h-100">
            <div class="card-body p-0 h-100">
              <div class="row h-100 g-0">
                <div class="col-6 px-5 py-4">
                  <div class="row w-100 h-100 d-flex align-items-center">
                    <form action="" class="w-100 h-100 d-flex flex-column justify-content-between">
                      <div class="row mt-5">
                        <h3 class="mb-1 text-center">Esqueceu sua senha?</h3>
                        <p class="mb-5 text-secondary text-center">Sem problemas, iremos enviar instruções para troca-la</p>
                      </div>
                      <div class="mb-5">
                        <label for="email-input" class="form-label mb-0">Email</label>
                        <input type="email" class="form-control rounded-3 border-2" id="email-input" placeholder="Email" required>
                      </div>
                      <div class="row">
                        <div class="col">
                          <a href="sign-in.php" class="btn w-auto mb-3">
                            < Voltar</a>
                        </div>
                        <div class="col">
                          <a href="sign-in-code-senha.php" class="btn btn-dark w-auto mb-3 float-end">Próximo ></a>
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
        <div class="col-12 d-lg-none d-block">
          <div class="row w-100">
            <form action="">
              <h3 class="mb-1 text-center">Esqueceu sua senha?</h3>
              <p class="mb-5 text-secondary text-center">Sem problemas, iremos enviar instruções para troca-la</p>
              <div class="mb-5">
                <label for="email-input" class="form-label mb-0">Email</label>
                <input type="email" class="form-control rounded-3 border-2" id="email-input" placeholder="Email" required>
              </div>
              <div class="row">
                <div class="col">
                  <a href="sign-in.php" class="btn w-auto mb-3">
                    < Voltar</a>
                </div>
                <div class="col">
                  <a href="sign-in-code-senha.php" class="btn btn-dark w-auto mb-3 float-end">Próximo ></a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="script.js"></script>

</html>