<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../");
    exit;
}
include('../controladores/conexao_bd.php');

$usuario = $_SESSION['id'];

$msgs = [];
$dest = [];

if (isset($_GET['id'])) {
    $de_id = $_GET['id'];
    $anuncio = $_GET['anuncio_id'];

    // Carregar mensagens
    $sql = "SELECT * FROM mensagens_chat 
            WHERE ((de_usuario = '$de_id' AND para_usuario = '$usuario') 
            OR (de_usuario = '$usuario' AND para_usuario = '$de_id')) 
            AND anuncio = '$anuncio' 
            ORDER BY data_envio";
    $resultado = mysqli_query($conexao, $sql);

    while ($linha = mysqli_fetch_assoc($resultado)) {
        $msgs[] = $linha;
    }

    // Marcar mensagens como lidas
    $sql = "UPDATE mensagens_chat 
            SET lida = 1 
            WHERE para_usuario = '$usuario' 
            AND anuncio = '$anuncio'";
    mysqli_query($conexao, $sql);

    // 🔥 Zerar contador também na tabela conversas
    $sql = "UPDATE conversas 
            SET nao_lidas_comprador = 0 
            WHERE anuncio_id = '$anuncio' AND comprador_id = '$usuario'";
    mysqli_query($conexao, $sql);

    $sql = "UPDATE conversas 
            SET nao_lidas_vendedor = 0 
            WHERE anuncio_id = '$anuncio' AND vendedor_id = '$usuario'";
    mysqli_query($conexao, $sql);

    // Carregar destinatário
    $sql = "SELECT * FROM usuarios WHERE id = '$de_id'";
    $resultado = mysqli_query($conexao, $sql);
    $dest = mysqli_fetch_assoc($resultado);
}

$sql = "UPDATE conversas c
LEFT JOIN (
    SELECT anuncio AS anuncio_id, para_usuario, COUNT(*) AS qtd
    FROM mensagens_chat
    WHERE lida = 0
    GROUP BY anuncio, para_usuario
) m 
ON m.anuncio_id = c.anuncio_id 
AND m.para_usuario = c.comprador_id
SET c.nao_lidas_comprador = IFNULL(m.qtd, 0)";
$resultado = mysqli_query($conexao, $sql);

// Atualizar contador para o vendedor
$sql = "UPDATE conversas c
LEFT JOIN (
    SELECT anuncio AS anuncio_id, para_usuario, COUNT(*) AS qtd
    FROM mensagens_chat
    WHERE lida = 0
    GROUP BY anuncio, para_usuario
) m 
ON m.anuncio_id = c.anuncio_id 
AND m.para_usuario = c.vendedor_id
SET c.nao_lidas_vendedor = IFNULL(m.qtd, 0)";
$resultado = mysqli_query($conexao, $sql);

$sql = "SELECT conversas.*, 
       usuarios.*, 
       carros.modelo AS modelo, 
       carros.versao AS versao, 
       marcas.nome AS marca,
       (SELECT MAX(data_envio) FROM mensagens_chat WHERE anuncio = conversas.anuncio_id AND ((de_usuario = conversas.comprador_id AND para_usuario = conversas.vendedor_id) OR (de_usuario = conversas.vendedor_id AND para_usuario = conversas.comprador_id))) AS data_ultima_mensagem
FROM conversas
INNER JOIN usuarios 
    ON conversas.vendedor_id = usuarios.id
INNER JOIN anuncios_carros carros 
    ON conversas.anuncio_id = carros.id
INNER JOIN marcas 
    ON carros.marca = marcas.id
WHERE comprador_id = '$usuario'";
$resultado = mysqli_query($conexao, $sql);

$conversas_comp = [];

if (mysqli_num_rows($resultado) > 0) {
    while ($linha = mysqli_fetch_assoc($resultado)) {
        $conversas_comp[] = $linha;
    }
}

$sql = "SELECT conversas.*, 
       usuarios.*, 
       carros.modelo AS modelo, 
       carros.versao AS versao, 
       marcas.nome AS marca,
       (SELECT MAX(data_envio) FROM mensagens_chat WHERE anuncio = conversas.anuncio_id AND ((de_usuario = conversas.comprador_id AND para_usuario = conversas.vendedor_id) OR (de_usuario = conversas.vendedor_id AND para_usuario = conversas.comprador_id))) AS data_ultima_mensagem
FROM conversas
INNER JOIN usuarios 
    ON conversas.comprador_id = usuarios.id
INNER JOIN anuncios_carros carros 
    ON conversas.anuncio_id = carros.id
INNER JOIN marcas 
    ON carros.marca = marcas.id
WHERE vendedor_id = '$usuario'";
$resultado = mysqli_query($conexao, $sql);

$conversas_vend = [];

if (mysqli_num_rows($resultado) > 0) {
    while ($linha = mysqli_fetch_assoc($resultado)) {
        $conversas_vend[] = $linha;
    }
}

mysqli_close($conexao);

function formatarDataEspecial($data_dmY)
{
    date_default_timezone_set('America/Sao_Paulo');

    $data_obj = DateTime::createFromFormat('d-m-Y', $data_dmY);
    $hoje = new DateTime();
    $ontem = (new DateTime())->modify('-1 day');

    $meses = [
        1 => 'janeiro',
        2 => 'fevereiro',
        3 => 'março',
        4 => 'abril',
        5 => 'maio',
        6 => 'junho',
        7 => 'julho',
        8 => 'agosto',
        9 => 'setembro',
        10 => 'outubro',
        11 => 'novembro',
        12 => 'dezembro'
    ];

    if ($data_obj->format('d-m-Y') === $hoje->format('d-m-Y')) {
        return 'Hoje';
    }

    if ($data_obj->format('d-m-Y') === $ontem->format('d-m-Y')) {
        return 'Ontem';
    }

    $dia = $data_obj->format('j');
    $mes = $meses[(int)$data_obj->format('m')];
    $ano = $data_obj->format('Y');
    $ano_atual = $hoje->format('Y');

    if ($ano !== $ano_atual) {
        return $dia . ' de ' . $mes . ' de ' . $ano;
    } else {
        return $dia . ' de ' . $mes;
    }
}

$msg_pos = 0;

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
    .list-group-item {
        --bs-bg-opacity: 1;
        border: 0;
        padding: .8rem 1.5rem;
        cursor: pointer;
    }

    .list-group-item.active {
        background-color: rgba(var(--bs-secondary-bg-rgb), var(--bs-bg-opacity)) !important;
        color: rgba(var(--bs-dark-rgb), var(--bs-text-opacity)) !important;
    }

    .fs-7 {
        font-size: calc(.6rem + .2vw);
    }

    .fs-8 {
        font-size: calc(.4rem + .2vw);
    }

    .mensagem-horario {
        font-size: calc(.5rem + .2vw);
    }

    .chat-container.selecionando .mensagem:not(.bg-verde-escuro-subtle):hover {
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-secondary-bg-rgb), var(--bs-bg-opacity)) !important;
    }
    
    .bg-verde-escuro-subtle {
        background-color: rgba(52, 79, 25, 0.15) !important;
    }
</style>

<body class="overflow-x-hidden">
    <?php include '../estruturas/modal/loja-modal.php'; ?>
    <?php include '../estruturas/alert/alert.php' ?>
    <main class="container-fluid d-flex vh-100 p-0">
        <?php $selected = 'mensagens';
        include_once '../estruturas/sidebar/sidebar.php' ?>
        <div class="col" style="margin-left: calc(200px + 5vw);">
            <div class="container-fluid d-flex flex-column h-100 overflow-auto">
                <div class="row flex-grow-1 overflow-hidden">
                    <div class="col-auto pt-4 px-0 border-end h-100">
                        <div class="h-100 p-0 d-flex flex-column" style="width: calc(150px + 10vw); max-height: 100%;">
                            <div class="bg-transparent border-0 border-bottom p-4">
                                <h2 class="pb-2 fw-semibold mb-3">Mensagens</h2>
                                <div class="bg-body-tertiary position-relative rounded-2 shadow-sm">
                                    <div class="position-absolute top-50 translate-middle-y ps-3 text-muted">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <input type="text" class="form-control border-1" style="padding-left: 2.5rem;" placeholder="Pesquisar..." aria-label="Pesquisar..." aria-describedby="basic-addon1">
                                </div>
                                <div class="row pt-4 g-0 g-lg-1 g-xl-2 g-xxl-3">
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="options" id="option1" <?= isset($_GET['vendendo']) && $_GET['vendendo'] == 'true'  ? '' : 'checked' ?> autocomplete="off" data-tab="#comprando-tab">
                                        <label class="btn btn-outline-dark w-100 text-nowrap overflow-visible text-center text-truncate position-relative" for="option1">
                                            Comprando
                                            <?php
                                            $nl = false;
                                            foreach ($conversas_comp as $c) {
                                                if ($c['nao_lidas_comprador'] > 0) {
                                                    $nl = true;
                                                    break;
                                                } else {
                                                    $nl = false;
                                                }
                                            }

                                            if ($nl): ?>
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="padding: 0;">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            <?php endif ?>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="options" id="option2" autocomplete="off" <?= isset($_GET['vendendo']) && $_GET['vendendo'] == 'true'  ? 'checked' : '' ?> data-tab="#vendendo-tab">
                                        <label class="btn btn-outline-dark w-100 text-nowrap overflow-visible text-center text-truncate position-relative" for="option2">
                                            Vendendo
                                            <?php
                                            $nl = false;
                                            foreach ($conversas_vend as $c) {
                                                if ($c['nao_lidas_vendedor'] > 0) {
                                                    $nl = true;
                                                    break;
                                                } else {
                                                    $nl = false;
                                                }
                                            }

                                            if ($nl): ?>
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="padding: 0;">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            <?php endif ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <ul id="comprando-tab" class="list-group list-group-flush flex-grow-1 overflow-y-auto <?= isset($_GET['vendendo']) && $_GET['vendendo'] == 'true'  ? 'd-none' : '' ?>">
                                <?php
                                foreach ($conversas_comp as $user): 
                                    date_default_timezone_set('America/Sao_Paulo');
                                    $lastMessageTime = isset($user['data_ultima_mensagem']) && !empty($user['data_ultima_mensagem']) ? strtotime($user['data_ultima_mensagem']) : time();
                                ?>
                                    <li class="list-group-item <?= isset($_GET['anuncio_id']) && $_GET['anuncio_id'] == $user['anuncio_id'] ? 'active' : '' ?>" data-id-conversa="<?= $user['id'] ?>" data-timestamp="<?= $lastMessageTime ?>" data-next-update="0">
                                        <a href="mensagens.php?anuncio_id=<?= $user['anuncio_id'] ?>&id=<?= $user['id'] ?>" class="d-flex align-items-center py-0 gap-3 text-decoration-none text-black">
                                            <div class="col-auto flex-shrink-0">
                                                <div class="ratio ratio-1x1" style="width: calc(30px + .5vw);">
                                                    <img src="../img/logo-fahren-bg.jpg" class="img-fluid rounded-circle" alt="Avatar">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <p class="mb-0 me-2 fs-6 fw-semibold text-truncate"><?= $user['nome'] . ' ' . $user['sobrenome'] ?></p>
                                                    <small class="text-muted text-nowrap tempo-relativo">agora</small>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex flex-column small text-truncate me-3">
                                                        <p class="mb-1 text-uppercase"><?= $user['marca'] . ' ' . $user['modelo'] . ' ' . $user['versao'] ?? 'Veículo não disponível' ?></p>
                                                        <p class="mb-0 text-muted"><?= $user['ultima_mensagem'] ?? '' ?></p>
                                                    </div>
                                                    <span class="badge rounded-circle" style="background-color: var(--cor-verde-escuro);" <?= $user['nao_lidas_comprador'] > 0 ? '' : 'style="display: none;"' ?>><?= $user['nao_lidas_comprador'] ?></span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <ul id="vendendo-tab" class="list-group list-group-flush flex-grow-1 overflow-y-auto <?= isset($_GET['vendendo']) && $_GET['vendendo'] == 'true'  ? '' : 'd-none' ?>">
                                <?php
                                foreach ($conversas_vend as $user): 
                                    date_default_timezone_set('America/Sao_Paulo');
                                    $lastMessageTime = isset($user['data_ultima_mensagem']) && !empty($user['data_ultima_mensagem']) ? strtotime($user['data_ultima_mensagem']) : time();
                                ?>
                                    <li class="list-group-item <?= isset($_GET['anuncio_id']) && $_GET['anuncio_id'] == $user['anuncio_id'] ? 'active' : '' ?>" data-id-conversa="<?= $user['id'] ?>" data-timestamp="<?= $lastMessageTime ?>" data-next-update="0">
                                        <a href="mensagens.php?vendendo=true&anuncio_id=<?= $user['anuncio_id'] ?>&id=<?= $user['id'] ?>" class="d-flex align-items-center py-0 gap-3 text-decoration-none text-black">
                                            <div class="col-auto flex-shrink-0">
                                                <div class="ratio ratio-1x1" style="width: calc(30px + .5vw);">
                                                    <img src="../img/logo-fahren-bg.jpg" class="img-fluid rounded-circle" alt="Avatar">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <p class="mb-0 me-2 fs-6 fw-semibold text-truncate"><?= $user['nome'] . ' ' . $user['sobrenome'] ?></p>
                                                    <small class="text-muted text-nowrap tempo-relativo">agora</small>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex flex-column small text-truncate me-3">
                                                        <p class="mb-1 text-uppercase"><i class="bi bi-eye-fill"></i>&nbsp;<?= $user['marca'] . ' ' . $user['modelo'] . ' ' . $user['versao'] ?? 'Veículo não disponível' ?></p>
                                                        <p class="mb-0 text-muted"><?= $user['ultima_mensagem'] ?? '' ?></p>
                                                    </div>
                                                    <span class="badge rounded-circle" style="background-color: var(--cor-verde-escuro);" <?= $user['nao_lidas_vendedor'] > 0 ? '' : 'style="display: none;"' ?>><?= $user['nao_lidas_vendedor'] ?></span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col h-100 px-0 <?= !isset($_GET['id']) ? 'd-none' : '' ?>">
                        <div class="card rounded-0 shadow-sm h-100 border-0">
                            <div class="card-body p-0 d-flex flex-column h-100 justify-content-between">
                                <div class="card-header p-3 bg-transparent d-flex align-items-center justify-content-between gap-3">
                                    <div class="col-auto gap-3 d-flex align-items-center">
                                        <div class="col-auto flex-shrink-0">
                                            <div class="ratio ratio-1x1" style="width: calc(30px + .5vw);">
                                                <img src="../img/logo-fahren-bg.jpg" class="img-fluid rounded-circle" alt="Avatar">
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="mb-0 text-capitalize"><?= $dest['nome'] . ' ' . $dest['sobrenome'] ?></h6>
                                        </div>
                                    </div>
                                    <div id="topbar" class="col-auto d-flex align-items-center gap-2">
                                        <button title="Whatsapp" class="btn border-0 btn-sm btn-whatsapp" <?= !empty($dest['telefone']) ? 'onclick="window.open(\'https://wa.me/' . preg_replace('/[^0-9]/', '', $dest['telefone']) . '\', \'_blank\')"' : 'style="display: none;"' ?>><i class="bi bi-whatsapp" data-action=""></i></button>
                                        <div class="dropdown">
                                            <button title="Mais" class="btn border-0 btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical" data-action=""></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <h6 class="dropdown-header">Mensagem</h6>
                                                </li>
                                                <li><a class="dropdown-item" href="#" data-action="apagar-todas"><i class="bi bi-trash"></i> Apagar mensagens</a></li>
                                                <li>
                                                    <h6 class="dropdown-divider"></h6>
                                                </li>
                                                <li>
                                                    <h6 class="dropdown-header">Anúncio</h6>
                                                </li>
                                                <li><a class="dropdown-item" href="../pagina-venda.php?id=<?= $_GET['anuncio_id'] ?>"><i class="bi bi-car-front"></i> Ver anúncio</a></li>
                                                <li><a class="dropdown-item <?= isset($_GET['vendendo']) && $_GET['vendendo'] == 'true'  ? '' : 'd-none' ?>" href="editar-anuncio.php?id=<?= $_GET['anuncio_id'] ?>"><i class="bi bi-pencil"></i> Editar anúncio</a></li>
                                            </ul>
                                        </div>
                                        <button title="Copiar" class="btn border-0 btn-sm sel-group" style="display: none;" data-action=""><i class="bi bi-copy"></i></button>
                                        <button title="Apagar" class="btn border-0 btn-sm sel-group" style="display: none;" data-action=""><i class="bi bi-trash"></i></button>
                                        <button title="Cancelar" class="btn border-0 btn-sm sel-group" style="display: none;" data-action="cancelar"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                </div>
                                <div class="card-body overflow-auto px-0 chat-container" style="height: calc(100% - 120px);">
                                    <div class="d-flex flex-column gap-0">
                                        <?php foreach ($msgs as $msg): ?>
                                            <?php $msg_pos++ ?>
                                            <?php if (!isset($data) || (isset($data) && $data != date("d-m-Y", strtotime($msg['data_envio'])))): ?>
                                                <?php $data = date("d-m-Y", strtotime($msg['data_envio'])) ?>
                                                <div class="d-flex justify-content-center align-items-center my-4">
                                                    <small class="bg-body-tertiary shadow-sm bg-white text-muted px-3 py-1 rounded-pill"><?= formatarDataEspecial($data); ?></small>
                                                </div>
                                            <?php endif ?>

                                            <?php if ($msg['de_usuario'] == $_GET['id']): ?>
                                                <?php if ($msg['double_apagada_para'] == 0): ?>
                                                    <div class="d-flex justify-content-start align-content-center mensagem px-3" data-id-msg="<?= $msg['id'] ?>" data-apagada-de="<?= $msg['apagada_de'] ?>" data-apagada-para="<?= $msg['apagada_para'] ?>" data-msg-pos="<?= $msg_pos ?>" data-sender-id="<?= $msg['de_usuario'] ?>" data-sender-name="<?= htmlspecialchars(($msg['de_usuario'] == $_GET['id']) ? ($dest['nome'] . ' ' . $dest['sobrenome']) : ($_SESSION['nome'] . ' ' . $_SESSION['sobrenome'])) ?>">
                                                        <div class="form-check me-1 align-items-center" style="display: none;">
                                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                        </div>
                                                        <div class="bg-light rounded-start-4 rounded-bottom-4 px-3 py-2 shadow-sm my-2">
                                                            <?php if ($msg['apagada_para'] == 0): ?>
                                                                <p class="mb-0"><?= $msg['texto'] ?></p>
                                                                <div class="d-flex justify-content-end">
                                                                    <small class="mt-0 mensagem-horario">
                                                                        <?= date("H:i", strtotime($msg['data_envio'])) ?>
                                                                    </small>
                                                                </div>
                                                            <?php else: ?>
                                                                <p class="mb-0 text-black" style="--bs-text-opacity: 0.6;"><i class="bi bi-ban"></i> Mensagem apagada</p>
                                                            <?php endif ?>
                                                        </div>
                                                    </div>
                                                <?php endif ?>
                                            <?php elseif ($msg['double_apagada_de'] == 0): ?>
                                                <div class="d-flex justify-content-end align-content-center mensagem px-3 mensagem-sua" data-id-msg="<?= $msg['id'] ?>" data-apagada-de="<?= $msg['apagada_de'] ?>" data-apagada-para="<?= $msg['apagada_para'] ?>" data-msg-pos="<?= $msg_pos ?>" data-sender-id="<?= $msg['de_usuario'] ?>" data-sender-name="<?= htmlspecialchars(($msg['de_usuario'] == $_GET['id']) ? ($dest['nome'] . ' ' . $dest['sobrenome']) : ($_SESSION['nome'] . ' ' . $_SESSION['sobrenome'])) ?>">
                                                    <div class="form-check me-auto align-items-center" style="display: none;">
                                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                    </div>
                                                    <div class="rounded-start-4 rounded-bottom-4 px-3 py-2 shadow-sm my-2" style="background-color: var(--cor-verde-escuro);">
                                                        <?php if ($msg['apagada_de'] == 0): ?>
                                                            <?php if ($msg['resposta_id'] != 0): ?>
                                                                <div class="d-flex justify-content-between w-100 mb-2 bg-opacity-25 bg-body-secondary rounded-2 p-2 pe-5 border-start border-white border-4">
                                                                    <div class="d-flex flex-column text-white">
                                                                        <div class="fw-semibold fs-7">Kelwin</div>
                                                                        <div class="fs-8">Bom dia</div>
                                                                    </div>
                                                                </div>
                                                            <?php endif ?>
                                                            <div class="d-flex flex-column">
                                                                <p class="mb-0 text-white"><?= $msg['texto'] ?></p>
                                                                <div class="d-flex justify-content-end">
                                                                    <small class="text-white text-opacity-75 mt-0 mensagem-horario">
                                                                        <?= date("H:i", strtotime($msg['data_envio'])) ?>
                                                                        <span class="align-middle"><i class="bi bi-check<?= $msg['lida'] == '1' ? '-all' : '' ?>"></i></span>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <p class="mb-0 text-white text-opacity-75"><i class="bi bi-ban"></i> Mensagem apagada</p>
                                                        <?php endif ?>
                                                    </div>
                                                </div>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                        <div class="dropdown">
                                            <div class="dropdown-menu" id="dropdown-mensagem" aria-labelledby="contextMenuTrigger">
                                                <button class="dropdown-item" type="button" data-action="copiar">
                                                    <i class="bi bi-clipboard"></i> Copiar
                                                </button>
                                                <button class="dropdown-item" type="button" data-action="responder">
                                                    <i class="bi bi-reply"></i> Responder
                                                </button>
                                                <button class="dropdown-item" type="button" data-action="selecionar">
                                                    <i class="bi bi-check2-square"></i> Selecionar
                                                </button>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <button class="dropdown-item text-danger" type="button" data-action="apagar" data-bs-toggle="modal" data-bs-target="#apaga-modal">
                                                    <i class="bi bi-trash"></i> Apagar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer px-3 py-2 border-0 bg-transparent">
                                    <div class="border rounded-2 shadow-sm p-2 d-flex flex-column align-items-center">
                                        <div class="d-none justify-content-between w-100 mb-2 bg-body-secondary rounded-2 p-2 border-start border-4" style="border-color: var(--cor-verde-escuro) !important;">
                                            <div class="d-flex flex-column">
                                                <div class="fw-semibold text-muted">Kelwin</div>
                                                <div class="text-muted">Bom dia</div>
                                            </div>
                                            <button class="btn border-0 bg-body-tertiary shadow-sm" id="fechar-resposta">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <form id="msg-form" class="d-flex gap-1 align-items-center w-100">
                                            <button class="btn" type="button" id="anexar"><i class="bi bi-plus-lg"></i></button>
                                            <input id="mensagem-input" type="text" class="form-control border-0 px-1" placeholder="Digite uma mensagem..." aria-label="Digite uma mensagem..." aria-describedby="button-send">
                                            <button class="btn" type="submit" id="button-send" disabled style="background-color: var(--cor-verde-escuro); color: white;"><i class="bi bi-send-fill"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="apaga-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form id="apagar-form" class="modal-content" method="POST">
                        <div class="modal-body p-5 pb-4">
                            <div class="text-start">
                                <h4>Você tem certeza?</h4>
                                <p class="text-muted">Essa ação não pode ser desfeita. Todas as suas mensagens nesta conversa serão apagadas.</p>
                            </div>
                        </div>
                        <div class="modal-footer d-flex border-top-0 mt-0">
                            <div class="col">
                                <button id="apaga-btn" type="submit" class="btn btn-danger w-100">Apagar</button>
                            </div>
                            <div class="col">
                                <button type="button" class="btn bg-body-secondary w-100" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </form>
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
        const tabs = $('input[data-tab]');

        tabs.on('change', function() {
            $('ul').toggleClass('d-none');
        })

        <?php if (isset($_GET['id'])): ?>
            const msgForm = $('#msg-form');
            const msg = $('#mensagem-input')
            const sendBtn = msgForm.find('button[type="submit"]')

            msg.on('input', function() {
                if ($(this).val().length > 0) {
                    sendBtn.prop('disabled', false)
                } else {
                    sendBtn.prop('disabled', true)
                }
            })

            // Handler para "Apagar mensagens" do menu dropdown
            $('[data-action="apagar-todas"]').on('click', function(e) {
                e.preventDefault();
                
                // Coletar TODAS as mensagens (tanto enviadas quanto recebidas)
                const todasAsMensagens = [];
                $('.mensagem').each(function() {
                    todasAsMensagens.push($(this).data('id-msg'));
                });

                if (todasAsMensagens.length === 0) {
                    mostrarAlerta('danger', 'Nenhuma mensagem para apagar');
                    return;
                }

                $('#apagar-form').data('msg-ids', todasAsMensagens);
                const apagarModal = new bootstrap.Modal(document.getElementById('apaga-modal'));
                apagarModal.show();
            });


            msgForm.on('submit', function(e) {
                e.preventDefault();
                $.post('../controladores/mensagens/enviar.php', {
                    de: <?= $_SESSION['id'] ?>,
                    para: <?= $_GET['id'] ?>,
                    anuncio: <?= $_GET['anuncio_id'] ?>,
                    texto: msg.val()
                }, function(res) {
                    location.reload();
                })
            })
        <?php endif ?>

        $('.chat-container').scrollTop($('.chat-container')[0].scrollHeight);

        const dropdownElement = document.getElementById('dropdown-mensagem');
        let dropdownInstance = null;

        // Função para limpar eventos
        function limparEventosContextMenu() {
            $(document).off('click.contextmenu keydown.contextmenu scroll.contextmenu');
            $(dropdownElement).off('hidden.bs.dropdown');
        }

        // Detectar botão direito nas mensagens
        $(document).on('contextmenu', '.mensagem', function(e) {
            e.preventDefault();

            // Fechar dropdown anterior
            if (dropdownInstance) {
                dropdownInstance.hide();
                limparEventosContextMenu();
            }

            const mensagemElement = $(this);
            const mensagemId = mensagemElement.data('id-msg');
            const apagadaDe = mensagemElement.data('apagada-de');
            const isSuaMensagem = mensagemElement.hasClass('mensagem-sua');
            const textoMensagem = mensagemElement.find('p').text().trim();

            // Criar nova instância do dropdown
            dropdownInstance = new bootstrap.Dropdown(dropdownElement, {
                reference: {
                    getBoundingClientRect: () => ({
                        width: 0,
                        height: 0,
                        top: e.clientY,
                        right: e.clientX,
                        bottom: e.clientY,
                        left: e.clientX
                    })
                }
            });

            // Armazenar dados
            $(dropdownElement)
                .data('mensagem-id', mensagemId)
                .data('mensagem-texto', textoMensagem)
                .data('sua-mensagem', isSuaMensagem)
                .data('apagada-de', apagadaDe);

            // Mostrar dropdown
            dropdownInstance.show();

            // Fechar ao clicar fora
            $(document).on('click.contextmenu', function(clickEvent) {
                if (!$(clickEvent.target).closest('#dropdown-mensagem').length) {
                    dropdownInstance.hide();
                    limparEventosContextMenu();
                }
            });

            // Fechar com ESC
            $(document).on('keydown.contextmenu', function(keyEvent) {
                if (keyEvent.key === 'Escape') {
                    dropdownInstance.hide();
                    limparEventosContextMenu();
                }
            });
        });

        // Ações do dropdown
        $('#dropdown-mensagem .dropdown-item').on('click', function(e) {
            e.preventDefault();

            const action = $(this).data('action');

            if (action === 'apagar-todas') {
                // Coletar TODAS as mensagens da conversa
                const todasAsMensagens = [];
                $('.mensagem').each(function() {
                    todasAsMensagens.push($(this).data('id-msg'));
                });

                if (todasAsMensagens.length === 0) {
                    mostrarAlerta('danger', 'Nenhuma mensagem para apagar');
                    return;
                }

                $('#apagar-form').data('msg-ids', todasAsMensagens);
                const apagarModal = new bootstrap.Modal(document.getElementById('apaga-modal'));
                apagarModal.show();
            } else if (action != 'apagar') {
                const mensagemId = $(dropdownElement).data('mensagem-id');
                const textoMensagem = $(dropdownElement).data('mensagem-texto');
                const isSuaMensagem = $(dropdownElement).data('sua-mensagem');
                executarAcaoMensagem(action, mensagemId, textoMensagem, isSuaMensagem);
            }

            if (dropdownInstance) {
                dropdownInstance.hide();
                limparEventosContextMenu();
            }
        });

        $('#apagar-form').on('submit', function(e) {
            e.preventDefault();

            const msgIds = $(this).data('msg-ids');

            if (msgIds && msgIds.length > 0) {
                // Sempre usar tipo=0 para double apagar
                apagarMultiplasMensagens(msgIds, 0);
            }

            // Fechar modal
            const apagarModal = bootstrap.Modal.getInstance(document.getElementById('apaga-modal'));
            apagarModal.hide();
        });

        let selecionando = false;
        const selectedMessages = [];

        function findSelectedIndexById(id) {
            return selectedMessages.findIndex(m => m.id == id);
        }

        function addSelectedMessage(obj) {
            if (findSelectedIndexById(obj.id) === -1) {
                selectedMessages.push(obj);
            }
            console.log('selectedMessages', selectedMessages);
        }

        function removeSelectedMessageById(id) {
            const idx = findSelectedIndexById(id);
            if (idx !== -1) {
                selectedMessages.splice(idx, 1);
            }
            console.log('selectedMessages', selectedMessages);
        }

        function ativarSelecao(msgID) {
            const msgElement = $(`.mensagem[data-id-msg="${msgID}"]`);
            if (msgElement.length === 0) {
                return;
            }
            selecionando = true;
            $('.chat-container').addClass('selecionando');
            $('#topbar').find('button').hide();
            $('#topbar').find('.sel-group').show();
            $('.mensagem').find('.form-check').css('display', 'flex');
            msgElement.addClass('bg-verde-escuro-subtle');
            msgElement.find('input[type="checkbox"]').prop('checked', true);

            const msgId = msgElement.data('id-msg');
            const msgPos = msgElement.data('msg-pos');
            const senderId = msgElement.data('sender-id');
            const senderName = msgElement.data('sender-name');
            const text = msgElement.find('p').text().trim();
            addSelectedMessage({
                id: msgId,
                senderName: senderName,
                senderId: senderId,
                text: text,
                msgPos: msgPos
            });
        }

        $(document).on('click', '.mensagem', function(e) {
            if (!selecionando) {
                return;
            }
            const isChecked = $(this).find('input[type="checkbox"]').is(':checked');
            const msgId = $(this).data('id-msg');
            const msgPos = $(this).data('msg-pos');
            const senderId = $(this).data('sender-id');
            const senderName = $(this).data('sender-name');
            const text = $(this).find('p').text().trim();

            if ($(e.target).is('input[type="checkbox"]') || $(e.target).is('label')) {
                if (isChecked) {
                    $(this).addClass('bg-verde-escuro-subtle');
                    addSelectedMessage({
                        id: msgId,
                        senderName: senderName,
                        senderId: senderId,
                        text: text,
                        msgPos: msgPos
                    });
                } else {
                    $(this).removeClass('bg-verde-escuro-subtle');
                    removeSelectedMessageById(msgId);
                }
            } else {
                if (isChecked) {
                    $(this).removeClass('bg-verde-escuro-subtle');
                    $(this).find('input[type="checkbox"]').prop('checked', false);
                    removeSelectedMessageById(msgId);
                } else {
                    $(this).addClass('bg-primary-subtle');
                    $(this).find('input[type="checkbox"]').prop('checked', true);
                    addSelectedMessage({
                        id: msgId,
                        senderName: senderName,
                        senderId: senderId,
                        text: text,
                        msgPos: msgPos
                    });
                }
            }
        });

        $('#topbar').find('button[data-action="cancelar"]').on('click', function() {
            selecionando = false;
            $('.chat-container').removeClass('selecionando');
            $('.mensagem').removeClass('bg-primary-subtle');
            $('.mensagem').find('input[type="checkbox"]').prop('checked', false);
            $('.mensagem').find('.form-check').css('display', 'none');
            $('#topbar').find('button').show();
            $('#topbar').find('.sel-group').hide();
            // Clear selected messages
            selectedMessages.length = 0;
            console.log('selectedMessages cleared', selectedMessages);
        });

        // Função para mostrar alerta
        function mostrarAlerta(tipo, mensagem) {
            const alertHtml = `
                <div class="position-fixed d-flex justify-content-center start-50 translate-middle-x mb-5 z-3 bottom-0">
                    <div class="alert alert-${tipo} alert-dismissible h-100 rounded-2" role="alert">
                        <div>
                            <span>
                                <i class="bi ${tipo === 'danger' ? 'bi-exclamation-triangle' : 'bi-check'}"></i>
                            </span> 
                            ${mensagem}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            const $alert = $(alertHtml);
            $('body').append($alert);
            
            $alert.delay(1500).fadeOut(1500, function() {
                $alert.remove();
            });
        }

        // Topbar buttons (copy and delete)
        $('#topbar').on('click', '.sel-group', function(e) {
            // detect copy button by icon
            if ($(this).find('.bi-copy').length) {
                if (selectedMessages.length === 0) {
                    mostrarAlerta('danger', 'Nenhuma mensagem selecionada');
                    return;
                }

                if (selectedMessages.length === 1) {
                    const text = selectedMessages[0].text;
                    // use existing copiarTexto function for single
                    navigator.clipboard.writeText(text).then(function() {
                        mostrarAlerta('success', 'Mensagem copiada');
                        // Deselecionar
                        selecionando = false;
                        $('.chat-container').removeClass('selecionando');
                        $('.mensagem').removeClass('bg-primary-subtle');
                        $('.mensagem').find('input[type="checkbox"]').prop('checked', false);
                        $('.mensagem').find('.form-check').css('display', 'none');
                        $('#topbar').find('button').show();
                        $('#topbar').find('.sel-group').hide();
                        selectedMessages.length = 0;
                    }).catch(function() {
                        // Fallback para navegadores antigos
                        const textArea = document.createElement('textarea');
                        textArea.value = text;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);
                        mostrarAlerta('success', 'Mensagem copiada');
                        // Deselecionar
                        selecionando = false;
                        $('.chat-container').removeClass('selecionando');
                        $('.mensagem').removeClass('bg-primary-subtle');
                        $('.mensagem').find('input[type="checkbox"]').prop('checked', false);
                        $('.mensagem').find('.form-check').css('display', 'none');
                        $('#topbar').find('button').show();
                        $('#topbar').find('.sel-group').hide();
                        selectedMessages.length = 0;
                    });
                    return;
                }

                // multiple: sort by msgPos and format "Sender: message" per line
                const sorted = selectedMessages.slice().sort((a, b) => parseInt(a.msgPos, 10) - parseInt(b.msgPos, 10));
                const lines = sorted.map(m => `${m.senderName}: ${m.text}`);
                const finalText = lines.join('\n');

                // try clipboard API
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(finalText).then(function() {
                        mostrarAlerta('success', 'Mensagens copiadas');
                        // Deselecionar
                        selecionando = false;
                        $('.chat-container').removeClass('selecionando');
                        $('.mensagem').removeClass('bg-primary-subtle');
                        $('.mensagem').find('input[type="checkbox"]').prop('checked', false);
                        $('.mensagem').find('.form-check').css('display', 'none');
                        $('#topbar').find('button').show();
                        $('#topbar').find('.sel-group').hide();
                        selectedMessages.length = 0;
                    }).catch(function() {
                        // fallback
                        const ta = document.createElement('textarea');
                        ta.value = finalText;
                        document.body.appendChild(ta);
                        ta.select();
                        document.execCommand('copy');
                        document.body.removeChild(ta);
                        mostrarAlerta('success', 'Mensagens copiadas');
                        // Deselecionar
                        selecionando = false;
                        $('.chat-container').removeClass('selecionando');
                        $('.mensagem').removeClass('bg-primary-subtle');
                        $('.mensagem').find('input[type="checkbox"]').prop('checked', false);
                        $('.mensagem').find('.form-check').css('display', 'none');
                        $('#topbar').find('button').show();
                        $('#topbar').find('.sel-group').hide();
                        selectedMessages.length = 0;
                    });
                } else {
                    const ta = document.createElement('textarea');
                    ta.value = finalText;
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    document.body.removeChild(ta);
                    mostrarAlerta('success', 'Mensagens copiadas');
                    // Deselecionar
                    selecionando = false;
                    $('.chat-container').removeClass('selecionando');
                    $('.mensagem').removeClass('bg-primary-subtle');
                    $('.mensagem').find('input[type="checkbox"]').prop('checked', false);
                    $('.mensagem').find('.form-check').css('display', 'none');
                    $('#topbar').find('button').show();
                    $('#topbar').find('.sel-group').hide();
                    selectedMessages.length = 0;
                }
            } else if ($(this).find('.bi-trash').length) {
                // Delete button
                if (selectedMessages.length === 0) {
                    mostrarAlerta('danger', 'Nenhuma mensagem selecionada');
                    return;
                }

                // Verificar se há alguma mensagem que NÃO é do usuário
                const temMensagemDoDestinatario = selectedMessages.some(msg => !$(`.mensagem[data-id-msg="${msg.id}"]`).hasClass('mensagem-sua'));
                
                $('#apagar-form').data('msg-ids', selectedMessages.map(m => m.id));
                $('#apagar-mim').prop('checked', true);

                // Se há mensagem do destinatário, não permite apagar para todos
                const podeApagarParaTodos = !temMensagemDoDestinatario;

                $('#apagar-todos').prop('disabled', !podeApagarParaTodos);

                if (!podeApagarParaTodos) {
                    $('#apagar-todos').parent().hide();
                    $('#apagar-mim').parent().hide();
                    $('#apaga-btn').text('Apagar para mim');
                } else {
                    $('#apagar-todos').parent().show();
                    $('#apagar-mim').parent().show();
                    $('#apaga-btn').text('Apagar');
                }

                // Mostrar modal
                const apagarModal = new bootstrap.Modal(document.getElementById('apaga-modal'));
                apagarModal.show();
            }
        });

        function executarAcaoMensagem(action, mensagemId, textoMensagem, tipo) {
            switch (action) {
                case 'copiar':
                    copiarTexto(textoMensagem);
                    break;

                case 'responder':
                    responderMensagem(mensagemId, textoMensagem);
                    break;

                case 'apagar':
                    apagarMensagem(mensagemId, tipo);
                    break;

                case 'selecionar':
                    ativarSelecao(mensagemId);
                    break;
            }
        }

        function responderMensagem(mensagemId, texto) {
            // Adicionar citação no input de mensagem
            const inputMensagem = $('#input-mensagem');
            const resposta = `> ${texto}\n\n`;
            inputMensagem.val(resposta + inputMensagem.val());
            inputMensagem.focus();

            mostrarAlerta('info', '↩️ Respondendo à mensagem...');
        }

        function apagarMensagem(mensagemId, tipo) {
            $.post('../controladores/mensagens/apagar-msg.php', {
                msg_ids: JSON.stringify([mensagemId]),
                tipo: tipo
            }).done(function(response) {
                location.reload();
                if (response.success) {
                    $(`.mensagem[data-id-msg="${mensagemId}"]`).fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            }).fail(function() {
                //mostrarFeedback('❌ Erro de conexão');
            });
        }

        function apagarMultiplasMensagens(msgIds, tipo) {
            $.post('../controladores/mensagens/apagar-msg.php', {
                msg_ids: JSON.stringify(msgIds),
                tipo: tipo
            }).done(function(response) {
                location.reload();
                if (response.success) {
                    msgIds.forEach(id => {
                        $(`.mensagem[data-id-msg="${id}"]`).fadeOut(300, function() {
                            $(this).remove();
                        });
                    });
                }
            }).fail(function() {
                mostrarAlerta('danger', 'Erro ao apagar mensagens');
            });
        }

        // ===== SISTEMA DE TIMESTAMP GLOBAL OTIMIZADO =====
        // Configurar timezone para São Paulo
        const timezoneSaoPaulo = 'America/Sao_Paulo';
        
        function getTimestampSaoPaulo() {
            // Pega o timestamp atual e converte para São Paulo
            const agora = new Date();
            // Criar um formatter para São Paulo
            const formatter = new Intl.DateTimeFormat('pt-BR', {
                timeZone: timezoneSaoPaulo,
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            
            const parts = formatter.formatToParts(agora);
            const valores = {};
            parts.forEach(part => {
                valores[part.type] = part.value;
            });
            
            // Converter de volta para timestamp
            const dataSP = new Date(
                valores.year,
                parseInt(valores.month) - 1,
                valores.day,
                valores.hour,
                valores.minute,
                valores.second
            );
            
            return Math.floor(dataSP.getTime() / 1000);
        }
        
        function formatarTempoRelativo(timestampMensagem) {
            const agora = getTimestampSaoPaulo();
            const diferenca = agora - timestampMensagem;
            
            // Menos de 1 minuto
            if (diferenca < 60) {
                return 'agora';
            }
            
            // Minutos
            if (diferenca < 3600) {
                const minutos = Math.floor(diferenca / 60);
                return minutos === 1 ? '1 min' : minutos + ' min';
            }
            
            // Horas
            if (diferenca < 86400) {
                const horas = Math.floor(diferenca / 3600);
                return horas === 1 ? '1 h' : horas + ' h';
            }
            
            // Dias
            if (diferenca < 604800) {
                const dias = Math.floor(diferenca / 86400);
                return dias === 1 ? '1 dia' : dias + ' dias';
            }
            
            // Semanas
            if (diferenca < 2592000) {
                const semanas = Math.floor(diferenca / 604800);
                return semanas === 1 ? '1 sem' : semanas + ' sem';
            }
            
            // Meses (aproximado)
            if (diferenca < 31536000) {
                const meses = Math.floor(diferenca / 2592000);
                return meses === 1 ? '1 mês' : meses + ' mês';
            }
            
            // Anos
            const anos = Math.floor(diferenca / 31536000);
            return anos === 1 ? '1 ano' : anos + ' anos';
        }
        
        function calcularProximaAtualizacao(timestampMensagem) {
            const agora = getTimestampSaoPaulo();
            const diferenca = agora - timestampMensagem;
            
            // Se é "agora" (< 1 min), próxima atualização em 60 - diferenca segundos
            if (diferenca < 60) {
                return 60 - diferenca;
            }
            
            // Se é minutos (< 60 min), próxima atualização em 60 segundos
            if (diferenca < 3600) {
                return 60;
            }
            
            // Se é horas (< 24 h), próxima atualização em 3600 segundos (1 hora)
            if (diferenca < 86400) {
                return 3600;
            }
            
            // Se é dias (< 7 dias), próxima atualização em 86400 segundos (1 dia)
            if (diferenca < 604800) {
                return 86400;
            }
            
            // Caso contrário, não precisa atualizar
            return Infinity;
        }
        
        function atualizarTemposRelativos() {
            const agora = getTimestampSaoPaulo();
            let proximaAtualizacao = Infinity;
            
            $('.list-group-item').each(function() {
                const $item = $(this);
                const timestamp = parseInt($item.data('timestamp'));
                const $tempo = $item.find('.tempo-relativo');
                
                // Calcular próxima atualização para este item
                const tempoProximo = calcularProximaAtualizacao(timestamp);
                
                // Atualizar o texto do tempo
                $tempo.text(formatarTempoRelativo(timestamp));
                
                // Manter registro da próxima atualização mais próxima
                if (tempoProximo < proximaAtualizacao) {
                    proximaAtualizacao = tempoProximo;
                }
            });
            
            // Agendar próxima atualização (com margem de 100ms para precisão)
            if (proximaAtualizacao !== Infinity) {
                setTimeout(atualizarTemposRelativos, proximaAtualizacao * 1000 + 100);
            }
        }
        
        // Inicializar tempos ao carregar a página
        atualizarTemposRelativos();

        let ultimoEstado = ""; // para comparar mudanças

        setInterval(() => {
            console.log(ultimoEstado)
            $.post('../controladores/mensagens/atualizar.php', {
                anuncio: <?= isset($_GET['anuncio_id']) ? $_GET['anuncio_id'] : 'null' ?>
            }).done(function(response) {
                try {
                    let data = JSON.parse(response);

                    // Ordena os dados para garantir consistência (evita diferenças por ordem)
                    let estadoAtual = JSON.stringify(
                        data.map(item => ({
                            anuncio_id: item.anuncio_id,
                            nao_lidas_comprador: item.nao_lidas_comprador ?? 0,
                            nao_lidas_vendedor: item.nao_lidas_vendedor ?? 0
                        })).sort((a, b) => a.anuncio_id - b.anuncio_id)
                    );

                    if (estadoAtual !== ultimoEstado) {
                        // Só recarrega se o estado atual for diferente do anterior
                        if (ultimoEstado !== "") {
                            location.reload();
                        }
                        ultimoEstado = estadoAtual;
                    }
                } catch (e) {
                    console.error("Erro ao interpretar JSON:", e, response);
                }
            });
        }, 1000);


    })
</script>

</html>