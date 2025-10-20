<?php
session_start();
include('../controladores/conexao_bd.php');

if (!isset($_SESSION['id'])) {
    header("Location: ../");
    exit;
}

$id_veiculo = $_GET['id'];
$_SESSION['id_veiculo_edit'] = $id_veiculo;


$id = $_SESSION['id'];

$sql = "SELECT carros.*, marcas.nome as marca_nome FROM anuncios_carros carros INNER JOIN marcas ON carros.marca = marcas.id WHERE carros.id = $id_veiculo";
$resultado = mysqli_query($conexao, $sql);

if (mysqli_num_rows($resultado) > 0) {
    $linha = mysqli_fetch_array($resultado);
    if ($linha['id_vendedor'] == $id) {
        $estado_local = $linha['estado_local'];
        $cidade = $linha['cidade'];
        $marca = $linha['marca'];
        $marca_nome = $linha['marca_nome'];
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
        $assentos_qtd = $linha['assentos_qtd'];
        $placa = $linha['placa'];
        $garantia = $linha['garantia'];
        $data_criacao = $linha['data_criacao'];
        $cor = $linha['cor'];
        $proprietario = $linha['quant_proprietario'];
        $revisao = $linha['revisao'];
        $vistoria = $linha['vistoria'];
        $sinistro = $linha['sinistro'];
        $ipva = $linha['ipva'];
        $licenciamento = $linha['licenciamento'];
        $estado_conservacao = $linha['estado_conservacao'];
        $uso_anterior = $linha['uso_anterior'];
        $troca = $linha['aceita_troca'];
        $email = $linha['email'];
        $telefone = $linha['telefone'];
        $ativo = $linha['ativo'];
    } else {
        header('Location: anuncios.php');
    }
}

$sql = "SELECT * FROM marcas";
$resultado = mysqli_query($conexao, $sql);

$marcas = [];

while ($linha = mysqli_fetch_assoc($resultado)) {
    $marcas[] = $linha;
}

$sql = "SELECT * FROM cores";
$resultado = mysqli_query($conexao, $sql);

$cores = [];

while ($linha = mysqli_fetch_assoc($resultado)) {
    $cores[] = $linha;
}

$sql = "SELECT * FROM carrocerias";
$resultado = mysqli_query($conexao, $sql);

$carrocerias = [];

while ($linha = mysqli_fetch_assoc($resultado)) {
    $carrocerias[] = $linha;
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
    .upload-foto:hover {
        background-color: #ccc !important;
        transition: all 0.25s ease;
    }

    /* Switch estilo Uiverse - Versão Fahren */
    .form-switch {
        position: relative;
        display: inline-block;
    }

    #anuncio-ativo {
        display: none;
    }

    .form-switch label {
        position: relative;
        padding: 10px 20px;
        background-color: #6c757d;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: white;
        font-size: 0.9em;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        min-width: 100px;
    }

    .form-switch label .bi-power {
        transition: all 0.3s ease;
        font-size: 1em;
    }

    /* Estado DESLIGADO (inativo) */
    #anuncio-ativo:not(:checked)+label {
        background-color: #6c757d;
    }

    /* Estado LIGADO (ativo) */
    #anuncio-ativo:checked+label {
        background-color: #198754;
        /* Verde do Bootstrap success */
    }

    /* Texto dinâmico */
    #anuncio-ativo:not(:checked)+label::before {
        content: "Inativo";
    }

    #anuncio-ativo:checked+label::before {
        content: "Ativo";
    }

    /* Remove o texto original do botão */
    .form-switch label .btn-text {
        display: none;
    }
</style>

<body class="overflow-x-hidden">
    <?php include '../estruturas/modal/loja-modal.php'; ?>
    <?php include '../estruturas/alert/alert.php' ?>
    <main class="container-fluid d-flex vh-100 p-0">
        <?php $selected = 'ad';
        include_once '../estruturas/sidebar/sidebar.php' ?>
        <div class="col" style="margin-left: calc(200px + 5vw);">
            <div class="container-fluid p-5 d-flex flex-column h-100 overflow-auto">
                <form action="../controladores/veiculos/mudar-infos-carro.php" method="POST">
                    <div class="row">
                        <h2 class="pb-2 fw-semibold mb-0">Meus anúncios</h2>
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="anuncios.php" class="link-dark link-underline-opacity-0 link-underline-opacity-100-hover">Anúncios</a></li>
                                <li class="breadcrumb-item active text-dark fw-semibold text-uppercase" aria-current="page"><?= "$marca_nome $modelo" ?></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row d-flex align-items-stretch mt-5">
                        <div class="col-4">
                            <h5>Preço e negociação</h5>
                            <p class="text-muted">Defina o valor e formas de contato</p>
                        </div>

                        <div class="col d-flex flex-column justify-content-between">
                            <div class="row row-cols-2 row-gap-3 mb-3">
                                <div class="col">
                                    <label for="preco-input" class="form-label">Preço</label>
                                    <div class="position-relative">
                                        <span class="position-absolute translate-middle-y top-50" style="margin-left: .75rem;">R$</span>
                                        <input type="text" class="form-control shadow-sm preco-input" style="padding-left: 2.25rem" id="preco-input" value="<?= number_format($preco, 2, ',', '.'); ?>" name="preco" placeholder="Informe o preço do veículo" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="troca-select" class="form-label">Aceita troca</label>
                                    <select class="form-select shadow-sm" id="troca-select" aria-label="Default select example" name="troca" required>
                                        <option value="0" selected>Não</option>
                                        <option value="1">Sim</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="email-input" class="form-label">Email de contato</label>
                                    <input type="gmail" class="form-control shadow-sm" id="email-input" value="<?= $email ?>" name="email" placeholder="Informe a placa do veículo" required>
                                </div>
                                <div class="col">
                                    <label for="telefone-input" class="form-label">Telefone de contato</label>
                                    <input type="text" class="form-control shadow-sm text-capitalize" id="telefone-input" value="<?= $telefone ?>" name="telefone" placeholder="Informe a placa do veículo" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-5">
                    <div class="row d-flex align-items-stretch">
                        <div class="col-4">
                            <h5>Dados do veículo</h5>
                            <p class="text-muted">Informe os dados do veículo</p>
                        </div>

                        <div class="col d-flex flex-column justify-content-between">
                            <div class="row row-cols-2 row-gap-3 mb-3">
                                <div class="col">
                                    <label for="marca-select" class="form-label">Marca</label>
                                    <select class="form-select shadow-sm" id="marca-select" aria-label="Default select example" name="marca" required>
                                        <?php foreach ($marcas as $marca_o): ?>
                                            <option value="<?= $marca_o['id'] ?>" <?php if ($marca == $marca_o['id']) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $marca_o['nome'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="modelo-select" class="form-label">Modelo</label>
                                    <input type="text" class="form-control shadow-sm text-capitalize" id="modelo-select" value="<?= $modelo ?>" name="modelo" placeholder="Informe a placa do veículo" required>
                                </div>
                                <div class="col">
                                    <label for="fabr-select" class="form-label">Ano de fabricação</label>
                                    <select class="form-select shadow-sm" id="fabr-select" aria-label="Default select example" name="ano_fabricacao" required>
                                        <?php
                                        $quantidade = date('Y') - 1930;
                                        for ($i = 0; $i <= $quantidade; $i++): ?>
                                            <option value="<?= date('Y') - $i ?>" <?php if ($ano_fabricacao == date('Y') - $i) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= date('Y') - $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="ano-select" class="form-label">Ano do modelo</label>
                                    <select class="form-select shadow-sm" id="ano-select" aria-label="Default select example" name="ano_modelo" required>
                                        <?php
                                        $quantidade = date('Y') - 1930;
                                        for ($i = $ano_fabricacao + 1; $i >= $ano_fabricacao; $i--): ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="versao-input" class="form-label">Versão</label>
                                    <input type="text" class="form-control shadow-sm text-capitalize" id="versao-input" value="<?= $versao ?>" name="versao" placeholder="Informe a placa do veículo" required>
                                </div>
                                <div class="col">
                                    <label for="cor-select" class="form-label">Cor</label>
                                    <select class="form-select shadow-sm" id="cor-select" aria-label="Default select example" name="cor" required>

                                        <?php foreach ($cores as $cor_o): ?>
                                            <option value="<?= $cor_o['id'] ?>" <?php if ($cor == $cor_o['id']) {
                                                                                    echo 'selected';
                                                                                } ?>><?= $cor_o['nome'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="carroceria-select" class="form-label">Carroceria</label>
                                    <select class="form-select shadow-sm" id="carroceria-select" aria-label="Default select example" name="carroceria" required>
                                        <?php foreach ($carrocerias as $carroceria_o): ?>
                                            <option value="<?= $carroceria_o['id'] ?>" <?php if ($carroceria == $carroceria_o['id']) {
                                                                                            echo 'selected';
                                                                                        } ?>><?= $carroceria_o['nome'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="propulsao-select" class="form-label">Propulsão</label>
                                    <select class="form-select shadow-sm" id="propulsao-select" aria-label="Default select example" name="propulsao" required>
                                        <?php foreach ($marcas as $marca_o): ?>
                                            <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                            echo 'selected';
                                                                                        } ?>><?= $marca_o['nome'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="combustivel-select" class="form-label">Combustível</label>
                                    <select class="form-select shadow-sm" id="combustivel-select" aria-label="Default select example" name="combustivel" required>
                                        <?php foreach ($marcas as $marca_o): ?>
                                            <option value="<?= $marca_o['value'] ?>" <?php if ($marca == $marca_o['value']) {
                                                                                            echo 'selected';
                                                                                        } ?>><?= $marca_o['nome'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="portas-select" class="form-label">Quantidade de portas</label>
                                    <select class="form-select shadow-sm" id="portas-select" aria-label="Default select example" name="portas_qtd" required>
                                        <option value="1">1 porta</option>
                                        <option value="2">2 portas</option>
                                        <option value="3">3 portas</option>
                                        <option value="4">4 portas</option>
                                        <option value="5">Mais de 4 portas</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="assentos-select" class="form-label">Quantidade de assentos</label>
                                    <select class="form-select shadow-sm" id="assentos-select" aria-label="Default select example" name="assentos_qtd" required>
                                        <option value="1">1 assento</option>
                                        <option value="2">2 assentos</option>
                                        <option value="3">3 assentos</option>
                                        <option value="4">4 assentos</option>
                                        <option value="5">5 assentos</option>
                                        <option value="6">6 ou mais assentos</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="blindagem-select" class="form-label">Blindagem</label>
                                    <select class="form-select shadow-sm" id="blindagem-select" aria-label="Default select example" name="blindagem" required>
                                        <option value="0">Sem blindagem</option>
                                        <option value="1">Com blindagem</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="placa-input" class="form-label">Placa</label>
                                    <input type="text" class="form-control shadow-sm" id="placa-input" value="<?= $placa ?>" name="placa" placeholder="Informe a placa do veículo" required>
                                </div>
                                <div class="col">
                                    <label for="garantia-select" class="form-label">Garantia</label>
                                    <select class="form-select shadow-sm" id="garantia-select" aria-label="Default select example" name="garantia" required>
                                        <option value="0">Sem garantia</option>
                                        <option value="1">1 mês</option>
                                        <option value="3">3 meses</option>
                                        <option value="6">6 meses</option>
                                        <option value="12">12 meses ou mais</option>
                                    </select>
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
                            <div class="row row-cols-3 row-cols-xxl-5 row-gap-3 mb-3 w-100">
                                <div class="col">
                                    <label for="foto" class="upload-foto ratio ratio-16x9 border border-secondary-subtle fs-2 text-secondary text-center border-1 bg-secondary-subtle rounded-3" style="cursor: pointer;">
                                        <div class="d-flex flex-column justify-content-center align-items-center w-100 h-100">
                                            <span><i class="bi bi-plus-lg"></i></span>
                                        </div>
                                    </label>

                                    <input type="file" id="foto" name="foto" accept="image/*" style="display:none;">
                                </div>

                                <?php for ($i = 0; $i <= 8; $i++): ?>
                                    <div class="col">
                                        <div class="ratio ratio-16x9 position-relative">
                                            <div class="position-absolute rounded-3 bg-black w-100 h-100 translate-middle start-50 top-50 z-1 bg-opacity-25 text-white d-flex align-items-center justify-content-center text-center fs-2 overlay d-none">
                                                <i class="bi bi-trash"></i>
                                            </div>
                                            <img src="../img/compras/1.png" class="img-fluid object-fit-cover shadow-sm rounded-3">
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
                                    <label for="condicao-select" class="form-label">Condição/Quilometragem</label>
                                    <div class="row">
                                        <div class="col">
                                            <select class="form-select shadow-sm" id="condicao-select" aria-label="Default select example" name="condicao" required>
                                                <option value="N">Novo</option>
                                                <option value="U" <?php if ($quilometragem > 0) {
                                                                        echo 'selected';
                                                                    } ?>>Usado</option>
                                            </select>
                                        </div>
                                        <div class="col-8" style="display: none;">
                                            <input type="text" class="form-control" id="quilometragem-input" value="<?= $quilometragem ?> km" name="quilometragem" placeholder="Informe a quilometragem do veículo" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="proprietario-select" class="form-label">Quantidade de proprietários</label>
                                    <select class="form-select shadow-sm" id="proprietario-select" aria-label="Default select example" name="proprietario" required>
                                        <option value="1">1° proprietário</option>
                                        <option value="2">2° proprietário</option>
                                        <option value="3">3° proprietário</option>
                                        <option value="4">4° proprietário</option>
                                        <option value="5">5° proprietário ou mais</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="revisao-select" class="form-label">Revisão (Últimos 12 meses)</label>
                                    <select class="form-select shadow-sm" id="revisao-select" aria-label="Default select example" name="revisao" required>
                                        <option value="0">Nenhuma</option>
                                        <option value="1">1 revisão</option>
                                        <option value="2">2 revisões</option>
                                        <option value="3">3 revisões</option>
                                        <option value="4">4 revisões</option>
                                        <option value="5">5 revisões ou mais</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="vistoria-select" class="form-label">Vistoria</label>
                                    <select class="form-select shadow-sm" id="vistoria-select" aria-label="Default select example" name="vistoria" required>
                                        <option value="F">Feita</option>
                                        <option value="V">Vencida</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="sinistro-select" class="form-label">Histórico de sinistro</label>
                                    <select class="form-select shadow-sm" id="sinistro-select" aria-label="Default select example" name="sinistro" required>
                                        <option value="0">Nenhum</option>
                                        <option value="L">Leve</option>
                                        <option value="M">Moderado</option>
                                        <option value="G">Grave</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="ipva-select" class="form-label">IPVA pago</label>
                                    <select class="form-select shadow-sm" id="ipva-select" aria-label="Default select example" name="ipva" required>
                                        <option value="D">Em dia</option>
                                        <option value="A">Atrasado</option>
                                        <option value="I">Isento</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="licenciamento-select" class="form-label">Licenciamento</label>
                                    <select class="form-select shadow-sm" id="licenciamento-select" aria-label="Default select example" name="licenciamento" required>
                                        <option value="D">Em dia</option>
                                        <option value="V">Vencido</option>
                                        <option value="T">Em processo de transferência</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="conservacao-select" class="form-label">Estado de consevação</label>
                                    <select class="form-select shadow-sm" id="conservacao-select" aria-label="Default select example" name="conservacao" required>
                                        <option value="4">Excelente</option>
                                        <option value="3">Bom</option>
                                        <option value="2">Regular</option>
                                        <option value="1">Ruim</option>
                                    </select>
                                </div>
                                <div class="col-12" id="uso-row" style="display: none;">
                                    <label for="uso-select" class="form-label">Uso anterior</label>
                                    <select class="form-select shadow-sm" id="uso-select" aria-label="Default select example" name="uso">
                                        <option value="">Nenhum uso</option>
                                        <option value="P">Uso particular</option>
                                        <option value="A">Carro de aluguel</option>
                                        <option value="T">Trasporte de passageiros</option>
                                        <option value="E">Frota empresarial</option>
                                        <option value="O">Outro</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-dark float-end shadow-sm">Salvar alterações  <i class="bi bi-floppy"></i></button>
                        </div>
                    </div>
                    <hr class="my-5">
                </form>
                <div class="row d-flex align-items-stretch mt-5">
                    <div class="col-4">
                        <h5>Anúncio ativo</h5>
                        <p class="text-muted">Defina se o anúncio está ativo</p>
                    </div>

                    <div class="col d-flex flex-column justify-content-between">
                        <div class="ms-auto">
                            <div class="form-switch">
                                <input type="checkbox" id="anuncio-ativo" name="ativo" <?= $ativo == 'A' ? 'checked' : '' ?> style="display: none;">
                                <label class="rounded-4" for="anuncio-ativo">
                                    <span class="btn-text">Ativo</span>
                                    <i class="bi bi-power"></i>
                                </label>
                            </div>
                        </div>

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
                                <button class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#delete-modal">Deletar anúncio<i class="bi bi-trash"></i></button>
                                <div class="modal fade" id="delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="../controladores/veiculos/deletar-anuncio.php" class="modal-content" method="POST">
                                            <div class="modal-body p-5">
                                                <div class="bg-danger-subtle rounded-circle d-flex text-danger justify-content-center align-items-center mb-3 mx-auto fs-5" style="width: 60px; height: 60px;">
                                                    <i class="bi bi-trash"></i>
                                                </div>
                                                <input type="text" name="id" class="d-none" value="<?= $id_veiculo ?>">
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
<script src="../script.js"></script>
<script>
    $(function() {
        const imgCard = $('.overlay').parent();

        $("#cor-select option[value='<?= $cor ?>']").prop('selected', true);
        $("#portas-select option[value='<?= $portas_qtd ?>']").prop('selected', true);
        $("#revisao-select option[value='<?= $revisao ?>']").prop('selected', true);
        $("#vistoria-select option[value='<?= $vistoria ?>']").prop('selected', true);
        $("#sinistro-select option[value='<?= $sinistro ?>']").prop('selected', true);
        $("#assentos-select option[value='<?= $assentos_qtd ?>']").prop('selected', true);
        $("#ipva-select option[value='<?= $ipva ?>']").prop('selected', true);
        $("#licenciamento-select option[value='<?= $licenciamento ?>']").prop('selected', true);
        $("#conservacao-select option[value='<?= $estado_conservacao ?>']").prop('selected', true);
        $("#uso-select option[value='<?= $uso_anterior ?>']").prop('selected', true);
        $("#blindagem-select option[value='<?= $blindagem ?>']").prop('selected', true);
        $("#garantia-select option[value='<?= $garantia ?>']").prop('selected', true);
        $("#proprietario-select option[value='<?= $proprietario ?>']").prop('selected', true);
        $("#troca-select option[value='<?= $troca ?>']").prop('selected', true);

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

        imgCard.on('click', function() {
            $(this).parent().fadeOut(250, function() {
                $(this).remove();
            })
        })
        const kmInput = $('#quilometragem-input');
        const condicaoSelect = $('#condicao-select');

        const usoRow = $('#uso-row');
        const usoSelect = $('#uso-select');

        function slideDown() {
            usoRow.slideDown(150);
            usoSelect.prop('required', true);
        };

        function slideUp() {
            usoRow.slideUp(100);
            usoSelect.prop('required', false);
            usoSelect.val('');
        };

        function condicaoFunc(e) {
            if (e.val() === 'N') {
                kmInput.val('0 km');
                kmInput.parent().hide();
                slideUp();
            } else {
                kmInput.parent().show();
                slideDown();
            }
        };

        condicaoFunc(condicaoSelect);

        condicaoSelect.on('change', function() {
            condicaoFunc($(this));
        });

        kmInput.on('focus', function() {
            kmInput.prop('required', false);
            if ($(this).val() === '0 km') {
                $(this).val('');
            } else {
                const currentValue = $(this).val().replace(' km', '').replace(/\./g, '');
                $(this).val(currentValue);
            }
        });

        kmInput.on('blur', function() {
            kmInput.prop('required', true);
            if ($(this).val() === '') {
                $(this).val('0 km');
            } else {
                let numericValue = $(this).val().replace(/\D/g, '');
                if (numericValue === '') {
                    numericValue = '0';
                }
                if (numericValue > 0) {
                    slideDown();
                } else {
                    slideUp();
                }
                const formattedValue = parseInt(numericValue, 10).toLocaleString('pt-BR') + ' km';
                $(this).val(formattedValue);
            }
        });

        $('#anuncio-ativo').on('change', function() {
            let ativoVal = $(this).prop('checked');

            console.log('Novo status:', ativoVal);

            $.post('../controladores/veiculos/ativar-veiculo.php', {
                    anuncio: <?= $_GET['id'] ?>,
                    ativo: ativoVal ? 1 : 0 // Envia 1 ou 0
                })
                .done(function(response) {
                    console.log('Status atualizado:', response);
                })
                .fail(function() {
                    console.error('Erro ao atualizar status');
                    // Reverter visualmente se der erro
                    $('#anuncio-ativo').prop('checked', !ativoVal);
                });
        });
    })
</script>

</html>