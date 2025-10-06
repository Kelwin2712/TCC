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
        <div class="col" style="margin-left: calc(200px + 5vw);">
            <div class="container-fluid p-5">
                <div class="row">
                    <h2 class="pb-2 fw-semibold mb-0">Configurações da Loja</h2>
                    <p class="text-muted">Gerencie sua loja e suas preferências</p>
                </div>
                <div class="mb-5 d-flex flex-wrap gap-2">
                    <input type="radio" class="btn-check" name="telas" id="tela-1" autocomplete="off" checked>
                    <label class="btn btn-outline-dark w-auto rounded-pill px-3 shadow-sm" for="tela-1">Informações da Loja</label>
                    <input type="radio" class="btn-check" name="telas" id="tela-2" autocomplete="off">
                    <label class="btn btn-outline-dark w-auto rounded-pill px-3 shadow-sm" for="tela-2">Endereço</label>
                    <input type="radio" class="btn-check" name="telas" id="tela-3" autocomplete="off">
                    <label class="btn btn-outline-dark w-auto rounded-pill px-3 shadow-sm" for="tela-3">Funcionamento</label>
                    <input type="radio" class="btn-check" name="telas" id="tela-4" autocomplete="off">
                    <label class="btn btn-outline-dark w-auto rounded-pill px-3 shadow-sm" for="tela-4">Formas de pagamento</label>
                </div>
                <form action="../controladores/mudar-loja.php" class="row d-flex align-items-stretch" id="loja-form" method="POST">
                    <div class="col-4">
                        <h5>Perfil da Loja</h5>
                        <p class="text-muted">Defina as informações da sua loja</p>
                    </div>

                    <div class="col d-flex flex-column justify-content-between">
                        <div class="row row-cols-2 row-gap-3 mb-3">
                            <div class="col">
                                <label for="nome-loja-input" class="form-label">Nome da Loja</label>
                                <input type="text" class="form-control shadow-sm" name="nome_loja" id="nome-loja-input" placeholder="Nome da Loja" value="<?= $nome_loja ?>">
                            </div>
                            <div class="col">
                                <label for="cnpj-input" class="form-label">CNPJ</label>
                                <input type="text" class="form-control shadow-sm" name="cnpj" id="cnpj-input" placeholder="CNPJ" value="<?= $cnpj ?>">
                            </div>
                            <div class="col">
                                <label for="telefone-loja-input" class="form-label">Telefone Comercial</label>
                                <input type="text" class="form-control shadow-sm" name="telefone" id="telefone-loja-input" placeholder="Telefone" value="<?= $telefone ?>">
                            </div>
                            <div class="col">
                                <label for="email-loja-input" class="form-label">E-mail da Loja</label>
                                <input type="email" class="form-control shadow-sm" name="email" id="email-loja-input" placeholder="Email" value="<?= $email ?>">
                            </div>
                            <div class="col-12">
                                <label for="descricao-input" class="form-label">Descrição da Loja</label>
                                <textarea class="form-control shadow-sm" name="descricao" id="descricao-input" rows="3" placeholder="Fale um pouco sobre sua loja"><?= $descricao ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-auto d-flex flex-column justify-content-center">
                        <div class="ratio ratio-1x1 mb-3">
                            <img src="../img/logo-fahren-bg.jpg" alt="Logo da Loja"
                                class="rounded-circle img-fluid mb-3 object-fit-cover">
                        </div>

                        <div class="d-flex gap-2 w-100">
                            <button class="btn text-muted border rounded-pill w-100 shadow-sm">Alterar logo</button>
                            <button class="btn text-muted border rounded-pill shadow-sm"><i class="bi bi-eraser"></i></button>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-dark float-end shadow-sm" disabled>Salvar alterações&nbsp;&nbsp;<i class="bi bi-floppy"></i></button>
                    </div>
                </form>
                <hr class="my-5">
                <div class="row d-flex align-items-stretch flex-nowrap">
                    <div class="col-4">
                        <h5>Endereço</h5>
                        <p class="text-muted">Defina a localização da sua loja</p>
                    </div>

                    <div class="col d-flex flex-column justify-content-between">
                        <div class="row row-cols-2 row-gap-3 mb-3">
                            <div class="col">
                                <label for="estado-input" class="form-label">Estado</label>
                                <input type="text" list="estado-list" class="form-control shadow-sm" name="estado" id="estado-input" placeholder="Estado" value="<?= $estado ?>">
                                <datalist id="estado-list">
                                    <option value="Acre">
                                    <option value="Alagoas">
                                    <option value="Amapá">
                                    <option value="Amazonas">
                                    <option value="Bahia">
                                    <option value="Ceará">
                                    <option value="Distrito Federal">
                                    <option value="Espírito Santo">
                                    <option value="Goiás">
                                    <option value="Maranhão">
                                    <option value="Mato Grosso">
                                    <option value="Mato Grosso do Sul">
                                    <option value="Minas Gerais">
                                    <option value="Pará">
                                    <option value="Paraíba">
                                    <option value="Paraná">
                                    <option value="Pernambuco">
                                    <option value="Piauí">
                                    <option value="Rio de Janeiro">
                                    <option value="Rio Grande do Norte">
                                    <option value="Rio Grande do Sul">
                                    <option value="Rondônia">
                                    <option value="Roraima">
                                    <option value="Santa Catarina">
                                    <option value="São Paulo">
                                    <option value="Sergipe">
                                    <option value="Tocantins">
                                </datalist>
                            </div>
                            <div class="col">
                                <label for="cidade-input" class="form-label">Cidade</label>
                                <input type="text" class="form-control shadow-sm" name="cidade" id="cidade-input" placeholder="Cidade" value="<?= $cidade ?>">
                            </div>
                            <div class="col-12">
                                <label for="endereco-input" class="form-label">Endereço completo</label>
                                <input type="text" class="form-control shadow-sm" name="endereco" id="endereco-input" placeholder="Rua, número, bairro" value="<?= $endereco ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-5">
                <div class="row d-flex align-items-center flex-nowrap">
                    <div class="col">
                        <h5>Deletar Loja</h5>
                        <p class="text-muted">Delete sua loja permanentemente</p>
                    </div>
                    <div class="col-auto d-flex flex-column justify-content-between">
                        <div class="row row-gap-3 mb-3">
                            <div class="col">
                                <button class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#delete-modal">Deletar loja&nbsp;<i class="bi bi-trash"></i></button>
                                <div class="modal fade" id="delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="../controladores/deletar-loja.php" class="modal-content" method="POST">
                                            <div class="modal-body p-5">
                                                <div class="bg-danger-subtle rounded-circle d-flex justify-content-center align-items-center mb-3 mx-auto fs-5" style="width: 60px; height: 60px;">
                                                    <i class="bi bi-trash"></i>
                                                </div>
                                                <h4 class="text-center">Deletar loja</h4>
                                                <p class="text-center small text-danger">ATENÇÃO!<br>essa ação é permanente e não poderá ser desfeita</p>
                                                <p class="mb-5 text-muted">Ao deletar sua loja, todos os seus dados e anúncios serão permanentemente removidos e não poderão ser recuperados</p>
                                                <label for="senha-deletar-input" class="form-label">Confirme com sua senha</label>
                                                <input type="password" class="form-control shadow-sm" id="senha-deletar-input" name="senha" placeholder="Senha" required>
                                            </div>
                                            <div class="modal-footer border-top-0">
                                                <button type="button" class="btn bg-body-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button id="delete-btn" type="submit" class="btn btn-danger" disabled>Deletar loja</button>
                                            </div>
                                        </form>
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