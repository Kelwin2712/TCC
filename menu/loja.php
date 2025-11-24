<?php
session_start();
include('../controladores/conexao_bd.php');

if (!isset($_SESSION['id']) || !isset($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$id_loja = $_GET['id'];

$sql = "SELECT * FROM lojas WHERE id = '$id_loja'";
$resultado = mysqli_query($conexao, $sql);

$loja = mysqli_fetch_assoc($resultado);

// determine permissions for current user on this loja
$can_edit = false;
$can_manage_members = false;
$is_owner = false;
if (isset($_SESSION['id']) && isset($loja['id'])) {
    $uid = (int) $_SESSION['id'];
    // try to read explicit owner_id (may not exist on older schemas)
    $owner_id = null;
    $res_owner = @mysqli_query($conexao, "SELECT owner_id FROM lojas WHERE id = " . (int)$loja['id'] . " LIMIT 1");
    if ($res_owner && mysqli_num_rows($res_owner) > 0) {
        $r_owner = mysqli_fetch_assoc($res_owner);
        if (isset($r_owner['owner_id'])) $owner_id = (int)$r_owner['owner_id'];
    }

    if ($owner_id && $owner_id === $uid) {
        $is_owner = true;
    }

    // read equipe row (if exists)
    $res_perm = @mysqli_query($conexao, "SELECT pode_editar_loja, pode_adicionar_membros, usuario_id, convidado_por FROM equipe WHERE loja_id = " . (int)$loja['id'] . " AND usuario_id = $uid LIMIT 1");
    if ($res_perm && mysqli_num_rows($res_perm) > 0) {
        $rperm = mysqli_fetch_assoc($res_perm);
        $can_edit = !empty($rperm['pode_editar_loja']) ? true : false;
        $can_manage_members = !empty($rperm['pode_adicionar_membros']) ? true : false;
        // fallback owner detection: the creator inserted into equipe with convidado_por IS NULL
        if (!$is_owner) {
            $res_creator = @mysqli_query($conexao, "SELECT usuario_id FROM equipe WHERE loja_id = " . (int)$loja['id'] . " AND status='A' AND convidado_por IS NULL LIMIT 1");
            if ($res_creator && mysqli_num_rows($res_creator) > 0) {
                $rcre = mysqli_fetch_assoc($res_creator);
                if ((int)$rcre['usuario_id'] === $uid) $is_owner = true;
            }
        }
    } else {
        // no equipe row for this user: check if they are the creator (fallback)
        $res_creator = @mysqli_query($conexao, "SELECT usuario_id FROM equipe WHERE loja_id = " . (int)$loja['id'] . " AND status='A' AND convidado_por IS NULL LIMIT 1");
        if ($res_creator && mysqli_num_rows($res_creator) > 0) {
            $rcre = mysqli_fetch_assoc($res_creator);
            if ((int)$rcre['usuario_id'] === $uid) {
                $is_owner = true; $can_edit = true; $can_manage_members = true;
            }
        }
    }

}

// leave connection open for subsequent queries (we'll close at end of script)
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Loja</title>
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
    <link rel="icon" type="png" href="../img/logo-oficial.png">
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        main {
            margin-left: calc(200px + 5vw);
            padding: 2rem;
        }

        #foto-edit-icon {
            width: 35px;
            height: 35px;
            cursor: pointer;
            position: absolute;
            top: 50%;
            right: -15px;
            transform: translateY(-50%);
        }

        #descricao-input:read-only {
            background-color: #f8f9fa;
            cursor: default;
        }

        #descricao-input.border-primary {
            border-width: 2px;
        }

        .titulo-e-botao {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: nowrap;
            margin-bottom: 1.5rem;
        }

        .botoes-acoes {
            display: flex;
            gap: 0.5rem;
        }

        /* Garantir que os botões fiquem iguais ao do config */
        .botoes-acoes .btn {
            height: 38px;
            /* ajuste para igualar */
            padding: 0 1rem;
            font-size: 0.875rem;
        }

        /* Carrossel menor */
        .carousel-inner img {
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
        }
    </style>
</head>

<body>
    <?php include '../estruturas/modal/loja-modal.php'; ?>
    <?php
    $selected = 'loja';
    $loja_id_selected = $_GET['id'];
    include_once '../estruturas/sidebar/sidebar.php';
    ?>

    <main>

            <div class="titulo-e-botao">
            <h2 class="fw-semibold mb-2 mb-md-0 mt-3">Minha Loja</h2>
            <div class="botoes-acoes">
                <?php if ($can_edit): ?>
                    <button class="btn btn-success d-flex align-items-center gap-1" id="save-btn">
                        <i class="bi bi-check-lg"></i>
                        <span>Salvar</span>
                    </button>
                    <?php if ($is_owner): ?>
                        <button id="delete-loja-btn" class="btn btn-outline-danger d-flex align-items-center gap-1 ms-2">
                            <i class="bi bi-trash"></i>
                            <span>Excluir loja</span>
                        </button>
                    <?php endif; ?>
                <?php else: ?>
                    <button id="leave-team-btn" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Sair da equipe</span>
                    </button>
                <?php endif; ?>
            </div>
            <!-- move form closing to include funcionamento table (removed stray closing to fix tab markup) -->
        </div>

        <!-- Linha do perfil -->
        <div class="d-flex align-items-center gap-3 mt-3 mb-5 position-relative flex-wrap">
            <div class="position-relative">
                <?php
                $logoPath = '../img/logo-fahren-bg.jpg';
                if (!empty($loja['logo']) && file_exists(__DIR__ . '/../img/lojas/' . $loja['id'] . '/' . $loja['logo'])) {
                    $logoPath = '../img/lojas/' . $loja['id'] . '/' . $loja['logo'];
                }
                ?>
                <img src="<?= $logoPath ?>" id="perfil-foto" class="rounded-circle"
                    style="width: 120px; height: 120px; object-fit: cover;">
                <input type="file" id="file" accept="image/*" class="d-none">
                <label for="file" id="foto-edit-icon"
                    class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center d-none">
                    <i class="bi bi-pencil-fill"></i>
                </label>
            </div>
            <div>
                <h3 class="fw-bold mb-1"><?= htmlspecialchars($loja['nome']) ?></h3>
                <div class="d-flex gap-4 text-muted small">
                    <span><strong>Data da criação:</strong> <span class="fw-medium"><?= date("d/m/Y", strtotime($loja['created_at'])); ?></span></span>
                    <span><strong>Seguidores:</strong> <span class="fw-medium"><?= (int)$loja['seguidores']; ?></span></span>
                </div>
            </div>
        </div>

        <div class="mb-5 d-flex flex-wrap gap-2">
            <input type="radio" class="btn-check" name="telas" id="tela-1" autocomplete="off" checked>
            <label class="btn btn-outline-dark w-auto rounded-pill px-3 shadow-sm" for="tela-1">Loja</label>
            <input type="radio" class="btn-check" name="telas" id="tela-2" autocomplete="off">
            <label class="btn btn-outline-dark w-auto rounded-pill px-3 shadow-sm" for="tela-2">Configurações</label>
            <input type="radio" class="btn-check" name="telas" id="tela-3" autocomplete="off">
            <label class="btn btn-outline-dark w-auto rounded-pill px-3 shadow-sm" for="tela-3">Funcionamento</label>
            <input type="radio" class="btn-check" name="telas" id="tela-4" autocomplete="off">
            <label class="btn btn-outline-dark w-auto rounded-pill px-3 shadow-sm" for="tela-4">Equipe</label>
        </div>

        <!-- Conteúdos das abas -->
        <?php
        // prepare horarios array if exists
        $horarios_arr = null;
        if (!empty($loja['horarios'])) {
            $tmp = json_decode($loja['horarios'], true);
            if (is_array($tmp)) $horarios_arr = $tmp;
        }
        ?>
        <div id="tela-contents" class="mt-3">
            <!-- Tela 1: visual / capa -->
            <div id="tela-content-1" class="tela-content">
                <?php
                $capaPath = '../img/banner/carousel-4.png';
                if (!empty($loja['capa']) && file_exists(__DIR__ . '/../img/lojas/' . $loja['id'] . '/' . $loja['capa'])) {
                    $capaPath = '../img/lojas/' . $loja['id'] . '/' . $loja['capa'];
                }
                ?>
                <div class="ratio w-100 border rounded shadow mb-2 rounded-5" style="--bs-aspect-ratio: 20%;">
                    <img src="<?= $capaPath ?>" class="object-fit-cover img-fluid rounded-5" alt="Capa da loja">
                </div>
                <?php
                // Lista de anúncios desta loja (mesmo layout de menu/anuncios.php)
                $lojaId = (int)$loja['id'];
                $sql_carros_loja = "SELECT carros.*, marcas.nome as marca_nome FROM anuncios_carros carros INNER JOIN marcas ON carros.marca = marcas.id WHERE carros.id_vendedor = $lojaId AND carros.tipo_vendedor = 1";
                $res_carros_loja = mysqli_query($conexao, $sql_carros_loja);
                $anuncios = [];
                $qtd_anuncios = 0;
                if ($res_carros_loja) {
                    $qtd_anuncios = mysqli_num_rows($res_carros_loja);
                    while ($row = mysqli_fetch_assoc($res_carros_loja)) { $anuncios[] = $row; }
                }
                ?>

                <div class="mt-4">
                    <h5 class="mb-3">Anúncios da loja</h5>
                    <div class="row row-cols-1 row-cols-xl-2 g-3">
                        <?php foreach ($anuncios as $carro): ?>
                            <div class="col">
                                <?php
                                $marca = $carro['marca_nome'];
                                $modelo = $carro['modelo'];
                                $versao = $carro['versao'];
                                $preco = $carro['preco'];
                                $ano = $carro['ano_fabricacao'] . '/' . $carro['ano_modelo'];
                                $km = is_numeric($carro['quilometragem']) ? number_format((int)$carro['quilometragem'], 0, ',', '.') : $carro['quilometragem'];
                                $cor = $carro['cor'];
                                $troca = $carro['aceita_troca'];
                                $revisao = $carro['revisao'];
                                $id = $carro['id'];
                                $loc = $carro['cidade'] . ' - '  . $carro['estado_local'];
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

                    <div class="row flex-grow-1 d-flex align-items-center <?php if ($qtd_anuncios > 0) { echo 'd-none'; } ?>">
                        <div class="text-center text-muted">
                            <p style="font-size: calc(2rem + 1.5vw) !important;"><i class="bi bi-x-circle-fill"></i></p>
                            <h4 class="mb-0">Nenhum anúncio encontrado</h4>
                            <p>Esta loja ainda não possui anúncios</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tela 2: Configurações (todos os campos exceto funcionamento) -->
            <div id="tela-content-2" class="tela-content d-none">
                <form id="config-form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= (int)$loja['id'] ?>">

                    <?php if ($can_edit): ?>
                    <h5 class="mb-3">Identidade Visual</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-12 d-flex align-items-center gap-4 flex-wrap">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="position-relative">
                                        <?php
                                        $logoPath = '../img/usuarios/avatares/user.png';
                                        if (!empty($loja['logo']) && file_exists(__DIR__ . '/../img/lojas/' . $loja['id'] . '/' . $loja['logo'])) {
                                            $logoPath = '../img/lojas/' . $loja['id'] . '/' . $loja['logo'];
                                        }
                                        ?>
                                        <img id="logo-preview-local" src="<?= $logoPath ?>" alt="Logo da loja" class="object-fit-cover rounded-circle border shadow-sm mb-2" width="128" height="128">
                                    </div>
                                    <small class="text-muted">Logo atual</small>
                                </div>
                                <div class="ms-auto d-flex flex-column align-items-end gap-2">
                                    <label for="logo-local" class="btn bg-body-secondary shadow-sm">
                                        <i class="bi bi-upload me-1"></i> Trocar logo
                                    </label>
                                    <input type="file" class="d-none" id="logo-local" name="logo_local" accept="image/*">
                                    <small class="text-muted">Formatos aceitos: PNG, JPG, até 10MB</small>
                                </div>
                            </div>
                            <div class="col-12 d-flex align-items-center gap-4 flex-wrap mt-3">
                                <div class="d-flex flex-column align-items-center" style="width:100%;max-width:50%;">
                                    <div class="ratio w-100 border rounded shadow-sm mb-2" style="--bs-aspect-ratio: 20%;">
                                        <?php
                                        $capaPathLocal = '../img/banner/carousel-1.png';
                                        if (!empty($loja['capa']) && file_exists(__DIR__ . '/../img/lojas/' . $loja['id'] . '/' . $loja['capa'])) {
                                            $capaPathLocal = '../img/lojas/' . $loja['id'] . '/' . $loja['capa'];
                                        }
                                        ?>
                                        <img class="object-fit-cover img-fluid" id="capa-preview-local" src="<?= $capaPathLocal ?>" alt="Capa do perfil">
                                    </div>
                                    <small class="text-muted">Capa atual</small>
                                </div>
                                <div class="ms-auto d-flex flex-column align-items-end gap-2">
                                    <label for="capa-local" class="btn bg-body-secondary shadow-sm">
                                        <i class="bi bi-upload me-1"></i> Trocar capa
                                    </label>
                                    <input type="file" class="d-none" id="capa-local" name="capa_local" accept="image/*">
                                    <small class="text-muted">Formatos aceitos: PNG, JPG, até 16MB</small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <h5 class="mb-3">Dados Cadastrais</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">Nome da loja</label>
                            <?php if ($can_edit): ?>
                                <input type="text" class="form-control shadow-sm config-input" name="nome" id="nome-input" value="<?= htmlspecialchars($loja['nome']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['nome'])): ?>
                                        <?= htmlspecialchars($loja['nome']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">Razão Social</label>
                            <?php if ($can_edit): ?>
                                <input type="text" class="form-control shadow-sm config-input" name="razao_social" id="razao-input" value="<?= htmlspecialchars($loja['razao_social']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['razao_social'])): ?>
                                        <?= htmlspecialchars($loja['razao_social']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">CNPJ</label>
                            <?php if ($can_edit): ?>
                                <input type="text" class="form-control shadow-sm config-input" name="cnpj" id="cnpj-input" value="<?= htmlspecialchars($loja['cnpj']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['cnpj'])): ?>
                                        <?= htmlspecialchars($loja['cnpj']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">Inscrição Estadual</label>
                            <?php if ($can_edit): ?>
                                <input type="text" class="form-control shadow-sm config-input" name="inscricao_estadual" id="insc-input" value="<?= htmlspecialchars($loja['inscricao_estadual']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['inscricao_estadual'])): ?>
                                        <?= htmlspecialchars($loja['inscricao_estadual']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">Descrição da loja</label>
                            <?php if ($can_edit): ?>
                                <textarea class="form-control shadow-sm config-input bg-transparent" name="descricao_loja" id="descricao-input" rows="3" maxlength="1000"><?= htmlspecialchars($loja['descricao_loja']) ?></textarea>
                            <?php else: ?>
                                <div class="border rounded p-2 bg-white text-muted" id="descricao-display">
                                    <?php if (!empty($loja['descricao_loja'])): ?>
                                        <?= nl2br(htmlspecialchars($loja['descricao_loja'])) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h5 class="mb-3">Localização</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-4">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">Número</label>
                            <?php if ($can_edit): ?>
                                <input type="text" class="form-control shadow-sm config-input" name="numero" id="numero-input" value="<?= htmlspecialchars($loja['numero']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['numero'])): ?>
                                        <?= htmlspecialchars($loja['numero']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-4">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">CEP</label>
                            <?php if ($can_edit): ?>
                                <input type="text" class="form-control shadow-sm config-input" name="cep" id="cep-input" value="<?= htmlspecialchars($loja['cep']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['cep'])): ?>
                                        <?= htmlspecialchars($loja['cep']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-4">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">Bairro</label>
                            <?php if ($can_edit): ?>
                                <input type="text" class="form-control shadow-sm config-input" name="bairro" id="bairro-input" value="<?= htmlspecialchars($loja['bairro']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['bairro'])): ?>
                                        <?= htmlspecialchars($loja['bairro']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">Estado (UF)</label>
                            <?php if ($can_edit): ?>
                                <select id="config-estado" name="estado" class="form-select shadow-sm">
                                    <option value="">Selecione um estado...</option>
                                </select>
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['estado'])): ?>
                                        <?= htmlspecialchars($loja['estado']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">Cidade</label>
                            <?php if ($can_edit): ?>
                                <select id="config-cidade" name="cidade" class="form-select shadow-sm"> 
                                    <option value=""><?= htmlspecialchars('Selecione o estado antes...') ?></option>
                                </select>
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['cidade'])): ?>
                                        <?= htmlspecialchars($loja['cidade']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h5 class="mb-3">Contato Comercial</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">Telefone fixo</label>
                            <?php if ($can_edit): ?>
                                <input type="text" class="form-control shadow-sm config-input" name="telefone_fixo" id="telfixo-input" value="<?= htmlspecialchars($loja['telefone_fixo']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['telefone_fixo'])): ?>
                                        <?= htmlspecialchars($loja['telefone_fixo']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">WhatsApp comercial</label>
                            <?php if ($can_edit): ?>
                                <input type="text" class="form-control shadow-sm config-input" name="whatsapp" id="whatsapp-input" value="<?= htmlspecialchars($loja['whatsapp']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['whatsapp'])): ?>
                                        <?= htmlspecialchars($loja['whatsapp']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">E-mail corporativo</label>
                            <?php if ($can_edit): ?>
                                <input type="email" class="form-control shadow-sm config-input" name="email_corporativo" id="email-input" value="<?= htmlspecialchars($loja['email_corporativo']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['email_corporativo'])): ?>
                                        <?= htmlspecialchars($loja['email_corporativo']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">Site</label>
                            <?php if ($can_edit): ?>
                                <input type="url" class="form-control shadow-sm config-input" name="site" id="site-input" value="<?= htmlspecialchars($loja['site']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['site'])): ?>
                                        <?= htmlspecialchars($loja['site']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">Instagram</label>
                            <?php if ($can_edit): ?>
                                <input type="text" class="form-control shadow-sm config-input" name="instagram" id="insta-input" value="<?= htmlspecialchars($loja['instagram']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['instagram'])): ?>
                                        <?= htmlspecialchars($loja['instagram']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label <?= $can_edit ? '' : 'fw-semibold' ?>">Facebook</label>
                            <?php if ($can_edit): ?>
                                <input type="text" class="form-control shadow-sm config-input" name="facebook" id="fb-input" value="<?= htmlspecialchars($loja['facebook']) ?>">
                            <?php else: ?>
                                <div class="form-control-plaintext text-muted">
                                    <?php if (!empty($loja['facebook'])): ?>
                                        <?= htmlspecialchars($loja['facebook']) ?>
                                    <?php else: ?>
                                        <small class="fst-italic text-muted">Não informado</small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                        <!-- end config form content -->
                    </div>

            <!-- Tela 3: Funcionamento (tabela por dia) -->
            <div id="tela-content-3" class="tela-content d-none">
                <h5 class="mb-3">Funcionamento (por dia)</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="ps-2">Aberto?</th>
                                <th class="ps-2">Dia</th>
                                <th class="ps-2">Abre</th>
                                <th class="ps-2">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $dias = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
                            for ($i=0;$i<7;$i++):
                                $abre = '';
                                $fecha = '';
                                $aberto = 0;
                                if (is_array($horarios_arr)) {
                                    $entry = isset($horarios_arr[$i]) ? $horarios_arr[$i] : null;
                                    if ($entry) {
                                        $aberto = !empty($entry['aberto']) ? 1 : 0;
                                        $abre = isset($entry['abre']) && $entry['abre'] !== null ? $entry['abre'] : '';
                                        $fecha = isset($entry['fecha']) && $entry['fecha'] !== null ? $entry['fecha'] : '';
                                    }
                                } else {
                                    // fallback to global hours + dias_funcionamento
                                    $dias_sel = array_filter(explode(',', $loja['dias_funcionamento']));
                                    $dias_sel = array_map('intval', $dias_sel);
                                    if (in_array($i+1, $dias_sel)) {
                                        $aberto = 1;
                                        $abre = $loja['hora_abre'];
                                        $fecha = $loja['hora_fecha'];
                                    }
                                }
                            ?>
                            <tr>
                                    <td class="ps-2 text-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input config-input" type="checkbox" name="horarios[<?= $i ?>][aberto]" value="1" <?= $aberto ? 'checked' : '' ?> <?= $can_edit ? '' : 'disabled' ?>>
                                        </div>
                                    </td>
                                <td class="ps-2"><?= $dias[$i] ?></td>
                                    <td>
                                        <?php if ($can_edit): ?>
                                            <input type="time" name="horarios[<?= $i ?>][abre]" class="form-control form-control-sm config-input" value="<?= htmlspecialchars($abre) ?>">
                                        <?php else: ?>
                                            <div class="text-muted small"><?= $abre ? htmlspecialchars($abre) : '&mdash;' ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($can_edit): ?>
                                            <input type="time" name="horarios[<?= $i ?>][fecha]" class="form-control form-control-sm config-input" value="<?= htmlspecialchars($fecha) ?>">
                                        <?php else: ?>
                                            <div class="text-muted small"><?= $fecha ? htmlspecialchars($fecha) : '&mdash;' ?></div>
                                        <?php endif; ?>
                                    </td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>

                    </form>

            <!-- Tela 4: Equipe -->
            <div id="tela-content-4" class="tela-content d-none">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Equipe</h5>
                    <div>
                        <?php if ($can_edit): ?>
                            <button type="button" class="btn btn-primary" id="open-add-member">Adicionar membro</button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- permissions explanation removed (clarity placed in-table via tooltips) -->
                <!-- Members table -->
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-striped" id="membros-table">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <?php if ($can_edit): ?>
                                    <th>Status</th>
                                    <th class="text-end">Ações</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // server-side initial render of members so non-edit users see the list immediately
                            $sql_m = "SELECT e.usuario_id AS id, u.nome, u.email, u.avatar, e.pode_editar_anuncio, e.pode_responder_mensagem, e.pode_editar_loja, e.pode_adicionar_membros, e.status, e.convidado_por
                                FROM equipe e
                                LEFT JOIN usuarios u ON u.id = e.usuario_id
                                WHERE e.loja_id = " . (int)$loja['id'] . " ORDER BY e.status ASC, u.nome ASC";
                            $resm = mysqli_query($conexao, $sql_m);
                            if ($resm) {
                                while ($m = mysqli_fetch_assoc($resm)) {
                                    $avatar = '../img/usuarios/avatares/user.png';
                                    if (!empty($m['avatar']) && is_file(__DIR__ . '/../' . $m['avatar'])) {
                                        $avatar = '../' . $m['avatar'];
                                    }
                                    ?>
                                    <tr>
                                        <td class="d-flex align-items-center gap-3"><img src="<?= $avatar ?>" width="48" height="48" class="rounded-circle"><div><div><?= htmlspecialchars($m['nome']) ?></div><small class="text-muted"><?= htmlspecialchars($m['email']) ?></small></div></td>
                                        <?php if ($can_edit): ?>
                                            <td>
                                                <?php
                                                    $statusHtml = '<span class="badge bg-secondary">Desconhecido</span>';
                                                    if ($m['status'] === 'P') $statusHtml = '<span class="badge bg-warning text-dark">Pendente</span>';
                                                    if ($m['status'] === 'A') $statusHtml = '<span class="badge bg-success">Ativo</span>';
                                                    echo $statusHtml;
                                                ?>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-outline-secondary edit-member me-2" data-user="<?= (int)$m['id'] ?>" title="Editar"><i class="bi bi-pencil"></i></button>
                                                <button class="btn btn-sm btn-outline-danger remove-member" data-user="<?= (int)$m['id'] ?>" title="Remover"><i class="bi bi-trash"></i></button>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
<script src="../script.js"></script>
<script>
    const saveBtn = document.getElementById('save-btn');
    const descricaoInput = document.getElementById('descricao-input');
    const charCount = document.getElementById('char-count');

    // Tab switching with persistence
    const lojaId = <?= (int)$loja['id'] ?>;
    const canEditEquipe = <?= $can_edit ? 'true' : 'false' ?>;
    function showTela(index) {
        document.querySelectorAll('.tela-content').forEach(function(el) { el.classList.add('d-none'); });
        const el = document.getElementById('tela-content-' + index);
        if (el) el.classList.remove('d-none');
    }

    document.querySelectorAll('input[name="telas"]').forEach(function(r) {
        r.addEventListener('change', function() {
            const id = this.id;
            if (id === 'tela-1') showTela(1);
            else if (id === 'tela-2') showTela(2);
            else if (id === 'tela-3') showTela(3);
            else if (id === 'tela-4') showTela(4);
            // persist selection per loja
            try { localStorage.setItem('loja_selected_tab_' + lojaId, id); } catch (e) { /* ignore */ }
        });
    });

    // show initial: try to restore from localStorage, otherwise use the checked radio
    (function() {
        try {
            const saved = localStorage.getItem('loja_selected_tab_' + lojaId);
            if (saved) {
                const el = document.getElementById(saved);
                if (el) {
                    el.checked = true;
                    const idx = parseInt(saved.replace('tela-','')) || 1;
                    showTela(idx);
                    return;
                }
            }
        } catch (e) { /* ignore storage errors */ }
        const checked = document.querySelector('input[name="telas"]:checked');
        if (checked) {
            const idx = parseInt(checked.id.replace('tela-','')) || 1;
            showTela(idx);
        } else {
            // fallback
            showTela(1);
        }
    })();

    // Save handler: submit form via fetch, show bootstrap alert on success
    saveBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = document.getElementById('config-form');
        if (!form) return;
        const fd = new FormData(form);
        // include files if selected
        const logoFile = document.getElementById('logo-local');
        const capaFile = document.getElementById('capa-local');
        if (logoFile && logoFile.files.length) fd.append('logo_local', logoFile.files[0]);
        if (capaFile && capaFile.files.length) fd.append('capa_local', capaFile.files[0]);

        saveBtn.disabled = true;
        fetch('../controladores/loja/atualizar-loja.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(res => {
                saveBtn.disabled = false;
                // show alert message at top of main
                const main = document.querySelector('main');
                if (main) {
                    // remove previous alerts
                    const prev = main.querySelector('.js-message-alert'); if (prev) prev.remove();
                    const div = document.createElement('div');
                    div.className = 'js-message-alert alert ' + (res.success ? 'alert-success' : 'alert-danger') + ' alert-dismissible fade show';
                    div.setAttribute('role','alert');
                    div.innerHTML = (res.message || (res.success ? 'Salvo com sucesso.' : 'Erro ao salvar.')) + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>';
                    main.insertBefore(div, main.firstChild);
                }
                // update previews if server returned urls
                if (res.logo_url) {
                    const logoPreview = document.getElementById('logo-preview-local');
                    const perfilFoto = document.getElementById('perfil-foto');
                    if (logoPreview) logoPreview.src = res.logo_url + '?t=' + Date.now();
                    if (perfilFoto) perfilFoto.src = res.logo_url + '?t=' + Date.now();
                }
                if (res.capa_url) {
                    const capaPreview = document.getElementById('capa-preview-local');
                    const tela1Img = document.querySelector('#tela-content-1 img.object-fit-cover');
                    if (capaPreview) capaPreview.src = res.capa_url + '?t=' + Date.now();
                    if (tela1Img) tela1Img.src = res.capa_url + '?t=' + Date.now();
                }
            }).catch(err => {
                saveBtn.disabled = false;
                const main = document.querySelector('main');
                if (main) {
                    const prev = main.querySelector('.js-message-alert'); if (prev) prev.remove();
                    const div = document.createElement('div');
                    div.className = 'js-message-alert alert alert-danger alert-dismissible fade show';
                    div.setAttribute('role','alert');
                    div.innerHTML = 'Erro de rede ao salvar. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>';
                    main.insertBefore(div, main.firstChild);
                }
                console.error(err);
            });
    });

    // Delete loja (owner only)
    const deleteBtn = document.getElementById('delete-loja-btn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(){
            if (!confirm('Tem certeza que deseja excluir esta loja? Esta ação é irreversível.')) return;
            const p = new URLSearchParams(); p.append('loja_id', <?= (int)$loja['id'] ?>);
            fetch('../controladores/loja/deletar-loja.php', { method: 'POST', body: p }).then(r=>r.json()).then(res=>{
                if (!res.success) { alert(res.message || 'Erro ao excluir'); return; }
                // redirect to home
                location.href = '../index.php';
            }).catch(err=>{ console.error(err); alert('Erro de rede'); });
        });
    }

    // Leave team (non-edit members)
    const leaveBtn = document.getElementById('leave-team-btn');
    if (leaveBtn) {
        leaveBtn.addEventListener('click', function(){
            if (!confirm('Deseja realmente sair da equipe desta loja?')) return;
            const p = new URLSearchParams(); p.append('loja_id', <?= (int)$loja['id'] ?>); p.append('usuario_id', <?= isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0 ?>);
            fetch('../controladores/loja/remover-membro.php', { method: 'POST', body: p }).then(r=>r.json()).then(res=>{
                if (!res.success) { alert(res.message || 'Erro ao sair da equipe'); return; }
                // redirect out of loja pages
                location.href = '../index.php';
            }).catch(err=>{ console.error(err); alert('Erro de rede'); });
        });
    }

    // Descrição contador
    if (descricaoInput && charCount) {
        charCount.textContent = descricaoInput.value ? descricaoInput.value.length : 0;
        descricaoInput.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
            charCount.classList.remove('text-warning', 'fw-bold', 'text-danger', 'fw-bolder');
            if (length >= 935 && length < 1000) charCount.classList.add('text-warning', 'fw-bold');
            else if (length === 1000) charCount.classList.add('text-danger', 'fw-bolder');
        });
    }

    // Previews for local logo/capa file inputs
    const logoLocalInput = document.getElementById('logo-local');
    const logoLocalPreview = document.getElementById('logo-preview-local');
    if (logoLocalInput && logoLocalPreview) {
        logoLocalInput.addEventListener('change', function(e) {
            const f = e.target.files[0];
            if (f && f.type.startsWith('image/')) {
                const r = new FileReader();
                r.onload = function(ev) { logoLocalPreview.src = ev.target.result; };
                r.readAsDataURL(f);
            }
        });
    }
    const capaLocalInput = document.getElementById('capa-local');
    const capaLocalPreview = document.getElementById('capa-preview-local');
    if (capaLocalInput && capaLocalPreview) {
        capaLocalInput.addEventListener('change', function(e) {
            const f = e.target.files[0];
            if (f && f.type.startsWith('image/')) {
                const r = new FileReader();
                r.onload = function(ev) { capaLocalPreview.src = ev.target.result; };
                r.readAsDataURL(f);
            }
        });
    }

    // IBGE: popular selects de estado/municipio para configuração da loja (apenas se os selects existirem)
    (function(){
        const ibgeEstadosUrl = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';
        const lojaEstado = document.getElementById('config-estado');
        const lojaCidade = document.getElementById('config-cidade');
        const savedEstado = <?= json_encode($loja['estado']) ?>;
        const savedCidade = <?= json_encode($loja['cidade']) ?>;

        if (!lojaEstado || !lojaCidade) return;

        // popular estados
        fetch(ibgeEstadosUrl).then(res => res.json()).then(estados => {
            estados.sort((a,b) => a.nome.localeCompare(b.nome));
            lojaEstado.innerHTML = '<option value="">Selecione um estado...</option>';
            estados.forEach(e => {
                const opt = document.createElement('option');
                opt.value = e.sigla;
                opt.dataset.id = e.id;
                opt.textContent = `${e.nome} (${e.sigla})`;
                lojaEstado.appendChild(opt);
            });
            if (savedEstado) {
                const opt = Array.from(lojaEstado.options).find(o => o.value && o.value.toLowerCase() === (savedEstado||'').toLowerCase());
                if (opt) { lojaEstado.value = opt.value; loadMunicipiosConfig(); }
            }
        }).catch(err => { console.error('Erro ao carregar estados IBGE:', err); });

        function loadMunicipiosConfig() {
            const estadoId = lojaEstado.options[lojaEstado.selectedIndex] && lojaEstado.options[lojaEstado.selectedIndex].dataset.id;
            if (!estadoId) { lojaCidade.innerHTML = '<option value="">Selecione um município...</option>'; return; }
            lojaCidade.innerHTML = '<option>Carregando municípios...</option>';
            fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estadoId}/municipios`).then(r => r.json()).then(list => {
                list.sort((a,b) => a.nome.localeCompare(b.nome));
                lojaCidade.innerHTML = '<option value="">Selecione um município...</option>';
                list.forEach(m => {
                    const o = document.createElement('option');
                    o.value = m.nome;
                    o.textContent = m.nome;
                    lojaCidade.appendChild(o);
                });
                if (savedCidade) {
                    const opt = Array.from(lojaCidade.options).find(o => o.value && o.value.toLowerCase() === (savedCidade||'').toLowerCase());
                    if (opt) lojaCidade.value = opt.value;
                }
            }).catch(err => { console.error('Erro ao carregar municípios IBGE:', err); lojaCidade.innerHTML = '<option value="">Erro ao carregar municípios</option>'; });
        }

        lojaEstado.addEventListener('change', loadMunicipiosConfig);
    })();

        // --- Equipe: modal markup insertion and logic ---
        (function(){
                const equipeModalHtml = `
        <div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Adicionar membro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Procurar usuário (nome ou email)</label>
                                <input type="search" id="member-search" class="form-control" placeholder="Digite nome ou email...">
                                <div id="member-suggestions" class="list-group mt-2"></div>
                            </div>

                            <hr>
                            <h6 class="mb-2">Permissões (aplicadas a todos os selecionados)</h6>
                            <div id="global-perms" class="d-flex gap-3 align-items-center mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="gp-edit-anuncios">
                                    <label class="form-check-label small" for="gp-edit-anuncios">Editar anúncios</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="gp-responder-msgs">
                                    <label class="form-check-label small" for="gp-responder-msgs">Responder mensagens</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="gp-edit-loja">
                                    <label class="form-check-label small" for="gp-edit-loja">Editar loja</label>
                                </div>
                                <?php if (isset($is_owner) && $is_owner): ?>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="gp-manage-members">
                                    <label class="form-check-label small" for="gp-manage-members">Gerenciar membros</label>
                                </div>
                                <?php endif; ?>
                            </div>

                            <h6 class="mb-2">Membros a serem convidados</h6>
                            <div id="pending-invites" class="list-group mb-2">
                                <!-- pending items appended here -->
                            </div>
                            <div class="text-muted small mb-2">Use o X para remover um usuário da lista antes de convidar.</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="send-invites-btn" class="btn btn-primary">Convidar selecionados</button>
                        </div>
                </div>
            </div>
        </div>`;
                document.body.insertAdjacentHTML('beforeend', equipeModalHtml);
                const editModalHtml = `
        <div class="modal fade" id="editMemberModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar permissões do membro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editm-usuario-id">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="editp-edit-anuncios">
                            <label class="form-check-label small" for="editp-edit-anuncios">Editar anúncios</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="editp-responder-msgs">
                            <label class="form-check-label small" for="editp-responder-msgs">Responder mensagens</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="editp-edit-loja">
                            <label class="form-check-label small" for="editp-edit-loja">Editar loja</label>
                        </div>
                            <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="editp-manage-members">
                            <label class="form-check-label small" for="editp-manage-members">Gerenciar membros</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" id="save-edit-member-btn" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </div>
        </div>`;
                document.body.insertAdjacentHTML('beforeend', editModalHtml);

                function debounce(fn, wait) { let t; return function(...a){ clearTimeout(t); t=setTimeout(()=>fn.apply(this,a), wait); }; }

                // Pending invites management inside modal
                const pendingInvites = {}; // key: user id -> user object
                const globalPerms = { pode_editar_anuncio: 0, pode_responder_mensagem: 0, pode_editar_loja: 0 };
                const membersCache = {}; // cache members returned from server for editing

                function renderPendingList(){
                    const container = document.getElementById('pending-invites');
                    if(!container) return;
                    container.innerHTML = '';
                    Object.values(pendingInvites).forEach(u => {
                        const item = document.createElement('div');
                        item.className = 'list-group-item d-flex align-items-start gap-3';
                        item.innerHTML = `
                            <img src="${u.avatar}" width="44" height="44" class="rounded-circle">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold">${u.nome}</div>
                                        <div class="text-muted small">${u.email}</div>
                                    </div>
                                    <button type="button" class="btn-close ms-2 remove-pending" aria-label="Remover" data-user="${u.id}"></button>
                                </div>
                            </div>
                        `;
                        container.appendChild(item);
                    });
                    // attach remove handlers
                    document.querySelectorAll('.remove-pending').forEach(btn => btn.addEventListener('click', function(){ const uid=this.dataset.user; delete pendingInvites[uid]; renderPendingList(); }));
                }

                function showSuggestions(q){
                        const sugg = document.getElementById('member-suggestions'); sugg.innerHTML='';
                        if(!q || q.length < 1) return;
                        fetch('../controladores/loja/buscar-usuarios.php?q='+encodeURIComponent(q)+'&loja_id=<?= (int)$loja['id'] ?>')
                                .then(r=>r.json()).then(list=>{
                                        list.forEach(u=>{
                                                const item = document.createElement('button');
                                                item.type='button';
                                                item.className='list-group-item list-group-item-action d-flex align-items-center gap-3';
                                                item.innerHTML = `<img src="${u.avatar}" width="40" height="40" class="rounded-circle">` +
                                                        `<div class="text-start"><div>${u.nome}</div><small class="text-muted">${u.email}</small></div>`;
                                                item.addEventListener('click', ()=> addToPendingFromSuggestion(u));
                                                sugg.appendChild(item);
                                        });
                                }).catch(console.error);
                }

                function addToPendingFromSuggestion(u){
                    // don't add duplicates in pending list
                    if(pendingInvites[String(u.id)]) { alert('Usuário já adicionado na lista de convites.'); return; }
                    // don't add if already in table (active or pending)
                    const exists = Array.from(document.querySelectorAll('#membros-table tbody tr')).some(tr => {
                        const btn = tr.querySelector('.remove-member');
                        return btn && btn.dataset.user == u.id;
                    });
                    if (exists) { alert('Usuário já está na equipe ou com convite pendente.'); return; }

                    // set default permission flags (server may override)
                    pendingInvites[String(u.id)] = Object.assign({}, u);
                    renderPendingList();
                }

                // send all pending invites
                function invitePendingMembers(){
                    const ids = Object.keys(pendingInvites);
                    if(!ids.length) { alert('Nenhum usuário selecionado para convidar.'); return; }
                    // read global perms from checkboxes
                    const gpEdit = document.getElementById('gp-edit-anuncios');
                    const gpResp = document.getElementById('gp-responder-msgs');
                    const gpEditLoja = document.getElementById('gp-edit-loja');
                    const gpManage = document.getElementById('gp-manage-members');
                    const perms = {
                        pode_editar_anuncio: gpEdit && gpEdit.checked ? 1 : 0,
                        pode_responder_mensagem: gpResp && gpResp.checked ? 1 : 0,
                        pode_editar_loja: gpEditLoja && gpEditLoja.checked ? 1 : 0,
                        pode_adicionar_membros: gpManage && gpManage.checked ? 1 : 0
                    };

                    const promises = ids.map(id => {
                        const params = new URLSearchParams();
                        params.append('loja_id', <?= (int)$loja['id'] ?>);
                        params.append('usuario_id', id);
                        Object.keys(perms).forEach(f => params.append(f, perms[f]));
                        return fetch('../controladores/loja/adicionar-membro.php', { method: 'POST', body: params }).then(r=>r.json());
                    });

                    Promise.all(promises).then(results => {
                        let anyError=false; let msgs=[];
                        results.forEach(res => { if(!res.success) { anyError=true; msgs.push(res.message || 'Erro'); } });
                        if(anyError) alert('Alguns convites falharam: ' + msgs.join('; '));
                        else {
                            for(const k of Object.keys(pendingInvites)) delete pendingInvites[k];
                            renderPendingList();
                            const modalEl = document.getElementById('addMemberModal');
                            const md = bootstrap.Modal.getInstance(modalEl);
                            if(md) md.hide();
                            refreshMembros();
                        }
                    }).catch(err=>{ console.error(err); alert('Erro de rede ao enviar convites.'); });
                }

                function removeMember(usuario_id){
                    return fetch('../controladores/loja/remover-membro.php', { method: 'POST', body: new URLSearchParams({ loja_id: <?= (int)$loja['id'] ?>, usuario_id: usuario_id }) }).then(r=>r.json());
                }

                function refreshMembros(){
                    fetch('../controladores/loja/listar-membros.php?loja_id=<?= (int)$loja['id'] ?>')
                        .then(r=>r.json()).then(list=>{
                            const tbody = document.querySelector('#membros-table tbody'); tbody.innerHTML='';
                            list.forEach(m=>{
                                // cache member for edit
                                membersCache[m.id] = m;
                                const tr = document.createElement('tr');
                                const userTd = document.createElement('td');
                                userTd.className = 'd-flex align-items-center gap-3';
                                userTd.innerHTML = `<img src="${m.avatar}" width="48" height="48" class="rounded-circle"><div><div>${m.nome}</div><small class="text-muted">${m.email}</small></div>`;
                                tr.appendChild(userTd);

                                if (canEditEquipe) {
                                    const statusTd = document.createElement('td');
                                    let statusHtml = '<span class="badge bg-secondary">Desconhecido</span>';
                                    if (m.status === 'P') statusHtml = '<span class="badge bg-warning text-dark">Pendente</span>';
                                    if (m.status === 'A') statusHtml = '<span class="badge bg-success">Ativo</span>';
                                    statusTd.innerHTML = statusHtml;
                                    const actionTd = document.createElement('td'); actionTd.className = 'text-end';
                                    actionTd.innerHTML = `<button class="btn btn-sm btn-outline-secondary edit-member me-2" data-user="${m.id}" title="Editar"><i class="bi bi-pencil"></i></button>` +
                                                         `<button class="btn btn-sm btn-outline-danger remove-member" data-user="${m.id}" title="Remover"><i class="bi bi-trash"></i></button>`;
                                    tr.appendChild(statusTd);
                                    tr.appendChild(actionTd);
                                }

                                tbody.appendChild(tr);
                            });

                            if (canEditEquipe) {
                                // attach remove handlers
                                document.querySelectorAll('.remove-member').forEach(function(btn){ btn.addEventListener('click', function(){ const uid=this.dataset.user; if(!confirm('Remover membro?')) return; removeMember(uid).then(r=>{ if(r.success) refreshMembros(); else alert(r.message||'Erro'); }); }); });
                                // attach edit handlers
                                document.querySelectorAll('.edit-member').forEach(function(btn){ btn.addEventListener('click', function(){ const uid=this.dataset.user; openEditModal(uid); }); });
                            }
                        }).catch(console.error);
                }

                function openEditModal(usuario_id){
                    const m = membersCache[usuario_id];
                    if(!m){ alert('Dados do membro não disponíveis. Recarregue e tente novamente.'); return; }
                    document.getElementById('editm-usuario-id').value = usuario_id;
                    document.getElementById('editp-edit-anuncios').checked = !!m.pode_editar_anuncio;
                    document.getElementById('editp-responder-msgs').checked = !!m.pode_responder_mensagem;
                    document.getElementById('editp-edit-loja').checked = !!m.pode_editar_loja;
                    document.getElementById('editp-manage-members').checked = !!m.pode_adicionar_membros;
                    const md = new bootstrap.Modal(document.getElementById('editMemberModal'));
                    md.show();
                }

                    document.addEventListener('click', function(e){ if(e.target && e.target.id==='save-edit-member-btn'){ const uid = document.getElementById('editm-usuario-id').value; if(!uid) return; const params=new URLSearchParams(); params.append('loja_id', <?= (int)$loja['id'] ?>); params.append('usuario_id', uid); params.append('pode_editar_anuncio', document.getElementById('editp-edit-anuncios').checked ? 1 : 0); params.append('pode_responder_mensagem', document.getElementById('editp-responder-msgs').checked ? 1 : 0); params.append('pode_editar_loja', document.getElementById('editp-edit-loja').checked ? 1 : 0); params.append('pode_adicionar_membros', document.getElementById('editp-manage-members').checked ? 1 : 0); fetch('../controladores/loja/adicionar-membro.php', { method:'POST', body: params }).then(r=>r.json()).then(res=>{ if(!res.success) alert(res.message||'Erro'); else { const md=bootstrap.Modal.getInstance(document.getElementById('editMemberModal')); if(md) md.hide(); refreshMembros(); } }).catch(err=>{ console.error(err); alert('Erro de rede'); }); } });

                document.addEventListener('click', function(e){ if(e.target && e.target.id==='open-add-member'){ const md=new bootstrap.Modal(document.getElementById('addMemberModal')); md.show(); // reset pending list when opening
                    for(const k of Object.keys(pendingInvites)) delete pendingInvites[k]; renderPendingList();
                    // reset global perms to defaults
                    const gpEdit = document.getElementById('gp-edit-anuncios'); const gpResp = document.getElementById('gp-responder-msgs'); const gpEditLoja = document.getElementById('gp-edit-loja'); if(gpEdit) gpEdit.checked = false; if(gpResp) gpResp.checked = false; if(gpEditLoja) gpEditLoja.checked = false;
                    setTimeout(()=>refreshMembros(),200); } });
                const msdeb = debounce(function(){ const el=document.getElementById('member-search'); if(!el) return; const v=el.value.trim(); showSuggestions(v); }, 250);
                document.addEventListener('input', function(e){ if(e.target && e.target.id==='member-search') msdeb(); });
                // wire invite button
                const sendInvBtn = document.getElementById('send-invites-btn'); if(sendInvBtn) sendInvBtn.addEventListener('click', invitePendingMembers);
                // initial load
                refreshMembros();
        })();
    </script>

</body>
</html>