<header>
  <nav class="navbar navbar-expand-lg shadow-sm py-0" data-bs-theme="light" style="background-color: #31451dff;">

    <div class="d-flex flex-column w-100">
      <div class="">
        <div class="container-xl d-flex align-items-center py-2">
          <a class="navbar-brand d-flex align-items-center me-4 me-sm-5 text-light" href="index.php">
            <img src="./img/logo-fahren.png" alt="Logo" width="20" height="25"
              class="d-inline-block align-text-center">
            <span class="ms-2 fw-semibold">Fahren</span>
          </a>
          <div class="col ms-0 ms-lg-5 me-4 me-lg-0">
            <div class="input-group">
              <div class="input-group-text bg-white rounded-start-4">
                <i class="bi bi-search text-dark"></i>
              </div>
              <input type="search" class="form-control ps-0 pe-4 border-start-0 rounded-end-4"
                placeholder="Buscar modelo ou marca" aria-label="Search">
            </div>
          </div>
          <button class="navbar-toggler rounded-4 border-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon border-light" style="filter: invert(1);"></span>
          </button>
        </div>
      </div>
      <div class="bg-dark">
        <div class="container-xl d-flex justify-content-between align-items-center pb-1">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-lg-0">
            <?php include 'config/options-2.php' ?>
          </ul>
        </div>

        <div class="d-flex gap-2 ms-auto align-items-center">
          <?php
          if (isset($_SESSION['nome'])) {
            include 'config/login-2.php';
          } else {
            include 'config/no-login-2.php';
          }
          ?>
        </div>
      </div>
      </div>
    </div>

  </nav>
</header>