<?php
session_start();
include('../controladores/conexao_bd.php');

if (!isset($_SESSION['nome'])) {
    header("Location: index.php");
}

$id_veiculo = $_GET['id'];


$id = $_SESSION['id'];

$sql = "SELECT * FROM anuncios_carros WHERE id = $id_veiculo";
$resultado = mysqli_query($conexao, $sql);

if (mysqli_num_rows($resultado) > 0) {
    $linha = mysqli_fetch_array($resultado);
    if ($linha['id_vendedor'] == $id) {
        $estado_local = $linha['estado_local'];
        $cidade = $linha['cidade'];
        $marca = $linha['marca'];
        $modelo = $linha['modelo'];
        $versao = $linha['versao'];
        $carroceria = $linha['carroceria'];
        $preco = $linha['preco'];
        $quilometragem = $linha['quilometragem'];
        $ano_fabricacao = $linha['ano_fabricacao'];
        $ano_modelo = $linha['ano_modelo'];
        $propulsao = $linha['propulsao'];
        $combustivel = $linha['combustivel'];
        $blindagem = $linha['blindagem'];
        $id_vendedor = $linha['id_vendedor'];
        $imagens = $linha['imagens'];
        $leilao = $linha['leilao'];
        $portas_qtd = $linha['portas_qtd'];
        $acentos_qtd = $linha['acentos_qtd'];
        $placa = $linha['placa'];
        $data_criacao = $linha['data_criacao'];
        $cor = $linha['cor'];
        $quant_proprietario = $linha['quant_proprietario'];
        $revisao = $linha['revisao'];
        $vistoria = $linha['vistoria'];
        $sinistro = $linha['sinistro'];
        $ipva = $linha['ipva'];
        $licenciamento = $linha['licenciamento'];
        $estado_conservacao = $linha['estado_conservacao'];
        $uso_anterior = $linha['uso_anterior'];
        $aceita_troca = $linha['aceita_troca'];
        $email = $linha['email'];
        $telefone = $linha['telefone'];
    } else {
        header('Location: anuncios.php');
    }
}

$sql = "SELECT value, nome FROM marcas";
$resultado = mysqli_query($conexao, $sql);

$marcas = [];

while ($linha = mysqli_fetch_array($resultado)) {
    $marcas[] = $linha;
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
<style>
    .overlay {
        cursor: pointer;
    }
</style>
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
                </div>
                <div class="row d-flex align-items-stretch">
                    <div class="col-4">
                        <h5>Dados do veículo</h5>
                        <p class="text-muted">Informe os dados do veículo</p>
                    </div>

                    <div class="col d-flex flex-column justify-content-between">
                        <div class="row row-cols-2 row-gap-3 mb-3">
                            <div class="col">
                                <label for="nome-input" class="form-label">Marca</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Modelo</label>
                                <input type="text" class="form-control shadow-sm" id="quilometragem-input" value="<?= $modelo ?>" name="quilometragem" placeholder="Informe a placa do veículo" required>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Ano de fabricação</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Ano do modelo</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Versão</label>
                                <input type="text" class="form-control shadow-sm" id="quilometragem-input" value="<?= $versao ?>" name="quilometragem" placeholder="Informe a placa do veículo" required>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Cor</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Carroceria</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Propulsão</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Combustível</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Quantidade de portas</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Quantidade de assentos</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Blindagem</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="nome-input" class="form-label">Placa</label>
                                <input type="text" class="form-control shadow-sm p-3 fs-4" id="quilometragem-input" value="<?= $placa ?>" name="quilometragem" placeholder="Informe a placa do veículo" required>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-5">
                <div class="row d-flex align-items-stretch">
                    <div class="col-4">
                        <h5>Imagens</h5>
                        <p class="text-muted">Informe a condição do veículo</p>
                    </div>

                    <div class="col d-flex justify-content-between">
                        <div class="row row-cols-5 row-gap-3 mb-3 w-100">
                            <div class="col">
                                <div id="add-card" class="ratio ratio-16x9 border border-secondary text-secondary border-1 bg-secondary-subtle rounded-3">
                                    <div class="d-flex flex-column justify-content-center align-items-center text-center w-100 h-100">
                                        <span><i class="bi bi-plus-lg"></i></span>
                                        <span id="add-text" style="display: none;">Adicionar imagem</span>
                                    </div>
                                </div>
                            </div>
                            <?php for ($i = 0; $i <= 8; $i++): ?>
                                <div class="col">
                                    <div class="ratio ratio-16x9 position-relative">
                                        <div class="position-absolute rounded-3 bg-black w-100 h-100 translate-middle start-50 top-50 z-1 bg-opacity-25 text-white d-flex align-items-center justify-content-center text-center fs-2 overlay d-none">
                                            <i class="bi bi-trash"></i>
                                        </div>
                                        <img src="../img/compras/1.png" class="img-fluid object-fit-cover rounded-3">
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <hr class="my-5">
                <div class="row d-flex align-items-stretch">
                    <div class="col-4">
                        <h5>Condição e uso</h5>
                        <p class="text-muted">Informe a condição do veículo</p>
                    </div>

                    <div class="col d-flex flex-column justify-content-between">
                        <div class="row row-cols-2 row-gap-3 mb-3">
                            <div class="col">
                                <label for="nome-input" class="form-label">Quilometragem</label>
                                <input type="text" class="form-control shadow-sm" id="quilometragem-input" value="<?= $quilometragem ?>" name="quilometragem" placeholder="Informe a placa do veículo" required>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Quantidade de proprietários</label>
                                <input type="text" class="form-control shadow-sm" id="quilometragem-input" value="<?= $modelo ?>" name="quilometragem" placeholder="Informe a placa do veículo" required>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Revisão (Últimos 12 meses)</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Vistoria</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Histórico de sinistro</label>
                                <input type="text" class="form-control shadow-sm" id="quilometragem-input" value="<?= $versao ?>" name="quilometragem" placeholder="Informe a placa do veículo" required>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">IPVA pago</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Licenciamento</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nome-input" class="form-label">Estado de consevação</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="nome-input" class="form-label">Uso anterior</label>
                                <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                    <?php foreach ($marcas as $marca_o): ?>
                                        <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-dark float-end shadow-sm" disabled>Salvar alterações&nbsp;&nbsp;<i class="bi bi-floppy"></i></button>
                    </div>
                </div>
                <hr class="my-5">
                <div class="row d-flex align-items-center flex-nowrap">
                    <div class="col">
                        <h5>Deletar anúncio</h5>
                        <p class="text-muted">Delete esse anúncio permanentemente</p>
                    </div>
                    <div class="col-auto d-flex flex-column justify-content-between">
                        <div class="row row-gap-3 mb-3">
                            <div class="col">
                                <button class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#delete-modal">Deletar anúncio&nbsp;<i class="bi bi-trash"></i></button>
                                <div class="modal fade" id="delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="../controladores/veiculos/deletar-anuncio.php" class="modal-content" method="POST">
                                            <div class="modal-body p-5">
                                                <div class="bg-danger-subtle rounded-circle d-flex justify-content-center align-items-center mb-3 mx-auto fs-5" style="width: 60px; height: 60px;">
                                                    <i class="bi bi-trash"></i>
                                                </div>
                                                <input type="text" name="id" class="d-none" value="<?= $id_veiculo ?>">
                                                <div class="text-center">
                                                    <h4>Você tem certeza?</h4>
                                                    <p class="text-muted">Essa ação não pode ser desfeita. Todos os dados serão perdidos.</p>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex flex-column border-top-0">
                                                <button id="delete-btn" type="submit" class="btn btn-danger w-100">Deletar conta</button>
                                                <button type="button" class="btn bg-body-secondary w-100" data-bs-dismiss="modal">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row flex-grow-1 d-flex align-items-center d-none">
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
<script src="script.js"></script>
<script>
    $(function() {
        const addCard = $('#add-card');
        const addText = $('#add-text');
        const imgCard = $('.overlay').parent();

        addCard.on('mouseenter', function() {
            addText.stop(true, true).slideDown(150);
        })

        addCard.on('mouseleave', function() {
            addText.stop(true, true).slideUp(150);
        })

        imgCard.hover(
            function() {
                $(this).find('.overlay').stop(true, true).fadeIn(150).removeClass("d-none");
            },
            function() {
                $(this).find('.overlay').stop(true, true).fadeOut(150, function() {
                    $(this).addClass("d-none");
                });
            }
        );

        imgCard.on('click', function () {
            $(this).parent().fadeOut(250, function() {
                $(this).remove();
            })
        })

    })
</script>

</html>