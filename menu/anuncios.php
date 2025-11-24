<?php
session_start();
include('../controladores/conexao_bd.php');

if (!isset($_SESSION['id'])) {
    header("Location: ../");
    exit;
}

$tipo = $_GET['tipo'] ?? 'carro';
$codicao = $_GET['codicao'] ?? 'usado';
$categoria = $_GET['categoria'] ?? null;
$vendedor = $_GET['vendedor'] ?? null;
$vendedor_img = $_GET['vendedor_img'] ?? null;
$vendedor_est = $_GET['vendedor_est'] ?? null;

$page = $_GET['page'] ?? 1;

$id = $_SESSION['id'];

 $sql = "SELECT carros.*, marcas.nome as marca_nome FROM anuncios_carros carros INNER JOIN marcas ON carros.marca = marcas.id WHERE carros.id_vendedor = $id AND carros.tipo_vendedor = 0";
$resultado = mysqli_query($conexao, $sql);

$carros = [];

$qtd_resultados = mysqli_num_rows($resultado) ?? 0;

while ($linha = mysqli_fetch_array($resultado)) {
    $carros[] = $linha;
}
// keep DB connection open because we'll query fotos for each anuncio below
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meus anúncios</title>
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
    <link rel="icon" type="png" href="../img/logo-oficial.png">
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<style>
    .card-anuncio .card-footer {
        z-index: 3;
    }
</style>

<body class="overflow-x-hidden">
    <?php include '../estruturas/modal/loja-modal.php';?>
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
                    <a href="anuncios.php" class="btn text-bg-dark rounded-pill">Anúncios</a>
                    <a href="reservas.php" class="btn border border-dark rounded-pill">Reservas</a>
                    <a href="../vender-placa.php" class="btn btn-dark ms-auto rounded-pill shadow-sm">+ Criar novo anúncio</a>
                </div>
                <div class="row row-cols-1 row-cols-xl-2 g-3">
                    <?php foreach ($carros as $carro): ?>
                        <div class="col">
                            <?php
                            $marca = $carro['marca_nome'];
                            $modelo = $carro['modelo'];
                            $versao = $carro['versao'];
                            $preco = $carro['preco'];
                            $ano = $carro['ano_fabricacao'] . '/' . $carro['ano_modelo'];
                            // format quilometragem with thousands separator for display (e.g. 1.000)
                            $km = is_numeric($carro['quilometragem']) ? number_format((int)$carro['quilometragem'], 0, ',', '.') : $carro['quilometragem'];
                            $cor = $carro['cor'];
                            $troca = $carro['aceita_troca'];
                            $revisao = $carro['revisao'];
                            $id = $carro['id'];
                            $loc = $carro['cidade'] . ' - '  . $carro['estado_local'];
                            // fetch first photo for this anuncio
                            $img1 = '../img/compras/1.png';
                            $qr = mysqli_query($conexao, "SELECT caminho_foto FROM fotos_carros WHERE carro_id = $id ORDER BY `ordem` ASC LIMIT 1");
                            if ($qr && mysqli_num_rows($qr) > 0) {
                                $r = mysqli_fetch_assoc($qr);
                                if (!empty($r['caminho_foto'])) $img1 = '../img/anuncios/carros/' . $id . '/' . $r['caminho_foto'];
                            }
                            include('../estruturas/card-compra/card-anuncios.php'); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="row flex-grow-1 d-flex align-items-center <?php if ($qtd_resultados > 0) {
                                                                                echo 'd-none';
                                                                            } ?>">
                    <div class="text-center text-muted">
                        <p style="font-size: calc(2rem + 1.5vw) !important;"><i class="bi bi-x-circle-fill"></i></p>
                        <h4 class="mb-0">Nenhuma anúncio feito ainda</h4>
                        <p>Gerencie todos os seus anúncios</p>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="../controladores/veiculos/deletar-anuncio.php" class="modal-content" method="POST">
                        <div class="modal-body p-5">
                            <div class="bg-danger-subtle rounded-circle d-flex text-danger justify-content-center align-items-center mb-3 mx-auto fs-5" style="width: 60px; height: 60px;">
                                <i class="bi bi-trash"></i>
                            </div>
                            <input type="text" name="id" id="id-delete" class="d-none" value="0">
                            <div class="text-center">
                                <h4>Você tem certeza?</h4>
                                <p class="text-muted">Essa ação não pode ser desfeita. Todos os dados serão perdidos.</p>
                            </div>
                        </div>
                        <div class="modal-footer d-flex flex-column border-top-0">
                            <button id="delete-btn" type="submit" class="btn btn-danger w-100">Deletar anúncio</button>
                            <button type="button" class="btn bg-body-secondary w-100" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
<?php if (isset($conexao)) { mysqli_close($conexao); } ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
<script src="../script.js"></script>
<script>
    $(function() {
        const buttonDelete = $('.bi-trash').parent();
        buttonDelete.on('click', function() {
            $('#id-delete').val($(this).data('id-delete'));
        })
    })
</script>
</html>