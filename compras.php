<?php session_start();
$filter_check_caminho = 'estruturas/filter/filter-checkbox.php';

$tipo = $_GET['tipo'] ?? 'carro';
$codicao = $_GET['codicao'] ?? 'usado';
$categoria = $_GET['categoria'] ?? null;
$vendedor = $_GET['vendedor'] ?? null;
$vendedor_img = $_GET['vendedor_img'] ?? null;
$vendedor_est = $_GET['vendedor_est'] ?? null;

$page = $_GET['page'] ?? 1;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="style.css">
  <title>Fahren</title>
</head>
<style>
  .accordion-button:not(.collapsed) {
    background-color: transparent;
    color: #212529;
  }

  .card-compra .carousel {
    z-index: 2;
  }

  .card-compra .favoritar-btn {
    z-index: 3;
  }
</style>

<body>
  <?php
  include 'estruturas/navbar/navbar-compras.php' ?>
  <main class="bg-body-tertiary fs-nav">
    <div class="container-fluid">
      <div class="row d-flex justify-content-center">
        <div class="col-12 col-lg-10 col-lg-10">
          <div class="row pt-5">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="link-dark link-underline-opacity-0 link-underline-opacity-100-hover">Home</a></li>
                
                <?php if (isset($vendedor)) {
                  echo "<li class=\"breadcrumb-item\"><a href=\"compras.php\" class=\"link-dark link-underline-opacity-0 link-underline-opacity-100-hover\">Carros</a></li>
                <li class=\"breadcrumb-item active text-dark fw-semibold\" aria-current=\"page\">".$vendedor."</li>";
                } else {
                  echo "<li class=\"breadcrumb-item active text-dark fw-semibold\" aria-current=\"page\">Carros</li>";
                };?>
              </ol>
              <h4><?php if (isset($vendedor)) {
                  echo "Anúncios de ".$vendedor;
                } else {
                  echo "Carros de todo o Brasil!";
                };?></h4>
            </nav>
          </div>
          <div class="row g-4">
            <div id="filtros-col" class="col-4 col-xl-3 col-xxl-2 vh-100 position-sticky top-0 pt-4 d-flex flex-column" style="max-height: 100vh;">
              <div id="filtros-over" class="overflow-auto rounded-2 border border-opacity-25 shadow-sm" style="max-height: 100%;">
                <div class="accordion w-100" id="accordionPanelsStayOpenExample">
                  <?php if (isset($vendedor)) {
                  echo "<!-- Vendedor ⬇️ -->
                  <div class=\"accordion-item border-0 border-bottom\">
                    <h2 class=\"accordion-header\">
                      <button class=\"accordion-button\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#vendedor\" aria-expanded=\"true\" aria-controls=\"vendedor\">
                        Vendedor
                      </button>
                    </h2>
                    <div id=\"vendedor\" class=\"accordion-collapse collapse show\">
                      <div class=\"accordion-body\">
                        <div class=\"row\">
                          <div class=\"rounded-3 border-2\">
                                    <div class=\"row\">
                                        <div class=\"col p-2 d-flex align-items-center justify-content-center\">
                                            <div class=\"ratio ratio-1x1\">
                                                <img src=\"".$vendedor_img."\" alt=\"\" class=\"img-fluid rounded-3 shadow-sm\">
                                            </div></i>
                                        </div>
                                        <div class=\"col-7 py-2\">
                                            <div class=\"row\">
                                                <p class=\"fw-semibold mb-0\">".$vendedor."</p>
                                            </div>
                                            <div class=\"row\">
                                                <small class=\"fw-semibold mb-0\">".$vendedor_est."<i class=\"bi bi-star-fill ms-1\"></i></small>
                                            </div>
                                        </div>
                                        <div class=\"col-3 d-inline-flex align-items-center text-nowrap\">
                                            <small>Aberto <i class=\"bi bi-circle-fill text-success\" style=\"font-size: 0.5rem !important; vertical-align: middle;\"></i></small>
                                        </div>
                                    </div>
                                </div>
                        </div>
                      </div>
                    </div>
                  </div>";
                };?>
                  <!-- Modelo ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#modelo" aria-expanded="true" aria-controls="modelo">
                        Modelo
                      </button>
                    </h2>
                    <div id="modelo" class="accordion-collapse collapse show">
                      <div class="accordion-body">
                        <div class="row mb-4">
                          <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" <?php if ($tipo == 'carro') {
                                                                                                                      echo 'checked';
                                                                                                                    } ?>>
                            <label class="btn btn-outline-dark rounded-start-5" for="btnradio1"><i class="bi bi-car-front-fill"></i> Carros</label>

                            <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" <?php if ($tipo == 'moto') {
                                                                                                                      echo 'checked';
                                                                                                                    } ?>>
                            <label class="btn btn-outline-dark rounded-end-5" for="btnradio2"><i class="bi bi-bicycle"></i> Motos</label>
                          </div>
                        </div>
                        <div class="row px-1">
                          <div class="mb-3">
                            <h6>Estado</h6>
                            <div class="row ps-3">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="usados" <?php if ($codicao == 'usado') {
                                                                                                              echo 'checked';
                                                                                                            } ?>>
                                <label class="form-check-label" for="usados">
                                  Usados
                                </label>
                                <small class="float-end">
                                  (5421)
                                </small>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="novos" <?php if ($codicao == 'novo') {
                                                                                                              echo 'checked';
                                                                                                            } ?>>
                                <label class="form-check-label" for="novos">
                                  Novos
                                </label>
                                <small class="float-end">
                                  (815)
                                </small>
                              </div>
                            </div>
                          </div>
                          <hr class="mb-4">
                          <div class="row px-1">
                            <div id="marcas-input" class="mb-3">
                              <h6>Marcas</h6>
                              <div class="input-group">
                                <select name="" id="" class="form-select">
                                  <option value="" selected hidden>Selecione a marca</option>
                                  <option value="0">Abarth</option>
                                  <option value="0">Alfa Romeo</option>
                                  <option value="0">Aston Martin</option>
                                  <option value="0">Audi</option>
                                  <option value="0">Bentley</option>
                                  <option value="0">BMW</option>
                                  <option value="0">Bugatti</option>
                                  <option value="0">BYD</option>
                                  <option value="0">Cadillac</option>
                                  <option value="0">Chevrolet</option>
                                  <option value="0">Chrysler</option>
                                  <option value="0">Citroën</option>
                                  <option value="0">Corvette</option>
                                  <option value="0">Dacia</option>
                                  <option value="0">Dodge</option>
                                  <option value="0">Ferrari</option>
                                  <option value="0">Fiat</option>
                                  <option value="0">Ford</option>
                                  <option value="0">Genesis</option>
                                  <option value="0">GMC</option>
                                  <option value="0">GWM</option>
                                  <option value="0">Honda</option>
                                  <option value="0">Hummer</option>
                                  <option value="0">Hyundai</option>
                                  <option value="0">Infiniti</option>
                                  <option value="0">JAECOO</option>
                                  <option value="0">Jaguar</option>
                                  <option value="0">Jeep</option>
                                  <option value="0">Kia</option>
                                  <option value="0">Koenigsegg</option>
                                  <option value="0">Lamborghini</option>
                                  <option value="0">Lacia</option>
                                  <option value="0">Land Rover</option>
                                  <option value="0">Lexus</option>
                                  <option value="0">Lincoln</option>
                                  <option value="0">Lotus</option>
                                  <option value="0">Maserati</option>
                                  <option value="0">Mazda</option>
                                  <option value="0">McLaren</option>
                                  <option value="0">Mercedes-Benz</option>
                                  <option value="0">MINI</option>
                                  <option value="0">Mitsubishi</option>
                                  <option value="0">Nissan</option>
                                  <option value="0">Omoda</option>
                                  <option value="0">Opel</option>
                                  <option value="0">Peugeot</option>
                                  <option value="0">Porsche</option>
                                  <option value="0">Ram</option>
                                  <option value="0">Renault</option>
                                  <option value="0">Rolls-Royce</option>
                                  <option value="0">Skoda</option>
                                  <option value="0">Smart</option>
                                  <option value="0">Subaru</option>
                                  <option value="0">Suzuki</option>
                                  <option value="0">Tesla</option>
                                  <option value="0">Toyota</option>
                                  <option value="0">Volkswagen</option>
                                  <option value="0">Volvo</option>
                                </select>
                                <button class="btn bg-white border d-none">X</button>
                              </div>
                            </div>
                            <div id="modelos-input" class="mb-3 d-none">
                              <h6>Modelos</h6>
                              <div class="input-group">
                                <select name="" id="" class="form-select">
                                  <option value="" selected hidden>Selecione o modelo</option>
                                  <option value="modelo-1">Modelo 1</option>
                                  <option value="modelo-2">Modelo 2</option>
                                  <option value="modelo-3">Modelo 3</option>
                                  <option value="modelo-4">Modelo 4</option>
                                  <option value="modelo-5">Modelo 5</option>
                                  <option value="modelo-6">Modelo 6</option>
                                  <option value="modelo-7">Modelo 7</option>
                                  <option value="modelo-8">Modelo 8</option>
                                  <option value="modelo-9">Modelo 9</option>
                                  <option value="modelo-10">Modelo 10</option>
                                </select>
                                <button class="btn bg-white border d-none">X</button>
                              </div>
                            </div>
                            <div id="versoes-input" class="mb-3 d-none">
                              <h6>Versões</h6>
                              <div class="input-group">
                                <select name="" id="" class="form-select">
                                  <option value="" selected hidden>Selecione a versão</option>
                                  <option value="versao-1">Versão 1</option>
                                  <option value="versao-2">Versão 2</option>
                                  <option value="versao-3">Versão 3</option>
                                  <option value="versao-4">Versão 4</option>
                                  <option value="versao-5">Versão 5</option>
                                  <option value="versao-6">Versão 6</option>
                                  <option value="versao-7">Versão 7</option>
                                  <option value="versao-8">Versão 8</option>
                                  <option value="versao-9">Versão 9</option>
                                  <option value="versao-10">Versão 10</option>
                                </select>
                                <button class="btn bg-white border d-none">X</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <!-- Preço ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#preco" aria-expanded="true" aria-controls="preco">
                        Preço
                      </button>
                    </h2>
                    <div id="preco" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row">
                          <div class="mb-1">
                            <h6>Intervalo de preço</h6>
                          </div>
                          <div class="row px-2 g-0 gap-2">
                            <div class="col">
                              <input type="text" class="form-control" id="preco-min" placeholder="Preço mínimo">
                            </div>
                            <div class="col">
                              <input type="text" class="form-control" id="preco-max" placeholder="Preço máximo">
                            </div>
                          </div>
                          <div class="row mt-3">
                            <h6 class="small mb-0">Faixa de preço específico</h6>
                            <div class="row row-cols-2 row-cols-xxl-3 gy-1">
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">≤ 50 mil</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">≤ 75 mil</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">≤ 100 mil</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">≤ 150 mil</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">≤ 250 mil</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">≤ 500 mil</div>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <!-- Ano ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ano" aria-expanded="true" aria-controls="ano">
                        Ano
                      </button>
                    </h2>
                    <div id="ano" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row">
                          <div class="mb-1">
                            <h6>Intervalo de tempo</h6>
                          </div>
                          <div class="row px-2 g-0 gap-2">
                            <div class="col">
                              <input type="text" class="form-control" id="ano-min" placeholder="Ano mínimo">
                            </div>
                            <div class="col">
                              <input type="text" class="form-control" id="ano-max" placeholder="Ano máximo">
                            </div>
                          </div>
                          <div class="row mt-3">
                            <h6 class="small mb-0">Ano específico</h6>
                            <div class="row row-cols-2 row-cols-xxl-4 gy-1">
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2025</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2024</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2023</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2022</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2021</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2020</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2019</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2018</div>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="row mt-3">
                            <h6 class="small mb-2">Intervalos de tempo</h6>
                            <div class="row row-cols-1 row-cols-md-2 ps-1 gx-0">
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2025-2022</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2022-2019</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2019-2016</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2016-2013</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2013-2010</div>
                                </button>
                              </div>
                              <div class="col">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small text-nowrap">2010-2000</div>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <!-- Quilometragem ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#km" aria-expanded="true" aria-controls="km">
                        Quilometragem
                      </button>
                    </h2>
                    <div id="km" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row">
                          <div class="mb-1">
                            <h6>Intervalo de quilometragem</h6>
                          </div>
                          <div class="row px-2 g-0 gap-2">
                            <div class="col">
                              <input type="text" class="form-control" id="km-min" placeholder="km mínimo">
                            </div>
                            <div class="col">
                              <input type="text" class="form-control" id="km-max" placeholder="km máximo">
                            </div>
                          </div>
                          <div class="row mt-3">
                            <h6 class="small mb-0">Quilometragens específico</h6>
                            <div class="row gy-1">
                              <div class="col-6">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small">&lt; 20K</div>
                                </button>
                              </div>
                              <div class="col-6">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small">20K-50K</div>
                                </button>
                              </div>
                              <div class="col-6">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small">50K-100K</div>
                                </button>
                              </div>
                              <div class="col-6">
                                <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                  <div class="small">&gt; 100K</div>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <!-- Cor ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cor" aria-expanded="true" aria-controls="cor">
                        Cor
                      </button>
                    </h2>
                    <div id="cor" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row ps-3">
                          <?php
                          $text = 'Branco';
                          $id = 'branco';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Preto';
                          $id = 'preto';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Vermelho';
                          $id = 'verm';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Azul';
                          $id = 'azul';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Cinza';
                          $id = 'cinza';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Prata';
                          $id = 'prata';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Vinho';
                          $id = 'vinho';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Marrom';
                          $id = 'marrom';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Laranja';
                          $id = 'laranja';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Amarelo';
                          $id = 'amarelo';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Dourado';
                          $id = 'dourado';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Verde';
                          $id = 'verde';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Bege';
                          $id = 'bege';
                          include $filter_check_caminho ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Carroceria ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button <?php if ($categoria == null or $categoria == 'ele' or $categoria == 'hib') {
                                                        echo ' collapsed';
                                                      } ?>" type="button" data-bs-toggle="collapse" data-bs-target="#carroceria" aria-expanded="true" aria-controls="carroceria">
                        Carroceria
                      </button>
                    </h2>
                    <div id="carroceria" class="accordion-collapse collapse <?php if ($categoria != null and $categoria != 'ele' and $categoria != 'hib') {
                                                                              echo 'show';
                                                                            } ?>">
                      <div class="accordion-body">
                        <div class="row ps-3">
                          <?php
                          $text = 'Sedan';
                          $id = 'sed';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Hatchback';
                          $id = 'hat';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Pickup';
                          $id = 'pic';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Coupé';
                          $id = 'cou';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Minivan';
                          $id = 'min';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Supercarro';
                          $id = 'sup';
                          include $filter_check_caminho ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Propulsão ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button<?php if ($categoria != 'ele' and $categoria != 'hib') {
                                                        echo ' collapsed';
                                                      } ?>" type="button" data-bs-toggle="collapse" data-bs-target="#propulsao" aria-expanded="true" aria-controls="propulsao">
                        Propulsão
                      </button>
                    </h2>
                    <div id="propulsao" class="accordion-collapse collapse<?php if ($categoria == 'ele' or $categoria == 'hib') {
                                                                            echo ' show';
                                                                          } ?>">
                      <div class="accordion-body">
                        <div class="row ps-3">
                          <?php
                          $text = 'Combustão';
                          $id = 'comb';
                          include $filter_check_caminho ?>
                          <div id="comb-tipos" class="ps-3">
                            <?php
                            $text = 'Gasolina';
                            $id = 'gas';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'Álcool';
                            $id = 'alc';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'Flex';
                            $id = 'fle';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'Diesel';
                            $id = 'die';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'GNV';
                            $id = 'gnv';
                            include $filter_check_caminho;
                            ?>
                          </div>
                          <?php
                          $text = 'Híbrido';
                          $id = 'hib';
                          include $filter_check_caminho ?>
                          <div id="hib-tipos" class="ps-3">
                            <?php
                            $text = 'HEV';
                            $id = 'hev';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'PHEV';
                            $id = 'phe';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'MHEV';
                            $id = 'mhe';
                            include $filter_check_caminho;
                            ?>
                          </div>
                          <?php
                          $text = 'Elétrico';
                          $id = 'ele';
                          include $filter_check_caminho ?>
                          <div id="ele-tipos" class="ps-3">
                            <?php
                            $text = 'BEV';
                            $id = 'bev';
                            include $filter_check_caminho;
                            ?>
                            <?php
                            $text = 'FCEV';
                            $id = 'fce';
                            include $filter_check_caminho;
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Câmbio ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cambio" aria-expanded="true" aria-controls="cambio">
                        Câmbio
                      </button>
                    </h2>
                    <div id="cambio" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row ps-3">
                          <?php
                          $text = 'Automático';
                          $id = 'aut';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Manual';
                          $id = 'man';
                          include $filter_check_caminho ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Blindagem ⬇️ -->
                  <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#blindagem" aria-expanded="true" aria-controls="blindagem">
                        Blindagem
                      </button>
                    </h2>
                    <div id="blindagem" class="accordion-collapse collapse">
                      <div class="accordion-body">
                        <div class="row ps-3">
                          <?php
                          $text = 'Sim';
                          $id = 'bli';
                          include $filter_check_caminho ?>
                          <?php
                          $text = 'Não';
                          $id = 'n-bli';
                          include $filter_check_caminho ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="row pt-4">
                <div class="col-auto me-auto">
                  <div class="fw-semibold small py-3">
                    1.234 resultados encontrados
                  </div>
                </div>
                <div class="col-auto d-flex align-items-center">
                  <button id="ordenar-btn" class="btn btn-light border me-2" disabled><i class="bi bi-filter"></i></button>
                  <div class="small">Ordenar por: </div>
                  <div class="col-auto">
                    <select id="ordenar-input" class="form-select form-select-sm bg-transparent border-0 fw-semibold">
                      <option value="relevancia" selected>Relevância</option>
                      <option value="preco">Preço</option>
                      <option value="ano">Ano</option>
                      <option value="km">KM</option>
                    </select>
                  </div>
                </div>
              </div>
              <div id="area-compra" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-6 g-3 g-lg-2">
                  <?php
                  $quantidade = 3;
                  for ($i = 1; $i <= $quantidade*12; $i++) {
                  echo '<div class="col">';
                  $nome = 'PORSCHE 911';
                  $info = '3.8 24V H6 GASOLINA TURBO PDK';
                  $preco = 'R$ 1.190.000';
                  $ano = '2020/2021';
                  $km = '2.500';
                  $id = 'carro-'.$i;
                  $loc = 'São José dos Campos - SP';
                  $img1 = 'img/compras/1.png';
                  $img2 = 'img/compras/2.png';
                  $img3 = 'img/compras/3.png';
                  $img4 = 'img/compras/4.png';
                  $img5 = 'img/compras/5.png';
                  $img6 = 'img/compras/6.png';
                  include 'estruturas/card-compra/card-compra.php';
                  echo '</div>';
                  };
                  ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="container-fluid mt-5 pb-5">
        <div class="row">
          <div class="col-12 d-flex justify-content-center">
            <nav aria-label="Page navigation example">
              <ul class="pagination pagination-dark">
                <li class="page-item <?php if ($page == 1) {echo 'disabled';} ?>">
                  <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-caret-left-fill"></i></a>
                </li>
                <li class="page-item <?php if ($page == 1) {echo 'active';} ?>"><a class="page-link border-0" href="compras.php?page=<?php if ($page == 1) {echo $page;} else {echo $page-1;} ?>"><?php if ($page == 1) {echo $page;} else {echo $page-1;} ?></a></li>
                <li class="page-item <?php if ($page != 1) {echo 'active';} ?>"><a class="page-link" href="compras.php?page=<?php if ($page == 1) {echo $page+1;} else {echo $page;} ?>"><?php if ($page == 1) {echo $page+1;} else {echo $page;} ?></a></li>
                <li class="page-item"><a class="page-link" href="compras.php?page=<?php if ($page == 1) {echo $page+2;} else {echo $page+1;} ?>"><?php if ($page == 1) {echo $page+2;} else {echo $page+1;} ?></a></li>
                <li class="page-item">
                  <a class="page-link" href="compras.php?page=<?= $page+1?>"><i class="bi bi-caret-right-fill"></i></a>
                </li>
              </ul>
            </nav>
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
    const cards = $('.card-compra');
    cards.each(function() {
      let num = 1;
      const card = $(this)
      card.data('quant', card.find('.carro-img').children().length);
      const quant = card.data('quant');
      card.find('.max').text(quant);
      card.find('.carousel-control-prev').on('click', function() {
        if (num === 1) {
          num = quant;
        } else {
          num --;
        };

        card.find('.min').text(num);
      });
      card.find('.carousel-control-next').on('click', function() {
        if (num === quant) {
          num = 1;
        } else {
          num ++;
        };
        card.find('.min').text(num);
      });

      card.find('.favoritar-btn').hide();
      
      card.on('mouseenter', function() {
        card.find('.favoritar-btn').fadeIn(250);
      });

      card.on('mouseleave', function() {
        card.find('.favoritar-btn').fadeOut(250);
      });
    });

    $("#ele").addClass('propulsao');
    $("#hib").addClass('propulsao');
    $("#comb").addClass('propulsao');

    $('.propulsao:checked').each(function() {
      $("#" + $(this).attr('id') + "-tipos").children().each(function(i, e) {
        $(e).find('input').prop('checked', true);
      });
    });

    $('.propulsao').each(function() {
      $(this).on("change", function() {
        const propu = this;
        $("#" + $(this).attr('id') + "-tipos").children().each(function(i, e) {
          $(e).find('input').prop('checked', $(propu).prop('checked'));
        });
      })
    });

    const order_btn = $('#ordenar-btn');
    const order_i = $(order_btn).find("i");

    $('#ordenar-input').on('change', function() {
      if ($(this).val() === 'relevancia') {
        $(order_i).removeClass('bi-sort-up');
        $(order_i).removeClass('bi-sort-down');
        $(order_i).addClass('bi-filter');
        $(order_btn).prop('disabled', true);
      } else {
        $(order_i).addClass('bi-sort-up');
        $(order_i).removeClass('bi-sort-down');
        $(order_i).removeClass('bi-filter');
        $(order_btn).prop('disabled', false);
      };
    });

    $(order_btn).on('click', function() {
      if ($(this).val() != 'relevancia') {
        $(order_i).toggleClass('bi-sort-up');
        $(order_i).toggleClass('bi-sort-down');
      };
    });

    $(window).on("scroll", function() {
      const filtrosCol = $("#filtros-col");
      const filtrosOver = $("#filtros-over");

      if (filtrosCol.length === 0 || filtrosOver.length === 0) return;

      const rect = filtrosCol[0].getBoundingClientRect();

      if (rect.top < 0) {
        filtrosOver.addClass("mt-auto");
      } else {
        filtrosOver.removeClass("mt-auto");
      }
    });

    const marcasInput = $("#marcas-input");
    const modelosInput = $("#modelos-input");
    const versoesInput = $("#versoes-input");

    marcasInput.find("select").on("change", function() {
      if ($(this).val()) {
        modelosInput.removeClass("d-none");
        marcasInput.find("button").removeClass("d-none");
      } else {
        modelosInput.addClass("d-none");
        marcasInput.find("button").addClass("d-none");
      }
    });

    modelosInput.find("select").on("change", function() {
      if ($(this).val()) {
        versoesInput.removeClass("d-none");
        modelosInput.find("button").removeClass("d-none");
      } else {
        versoesInput.addClass("d-none");
        modelosInput.find("button").addClass("d-none");
      }
    });

    versoesInput.find("select").on("change", function() {
      if ($(this).val()) {
        versoesInput.find("button").removeClass("d-none");
      } else {
        versoesInput.find("button").addClass("d-none");
      }
    });

    marcasInput.find("button").on("click", function() {
      marcasInput.find("select").val("");
      marcasInput.find("button").addClass("d-none");
      modelosInput.find("button").addClass("d-none");
      versoesInput.find("button").addClass("d-none");
      modelosInput.addClass("d-none");
      modelosInput.find("select").val("");
      versoesInput.addClass("d-none");
      versoesInput.find("select").val("");
    });

    modelosInput.find("button").on("click", function() {
      modelosInput.find("select").val("");
      modelosInput.find("button").addClass("d-none");
      versoesInput.find("button").addClass("d-none");
      versoesInput.addClass("d-none");
      versoesInput.find("select").val("");
    });

    versoesInput.find("button").on("click", function() {
      versoesInput.find("select").val("");
      versoesInput.find("button").addClass("d-none");
    });
  });
</script>

</html>