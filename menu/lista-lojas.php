<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../");
    exit;
}

$nome = $_SESSION['nome'] ?? '';
$sobrenome = $_SESSION['sobrenome'] ?? '';
$telefone = $_SESSION['telefone'] ?? '';
$cpf = $_SESSION['cpf'] ?? '';
$email = $_SESSION['email'] ?? '';
$data_nascimento = $_SESSION['data_nascimento'] ?? '';

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
        <?php $selected = 'loja';
        include_once '../estruturas/sidebar/sidebar.php' ?>
        <div class="col d-flex" style="margin-left: calc(200px + 5vw);">
            <div class="container p-5 d-flex justify-content-center align-items-center flex-grow-1">
                <div class="card p-3 w-100">
                    <div class="card-body">
                        <div class="mb-5">
                            <label for="exampleFormControlInput1" class="form-label">Suas lojas</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Procure as lojas que você está participando">
                        </div>
                        <ul class="list-group list-group-flush flex-grow-1 overflow-y-auto">
                                    <?php
                                    $quantidade = 10;
                                    for ($i = 1; $i <= $quantidade; $i++): ?>
                                        <li class="list-group-item d-flex align-items-center gap-3">
                                            <div class="col-auto flex-shrink-0">
                                                <div class="ratio ratio-1x1" style="width: calc(30px + .5vw);">
                                                    <img src="../img/logo-fahren-bg.jpg" class="img-fluid rounded-circle" alt="Avatar">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden d-flex justify-content-between align-items-center">
                                                <h6 class="mb-1 me-2 text-truncate">Loja</h6>
                                                <span><i class="bi bi-chevron-right"></i></span>
                                            </div>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="../script.js"></script>
<script>
    $(function() {
        $('#senha-deletar-input').on('input', function() {
            var senha = $(this).val();
            if (senha.length > 0) {
                $('#delete-btn').prop('disabled', false);
            } else {
                $('#delete-btn').prop('disabled', true);
            }
        });

        function checkForm() {
            var infos = [];
            $('#info-form input').each(function() {
                infos.push($(this).val().trim());
            });
            return infos;
        };

        let InfosAtuais = checkForm();

        $('form input').on('input', function() {
            let novos = checkForm();
            let iguais = InfosAtuais.length === novos.length &&
                InfosAtuais.every((val, i) => val === novos[i]);

            if (iguais) {
                $("#info-form button[type='submit']").prop('disabled', true);
            } else {
                $("#info-form button[type='submit']").prop('disabled', false);
            }
        });
    });
</script>

</html>