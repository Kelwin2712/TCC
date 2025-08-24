<?php
session_start();
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
<style>
  .card-footer .btn {
    position: relative;
    z-index: 2;
  }
</style>

<body>
  <?php include 'estruturas/navbar/navbar-default.php' ?>
  <main class="bg-body-tertiary fs-nav">
    <div id="banner-carousel" class="carousel carousel-fade" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="./img/banner/carousel-1.png" class="d-block w-100 c-img">
        </div>
        <div class="carousel-item">
          <img src="./img/banner/carousel-2.png" class="d-block w-100 c-img">
        </div>
        <div class="carousel-item">
          <img src="./img/banner/carousel-3.png" class="d-block w-100 c-img">
        </div>
      </div>
      <button class="carousel-control-prev" style="width: 10%;" type="button" data-bs-target="#banner-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" style="width: 10%;" type="button" data-bs-target="#banner-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
    <div class="container py-4">
      <div class="card rounded-5 border-0 shadow-sm">
        <div class="card-body px-4 py-2">
          <div class="row bg-body-tertiary shadow-sm rounded-top-4 rounded-bottom-3 px-1 py-2 align-items-center d-flex">
            <div class="col">
              <span><i class="bi bi-stars me-2"></i>Recomendação: Lamborghini Aventador SVJ </span>
            </div>
            <div class="col-auto ">
              <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
          </div>
          <div class="row">
            <input type="text" class="form-control border-0 my-3" placeholder="Encontre o modelo que você procura...">
          </div>
          <div class="row g-2">
            <div class="col-auto">
              <div class="input-group">
                <div class="input-group-text pe-0 bg-transparent rounded-start-5">
                  <i class="bi bi-buildings"></i>
                </div>
                <select id="marca-select" class="form-select border-start-0 rounded-end-5 shadow-sm">
                  <option value="">Marca</option>
                  <option value="0">Alfa Romeo</option>
                  <option value="0">Audi</option>
                  <option value="0">Bentley</option>
                  <option value="0">BMW</option>
                  <option value="0">Cadillac</option>
                  <option value="0">Chevrolet</option>
                  <option value="0">Dodge</option>
                  <option value="0">Ferrari</option>
                  <option value="0">Fiat</option>
                  <option value="0">Ford</option>
                  <option value="0">GMC</option>
                  <option value="0">GWM</option>
                  <option value="0">Haval</option>
                  <option value="0">Honda</option>
                  <option value="0">Hummer</option>
                  <option value="0">Hyundai</option>
                  <option value="0">Infiniti</option>
                </select>
              </div>
            </div>
            <div class="col-2">
              <div class="input-group">
                <div class="input-group-text pe-0 bg-transparent border-end-0 rounded-start-5">
                  <i class="bi bi-car-front"></i>
                </div>
                <select id="modelo-select" class="form-select bg-transparent border-start-0 rounded-end-5 shadow-sm" disabled>
                  <option value="" selected>Modelo</option>
                  <option value="0">Audi R8 Spyder</option>
                  <option value="1">Ferrari 488</option>
                  <option value="2">Porsche Macan</option>
                  <option value="3">Mercedes-Benz AMG GT63</option>
                  <option value="4">Lamborghini Gallardo</option>
                  <option value="5">BMW X7</option>
                </select>
              </div>
            </div>
            <div class="col-auto">
              <div class="input-group">
                <div class="input-group-text pe-0 bg-transparent rounded-start-5">
                  <i class="bi bi-pin-map"></i>
                </div>
                <select class="form-select border-start-0 rounded-end-5 shadow-sm">
                  <option value="">Estado</option>
                  <option value="1">Acre</option>
                  <option value="2">Alagoas</option>
                  <option value="3">Amapá</option>
                  <option value="4">Amazonas</option>
                  <option value="5">Bahia</option>
                  <option value="6">Ceará</option>
                  <option value="7">Distrito Federal</option>
                  <option value="8">Espírito Santo</option>
                  <option value="9">Goiás</option>
                  <option value="10">Maranhão</option>
                  <option value="11">Mato Grosso</option>
                  <option value="12">Mato Grosso do Sul</option>
                  <option value="13">Minas Gerais</option>
                  <option value="14">Pará</option>
                  <option value="15">Paraíba</option>
                  <option value="16">Paraná</option>
                  <option value="17">Pernambuco</option>
                  <option value="18">Piauí</option>
                  <option value="19">Rio de Janeiro</option>
                  <option value="20">Rio Grande do Norte</option>
                  <option value="21">Rio Grande do Sul</option>
                  <option value="22">Rondônia</option>
                  <option value="23">Roraima</option>
                  <option value="24">Santa Catarina</option>
                  <option value="25">São Paulo</option>
                  <option value="26">Sergipe</option>
                  <option value="27">Tocantins</option>
                </select>
              </div>
            </div>
            <div class="col-auto">
              <div class="input-group">
                <span class="input-group-text bg-transparent border rounded-start-5 shadow-sm">De</span>
                <input type="text" id="preco-de" class="form-control border border-start-0 ps-0" placeholder="R$--" aria-label="Preço mínimo" style="max-width: 100px;">
                <span class="input-group-text bg-transparent border">Até</span>
                <input type="text" id="preco-ate" class="form-control border border-start-0 ps-0 rounded-end-5 shadow-sm" placeholder="R$--" aria-label="Preço máximo" style="max-width: 100px;">
              </div>
            </div>
            <div class="col-auto ms-auto">
              <button class="btn btn-dark rounded-pill px-3"><i class="bi bi-search me-2"></i>Pesquisar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container py-4">
      <div class="row mb-4 align-items-center">
        <h4 class="col-8">Categorias</h4>
        <a class="col-4 link-secondary text-end link-underline link-underline-opacity-0 link-opacity-75 link-underline-opacity-100-hover link-opacity-100-hover" href="compras.php">
          (Ver todas as categorias)
        </a>
      </div>
      <div id="categorias-carousel" class="carousel carousel-dark multi-carousel multi-carousel-6 px-4">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <a href="compras.php" class="card-hover card">
              <div class="card-img-overlay d-flex flex-column justify-content-end text-white">
                <h5 class="card-title fw-bold">Elétricos</h5>
              </div>
              <img src="./img/categorias/categorias-1.png" class="card-img">
            </a>
          </div>
          <div class="carousel-item">
            <a href="compras.php" class="card-hover card">
              <div class="card-img-overlay d-flex flex-column justify-content-end text-white">
                <h5 class="card-title fw-bold">Sedans</h5>
              </div>
              <img src="./img/categorias/categorias-2.png" class="card-img">
            </a>
          </div>
          <div class="carousel-item">
            <a href="compras.php" class="card-hover card">
              <div class="card-img-overlay d-flex flex-column justify-content-end text-white">
                <h5 class="card-title fw-bold">Hatchbacks</h5>
              </div>
              <img src="./img/categorias/categorias-3.png" class="card-img">
            </a>
          </div>
          <div class="carousel-item">
            <a href="compras.php" class="card-hover card">
              <div class="card-img-overlay d-flex flex-column justify-content-end text-white">
                <h5 class="card-title fw-bold">Pickups</h5>
              </div>
              <img src="./img/categorias/categorias-4.png" class="card-img">
            </a>
          </div>
          <div class="carousel-item">
            <a href="compras.php" class="card-hover card">
              <div class="card-img-overlay d-flex flex-column justify-content-end text-white">
                <h5 class="card-title fw-bold">Coupés</h5>
              </div>
              <img src="./img/categorias/categorias-5.png" class="card-img">
            </a>
          </div>
          <div class="carousel-item">
            <a href="compras.php" class="card-hover card">
              <div class="card-img-overlay d-flex flex-column justify-content-end text-white">
                <h5 class="card-title fw-bold">Minivans</h5>
              </div>
              <img src="./img/categorias/categorias-6.png" class="card-img">
            </a>
          </div>
          <div class="carousel-item">
            <a href="compras.php" class="card-hover card">
              <div class="card-img-overlay d-flex flex-column justify-content-end text-white">
                <h5 class="card-title fw-bold">Híbridos</h5>
              </div>
              <img src="./img/categorias/categorias-7.png" class="card-img">
            </a>
          </div>
          <div class="carousel-item">
            <a href="compras.php" class="card-hover card">
              <div class="card-img-overlay d-flex flex-column justify-content-end text-white">
                <h5 class="card-title fw-bold">Supercarros</h5>
              </div>
              <img src="./img/categorias/categorias-8.png" class="card-img">
            </a>
          </div>
        </div>
        <button class="carousel-control-prev d-flex justify-content-start w-auto" type="button" data-bs-target="#categorias-carousel" id="categorias-prev" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next d-flex justify-content-end w-auto" type="button" data-bs-target="#categorias-carousel" id="categorias-next" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
    <div class="container py-4">
      <div class="row mb-4 align-items-center">
        <h4 class="col-8">Principais marcas</h4>
        <a class="col-4 link-secondary text-end link-underline link-underline-opacity-0 link-opacity-75 link-underline-opacity-100-hover link-opacity-100-hover" href="compras.php"">
          (Ver todas as marcas)
        </a>
      </div>
      <div class=" row g-3 align-items-stretch justify-content-between">
          <div class="col-4 col-md-3 col-lg-2 d-flex">
            <a href="compras.php"" class=" bg-body img-hover d-flex align-items-center justify-content-center p-3 border rounded w-100" style="height: 120px;">
              <img src="./img/marcas/toyota-logo.png" alt="Toyota" class="img-fluid" style="max-height: 60px; width: auto;">
            </a>
          </div>
          <div class="col-4 col-md-3 col-lg-2 d-flex">
            <a href="compras.php"" class=" bg-body img-hover d-flex align-items-center justify-content-center p-3 border rounded w-100" style="height: 120px;">
              <img src="./img/marcas/honda-logo.png" alt="Honda" class="img-fluid" style="max-height: 50px; width: auto;">
            </a>
          </div>
          <div class="col-4 col-md-3 col-lg-2 d-flex">
            <a href="compras.php"" class=" bg-body img-hover d-flex align-items-center justify-content-center p-3 border rounded w-100" style="height: 120px;">
              <img src="./img/marcas/kia-logo.png" alt="Kia" class="img-fluid" style="max-height: 55px; width: auto;">
            </a>
          </div>
          <div class="col-4 col-md-3 col-lg-2 d-flex">
            <a href="compras.php"" class=" bg-body img-hover d-flex align-items-center justify-content-center p-3 border rounded w-100" style="height: 120px;">
              <img src="./img/marcas/renault-logo.png" alt="Renault" class="img-fluid" style="max-height: 65px; width: auto;">
            </a>
          </div>
          <div class="col-4 col-md-3 col-lg-2 d-flex">
            <a href="compras.php"" class=" bg-body img-hover d-flex align-items-center justify-content-center p-3 border rounded w-100" style="height: 120px;">
              <img src="./img/marcas/chevrolet-logo.png" alt="Chevrolet" class="img-fluid" style="max-height: 45px; width: auto;">
            </a>
          </div>
          <div class="col-4 col-md-3 col-lg-2 d-flex">
            <a href="compras.php"" class=" bg-body img-hover d-flex align-items-center justify-content-center p-3 border rounded w-100" style="height: 120px;">
              <img src="./img/marcas/volkswagen-logo.png" alt="Volkswagen" class="img-fluid" style="max-height: 70px; width: auto;">
            </a>
          </div>
      </div>
    </div>
    <div class="container py-4">
      <div class="row mb-4 align-items-center">
        <h4 class="col-8">Carros mais procurados</h4>
        <a class="col-4 link-secondary text-end link-underline link-underline-opacity-0 link-opacity-75 link-underline-opacity-100-hover link-opacity-100-hover" href="compras.php"">
          (Ver todas as marcas)
        </a>
      </div>
      <div id=" populares-carousel" class="carousel carousel-dark multi-carousel multi-carousel-5 px-4">
          <div class="carousel-inner">
            <?php
            $nome = 'AUDI RS6';
            $info = '2.0 TFSI GASOLINA SPORTBACK PRESTIGE PLUS S TRONIC';
            $preco = 'R$ 100.000';
            $ano = '2020/2021';
            $km = '52.524';
            $loc = 'São Paulo - SP';
            $img = './img/carros/img-1.png';
            include 'estruturas/card-compra/card-index.php' ?>
            <?php
            $nome = 'AUDI A5';
            $info = '2.0 TFSI GASOLINA SPORTBACK PRESTIGE PLUS S TRONIC';
            $preco = 'R$ 100.000';
            $ano = '2020/2021';
            $km = '52.524';
            $loc = 'São Paulo - SP';
            $img = './img/carros/img-1.png';
            include 'estruturas/card-compra/card-index.php' ?>
            <?php
            $nome = 'AUDI A5';
            $info = '2.0 TFSI GASOLINA SPORTBACK PRESTIGE PLUS S TRONIC';
            $preco = 'R$ 100.000';
            $ano = '2020/2021';
            $km = '52.524';
            $loc = 'São Paulo - SP';
            $img = './img/carros/img-1.png';
            include 'estruturas/card-compra/card-index.php' ?>
            <?php
            $nome = 'AUDI A5';
            $info = '2.0 TFSI GASOLINA SPORTBACK PRESTIGE PLUS S TRONIC';
            $preco = 'R$ 100.000';
            $ano = '2020/2021';
            $km = '52.524';
            $loc = 'São Paulo - SP';
            $img = './img/carros/img-1.png';
            include 'estruturas/card-compra/card-index.php' ?>
            <?php
            $nome = 'AUDI A5';
            $info = '2.0 TFSI GASOLINA SPORTBACK PRESTIGE PLUS S TRONIC';
            $preco = 'R$ 100.000';
            $ano = '2020/2021';
            $km = '52.524';
            $loc = 'São Paulo - SP';
            $img = './img/carros/img-1.png';
            include 'estruturas/card-compra/card-index.php' ?>
            <?php
            $nome = 'AUDI A5';
            $info = '2.0 TFSI GASOLINA SPORTBACK PRESTIGE PLUS S TRONIC';
            $preco = 'R$ 100.000';
            $ano = '2020/2021';
            $km = '52.524';
            $loc = 'São Paulo - SP';
            $img = './img/carros/img-1.png';
            include 'estruturas/card-compra/card-index.php' ?>
            <?php
            $nome = 'AUDI A5';
            $info = '2.0 TFSI GASOLINA SPORTBACK PRESTIGE PLUS S TRONIC';
            $preco = 'R$ 100.000';
            $ano = '2020/2021';
            $km = '52.524';
            $loc = 'São Paulo - SP';
            $img = './img/carros/img-1.png';
            include 'estruturas/card-compra/card-index.php' ?>
            <?php
            $nome = 'AUDI A5';
            $info = '2.0 TFSI GASOLINA SPORTBACK PRESTIGE PLUS S TRONIC';
            $preco = 'R$ 100.000';
            $ano = '2020/2021';
            $km = '52.524';
            $loc = 'São Paulo - SP';
            $img = './img/carros/img-1.png';
            include 'estruturas/card-compra/card-index.php' ?>
          </div>
          <button class="carousel-control-prev d-flex justify-content-start w-auto" type="button" data-bs-target="#populares-carousel" id="populares-prev" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next d-flex justify-content-end w-auto" type="button" data-bs-target="#populares-carousel" id="populares-next" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
      </div>
    </div>
  </main>
  <?php include 'estruturas/footer/footer.php' ?>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="script.js"></script>
<script>
  $(function() {
    $(".favoritar").click(function() {
      $(this).find("i").toggleClass("bi-heart bi-heart-fill text-danger");
    });

    $("#marca-select").change(function() {
      var selectedMarca = $(this).val();
      if (selectedMarca) {
        $("#modelo-select").prop("disabled", false);
      } else {
        $("#modelo-select").prop("disabled", true);
        $("#modelo-select").val("");
      }
    });
  });
</script>

</html>