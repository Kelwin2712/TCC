<?php
session_start();

if (!isset($_SESSION['nome'])) {
    header("Location: index.php");
}
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuração</title>
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
    <link rel="icon" type="png" href="../img/logo-oficial.png">
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="overflow-x-hidden">
    <?php include '../estruturas/alert/alert.php' ?>
    <main class="container-fluid d-flex vh-100 p-0">
        <?php $selected = 'compras';
        include_once '../estruturas/sidebar/sidebar.php' ?>
        <div class="col"  style="margin-left: calc(200px + 5vw);">
            <div class="container-fluid p-5 d-flex flex-column h-100 overflow-auto">
                <div class="row">
                    <h2 class="pb-2 fw-semibold mb-0">Minhas compras</h2>
                    <p class="text-muted">Veja todos os veículos comprados até agora</p>
                </div>
                <div class="mb-5 d-flex flex-wrap gap-2">
                    <input type="radio" class="btn-check" name="telas" id="tela-1" autocomplete="off" checked>
                    <label class="btn btn-outline-dark w-auto rounded-pill px-3" for="tela-1">Carros</label>
                    <input type="radio" class="btn-check" name="telas" id="tela-2" autocomplete="off">
                    <label class="btn btn-outline-dark w-auto rounded-pill px-3" for="tela-2">Motos</label>
                </div>
                <div class="row flex-grow-1 d-flex align-items-center">
                    <div class="text-center text-muted">
                        <p style="font-size: calc(2rem + 1.5vw) !important;"><i class="bi bi-cart-x-fill"></i></p>
                        <h4 class="mb-0">Nenhuma compra feita ainda</h4>
                        <p>Ao comprar um veículo, veja todas as informações nessa tela</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="../script.js"></script>
</html>