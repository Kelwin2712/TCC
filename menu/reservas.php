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

$sql = "SELECT carros.*, marcas.nome as marca_nome FROM anuncios_carros carros INNER JOIN marcas ON carros.marca = marcas.id WHERE carros.id_vendedor = $id";
$resultado = mysqli_query($conexao, $sql);

$carros = [];

$qtd_resultados = mysqli_num_rows($resultado) ?? 0;

while ($linha = mysqli_fetch_array($resultado)) {
    $carros[] = $linha;
}
 
 // Buscar reservas relacionadas aos veículos do usuário
 $sql = "SELECT r.*, a.modelo as modelo_veiculo, a.versao as versao_veiculo, a.marca as marca_veiculo, a.id_vendedor FROM reservas r INNER JOIN anuncios_carros a ON r.id_veiculo = a.id WHERE a.id_vendedor = $id ORDER BY r.criado_em DESC";
 $resultado = mysqli_query($conexao, $sql);
 $reservas = [];
 while ($linha = mysqli_fetch_assoc($resultado)) {
     $reservas[] = $linha;
 }
 
 $qtd_reservas = count($reservas);

// Formata telefone para exibição: (AA) NNNNN-NNNN — coloca o '-' antes dos 4 últimos dígitos
function format_phone_display($digits) {
    $digits_only = preg_replace('/\D+/', '', $digits);
    if ($digits_only === '') return '';
    if (strlen($digits_only) <= 2) return '(' . $digits_only . ')';
    $area = substr($digits_only, 0, 2);
    $rest = substr($digits_only, 2);
    $len = strlen($rest);
    if ($len <= 4) {
        // sem parte antes do '-' (ex.: (11) 1234)
        return '(' . $area . ') ' . $rest;
    }
    $last4 = substr($rest, -4);
    $firstPart = substr($rest, 0, $len - 4);
    return '(' . $area . ') ' . $firstPart . '-' . $last4;
}

// Retorna abreviação do dia da semana em português (minúscula)
function day_abbr_pt($date) {
    $w = date('w', strtotime($date)); // 0 (domingo) .. 6 (sábado)
    $map = ['dom','seg','ter','qua','qui','sex','sab'];
    return $map[$w] ?? '';
}

mysqli_close($conexao);
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
    <?php include '../estruturas/modal/loja-modal.php'; ?>
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
                    <a href="anuncios.php" class="btn border border-dark rounded-pill">Anúncios</a>
                    <a href="reservas.php" class="btn text-bg-dark rounded-pill">Reservas</a>
                    <a href="../vender-placa.php" class="btn btn-dark ms-auto rounded-pill shadow-sm d-none">+ Criar novo anúncio</a>
                </div>
                <div class="row row-cols-1 g-3">
                    <?php if (!empty($reservas)): ?>
                        <?php foreach ($reservas as $res): ?>
                            <?php
                                // Badge class por status
                                $badgeClass = 'bg-secondary-subtle text-secondary';
                                switch ($res['status']) {
                                    case 'confirmada': $badgeClass = 'bg-success-subtle text-success-emphasis'; break;
                                    case 'cancelada': $badgeClass = 'bg-danger-subtle text-danger-emphasis'; break;
                                    case 'realizada': $badgeClass = 'bg-info-subtle text-info-emphasis'; break;
                                    default: $badgeClass = 'bg-warning-subtle text-warning-emphasis'; break;
                                }
                                $data_display = day_abbr_pt($res['data']);
                                $dia = date('d', strtotime($res['data']));
                            ?>
                            <div class="col-12">
                                <div class="card rounded-5 reserva-card" role="button" data-id="<?= $res['id'] ?>"
                                     data-nome="<?= htmlspecialchars($res['nome'], ENT_QUOTES) ?>"
                                     data-telefone="<?= htmlspecialchars(format_phone_display($res['telefone']), ENT_QUOTES) ?>"
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
                                     data-status="<?= $res['status'] ?>"
                                     >
                                    <div class="card-body d-flex px-0 gap-4">
                                            <div class="d-flex flex-column text-center justify-content-center align-items-center px-4 border-end">
                                            <span class="fs-6 text-uppercase"><?= $data_display ?></span>
                                            <span class="fs-2 fw-bold"><?= $dia ?></span>
                                        </div>
                                        <div class="row row-cols-2 text-muted w-100 align-items-start">
                                            <div class="col-auto d-flex flex-column justify-content-center gap-2">
                                                <span class="text-dark fw-bold text-uppercase"><?= htmlspecialchars($res['marca_veiculo'] . ' ' . $res['modelo_veiculo'] . ' ' . $res['versao_veiculo'], ENT_QUOTES) ?></span>
                                                <span>Cliente: <span class="fw-semibold"><?= htmlspecialchars($res['nome'], ENT_QUOTES) ?></span></span>
                                                <small class="text-muted">Contato: <?= htmlspecialchars(format_phone_display($res['telefone']), ENT_QUOTES) ?> · <?= htmlspecialchars($res['email'], ENT_QUOTES) ?></small>
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
                                        <p class="mb-0">Nenhuma reserva encontrada.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
            <!-- Modal de visualização/edição de reserva -->
            <div class="modal fade" id="reserva-view-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reservaViewLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reservaViewLabel">Reserva</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <form id="reserva-edit-form">
                                <input type="hidden" name="id" id="reserva-id">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nome</label>
                                        <input type="text" id="reserva-nome" name="nome" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Telefone</label>
                                        <input type="tel" id="reserva-telefone" name="telefone" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" id="reserva-email" name="email" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Preferência de contato</label>
                                        <select id="reserva-preferencia" name="preferencia_contato" class="form-select">
                                            <option value="telefone">Telefone</option>
                                            <option value="whatsapp">WhatsApp</option>
                                            <option value="email">E-mail</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Data</label>
                                        <input type="date" id="reserva-data" name="data" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Hora</label>
                                        <input type="time" id="reserva-hora" name="hora" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Acompanhantes</label>
                                        <input type="number" id="reserva-acomp" name="acompanhantes_qtd" class="form-control" min="0">
                                    </div>
                                </div>

                                <hr>

                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label class="form-label">Estado</label>
                                        <select id="reserva-estado" name="estado" class="form-select">
                                            <option value="">Selecione um estado...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Cidade</label>
                                        <select id="reserva-cidade" name="cidade" class="form-select">
                                            <option value="">Selecione um município...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Bairro</label>
                                        <input type="text" id="reserva-bairro" name="bairro" class="form-control">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Rua</label>
                                        <input type="text" id="reserva-rua" name="rua" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Número</label>
                                        <input type="text" id="reserva-numero" name="numero" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Complemento</label>
                                        <input type="text" id="reserva-complemento" name="complemento" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">CEP</label>
                                        <input type="text" id="reserva-cep" name="cep" class="form-control">
                                    </div>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label">Observações</label>
                                    <textarea id="reserva-observacoes" name="observacoes" class="form-control" rows="3"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer d-flex flex-column border-top-0">
                            <div class="w-100 d-flex gap-2 mb-2">
                                <button type="button" class="btn btn-primary w-100" id="reserva-save">Salvar alterações</button>
                                <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">Fechar</button>
                            </div>
                            <div class="w-100 d-flex gap-2" id="reserva-actions">
                                <button type="button" class="btn btn-success w-100" id="reserva-confirm">Confirmar</button>
                                <button type="button" class="btn btn-danger w-100" id="reserva-cancel">Cancelar</button>
                            </div>
                            <div class="w-100 d-flex gap-2 d-none" id="reserva-after-confirm">
                                <button type="button" class="btn btn-outline-secondary w-50" id="reserva-to-pendente">Voltar para pendente</button>
                                <button type="button" class="btn btn-success w-50" id="reserva-to-realizada">Marcar como realizada</button>
                            </div>
                            <div class="w-100 d-flex gap-2 d-none" id="reserva-after-cancel">
                                <button type="button" class="btn btn-outline-secondary w-50" id="reserva-to-pendente-2">Voltar para pendente</button>
                                <button type="button" class="btn btn-danger w-50" id="reserva-delete">Excluir reserva</button>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Modal de delete padrão (anúncios) preservado abaixo -->
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
<script src="../script.js"></script>
<script>
    $(function() {
        // handler simples para modal de deletar anúncio (preservado)
        const buttonDelete = $('.bi-trash').parent();
        buttonDelete.on('click', function() {
            $('#id-delete').val($(this).data('id-delete'));
        })

        // Funções auxiliares
        function fillModalFromCard($card) {
            const id = $card.data('id');
            $('#reserva-id').val(id);
            $('#reserva-nome').val($card.data('nome'));
            $('#reserva-telefone').val($card.data('telefone'));
            $('#reserva-email').val($card.data('email'));
            $('#reserva-preferencia').val($card.data('preferencia_contato'));
            $('#reserva-data').val($card.data('data'));
            $('#reserva-hora').val($card.data('hora'));
            $('#reserva-acomp').val($card.data('acompanhantes_qtd'));
            // set state and city via helper (loads municipios when needed)
            setModalLocation($card.data('estado'), $card.data('cidade'));
            $('#reserva-bairro').val($card.data('bairro'));
            $('#reserva-rua').val($card.data('rua'));
            $('#reserva-numero').val($card.data('numero'));
            $('#reserva-complemento').val($card.data('complemento'));
            $('#reserva-cep').val($card.data('cep'));
            $('#reserva-observacoes').val($card.data('observacoes'));
            const status = $card.data('status');
            setActionButtons(status);
        }

        function setActionButtons(status) {
            $('#reserva-actions').removeClass('d-none');
            $('#reserva-after-confirm, #reserva-after-cancel').addClass('d-none');
            if (status === 'pendente') {
                $('#reserva-actions').removeClass('d-none');
            } else if (status === 'confirmada') {
                // após confirmar mostrar opções adicionais
                $('#reserva-actions').addClass('d-none');
                $('#reserva-after-confirm').removeClass('d-none');
            } else if (status === 'cancelada') {
                $('#reserva-actions').addClass('d-none');
                $('#reserva-after-cancel').removeClass('d-none');
            } else if (status === 'realizada') {
                $('#reserva-actions').addClass('d-none');
                // permitir apenas voltar a pendente
                $('#reserva-after-confirm').removeClass('d-none');
                $('#reserva-to-realizada').addClass('d-none');
            }
        }

        function updateCardStatus(id, status) {
            const $card = $('.reserva-card[data-id="' + id + '"]');
            const $badge = $card.find('.reserva-badge');
            $badge.text(status.charAt(0).toUpperCase() + status.slice(1));
            $card.data('status', status);
            // atualizar classes
            $badge.removeClass('bg-warning-subtle text-warning-emphasis bg-success-subtle text-success-emphasis bg-danger-subtle text-danger-emphasis bg-info-subtle text-info-emphasis bg-secondary-subtle text-secondary');
            switch (status) {
                case 'confirmada': $badge.addClass('bg-success-subtle text-success-emphasis'); break;
                case 'cancelada': $badge.addClass('bg-danger-subtle text-danger-emphasis'); break;
                case 'realizada': $badge.addClass('bg-info-subtle text-info-emphasis'); break;
                default: $badge.addClass('bg-warning-subtle text-warning-emphasis'); break;
            }
        }

        // Abrir modal ao clicar em cartão
        $(document).on('click', '.reserva-card', function() {
            const $card = $(this);
            fillModalFromCard($card);
            // show bootstrap modal
            const modalEl = document.getElementById('reserva-view-modal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });

        const dateInput = $('input[type="date"]')

        dateInput.attr('min', new Date().toISOString().split('T')[0]);
        dateInput.val(new Date().toISOString().split('T')[0]);

        // Salvar alterações (update)
        $('#reserva-save').on('click', function() {
            const data = $('#reserva-edit-form').serializeArray();
            data.push({name: 'action', value: 'update'});
            $.post('../controladores/reservas/atualizar-reserva.php', data)
                .done(function(resp) {
                    if (resp.success) {
                        const id = $('#reserva-id').val();
                        // atualizar dados do card localmente
                        const $card = $('.reserva-card[data-id="' + id + '"]');
                        $card.data('nome', $('#reserva-nome').val());
                        $card.data('telefone', $('#reserva-telefone').val());
                        $card.data('email', $('#reserva-email').val());
                        $card.data('preferencia_contato', $('#reserva-preferencia').val());
                        $card.data('data', $('#reserva-data').val());
                        $card.data('hora', $('#reserva-hora').val());
                        $card.data('acompanhantes_qtd', $('#reserva-acomp').val());
                        $card.data('estado', $('#reserva-estado').val());
                        $card.data('cidade', $('#reserva-cidade').val());
                        $card.data('bairro', $('#reserva-bairro').val());
                        $card.data('rua', $('#reserva-rua').val());
                        $card.data('numero', $('#reserva-numero').val());
                        $card.data('complemento', $('#reserva-complemento').val());
                        $card.data('cep', $('#reserva-cep').val());
                        $card.data('observacoes', $('#reserva-observacoes').val());
                        // atualizar visual do cliente
                        $card.find('.fw-semibold').first().text($('#reserva-nome').val());
                        // mostrar alerta simples
                        location.reload(); // para simplificar sincronização visual e mostrar msg em $_SESSION
                    } else {
                        alert('Erro: ' + resp.message);
                    }
                })
                .fail(function() { alert('Erro ao salvar.'); });
        });

        // Confirmar reserva
        $('#reserva-confirm').on('click', function() {
            const id = $('#reserva-id').val();
            $.post('../controladores/reservas/atualizar-reserva.php', {id: id, action: 'status', status: 'confirmada'})
                .done(function(resp) {
                    if (resp.success) {
                        updateCardStatus(id, 'confirmada');
                        setActionButtons('confirmada');
                    } else alert(resp.message);
                });
        });

        // Cancelar reserva
        $('#reserva-cancel').on('click', function() {
            const id = $('#reserva-id').val();
            $.post('../controladores/reservas/atualizar-reserva.php', {id: id, action: 'status', status: 'cancelada'})
                .done(function(resp) {
                    if (resp.success) {
                        updateCardStatus(id, 'cancelada');
                        setActionButtons('cancelada');
                    } else alert(resp.message);
                });
        });

        // Voltar para pendente (aparece em ambos casos)
        $('#reserva-to-pendente, #reserva-to-pendente-2').on('click', function() {
            const id = $('#reserva-id').val();
            $.post('../controladores/reservas/atualizar-reserva.php', {id: id, action: 'status', status: 'pendente'})
                .done(function(resp) {
                    if (resp.success) {
                        updateCardStatus(id, 'pendente');
                        setActionButtons('pendente');
                    } else alert(resp.message);
                });
        });

        // Marcar como realizada
        $('#reserva-to-realizada').on('click', function() {
            const id = $('#reserva-id').val();
            $.post('../controladores/reservas/atualizar-reserva.php', {id: id, action: 'status', status: 'realizada'})
                .done(function(resp) {
                    if (resp.success) {
                        updateCardStatus(id, 'realizada');
                        setActionButtons('realizada');
                    } else alert(resp.message);
                });
        });

        // Excluir reserva
        $('#reserva-delete').on('click', function() {
            if (!confirm('Tem certeza que deseja excluir esta reserva?')) return;
            const id = $('#reserva-id').val();
            $.post('../controladores/reservas/deletar-reserva.php', {id: id})
                .done(function(resp) {
                    if (resp.success) {
                        // remover cartão
                        $('.reserva-card[data-id="' + id + '"]').parent().fadeOut(300, function() { $(this).remove(); });
                        const modalEl = document.getElementById('reserva-view-modal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    } else alert(resp.message);
                })
                .fail(function() { alert('Erro ao excluir.'); });
        });

        // --- IBGE: popular selects de estados e municípios para reservas ---
        const ibgeEstadosUrl = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';

        function populateStates() {
            $.getJSON(ibgeEstadosUrl, function(estados) {
                estados.sort((a, b) => a.nome.localeCompare(b.nome));
                const $reservaEstados = $('#reserva-estado');
                $reservaEstados.empty().append('<option value="">Selecione um estado...</option>');
                $.each(estados, function(i, estado) {
                    const opt = `<option value="${estado.sigla}" data-id="${estado.id}">${estado.nome} (${estado.sigla})</option>`;
                    $reservaEstados.append(opt);
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
                    $mun.val(selectedCity);
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

        $(document).on('change', '#reserva-estado', function() { loadMunicipiosFor('#reserva-estado', '#reserva-cidade'); });

        function setModalLocation(stateUf, cityName, attempt = 0) {
            if (attempt > 12) return;
            if ($('#reserva-estado option').length <= 1) {
                setTimeout(function() { setModalLocation(stateUf, cityName, attempt + 1); }, 150);
                return;
            }
            if (!stateUf) {
                $('#reserva-estado').val('');
                $('#reserva-cidade').html('<option value="">Selecione um estado primeiro...</option>');
                return;
            }
            $('#reserva-estado').val(stateUf);
            loadMunicipiosFor('#reserva-estado', '#reserva-cidade', cityName);
        }

        populateStates();
    })
</script>

</html>