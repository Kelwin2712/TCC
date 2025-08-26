<nav class="navbar navbar-expand-lg bg-body shadow-sm" data-bs-theme="light">
    <div class="container-fluid px-lg-5">
      <a class="navbar-brand" href="index.php">
        <img src="./img/logo-fahren.png" alt="Logo" width="15" height="20" class="d-inline-block align-text-center" style="filter: invert(1);">
        Fahren
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto me-3 mb-3 mb-lg-0">
          <?php include 'options.php'?>
        </ul>
        <div class="col-auto">
          
        <div class="input-group">
          <div class="input-group-text bg-white rounded-start-4 border-dark"><i class="bi bi-search text-dark"></i></div>
          <input type="search" class="form-control ps-0 pe-4 border-start-0 rounded-end-4 border-dark" placeholder="Buscar modelo ou marca" aria-label="Search">
        </div>
        </div>
        <div class="d-flex gap-2">
          <div class="vr ms-4"></div>
          <?php 
              if (isset($_SESSION['nome'])) {
                echo "<div class=\"nav-item dropdown\">
                  <button class=\"btn dropdown-toggle\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\"><i class=\"bi bi-person-fill me-1\"></i>"
                  .  $_SESSION['nome'] .
                  "</button>
                  <ul class=\"dropdown-menu\">
                    <li><a class=\"dropdown-item\" href=\"configuracoes.php\"><i class=\"bi bi-gear-fill me-2\"></i>Configurações</a></li>
                    <li><a class=\"dropdown-item\" href=\"funcoes_php/logout.php\"><i class=\"bi bi-door-open me-2\"></i>Sair</a></li>
                  </ul>
                </div>";
              } else {
          echo '<form action="sign-in.php" class="ms-4">
            <button class="btn d-flex align-items-center gap-2" type="submit">
              <i class="bi bi-person-fill"></i>
              <span>Entrar</span>
            </button>
          </form>';
              }
              ?>
          <form action="sign-up.php" class="<?php 
              if (isset($_SESSION['nome'])) {
                echo 'visually-hidden';
              }
              ?>">
            <button class="btn btn-dark d-flex align-items-center gap-2" type="submit">
              <i class="bi bi-person-fill-add"></i>
              <span>Cadastrar-se</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </nav>