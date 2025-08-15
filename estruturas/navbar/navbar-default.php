<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm sticky-top" data-bs-theme="light">
    <div class="container-fluid px-lg-5">
      <a class="navbar-brand" href="index.php">
        <img src="./img/logo-fahren.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top" style="filter: invert(1);">
        Fahren
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto me-auto mb-2 mb-lg-0">
          <?php include 'options.php'?>
        </ul>
        <div class="d-flex gap-2">
          <form action="sign-in.php">
            <button class="btn btn-dark d-flex align-items-center gap-2" type="submit">
              <i class="bi bi-person-fill"></i>
              <span>Entrar</span>
            </button>
          </form>
          <form action="sign-up.php">
            <button class="btn btn-outline-dark d-flex align-items-center gap-2" type="submit">
              <i class="bi bi-person-fill-add"></i>
              <span>Cadastrar-se</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </nav>