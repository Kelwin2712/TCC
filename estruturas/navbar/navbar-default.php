<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm <?php if ($sticky == false) {echo 'sticky-top';}?>" data-bs-theme="light">
    <div class="container-fluid px-lg-5">
      <a class="navbar-brand" href="index.php">
        <img src="./img/logo-fahren.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top" style="filter: invert(1);">
        Fahren
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto me-4 mb-3 mb-lg-0">
          <?php include 'options.php'?>
        </ul>
        <div class="d-flex gap-2">
          <div class="vr"></div>
          <?php 
              if (isset($_SESSION['nome'])) {
                echo "<div class=\"dropdown\">
                  <button class=\"btn dropdown-toggle\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\"><i class=\"bi bi-person-fill me-1\"></i>"
                  .  $_SESSION['nome'] .
                  "</button>
                  <ul class=\"dropdown-menu\">
                    <li><a class=\"dropdown-item\" href=\"#\">Perfil</a></li>
                    <li><a class=\"dropdown-item\" href=\"#\">Configurações</a></li>
                    <li><a class=\"dropdown-item\" href=\"#\">Sair</a></li>
                  </ul>
                </div>";
              } else {
          echo '<form action="sign-in.php" class="ms-4">
            <button class="btn d-flex align-items-center gap-2" type="submit">
              <i class="bi bi-person-fill"></i>
              <span></span>
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