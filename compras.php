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
</style>

<body>
  <?php include 'estruturas/navbar/navbar-default.php' ?>
  <main class="bg-body-tertiary fs-nav">
    <div class="container-fluid py-5">
      <div class="row d-flex justify-content-center">
        <div class="col-12 col-lg-10 col-lg-10">
          <div class="row g-4">
            <div class="col-4 col-xl-3 col-xxl-2">
              <div class="card">
                <div class="card-body p-0">
                  <div class="overflow-auto" style="max-height: 100vh;">
                    <div class="accordion w-100" id="accordionPanelsStayOpenExample">
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
                                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                                <label class="btn btn-outline-dark" for="btnradio1"><i class="bi bi-car-front-fill"></i> Carros</label>

                                <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                                <label class="btn btn-outline-dark" for="btnradio2"><i class="bi bi-bicycle"></i> Motos</label>
                              </div>
                            </div>
                            <hr class="mb-4">
                            <div class="row px-1">
                              <div class="mb-3">
                                <h6>Estado</h6>
                                <div class="row ps-3">
                                  <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                                    <label class="form-check-label" for="checkDefault">
                                      Usados
                                    </label>
                                    <small class="float-end">
                                      (5421)
                                    </small>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="checkChecked" checked>
                                    <label class="form-check-label" for="checkChecked">
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
                                <div class="mb-3">
                                  <h6>Marcas</h6>
                                  <div class="input-group">
                                    <select name="" id="" class="form-select">
                                      <option value="" selected hidden>Selecione a marca</option>
                                      <option value="audi">Audi</option>
                                      <option value="bmw">BMW</option>
                                      <option value="ferrari">Ferrari</option>
                                      <option value="ford">Ford</option>
                                      <option value="lamborghini">Lamborghini</option>
                                      <option value="mercedes-benz">Mercedes-Benz</option>
                                      <option value="porsche">Porsche</option>
                                      <option value="rolls-royce">Rolls-Royce</option>
                                      <option value="tesla">Tesla</option>
                                      <option value="volkswagen">Volkswagen</option>
                                    </select>
                                    <button class="btn bg-white border">X</button>
                                  </div>
                                </div>
                                <div class="mb-3">
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
                                    <button class="btn bg-white border">X</button>
                                  </div>
                                </div>
                                <div class="mb-3">
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
                                    <button class="btn bg-white border">X</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>
                      <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ano" aria-expanded="true" aria-controls="ano">
                            Ano
                          </button>
                        </h2>
                        <div id="ano" class="accordion-collapse collapse show">
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
                                <div class="row row-cols-4 gy-1">
                                  <div class="col">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2025</div>
                                    </button>
                                  </div>
                                  <div class="col">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2024</div>
                                    </button>
                                  </div>
                                  <div class="col">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2023</div>
                                    </button>
                                  </div>
                                  <div class="col">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2022</div>
                                    </button>
                                  </div>
                                  <div class="col">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2021</div>
                                    </button>
                                  </div>
                                  <div class="col">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2020</div>
                                    </button>
                                  </div>
                                  <div class="col">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2019</div>
                                    </button>
                                  </div>
                                  <div class="col">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2018</div>
                                    </button>
                                  </div>
                                </div>
                              </div>
                              <div class="row mt-3">
                                <h6 class="small mb-0">Intervalos de tempo</h6>
                                <div class="row ps-1 gx-0">
                                  <div class="col-4">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2025-2022</div>
                                    </button>
                                  </div>
                                  <div class="col-4">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2022-2019</div>
                                    </button>
                                  </div>
                                  <div class="col-4">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2019-2016</div>
                                    </button>
                                  </div>
                                  <div class="col-4">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2016-2013</div>
                                    </button>
                                  </div>
                                  <div class="col-4">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2013-2010</div>
                                    </button>
                                  </div>
                                  <div class="col-4">
                                    <button class="btn bg-secondary-subtle w-auto rounded-pill py-0 px-2">
                                      <div class="small">2010-2000</div>
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>
                      <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#km" aria-expanded="true" aria-controls="km">
                            Quilometragem
                          </button>
                        </h2>
                        <div id="km" class="accordion-collapse collapse show">
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
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="row">
                <div class="col-auto me-auto">
                  <div class="fw-semibold small py-2">
                    1.234 resultados encontrados
                  </div>
                </div>
                  <div class="col-auto d-flex align-items-center">
                    <i class="bi bi-arrow-down-up me-2"></i>
                    <div class="small">Ordenar por: </div>
                  <div class="col-auto">
                    <select class="form-select form-select-sm bg-transparent border-0 fw-semibold">
                      <option value="relevancia" selected>Relevância</option>
                      <option value="preco">Preço</option>
                      <option value="ano">Ano</option>
                      <option value="km">Quilometragem</option>
                    </select>
                  </div>
                  </div>
              </div>
              <div id="area-compra" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-6 g-3 g-lg-1">
                <div class="col">
                  <?php
                  $nome = 'AUDI RS6';
                  $info = '2.0 TFSI GASOLINA SPORTBACK PRESTIGE PLUS S TRONIC';
                  $preco = 'R$ 100.000';
                  $ano = '2020/2021';
                  $km = '52.524';
                  $loc = 'São Paulo - SP';
                  $img = './img/carros/img-1.png';
                  include 'estruturas/card-compra/card-compra.php' ?>
                </div>
              </div>
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
<script>
  function duplicarCard(vezes) {
    vezes = vezes * 12
    const row = document.getElementById("area-compra");
    const cardOriginal = row.querySelector(".col");

    for (let i = 0; i < vezes - 1; i++) {
      const clone = cardOriginal.cloneNode(true);
      row.appendChild(clone);
    }
  }

  duplicarCard(5);
</script>

</html>