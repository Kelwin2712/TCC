<?php session_start(); ?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuração</title>
    <link rel="icon" type="png" href="img/logo-oficial.png">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="min-vh-100">
    <?php include 'estruturas/navbar/navbar-default.php' ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 d-flex">
                <div class="flex-shrink-0 p-3" style="width: 280px;"> <a href="index.php" class="d-flex align-items-center pb-3 mb-3 link-body-emphasis text-decoration-none border-bottom"> <svg class="bi pe-none me-2" width="30" height="24" aria-hidden="true">
                            <use xlink:href="#bootstrap"></use>
                        </svg> <span class="fs-5 fw-semibold">Collapsible</span> </a>
                    <ul class="list-unstyled ps-0">
                        <li class="mb-1"> <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                                Home
                            </button>
                            <div class="collapse show" id="home-collapse">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Overview</a></li>
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Updates</a></li>
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Reports</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="mb-1"> <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                                Dashboard
                            </button>
                            <div class="collapse" id="dashboard-collapse">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Overview</a></li>
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Weekly</a></li>
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Monthly</a></li>
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Annually</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="mb-1"> <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                                Orders
                            </button>
                            <div class="collapse" id="orders-collapse">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">New</a></li>
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Processed</a></li>
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Shipped</a></li>
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Returned</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="border-top my-3"></li>
                        <li class="mb-1"> <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                                Account
                            </button>
                            <div class="collapse" id="account-collapse">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">New...</a></li>
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Profile</a></li>
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Settings</a></li>
                                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Sign out</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <section class="configuracao container-fluid px-md-5 py-4">
                    <h2><i class="bi bi-person-fill-gear me-1"></i>Configuração da conta @</h2>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6 col-lg-4 mb-1">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><i class="bi bi-person-vcard me-1"></i>Usuário</h5>
                                    <p class="card-text flex-grow-1">Dados e Informações sobre a sua Conta</p>
                                    <a href="B-user.html" class="btn btn-dark">
                                        Acessar Configurações
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-1">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><i class="bi bi-emoji-smile me-1"></i>Suas Preferências</h5>
                                    <p class="card-text flex-grow-1">Escolha as suas preferências</p>
                                    <a href="C-pref.html" class="btn btn-dark">
                                        Ajustar as Preferências
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-1">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><i class="bi bi-grid-3x3 me-1"></i>Interface</h5>
                                    <p class="card-text flex-grow-1">Escolha como seja sua interface</p>
                                    <a href="D-interface.html" class="btn btn-dark">
                                        Customizar Interface
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-1">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><i class="bi bi-coin me-1"></i>Financeiro</h5>
                                    <p class="card-text flex-grow-1">Saiba tudo sobre o Setor Financeiro</p>
                                    <a href="E-financeiro.html" class="btn btn-dark">
                                        Gerenciar Pagamentos
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-1">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><i class="bi bi-person-raised-hand"></i>Suporte</h5>
                                    <p class="card-text flex-grow-1">Para qualquer dúvida e contatar-nos</p>
                                    <a href="F-suporte.html" class="btn btn-dark">
                                        Preciso de Ajuda
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-1">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><i class="bi bi-shield-check me-1"></i>Privacidade</h5>
                                    <p class="card-text flex-grow-1">Segurança e Termos de Privacidade!</p>
                                    <a href="G-privacidade.html" class="btn btn-dark">
                                        Saiba mais
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <i class="bi bi-exclamation-circle- me-1fill"></i><span>Solicitar <a href="#" style="text-decoration: none;">Informações e Dados sobre a minha Conta</a></span>
                        </div>
                    <div class="col-12">
                        <i class="bi bi-trash3-fill me-1"></i><span>Solicitar <a class="delete-account" href="#">Exclusão da minha Conta</a></span>
                    </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>