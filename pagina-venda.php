<?php session_start();?>

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
    <?php include 'estruturas/navbar/navbar-default.php' ?>
    <main class="bg-body-tertiary fs-nav">
        <div class="container py-5">
            <div class="row g-5 mb-3">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body px-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="ratio ratio-16x9">
                                        <div id="imagems-carro" class="carousel slide">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <img src="img/vendas/911-1.jpg" class="d-block w-100" alt="Imagem 1">
                                                </div>
                                                <div class="carousel-item">
                                                    <img src="img/vendas/911-2.jpg" class="d-block w-100" alt="Imagem 2">
                                                </div>
                                                <div class="carousel-item">
                                                    <img src="img/vendas/911-1.jpg" class="d-block w-100" alt="Imagem 3">
                                                </div>
                                                <div class="carousel-item">
                                                    <img src="img/vendas/911-2.jpg" class="d-block w-100" alt="Imagem 4">
                                                </div>
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#imagems-carro" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#imagems-carro" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="progress" role="progressbar" aria-label="Example 1px high" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="height: 4px">
                                        <div class="progress-bar bg-dark" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0">
                                <div class="col-3">
                                    <img src="img/vendas/911-2.jpg" alt="" class="img-fluid">
                                </div>
                                <div class="col-3">
                                    <img src="img/vendas/911-1.jpg" alt="" class="img-fluid opacity-50">
                                </div>
                                <div class="col-3">
                                    <img src="img/vendas/911-2.jpg" alt="" class="img-fluid opacity-50">
                                </div>
                                <div class="col-3">
                                    <img src="img/vendas/911-1.jpg" alt="" class="img-fluid opacity-50">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body px-4 d-flex flex-column justify-content-between">
                            <div class="row d-flex justify-content-between">
                                <div class="col-auto">
                                    <p class="fs-1 fw-semibold mb-0">R$892.500</p>
                                </div>
                                <div class="col-auto">
                                    <span class="badge rounded-pill text-bg-success"><i class="bi bi-shield-check"></i> Confiável</span>
                                </div>
                                <p>Envie uma mensagem para o vendedor</p>
                            </div>
                            <div class="row">
                                <div class="mb-2">
                                    <label for="nome-input" class="form-label mb-0">Nome</label>
                                    <input type="text" class="form-control rounded-3 border-2" id="nome-input" placeholder="Nome" required>
                                </div>
                                <div class="mb-2">
                                    <label for="email-input" class="form-label mb-0">Email</label>
                                    <input type="email" class="form-control rounded-3 border-2" id="email-input" placeholder="Email" required>
                                </div>
                                <div class="mb-2">
                                    <label for="telefone-input" class="form-label mb-0">Telefone</label>
                                    <input type="email" class="form-control rounded-3 border-2" id="telefone-input" placeholder="Telefone" required>
                                </div>
                                <div class="mb-2">
                                    <label for="mensagem-input" class="form-label mb-0">Mensagem</label>
                                    <input type="text" class="form-control rounded-3 border-2 pb-5" id="mensagem-input" placeholder="Mensagem" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <button type="submit" class="btn btn-dark w-100 mb-3 py-2">Enviar mensagem</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5 mb-3">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body py-4">
                            <div class="row mb-4 d-flex justify-content-between px-4">
                                <div class="col">
                                    <h2 class="fw-bold mb-0 text-uppercase">PORSCHE 911</h2>
                                    <p class="text-uppercase">3.0 24V H6 GASOLINA CARRERA S PDK</p>
                                </div>
                                <div class="col">
                                    <p class="text-capitalize text-end"><i class="bi bi-geo-alt"></i> São José dos Campos - SP</p>
                                </div>
                            </div>
                            <div class="row px-4 pt-3">
                                <p>Informações</p>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Ano</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold ">2020/2021</p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">KM</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold ">2.500</p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Cor</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold ">Preta</p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Câmbio</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold ">Automático</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Blindando</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold ">Não</p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Aceita troca</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold ">Sim</p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Revisão feita</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold ">Sim</p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Combustível</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold ">Gasolina</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="w-100">
                            <div class="row px-4 pt-3">
                                <p>Descrição do veículo</p>
                                <p class="text-secondary">Blindado DVB (Divena) com vidros AGP B33; Downpipe, Stage 2 by Soldera, filtro K&N; Cor Preta GT com PPF Full transparente/fosco; Faróis LED PDLS Plus; Rodas RS Spyder em cinza acetinado; Interno Cinza Ardósia; Bancos elétricos com memória; Pacote Sport Chrono; Sistema de áudio BOSE; Sport Exhaust; PASM; Câmbio PDK; Teto Solar Panorâmico blindado; Painel e Multimídia TFT; Apple Car Play e Android Auto; Drive Mode no volante; Ar-condicionado Dual-Zone; Revisado Outros Opcionais: Comando de áudio no volante, Controle de estabilidade, Direção Elétrica, Distribuição eletrônica de frenagem, Kit Multimídia, Pára-choques na cor do veículo, Porta-copos.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-auto">
                        <div class="card-body py-4">
                            <div class="row d-flex justify-content-between">
                                <div class="col">
                                    <p>Vendedor</p>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn bg-primary-subtle text-primary fs-7 px-2 float-end" style="padding-top: .125rem; padding-bottom: .125rem;">Seguir</button>
                                </div>
                            </div>
                            <a href="#" class="row px-2 text-decoration-none text-dark">
                                <div class="rounded-3 border-2">
                                    <div class="row">
                                        <div class="col p-2 d-flex align-items-center justify-content-center">
                                            <div class="ratio ratio-1x1">
                                                <img src="img/logo-fahren-bg.jpg" alt="" class="img-fluid rounded-3 shadow-sm">
                                            </div></i>
                                        </div>
                                        <div class="col-7 py-2">
                                            <div class="row">
                                                <p class="fw-semibold mb-0">Fahren imports</p>
                                            </div>
                                            <div class="row">
                                                <small class="fw-semibold mb-0">4.63 <i class="bi bi-star-fill"></i></small>
                                            </div>
                                        </div>
                                        <div class="col-3 d-inline-flex align-items-center">
                                            <small>Aberto <i class="bi bi-circle-fill text-success" style="font-size: 0.5rem !important; vertical-align: middle;"></i></small>
                                        </div>
                                    </div>
                                </div>
                            </a>
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

</html>