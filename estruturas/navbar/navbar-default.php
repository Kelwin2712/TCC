<header class="sticky-top">
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
        <ul class="navbar-nav ms-auto me-4 mb-3 mb-lg-0">
          <?php include 'config/options.php' ?>
        </ul>
        <div class="d-flex gap-2">
          <div class="vr"></div>
          <?php
          if (isset($_SESSION['nome'])) {
            include 'config/login.php';
          } else {
            include 'config/no-login.php';
          }
          ?>
        </div>
      </div>
    </div>
  </nav>
</header>