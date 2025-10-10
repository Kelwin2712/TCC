<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../");
    exit;
}
include('../controladores/conexao_bd.php');
$sql = "SELECT * FROM usuarios";
$resultado = mysqli_query($conexao, $sql);

$usuarios = [];

if (mysqli_num_rows($resultado) > 0) {
    while ($linha = mysqli_fetch_array($resultado)) {
        $usuarios[] = $linha;
    }
} else {
    $_SESSION['msg_alert'] = ['danger', 'Não foi possível carregar as mensagens!'];
    header('Location: ../../index.php');
    exit();
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

    .mensagem-horario {
        font-size: calc(.5rem + .2vw);
    }
</style>

<body class="overflow-x-hidden">
    <?php include '../estruturas/modal/loja-modal.php'; ?>
    <?php include '../estruturas/alert/alert.php' ?>
    <main class="container-fluid d-flex vh-100 p-0">
        <?php $selected = 'mensagens';
        include_once '../estruturas/sidebar/sidebar.php' ?>
        <div class="col" style="margin-left: calc(200px + 5vw);">
            <div class="container-fluid p-5 d-flex flex-column h-100 overflow-auto">
                <div class="row flex-grow-1 overflow-hidden">
                    <div class="col-auto h-100">
                        <div class="card shadow-sm h-100" style="width: calc(150px + 10vw); max-height: 100%;">
                            <div class="card-body p-0 d-flex flex-column h-100">
                                <div class="card-header bg-transparent border-0 border-bottom p-4">
                                    <h2 class="pb-2 fw-semibold mb-3">Mensagens</h2>
                                    <div class="bg-body-tertiary position-relative rounded-2 shadow-sm">
                                        <div class="position-absolute top-50 translate-middle-y ps-3 text-muted">
                                            <i class="bi bi-search"></i>
                                        </div>
                                        <input type="text" class="form-control border-1" style="padding-left: 2.5rem;" placeholder="Pesquisar..." aria-label="Pesquisar..." aria-describedby="basic-addon1">
                                    </div>
                                </div>
                                <ul class="list-group list-group-flush flex-grow-1 overflow-y-auto">
                                    <?php
                                    foreach ($usuarios as $user): ?>
                                        <li class="list-group-item d-flex align-items-center gap-3">
                                            <div class="col-auto flex-shrink-0">
                                                <div class="ratio ratio-1x1" style="width: calc(30px + .5vw);">
                                                    <img src="../img/logo-fahren-bg.jpg" class="img-fluid rounded-circle" alt="Avatar">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <p class="mb-1 me-2 fs-6 fw-semibold text-truncate"><?=$user['nome'].' '.$user['sobrenome']?></p>
                                                    <small class="text-muted text-nowrap">3 dias</small>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="text-muted small mb-0 text-truncate">Mensagem</p>
                                                    <span class="badge bg-primary rounded-circle">1</span>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col h-100">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-0 d-flex flex-column h-100 justify-content-between">
                                <div class="card-header p-3 bg-transparent d-flex align-items-center justify-content-between gap-3">
                                    <div class="col-auto gap-3 d-flex align-items-center">
                                        <div class="col-auto flex-shrink-0">
                                            <div class="ratio ratio-1x1" style="width: calc(30px + .5vw);">
                                                <img src="../img/logo-fahren-bg.jpg" class="img-fluid rounded-circle" alt="Avatar">
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="mb-0">Nome do usuário</h6>
                                            <small class="text-muted"><span><i class="bi bi-circle-fill align-middle text-success" style="font-size: .4rem;"></i></span>&nbsp;Online</small>
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex align-items-center gap-3">
                                        <button class="btn border shadow-sm btn-sm"><i class="bi bi-telephone-fill"></i></button>
                                        <button class="btn border shadow-sm btn-sm"><i class="bi bi-whatsapp"></i></button>
                                        <button class="btn border shadow-sm btn-sm"><i class="bi bi-three-dots-vertical"></i></button>

                                    </div>
                                </div>
                                <div class="card-body overflow-auto px-3 chat-container" style="height: calc(100% - 120px);">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="d-flex flex-column align-items-start">
                                            <div class="bg-light rounded-3 p-3 shadow-sm mb-1">
                                                <p class="mb-0">Olá! Como posso ajudar você hoje?</p>
                                            </div>
                                            <small class="text-muted mt-1 mensagem-horario">10:00 AM</small>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <div class="bg-primary text-white rounded-3 p-3 shadow-sm mb-1">
                                                <p class="mb-0">Oi! Preciso de ajuda com meu pedido.</p>
                                            </div>
                                            <small class="text-muted mt-1 mensagem-horario">10:02 AM</small>
                                        </div>
                                        <div class="d-flex flex-column align-items-start">
                                            <div class="bg-light rounded-3 p-3 shadow-sm mb-1">
                                                <p class="mb-0">Claro! Qual é o número do seu pedido?</p>
                                            </div>
                                            <small class="text-muted mt-1 mensagem-horario">10:05 AM</small>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <div class="bg-primary text-white rounded-3 p-3 shadow-sm mb-1">
                                                <p class="mb-0">É 123456.</p>
                                            </div>
                                            <small class="text-muted mt-1 mensagem-horario">10:07 AM</small>
                                        </div>
                                        <div class="d-flex flex-column align-items-start">
                                            <div class="bg-light rounded-3 p-3 shadow-sm mb-1">
                                                <p class="mb-0">Obrigado! Vou verificar o status do seu pedido e retorno em breve.</p>
                                            </div>
                                            <small class="text-muted mt-1 mensagem-horario">10:10 AM</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer px-3 py-2 border-0 bg-transparent">
                                    <div class="border rounded-2 shadow-sm p-2 d-flex flex-column align-items-center">
                                        <div class="d-flex gap-1 align-items-center w-100">
                                            <button class="btn" type="button" id="anexar"><i class="bi bi-plus-lg"></i></button>
                                            <input type="text" class="form-control border-0 px-1" placeholder="Digite uma mensagem..." aria-label="Digite uma mensagem..." aria-describedby="button-send">
                                            <button class="btn btn-primary" type="button" id="button-send"><i class="bi bi-send-fill"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        $('li').on('click', function() {
            $('li.active').removeClass('active');
            $(this).addClass('active');
        });
    })
</script>

</html>