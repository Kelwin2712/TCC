<?php
session_start();
include('../controladores/conexao_bd.php');

if (!isset($_SESSION['nome'])) {
    header("Location: index.php");
}

$tipo = $_GET['tipo'] ?? 'carro';
$codicao = $_GET['codicao'] ?? 'usado';
$categoria = $_GET['categoria'] ?? null;
$vendedor = $_GET['vendedor'] ?? null;
$vendedor_img = $_GET['vendedor_img'] ?? null;
$vendedor_est = $_GET['vendedor_est'] ?? null;

$page = $_GET['page'] ?? 1;

$id = $_SESSION['id'];

$sql = "SELECT * FROM anuncios_carros WHERE id_vendedor = $id";
$resultado = mysqli_query($conexao, $sql);

$carros = [];

$qtd_resultados = mysqli_num_rows($resultado) ?? 0;

while ($linha = mysqli_fetch_array($resultado)) {
    $carros[] = $linha;
}

mysqli_close($conexao);
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
        <?php $selected = 'ad';
        include_once '../estruturas/sidebar/sidebar.php' ?>
        <div class="col" style="margin-left: calc(200px + 5vw);">
            <div class="container-fluid p-5 d-flex flex-column h-100 overflow-auto">
                <div class="row">
                    <h2 class="pb-2 fw-semibold mb-0">Meus anúncios</h2>
                    <p class="text-muted">Veja todos os seus anúncios</p>

                </div>
                <div class="mb-5 d-flex flex-wrap gap-2">
                    <input type="radio" class="btn-check" name="telas" id="tela-1" autocomplete="off" checked>
                    <label class="btn btn-outline-dark w-auto rounded-pill px-3" for="tela-1">Carros</label>
                    <input type="radio" class="btn-check" name="telas" id="tela-2" autocomplete="off">
                    <label class="btn btn-outline-dark w-auto rounded-pill px-3" for="tela-2">Motos</label>
                    <a href="../vender-placa.php" class="btn btn-dark ms-auto rounded-pill shadow-sm">+ Criar novo anúncio</a>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 row-cols-xxl-6">
                    <?php foreach ($carros as $carro): ?>
                        <div class="col">
                            <?php
                            $marca = $carro['marca'];
                            $modelo = $carro['modelo'];
                            $versao = $carro['versao'];
                            $preco = $carro['preco'];
                            $ano = $carro['ano_fabricacao'] . '/' . $carro['ano_modelo'];
                            $km = $carro['quilometragem'];
                            $cor = $carro['cor'];
                            $troca = $carro['aceita_troca'];
                            $revisao = $carro['revisao'];
                            $id = $carro['id'];
                            $loc = 'São José dos Campos - SP';
                            $img1 = '../img/compras/1.png';
                            include('../estruturas/card-compra/card-anuncios.php'); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="row flex-grow-1 d-flex align-items-center <?php if ($qtd_resultados > 0) {echo 'd-none';}?>">
                    <div class="text-center text-muted">
                        <p style="font-size: calc(2rem + 1.5vw) !important;"><i class="bi bi-x-circle-fill"></i></p>
                        <h4 class="mb-0">Nenhuma anúncio feito ainda</h4>
                        <p>Gerencie todos os seus anúncios</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
<script src="../script.js"></script>

</html>