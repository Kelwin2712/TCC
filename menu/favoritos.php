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

$id = $_SESSION['id'];

$sql = "SELECT carros.*, marcas.nome as marca_nome FROM favoritos fav  INNER JOIN anuncios_carros carros ON fav.anuncio_id = carros.id INNER JOIN marcas ON carros.marca = marcas.id WHERE fav.usuario_id = $id";
$resultado = mysqli_query($conexao, $sql);

$carros = [];

$qtd_resultados = mysqli_num_rows($resultado) ?? 0;

while ($linha = mysqli_fetch_array($resultado)) {
    $carros[] = $linha;
}
// keep DB connection open to fetch fotos for each favorite when rendering
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Favoritos</title>
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
    <link rel="icon" type="png" href="../img/logo-oficial.png">
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<style>
    .card-compra .btn {
        z-index: 3;
    }
</style>

<body class="overflow-x-hidden">
    <?php include '../estruturas/modal/loja-modal.php';?>
    <?php include '../estruturas/alert/alert.php' ?>
    <main class="container-fluid d-flex vh-100 p-0">
        <?php $selected = 'fav';
        include_once '../estruturas/sidebar/sidebar.php' ?>
        <div class="col" style="margin-left: calc(200px + 5vw);">
            <div class="container-fluid p-5 d-flex flex-column h-100 overflow-auto">
                <div class="row mb-5">
                    <h2 class="pb-2 fw-semibold mb-0">Favoritos</h2>
                    <p class="text-muted">Veja todos os seus veículos favoritos</p>

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
                            $km = $carro['quilometragem'];
                            $cor = $carro['cor'];
                            $troca = $carro['aceita_troca'];
                            $revisao = $carro['revisao'];
                            $id = $carro['id'];
                            $loc = 'São José dos Campos - SP';
                            // fetch first photo for this anuncio
                            $img1 = '../img/compras/1.png';
                            $qr = mysqli_query($conexao, "SELECT caminho_foto FROM fotos_carros WHERE carro_id = $id ORDER BY `ordem` ASC LIMIT 1");
                            if ($qr && mysqli_num_rows($qr) > 0) {
                                $r = mysqli_fetch_assoc($qr);
                                if (!empty($r['caminho_foto'])) $img1 = '../img/anuncios/carros/' . $id . '/' . $r['caminho_foto'];
                            }
                            include('../estruturas/card-compra/card-favoritos.php'); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="vazio" class="row flex-grow-1 d-flex align-items-center <?php if ($qtd_resultados > 0) {
                                                                            echo 'd-none';
                                                                        } ?>">
                    <div class="text-center text-muted">
                        <p style="font-size: calc(2rem + 1.5vw) !important;"><i class="bi bi-heartbreak-fill"></i></p>
                        <h4 class="mb-0">Nada adicionado aos favoritos ainda</h4>
                        <p>Ache um veículo que você gosta e clique no <span><i class="bi bi-heart"></i></span></p>
                    </div>
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

  $(document).on('click', 'button.favoritar', function() {
    const card = $(this).parents('.card').parent();
    const row = card.parent();
    let anuncioID = $(this).data('anuncio');

    console.log(anuncioID)

    $.post('../controladores/veiculos/favoritar-veiculo.php', {
      usuario: <?= $_SESSION['id'] ?>,
      anuncio: anuncioID
    }, function(resposta) {
      if (resposta == 'desfav') {
        card.fadeOut('fast', function() {
            card.remove();
            if (row.children().length == 0) {
                $('#vazio').removeClass('d-none');
            }
        });
      }
    });
  });
</script>
</html>