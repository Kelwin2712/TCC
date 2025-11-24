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
        $condicao = $linha['condicao'] ?? 'N';
        $descricao = $linha['descricao'];
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

// fetch fotos for this anuncio to prefill image slots
$photos = [];
$qr = mysqli_query($conexao, "SELECT caminho_foto FROM fotos_carros WHERE carro_id = $id_veiculo ORDER BY `ordem` ASC");
if ($qr) {
    while ($r = mysqli_fetch_assoc($qr)) $photos[] = $r['caminho_foto'];
}
// keep connection open for later actions; we'll close it at end of the file
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

    /* Drag & drop previews */
    .image-dropzone {
        border: 2px dashed #ced4da;
        padding: 0.5rem;
        border-radius: .5rem;
        transition: border-color .15s ease, background-color .15s ease;
        background-clip: padding-box;
    }

    .image-dropzone.dragover {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.03);
    }

    .img-item {
        position: relative;
    }

    /* circular delete button hidden by default, appears on hover/focus */
    .img-item .delete-mark {
        position: absolute;
        z-index: 10;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        width: 34px;
        height: 34px;
        display: none;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
        border: none;
        padding: 0;
        font-size: 1rem;
    }

    /* show delete icon only on hover/focus for accessibility */
    .img-item:hover .delete-mark,
    .img-item:focus-within .delete-mark {
        display: flex;
    }

    /* marked state: reduce image opacity (no green/highlight) */
    .img-item.marked img {
        opacity: 0.6;
        transition: opacity .12s ease;
    }

    /* remove previous new-badge usage (new previews match existing images) */
    .img-item .new-badge {
        display: none;
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

    /* Add-image tile (appear like other image cards but darker and with +) */
    .img-item.add-item .ratio {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.03);
        border-radius: .5rem;
        cursor: pointer;
        transition: background-color .15s ease, transform .12s ease;
        color: rgba(33, 37, 41, 0.8);
    }

    .img-item.add-item .ratio:hover {
        background-color: rgba(0, 0, 0, 0.06);
        transform: translateY(-2px);
    }

    .img-item.add-item .add-plus {
        font-size: 2rem;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        user-select: none;
    }

    .img-item.add-item:focus-within .ratio,
    .img-item.add-item:focus .ratio {
        outline: 3px solid rgba(13, 110, 253, 0.12);
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
                <form id="edit-anuncio-form" action="../controladores/veiculos/mudar-infos-carro.php" method="POST" enctype="multipart/form-data">
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
                                        <input type="text" class="form-control shadow-sm preco-input" style="padding-left: 2.25rem" id="preco-input" value="<?= number_format($preco, 0, ',', '.'); ?>" name="preco" placeholder="Informe o preço do veículo" required>
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
                                <div class="col">
                                    <label class="form-label">Estado</label>
                                    <select id="anuncio-estado" name="estado_local" class="form-select">
                                        <option value="">Selecione um estado...</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label">Cidade</label>
                                    <select id="anuncio-cidade" name="cidade" class="form-select">
                                        <option value="">Selecione um município...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal moved to after the main form to avoid nested forms -->
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
                                        <option value="">Selecione a propulsão</option>
                                        <option value="eletrico" <?php if ($propulsao === 'eletrico') echo 'selected'; ?>>Elétrico</option>
                                        <option value="combustao" <?php if ($propulsao === 'combustao') echo 'selected'; ?>>Combustão</option>
                                        <option value="hibrido" <?php if ($propulsao === 'hibrido') echo 'selected'; ?>>Híbrido</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="combustivel-select" class="form-label">Combustível</label>
                                    <select class="form-select shadow-sm" id="combustivel-select" aria-label="Default select example" name="combustivel" required>
                                        <option value="" selected hidden>Selecione o combustível</option>
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
                                        <option value="6">6 assentos</option>
                                        <option value="7">7 assentos</option>
                                        <option value="8">8 ou mais assentos</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="cambio-select" class="form-label">Câmbio</label>
                                    <select class="form-select shadow-sm" id="cambio-select" aria-label="Default select example" name="cambio" required>
                                        <option value="">Selecione o câmbio</option>
                                        <option value="A" <?php if (isset($linha) && $linha['cambio'] === 'A') echo 'selected'; ?>>Automático</option>
                                        <option value="M" <?php if (isset($linha) && $linha['cambio'] === 'M') echo 'selected'; ?>>Manual</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="blindagem-select" class="form-label">Blindagem</label>
                                    <select class="form-select shadow-sm" id="blindagem-select" aria-label="Default select example" name="blindagem" required>
                                        <option value="0" <?php if ($blindagem == 0) echo 'selected'; ?>>Sem blindagem</option>
                                        <option value="1" <?php if ($blindagem == 1) echo 'selected'; ?>>Com blindagem</option>
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
                                <!-- descricao editavel abaixo de placa/garantia -->
                                <div class="col-12 mt-3">
                                    <label for="descricao-input" class="form-label">Descrição do veículo</label>
                                    <textarea id="descricao-input" name="descricao" class="form-control" rows="4" maxlength="1000" placeholder="Descreva o veículo"><?= htmlspecialchars($descricao ?? '', ENT_QUOTES) ?></textarea>
                                    <div class="form-text text-end"><span id="desc-count"><?= mb_strlen($descricao ?? '') ?></span>/1000</div>
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
                            <div class="w-100">
                                <div class="d-flex justify-content-end align-items-center mb-2">
                                    <div>
                                        <small class="text-muted mb-2">Fotos existentes: <?= count($photos) ?></small>
                                    </div>
                                </div>

                                <div id="image-dropzone" class="image-dropzone">
                                    <input type="file" id="new-fotos-input" name="new_fotos[]" accept="image/*" multiple style="display:none;">
                                    <div id="images-grid" class="row row-cols-3 row-cols-xxl-5 row-gap-3 mb-0 g-3">
                                        <!-- Add-image tile: appears as the FIRST card among images -->
                                        <div class="col img-item add-item" role="button" tabindex="0" aria-label="Adicionar fotos">
                                            <div class="ratio ratio-16x9 position-relative">
                                                <div class="add-plus"><i class="bi bi-plus-lg"></i></div>
                                            </div>
                                        </div>
                                        <?php
                                        // render existing photos
                                        foreach ($photos as $p):
                                            $src = '../img/anuncios/carros/' . $id_veiculo . '/' . $p;
                                        ?>
                                            <div class="col img-item existing-item" data-filename="<?= htmlspecialchars($p, ENT_QUOTES) ?>">
                                                <div class="ratio ratio-16x9 position-relative">
                                                    <button type="button" class="m-1 delete-mark position-absolute top-0 end-0" title="Marcar para deletar"><i class="bi bi-trash"></i></button>
                                                    <img src="<?= $src ?>" class="img-fluid object-fit-cover shadow-sm rounded-3">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
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
                                                <option value="N" <?php if (($condicao ?? 'N') === 'N') {
                                                                        echo 'selected';
                                                                    } ?>>Novo</option>
                                                <option value="S" <?php if (($condicao ?? '') === 'S') {
                                                                        echo 'selected';
                                                                    } ?>>Seminovo</option>
                                                <option value="U" <?php if (($condicao ?? '') === 'U' || ($quilometragem > 0 && empty($condicao))) {
                                                                        echo 'selected';
                                                                    } ?>>Usado</option>
                                            </select>
                                        </div>
                                        <div class="col-8" style="display: none;">
                                            <input type="text" class="form-control" id="quilometragem-input" value="<?= ($quilometragem !== '' && is_numeric($quilometragem)) ? number_format((int)$quilometragem, 0, ',', '.') . ' km' : '0 km' ?>" name="quilometragem" placeholder="Informe a quilometragem do veículo" required>
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

                <!-- Modal de visualização/edição de reserva (movido para fora do form principal) -->
                <div class="modal fade" id="view-reserva-view-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewReservaViewLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewReservaViewLabel">Reserva</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <div class="modal-body">
                                <form id="view-reserva-edit-form">
                                    <input type="hidden" name="id" id="view-reserva-id">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Nome</label>
                                            <input type="text" id="view-reserva-nome" name="nome" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Telefone</label>
                                            <input type="tel" id="view-reserva-telefone" name="telefone" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" id="view-reserva-email" name="email" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Preferência de contato</label>
                                            <select id="view-reserva-preferencia" name="preferencia_contato" class="form-select">
                                                <option value="telefone">Telefone</option>
                                                <option value="whatsapp">WhatsApp</option>
                                                <option value="email">E-mail</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Data</label>
                                            <input type="date" id="view-reserva-data" name="data" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Hora</label>
                                            <input type="time" id="view-reserva-hora" name="hora" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Acompanhantes</label>
                                            <input type="number" id="view-reserva-acomp" name="acompanhantes_qtd" class="form-control" min="0">
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Estado</label>
                                            <select id="view-reserva-estado" name="estado" class="form-select">
                                                <option value="">Carregando estados...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Cidade</label>
                                            <select id="view-reserva-cidade" name="cidade" class="form-select">
                                                <option value="">Selecione um estado primeiro...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Bairro</label>
                                            <input type="text" id="view-reserva-bairro" name="bairro" class="form-control">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Rua</label>
                                            <input type="text" id="view-reserva-rua" name="rua" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Número</label>
                                            <input type="text" id="view-reserva-numero" name="numero" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Complemento</label>
                                            <input type="text" id="view-reserva-complemento" name="complemento" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">CEP</label>
                                            <input type="text" id="view-reserva-cep" name="cep" class="form-control">
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="mb-3">
                                        <label class="form-label">Observações</label>
                                        <textarea id="view-reserva-observacoes" name="observacoes" class="form-control" rows="3"></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer d-flex flex-column border-top-0">
                                <div class="w-100 d-flex gap-2 mb-2">
                                    <button type="button" class="btn btn-primary w-100" id="view-reserva-save">Salvar alterações</button>
                                    <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">Fechar</button>
                                </div>
                                <div class="w-100 d-flex gap-2" id="view-reserva-actions">
                                    <button type="button" class="btn btn-success w-100" id="view-reserva-confirm">Confirmar</button>
                                    <button type="button" class="btn btn-danger w-100" id="view-reserva-cancel">Cancelar</button>
                                </div>
                                <div class="w-100 d-flex gap-2 d-none" id="view-reserva-after-confirm">
                                    <button type="button" class="btn btn-outline-secondary w-50" id="view-reserva-to-pendente">Voltar para pendente</button>
                                    <button type="button" class="btn btn-success w-50" id="view-reserva-to-realizada">Marcar como realizada</button>
                                </div>
                                <div class="w-100 d-flex gap-2 d-none" id="view-reserva-after-cancel">
                                    <button type="button" class="btn btn-outline-secondary w-50" id="view-reserva-to-pendente-2">Voltar para pendente</button>
                                    <button type="button" class="btn btn-danger w-50" id="view-reserva-delete">Excluir reserva</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                        <h5>Reservas</h5>
                        <p class="text-muted">Gerencie todas as reservas de visitas</p>
                    </div>
                    <div class="col-auto d-flex flex-column justify-content-between">
                        <div class="row row-gap-3 mb-3">
                            <div class="col">
                                <button class="btn btn-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#reserva-modal">Criar nova reserva <i class="bi bi-plus-lg"></i></button>
                                <div class="modal fade" id="reserva-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reservaModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <form class="modal-content" id="form-reserva" method="POST" action="../controladores/reservas/criar-reserva.php" novalidate>
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title" id="reservaModalLabel">Criar nova reserva de visita</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <input type="hidden" name="id_veiculo" id="reserva-id-veiculo" value="<?= $id_veiculo ?>">

                                                <!-- Contato -->
                                                <div class="row g-3 mb-2">
                                                    <div class="col-md-6">
                                                        <label for="reserva-nome" class="form-label">Nome completo</label>
                                                        <input type="text" class="form-control" id="reserva-nome" name="nome" placeholder="Nome do visitante" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="reserva-telefone" class="form-label">Telefone</label>
                                                        <input type="tel" class="form-control telefone-mask" id="reserva-telefone" maxlength="15" name="telefone" placeholder="(00) 9 9999-9999" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="reserva-email" class="form-label">E-mail</label>
                                                        <input type="email" class="form-control" id="reserva-email" name="email" placeholder="exemplo@dominio.com">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="reserva-contato" class="form-label">Preferência de contato</label>
                                                        <select class="form-select" id="reserva-contato" name="preferencia_contato">
                                                            <option value="telefone">Telefone</option>
                                                            <option value="whatsapp">WhatsApp</option>
                                                            <option value="email">E-mail</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Data / Hora / Pessoas -->
                                                <div class="row g-3 mb-2">
                                                    <div class="col-md-4">
                                                        <label for="reserva-data" class="form-label">Data</label>
                                                        <input type="date" class="form-control" id="reserva-data" name="data" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="reserva-hora" class="form-label">Hora</label>
                                                        <input type="time" class="form-control" id="reserva-hora" name="hora" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="reserva-acompcount" class="form-label">Nº de acompanhantes</label>
                                                        <input type="number" class="form-control" id="reserva-acompcount" name="acompanhantes_qtd" min="0" max="10" value="0">
                                                    </div>
                                                </div>

                                                <!-- Endereço do visitante -->
                                                <div class="row g-3 mt-2 mb-2">
                                                    <div class="col-12">
                                                        <h6 class="mb-2">Endereço</h6>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="reserva-estado" class="form-label">Estado</label>
                                                        <select class="form-select" id="reserva-estado" name="estado">
                                                            <option value="">Carregando estados...</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label for="reserva-cidade" class="form-label">Cidade</label>
                                                        <select class="form-select" id="reserva-cidade" name="cidade">
                                                            <option value="">Selecione um estado primeiro...</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="reserva-bairro" class="form-label">Bairro</label>
                                                        <input type="text" class="form-control" id="reserva-bairro" name="bairro" placeholder="Bairro">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="reserva-rua" class="form-label">Rua</label>
                                                        <input type="text" class="form-control" id="reserva-rua" name="rua" placeholder="Rua, avenida, etc.">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="reserva-numero" class="form-label">Número</label>
                                                        <input type="text" class="form-control" id="reserva-numero" name="numero" placeholder="123">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label for="reserva-complemento" class="form-label">Complemento</label>
                                                        <input type="text" class="form-control" id="reserva-complemento" name="complemento" placeholder="Apto, casa">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label for="reserva-cep" class="form-label">CEP</label>
                                                        <input type="text" class="form-control cep-mask" id="reserva-cep" name="cep" placeholder="00000-000" maxlength="9">
                                                    </div>
                                                </div>

                                                <!-- Observações (último) -->
                                                <div class="row g-3 mt-3">
                                                    <div class="col-12">
                                                        <label for="reserva-observacoes" class="form-label">Observações</label>
                                                        <textarea class="form-control" id="reserva-observacoes" name="observacoes" rows="3" placeholder="Anotações adicionais (ex.: preferências, horário alternativo)"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex flex-column border-top-0 p-4">
                                                <div class="w-100 d-flex gap-2">
                                                    <button type="submit" class="btn btn-dark w-100" id="btn-salvar-reserva">Salvar reserva</button>
                                                    <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                // Carregar reservas desse veículo
                include('../controladores/conexao_bd.php');
                $sql_res = "SELECT * FROM reservas WHERE id_veiculo = $id_veiculo ORDER BY criado_em DESC";
                $res_result = mysqli_query($conexao, $sql_res);
                $reservas = [];
                while ($r = mysqli_fetch_assoc($res_result)) {
                    $reservas[] = $r;
                }

                // Helpers locais
                function format_phone_display_local($digits)
                {
                    $digits_only = preg_replace('/\D+/', '', $digits);
                    if ($digits_only === '') return '';
                    if (strlen($digits_only) <= 2) return '(' . $digits_only . ')';
                    $area = substr($digits_only, 0, 2);
                    $rest = substr($digits_only, 2);
                    $len = strlen($rest);
                    if ($len <= 4) {
                        return '(' . $area . ') ' . $rest;
                    }
                    $last4 = substr($rest, -4);
                    $firstPart = substr($rest, 0, $len - 4);
                    return '(' . $area . ') ' . $firstPart . '-' . $last4;
                }

                function day_abbr_pt_local($date)
                {
                    $w = date('w', strtotime($date));
                    $map = ['dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sab'];
                    return $map[$w] ?? '';
                }

                mysqli_close($conexao);
                ?>

                <div class="row row-cols-1 g-3">
                    <?php if (!empty($reservas)): ?>
                        <?php foreach ($reservas as $res): ?>
                            <?php
                            $badgeClass = 'bg-warning-subtle text-warning-emphasis';
                            switch ($res['status']) {
                                case 'confirmada':
                                    $badgeClass = 'bg-success-subtle text-success-emphasis';
                                    break;
                                case 'cancelada':
                                    $badgeClass = 'bg-danger-subtle text-danger-emphasis';
                                    break;
                                case 'realizada':
                                    $badgeClass = 'bg-info-subtle text-info-emphasis';
                                    break;
                            }
                            $data_display = day_abbr_pt_local($res['data']);
                            $dia = date('d', strtotime($res['data']));
                            ?>
                            <div class="col-12">
                                <div class="card rounded-5 reserva-card" role="button" data-id="<?= $res['id'] ?>"
                                    data-nome="<?= htmlspecialchars($res['nome'], ENT_QUOTES) ?>"
                                    data-telefone="<?= htmlspecialchars(format_phone_display_local($res['telefone']), ENT_QUOTES) ?>"
                                    data-email="<?= htmlspecialchars($res['email'], ENT_QUOTES) ?>"
                                    data-preferencia_contato="<?= htmlspecialchars($res['preferencia_contato'], ENT_QUOTES) ?>"
                                    data-data="<?= $res['data'] ?>"
                                    data-hora="<?= $res['hora'] ?>"
                                    data-acompanhantes_qtd="<?= $res['acompanhantes_qtd'] ?>"
                                    data-estado="<?= htmlspecialchars($res['estado'], ENT_QUOTES) ?>"
                                    data-cidade="<?= htmlspecialchars($res['cidade'], ENT_QUOTES) ?>"
                                    data-bairro="<?= htmlspecialchars($res['bairro'], ENT_QUOTES) ?>"
                                    data-rua="<?= htmlspecialchars($res['rua'], ENT_QUOTES) ?>"
                                    data-numero="<?= htmlspecialchars($res['numero'], ENT_QUOTES) ?>"
                                    data-complemento="<?= htmlspecialchars($res['complemento'], ENT_QUOTES) ?>"
                                    data-cep="<?= htmlspecialchars($res['cep'], ENT_QUOTES) ?>"
                                    data-observacoes="<?= htmlspecialchars($res['observacoes'], ENT_QUOTES) ?>"
                                    data-status="<?= $res['status'] ?>">
                                    <div class="card-body d-flex px-0 gap-4">
                                        <div class="d-flex flex-column text-center justify-content-center align-items-center px-4 border-end">
                                            <span class="fs-6 text-uppercase"><?= $data_display ?></span>
                                            <span class="fs-2 fw-bold"><?= $dia ?></span>
                                        </div>
                                        <div class="row row-cols-2 text-muted w-100 align-items-center">
                                            <div class="col-auto d-flex flex-column justify-content-center gap-2">
                                                <span>Cliente: <span class="fw-semibold"><?= htmlspecialchars($res['nome'], ENT_QUOTES) ?></span></span>
                                                <small class="text-muted">Contato: <?= htmlspecialchars(format_phone_display_local($res['telefone']), ENT_QUOTES) ?> · <?= htmlspecialchars($res['email'], ENT_QUOTES) ?></small>
                                            </div>
                                            <div class="col-auto d-flex flex-column justify-content-center gap-2">
                                                <span><i class="bi bi-clock-history"></i> &nbsp;<?= date('H:i', strtotime($res['hora'])) ?></span>
                                                <span>Status: <span class="fw-semibold px-2 py-1 rounded-pill <?= $badgeClass ?> reserva-badge" data-status="<?= $res['status'] ?>"><?= ucfirst($res['status']) ?></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="card rounded-5">
                                <div class="card-body">
                                    <div class="text-center text-muted">
                                        <p class="mb-0">Nenhuma reserva feita ainda</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
                                <button class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#delete-modal">Deletar anúncio <i class="bi bi-trash"></i></button>
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
        $("#condicao-select option[value='<?= $condicao ?? 'N' ?>']").prop('selected', true);

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

        const dateInput = $('input[type="date"]')

        dateInput.attr('min', new Date().toISOString().split('T')[0]);
        dateInput.val(new Date().toISOString().split('T')[0]);

        // --- Handlers para abrir/editar/alterar status/excluir reservas a partir dos cards nesta página ---
        function fillViewModalFromCard($card) {
            const id = $card.data('id');
            $('#view-reserva-id').val(id);
            $('#view-reserva-nome').val($card.data('nome'));
            $('#view-reserva-telefone').val($card.data('telefone'));
            $('#view-reserva-email').val($card.data('email'));
            $('#view-reserva-preferencia').val($card.data('preferencia_contato'));
            $('#view-reserva-data').val($card.data('data'));
            $('#view-reserva-hora').val($card.data('hora'));
            $('#view-reserva-acomp').val($card.data('acompanhantes_qtd'));
            // set state (UF) and load municipalities, then set city
            setViewModalLocation($card.data('estado'), $card.data('cidade'));
            $('#view-reserva-bairro').val($card.data('bairro'));
            $('#view-reserva-rua').val($card.data('rua'));
            $('#view-reserva-numero').val($card.data('numero'));
            $('#view-reserva-complemento').val($card.data('complemento'));
            $('#view-reserva-cep').val($card.data('cep'));
            $('#view-reserva-observacoes').val($card.data('observacoes'));
            const status = $card.data('status');
            setViewActionButtons(status);
        }

        function setViewActionButtons(status) {
            $('#view-reserva-actions').removeClass('d-none');
            $('#view-reserva-after-confirm, #view-reserva-after-cancel').addClass('d-none');
            if (status === 'pendente') {
                $('#view-reserva-actions').removeClass('d-none');
            } else if (status === 'confirmada') {
                $('#view-reserva-actions').addClass('d-none');
                $('#view-reserva-after-confirm').removeClass('d-none');
            } else if (status === 'cancelada') {
                $('#view-reserva-actions').addClass('d-none');
                $('#view-reserva-after-cancel').removeClass('d-none');
            } else if (status === 'realizada') {
                $('#view-reserva-actions').addClass('d-none');
                $('#view-reserva-after-confirm').removeClass('d-none');
                $('#view-reserva-to-realizada').addClass('d-none');
            }
        }

        function updateCardStatus(id, status) {
            const $card = $('.reserva-card[data-id="' + id + '"]');
            const $badge = $card.find('.reserva-badge');
            $badge.text(status.charAt(0).toUpperCase() + status.slice(1));
            $card.data('status', status);
            $badge.removeClass('bg-warning-subtle text-warning-emphasis bg-success-subtle text-success-emphasis bg-danger-subtle text-danger-emphasis bg-info-subtle text-info-emphasis bg-secondary-subtle text-secondary');
            switch (status) {
                case 'confirmada':
                    $badge.addClass('bg-success-subtle text-success-emphasis');
                    break;
                case 'cancelada':
                    $badge.addClass('bg-danger-subtle text-danger-emphasis');
                    break;
                case 'realizada':
                    $badge.addClass('bg-info-subtle text-info-emphasis');
                    break;
                default:
                    $badge.addClass('bg-warning-subtle text-warning-emphasis');
                    break;
            }
        }

        // Abrir modal ao clicar no cartão (mesma classe .reserva-card usada na listagem)
        $(document).on('click', '.reserva-card', function() {
            const $card = $(this);
            fillViewModalFromCard($card);
            const modalEl = document.getElementById('view-reserva-view-modal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });

        // Salvar alterações (update) — construir explicitamente o payload para evitar problemas de serialize
        $('#view-reserva-save').on('click', function() {
            const payload = {
                id: $('#view-reserva-id').val(),
                action: 'update',
                nome: $('#view-reserva-nome').val(),
                telefone: $('#view-reserva-telefone').val(),
                email: $('#view-reserva-email').val(),
                preferencia_contato: $('#view-reserva-preferencia').val(),
                data: $('#view-reserva-data').val(),
                hora: $('#view-reserva-hora').val(),
                acompanhantes_qtd: $('#view-reserva-acomp').val(),
                estado: $('#view-reserva-estado').val(),
                cidade: $('#view-reserva-cidade').val(),
                bairro: $('#view-reserva-bairro').val(),
                rua: $('#view-reserva-rua').val(),
                numero: $('#view-reserva-numero').val(),
                complemento: $('#view-reserva-complemento').val(),
                cep: $('#view-reserva-cep').val(),
                observacoes: $('#view-reserva-observacoes').val()
            };

            $.post('../controladores/reservas/atualizar-reserva.php', payload)
                .done(function(resp) {
                    if (resp.success) {
                        const id = payload.id;
                        const $card = $('.reserva-card[data-id="' + id + '"]');
                        $card.data('nome', payload.nome);
                        $card.data('telefone', payload.telefone);
                        $card.data('email', payload.email);
                        $card.data('preferencia_contato', payload.preferencia_contato);
                        $card.data('data', payload.data);
                        $card.data('hora', payload.hora);
                        $card.data('acompanhantes_qtd', payload.acompanhantes_qtd);
                        $card.data('estado', payload.estado);
                        $card.data('cidade', payload.cidade);
                        $card.data('bairro', payload.bairro);
                        $card.data('rua', payload.rua);
                        $card.data('numero', payload.numero);
                        $card.data('complemento', payload.complemento);
                        $card.data('cep', payload.cep);
                        $card.data('observacoes', payload.observacoes);
                        $card.find('.fw-semibold').first().text(payload.nome);
                        // Recarregar para sincronizar mensagens de sessão
                        location.reload();
                    } else {
                        alert('Erro: ' + resp.message);
                    }
                })
                .fail(function() {
                    alert('Erro ao salvar.');
                });
        });

        // Confirmar reserva
        $('#view-reserva-confirm').on('click', function() {
            const id = $('#view-reserva-id').val();
            $.post('../controladores/reservas/atualizar-reserva.php', {
                    id: id,
                    action: 'status',
                    status: 'confirmada'
                })
                .done(function(resp) {
                    if (resp.success) {
                        updateCardStatus(id, 'confirmada');
                        setViewActionButtons('confirmada');
                    } else alert(resp.message);
                });
        });

        // Cancelar reserva
        $('#view-reserva-cancel').on('click', function() {
            const id = $('#view-reserva-id').val();
            $.post('../controladores/reservas/atualizar-reserva.php', {
                    id: id,
                    action: 'status',
                    status: 'cancelada'
                })
                .done(function(resp) {
                    if (resp.success) {
                        updateCardStatus(id, 'cancelada');
                        setViewActionButtons('cancelada');
                    } else alert(resp.message);
                });
        });

        // Voltar para pendente
        $('#view-reserva-to-pendente, #view-reserva-to-pendente-2').on('click', function() {
            const id = $('#view-reserva-id').val();
            $.post('../controladores/reservas/atualizar-reserva.php', {
                    id: id,
                    action: 'status',
                    status: 'pendente'
                })
                .done(function(resp) {
                    if (resp.success) {
                        updateCardStatus(id, 'pendente');
                        setViewActionButtons('pendente');
                    } else alert(resp.message);
                });
        });

        // Marcar como realizada
        $('#view-reserva-to-realizada').on('click', function() {
            const id = $('#view-reserva-id').val();
            $.post('../controladores/reservas/atualizar-reserva.php', {
                    id: id,
                    action: 'status',
                    status: 'realizada'
                })
                .done(function(resp) {
                    if (resp.success) {
                        updateCardStatus(id, 'realizada');
                        setViewActionButtons('realizada');
                    } else alert(resp.message);
                });
        });

        // Excluir reserva
        $('#view-reserva-delete').on('click', function() {
            if (!confirm('Tem certeza que deseja excluir esta reserva?')) return;
            const id = $('#view-reserva-id').val();
            $.post('../controladores/reservas/deletar-reserva.php', {
                    id: id
                })
                .done(function(resp) {
                    if (resp.success) {
                        $('.reserva-card[data-id="' + id + '"]').parent().fadeOut(300, function() {
                            $(this).remove();
                        });
                        const modalEl = document.getElementById('view-reserva-view-modal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    } else alert(resp.message);
                })
                .fail(function() {
                    alert('Erro ao excluir.');
                });
        });

        // ---- Image editor: drag & drop, paste, add, mark for delete (apply on save) ----
        const $dropzone = $('#image-dropzone');
        const $fileInput = $('#new-fotos-input');
        const $imagesGrid = $('#images-grid');
        const $form = $('#edit-anuncio-form');
        let newFiles = []; // array of File objects to upload
        let deletions = []; // filenames (existing) marked for deletion

        function renderNewPreview(file, idx) {
            const url = URL.createObjectURL(file);
            const $col = $(`
                <div class="col img-item new-item" data-new-index="${idx}">
                    <div class="ratio ratio-16x9 position-relative">
                        <button type="button" class="delete-mark" title="Remover"><i class="bi bi-x-lg"></i></button>
                        <img src="${url}" class="img-fluid object-fit-cover shadow-sm rounded-3">
                    </div>
                </div>
            `);
            // delete handler for new file
            $col.find('.delete-mark').on('click', function() {
                // remove from newFiles by index marker
                const i = parseInt($col.attr('data-new-index'));
                newFiles[i] = null; // mark removed; will be filtered later
                $col.remove();
            });
            // append new preview at the end so added images appear last
            $imagesGrid.append($col);
        }

        function addFilesList(list) {
            for (let i = 0; i < list.length; i++) {
                const f = list[i];
                if (!f || !f.type.startsWith('image/')) continue;
                newFiles.push(f);
                renderNewPreview(f, newFiles.length - 1);
            }
        }

        // Open file input when clicking the add-image tile (or pressing Enter/Space when focused)
        $imagesGrid.on('click', '.img-item.add-item', function() {
            $fileInput.trigger('click');
        });
        $imagesGrid.on('keydown', '.img-item.add-item', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $fileInput.trigger('click');
            }
        });

        $fileInput.on('change', function(e) {
            const files = e.target.files;
            addFilesList(files);
            // reset input so selecting same file again works
            $(this).val('');
        });

        $dropzone.on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });
        $dropzone.on('dragleave dragend drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });
        $dropzone.on('drop', function(e) {
            const dt = e.originalEvent.dataTransfer;
            if (!dt) return;
            if (dt.files && dt.files.length) {
                addFilesList(dt.files);
            }
        });

        // Paste support (Ctrl+V)
        $(document).on('paste', function(e) {
            const items = (e.originalEvent.clipboardData || e.clipboardData).items;
            if (!items) return;
            const files = [];
            for (let i = 0; i < items.length; i++) {
                const it = items[i];
                if (it.kind === 'file') {
                    const f = it.getAsFile();
                    if (f) files.push(f);
                }
            }
            if (files.length) addFilesList(files);
        });

        // toggle deletion for existing items
        $imagesGrid.on('click', '.existing-item .delete-mark', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const $item = $(this).closest('.existing-item');
            const filename = $item.data('filename');
            if (!$item.hasClass('marked')) {
                $item.addClass('marked');
                deletions.push(filename);
                $(this).attr('title', 'Desmarcar exclusão');
                $(this).html('<i class="bi bi-check-lg"></i>');
            } else {
                $item.removeClass('marked');
                deletions = deletions.filter(x => x !== filename);
                $(this).attr('title', 'Marcar para deletar');
                $(this).html('<i class="bi bi-trash"></i>');
            }
        });

        // on submit: build FormData including deletions and newFiles and send via AJAX
        $form.on('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            // append deletions
            for (let i = 0; i < deletions.length; i++) fd.append('delete_photos[]', deletions[i]);
            // append new files
            for (let i = 0; i < newFiles.length; i++) {
                const f = newFiles[i];
                if (!f) continue;
                fd.append('new_fotos[]', f, f.name);
            }

            // disable save button
            const $saveBtn = $form.find('button[type=submit]');
            $saveBtn.prop('disabled', true).text('Salvando...');

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json'
            }).done(function(resp) {
                if (resp && resp.success) {
                    if (resp.redirect) location.href = resp.redirect;
                    else location.reload();
                } else {
                    alert((resp && resp.message) ? resp.message : 'Erro ao salvar alterações.');
                    $saveBtn.prop('disabled', false).text('Salvar alterações  \u00A0\u00A0\u00A0\u00A0\u00A0\u00A0\u00A0\u00A0');
                }
            }).fail(function() {
                alert('Erro de comunicação ao salvar.');
                $saveBtn.prop('disabled', false).text('Salvar alterações  \u00A0\u00A0\u00A0\u00A0\u00A0\u00A0\u00A0\u00A0');
            });
        });

        // descrição counter
        const $desc = $('#descricao-input');
        const $descCount = $('#desc-count');
        if ($desc.length) {
            $desc.on('input', function() {
                $descCount.text($(this).val().length);
            });
        }

        // --- Localização (IBGE): popular selects de estados e municípios para reservas ---
        const ibgeEstadosUrl = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';

        function populateStates() {
            $.getJSON(ibgeEstadosUrl, function(estados) {
                estados.sort((a, b) => a.nome.localeCompare(b.nome));
                const $reservaEstados = $('#reserva-estado');
                const $viewEstados = $('#view-reserva-estado');
                const $anuncioEstados = $('#anuncio-estado');
                $reservaEstados.empty().append('<option value="">Selecione um estado...</option>');
                $viewEstados.empty().append('<option value="">Selecione um estado...</option>');
                if ($anuncioEstados.length) $anuncioEstados.empty().append('<option value="">Selecione um estado...</option>');
                $.each(estados, function(i, estado) {
                    const opt = `<option value="${estado.sigla}" data-id="${estado.id}">${estado.nome} (${estado.sigla})</option>`;
                    $reservaEstados.append(opt);
                    $viewEstados.append(opt);
                    if ($anuncioEstados.length) $anuncioEstados.append(opt);
                });
            });
        }

        function loadMunicipiosFor($estadoSelect, $municipioSelect, selectedCity) {
            const $sel = $($estadoSelect);
            const $mun = $($municipioSelect);
            const estadoId = $sel.find('option:selected').data('id');
            if (!estadoId) {
                $mun.html('<option value="">Selecione um estado primeiro...</option>');
                return;
            }
            const urlMunicipios = `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estadoId}/municipios`;
            $mun.html('<option value="">Carregando municípios...</option>');
            $.getJSON(urlMunicipios, function(municipios) {
                municipios.sort((a, b) => a.nome.localeCompare(b.nome));
                $mun.empty().append('<option value="">Selecione um município...</option>');
                $.each(municipios, function(i, municipio) {
                    $mun.append(`<option value="${municipio.nome}">${municipio.nome}</option>`);
                });
                if (selectedCity) {
                    // try to select by exact match
                    $mun.val(selectedCity);
                    // fallback: try case-insensitive match
                    if (!$mun.val() && selectedCity) {
                        const opts = $mun.find('option');
                        opts.each(function() {
                            if ($(this).text().toLowerCase() === selectedCity.toLowerCase()) {
                                $mun.val($(this).val());
                                return false;
                            }
                        });
                    }
                }
            });
        }

        // wire change handlers
        $(document).on('change', '#reserva-estado', function() {
            loadMunicipiosFor('#reserva-estado', '#reserva-cidade');
        });
        $(document).on('change', '#view-reserva-estado', function() {
            loadMunicipiosFor('#view-reserva-estado', '#view-reserva-cidade');
        });
        // also wire anuncio estado change so municipios carreguem quando editando anúncio
        $(document).on('change', '#anuncio-estado', function() { loadMunicipiosFor('#anuncio-estado', '#anuncio-cidade'); });

        // expose a helper to set view modal location (state UF and city name)
        function setViewModalLocation(stateUf, cityName, attempt = 0) {
            // retry a few times if states are still loading
            if (attempt > 12) return; // give up
            if ($('#view-reserva-estado option').length <= 1) {
                setTimeout(function() {
                    setViewModalLocation(stateUf, cityName, attempt + 1);
                }, 150);
                return;
            }
            if (!stateUf) {
                $('#view-reserva-estado').val('');
                $('#view-reserva-cidade').html('<option value="">Selecione um estado primeiro...</option>');
                return;
            }
            $('#view-reserva-estado').val(stateUf);
            // after state is set, load municipios and set city
            loadMunicipiosFor('#view-reserva-estado', '#view-reserva-cidade', cityName);
        }

        // Propulsão field dynamic loader
        const propulsaoSelect = $('#propulsao-select');
        const combustivelSelect = $('#combustivel-select');
        
        const propulsaoConfig = {
            'eletrico': { combustivel: 'Elétrico', opcoes: ['Elétrico'] },
            'combustao': { combustivel: 'Gasolina', opcoes: ['Gasolina', 'Álcool', 'Flex', 'Diesel', 'GNV'] },
            'hibrido': { combustivel: 'HEV', opcoes: ['HEV', 'PHEV'] }
        };
        
        function updateCombustivelOptions() {
            const selectedValue = propulsaoSelect.val();
            combustivelSelect.empty();
            combustivelSelect.append('<option value="" selected hidden>Selecione o combustível</option>');
            
            if (selectedValue && propulsaoConfig[selectedValue]) {
                const opcoes = propulsaoConfig[selectedValue].opcoes;
                opcoes.forEach(function(opcao) {
                    combustivelSelect.append('<option value="' + opcao + '"' + (opcao === '<?= htmlspecialchars($combustivel ?? '') ?>' ? ' selected' : '') + '>' + opcao + '</option>');
                });
            }
        }
        
        // Initialize on page load
        updateCombustivelOptions();
        
        // Update when propulsão changes
        propulsaoSelect.on('change', updateCombustivelOptions);

        // initialize states on page load
        populateStates();
        // set anuncio location if available from server
        <?php if (!empty($estado_local)): ?>
                // wait for states to populate and then set
                (function waitAndSetAnuncio(attempt) {
                    if (attempt > 12) return;
                    if ($('#anuncio-estado option').length <= 1) {
                        setTimeout(function() {
                            waitAndSetAnuncio(attempt + 1);
                        }, 150);
                        return;
                    }
                    $('#anuncio-estado').val('<?= $estado_local ?>');
                    // load municipios and select city
                    loadMunicipiosFor('#anuncio-estado', '#anuncio-cidade', '<?= addslashes($cidade) ?>');
                })(0);
        <?php endif; ?>
    })
</script>

</html>