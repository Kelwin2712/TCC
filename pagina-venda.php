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
                                        <img src="img/vendas/911-1.jpg" alt="Carro principal" class="img-fluid object-fit-cover">
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
                        <div class="card-body px-5">
                            <div class="row mb-5 d-flex justify-content-between">
                                <div class="col">
                                    <h2 class="fw-bold mb-0 text-uppercase">PORSCHE 911</h2>
                                    <p class="text-uppercase">3.0 24V H6 GASOLINA CARRERA S PDK</p>
                                </div>
                                <div class="col">
                                    <p class="text-uppercase text-end"><i class="bi bi-geo-alt"></i> São José dos Campos - SP</p>
                                </div>
                            </div>
                            <div class="row">
                                <p>Informações</p>
                                <div class="col-3">
                                    <div class="row">
                                        <small class="text-center">Ano</small>
                                    </div>
                                    <div class="row">
                                        <p class="fw-semibold text-center">2020/2021</p>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <small class="text-center">KM</small>
                                    </div>
                                    <div class="row">
                                        <p class="fw-semibold text-center">2.500</p>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <small class="text-center">Cor</small>
                                    </div>
                                    <div class="row">
                                        <p class="fw-semibold text-center">Preta</p>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <small class="text-center">Câmbio</small>
                                    </div>
                                    <div class="row">
                                        <p class="fw-semibold text-center">Automático</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="container-fluid bg-body-secondary">
        <div class="container">
            <footer class="pt-4">
                <div class="row">
                    <div class="col-6 col-md-2 mb-3">
                        <h5>Título 1</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-6 col-md-2 mb-3">
                        <h5>Título 1</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-6 col-md-2 mb-3">
                        <h5>Título 1</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link p-0 text-body-secondary">Opção 1</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-5 offset-md-1 mb-3">
                        <form>
                            <h5>Receba as últimas notícias do mundo automotivo</h5>
                            <p>Junte-se à nossa comunidade e fique por dentro de tudo!</p>
                            <div class="d-flex flex-column flex-sm-row w-100 gap-2">
                                <label for="newsletter1" class="visually-hidden">Email</label>
                                <input id="newsletter1" type="email" class="form-control" placeholder="Email">
                                <button class="btn btn-dark" type="button">Participar</button>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
                        <p>© 2025 Fahren. Todos os diretos reservados.</p>
                        <ul class="list-unstyled d-flex">
                            <li class="ms-3">
                                <a class="link-body-emphasis" href="#" aria-label="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            </li>
                            <li class="ms-3">
                                <a class="link-body-emphasis" href="#" aria-label="Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                            </li>
                            <li class="ms-3">
                                <a class="link-body-emphasis" href="#" aria-label="Youtube">
                                    <i class="bi bi-youtube"></i>
                                </a>
                            </li>
                            <li class="ms-3">
                                <a class="link-body-emphasis" href="#" aria-label="Tiktok">
                                    <i class="bi bi-tiktok"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="script.js"></script>

</html>