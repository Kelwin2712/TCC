<?php
session_start();
include('controladores/conexao_bd.php');

$id_veiculo = $_GET['id'];

if (!$id_veiculo) {
    header('Location: index.php');
    exit;
}

$sql = "SELECT carros.*, 
    marcas.nome as marca_nome, 
    cores.nome as cor_nome, 
    usuarios.nome as usuario_nome, usuarios.sobrenome as usuario_sobrenome, usuarios.avatar as usuario_avatar, COALESCE(usuarios.seguidores,0) AS usuario_seguidores,
    lojas.id AS loja_id_db, lojas.nome AS loja_nome, lojas.logo AS loja_logo, COALESCE(lojas.seguidores,0) AS loja_seguidores
    , lojas.horarios AS loja_horarios
    FROM anuncios_carros carros
    INNER JOIN cores ON carros.cor = cores.id
    INNER JOIN marcas ON carros.marca = marcas.id
    LEFT JOIN usuarios ON carros.id_vendedor = usuarios.id
    LEFT JOIN lojas ON carros.id_vendedor = lojas.owner_id
    WHERE carros.id = $id_veiculo";
$resultado = mysqli_query($conexao, $sql);


if (mysqli_num_rows($resultado) > 0) {
    $carro = mysqli_fetch_array($resultado);
} else {
    header('Location: index.php');
    exit;
}

// fetch photos for this vehicle
$photos = [];
$qr = mysqli_query($conexao, "SELECT caminho_foto FROM fotos_carros WHERE carro_id = $id_veiculo ORDER BY `ordem` ASC");
if ($qr) {
    while ($r = mysqli_fetch_assoc($qr)) $photos[] = $r['caminho_foto'];
}

// determine display info for vendedor: if anuncio is from a loja (tipo_vendedor=1 and loja_id present), prefer loja info
$vendedor = '';
$vendedor_img = 'img/usuarios/avatares/user.png';
$vendedor_seg_count = 0;
// prefer loja when tipo_vendedor indicates 'pj' (1) and loja exists
if (isset($carro['tipo_vendedor']) && (int)$carro['tipo_vendedor'] === 1 && !empty($carro['loja_id_db'])) {
    // loja name
    $vendedor = $carro['loja_nome'] ?: ($carro['usuario_nome'] . ' ' . $carro['usuario_sobrenome']);
    // try to use loja logo stored in img/lojas/{id}/{logo}
    if (!empty($carro['loja_logo']) && file_exists(__DIR__ . '/img/lojas/' . $carro['loja_id_db'] . '/' . $carro['loja_logo'])) {
        $vendedor_img = 'img/lojas/' . $carro['loja_id_db'] . '/' . $carro['loja_logo'];
    } else {
        // fallback to user's avatar if present
        $vendedor_img = !empty($carro['usuario_avatar']) ? $carro['usuario_avatar'] : 'img/usuarios/avatares/user.png';
    }
    $vendedor_seg_count = isset($carro['loja_seguidores']) ? (int)$carro['loja_seguidores'] : (isset($carro['usuario_seguidores']) ? (int)$carro['usuario_seguidores'] : 0);
} else {
    // default: use user info
    $vendedor = trim(($carro['usuario_nome'] ?? '') . ' ' . ($carro['usuario_sobrenome'] ?? '')) ?: 'Vendedor';
    $vendedor_img = !empty($carro['usuario_avatar']) ? $carro['usuario_avatar'] : 'img/usuarios/avatares/user.png';
    $vendedor_seg_count = isset($carro['usuario_seguidores']) ? (int)$carro['usuario_seguidores'] : 0;
}
$vendedor_seg = number_format($vendedor_seg_count, 0, ',', '.');

// Horários da loja (se aplicável)
$loja_horarios = null;
$loja_open_now = false;
$loja_today_label = '';
$loja_today_open = null;
$loja_today_close = null;
if (isset($carro['tipo_vendedor']) && (int)$carro['tipo_vendedor'] === 1 && !empty($carro['loja_id_db'])) {
    if (!empty($carro['loja_horarios'])) {
        $tmp = json_decode($carro['loja_horarios'], true);
        if (is_array($tmp)) $loja_horarios = $tmp;
    }

    // compute if open right now and today's open/close times
    if (is_array($loja_horarios)) {
        $dias = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
        $now = new DateTime();
        $today_idx = (int)$now->format('w'); // 0 (Sunday) - 6 (Saturday)

        // helper: format time string to HH:MM (strip seconds if present)
        function _format_short_time($s) {
            if (!$s) return null;
            if (preg_match('/^(\d{1,2}):(\d{2})/', $s, $m)) {
                return sprintf('%02d:%02d', (int)$m[1], (int)$m[2]);
            }
            return $s;
        }

        // helper: parse a time string into a DateTime for today, robust to H:i or H:i:s
        function _parse_time_for_today($time_str, $now_dt) {
            if (!$time_str) return false;
            $formats = ['H:i:s', 'H:i'];
            foreach ($formats as $fmt) {
                $dt = DateTime::createFromFormat($fmt, $time_str);
                if ($dt !== false) {
                    $dt->setDate((int)$now_dt->format('Y'), (int)$now_dt->format('m'), (int)$now_dt->format('d'));
                    return $dt;
                }
            }
            try {
                $dt = new DateTime($time_str);
                $dt->setDate((int)$now_dt->format('Y'), (int)$now_dt->format('m'), (int)$now_dt->format('d'));
                return $dt;
            } catch (Exception $e) {
                return false;
            }
        }

        $entry = isset($loja_horarios[$today_idx]) ? $loja_horarios[$today_idx] : null;
        if ($entry && !empty($entry['aberto'])) {
            $abre = isset($entry['abre']) ? $entry['abre'] : null;
            $fecha = isset($entry['fecha']) ? $entry['fecha'] : null;
            if ($abre) $loja_today_open = _format_short_time($abre);
            if ($fecha) $loja_today_close = _format_short_time($fecha);

            if ($abre && $fecha) {
                // build DateTime for today (robust parsing)
                $dt_abre = _parse_time_for_today($abre, $now);
                $dt_fecha = _parse_time_for_today($fecha, $now);
                if ($dt_abre && $dt_fecha) {
                    // if fecha <= abre assume closing next day
                    if ($dt_fecha <= $dt_abre) {
                        $dt_fecha->modify('+1 day');
                    }
                    if ($now >= $dt_abre && $now < $dt_fecha) {
                        $loja_open_now = true;
                    }
                }
            }
        }
        $loja_today_label = $dias[$today_idx];
    }
}

// determine if current user already favorited this anuncio
$user_id = $_SESSION['id'] ?? null;
$favoritado = 0;
if ($user_id) {
    $uid = mysqli_real_escape_string($conexao, $user_id);
    $aid = (int)$id_veiculo;
    $qf = mysqli_query($conexao, "SELECT id FROM favoritos WHERE usuario_id = '$uid' AND anuncio_id = $aid LIMIT 1");
    if ($qf && mysqli_num_rows($qf) > 0) $favoritado = 1;
}

// ensure seguidores table exists and check if current user follows this vendedor
$seguindo = 0;
$vendedor_id = isset($carro['id_vendedor']) ? (int)$carro['id_vendedor'] : 0;

// If this is a loja (tipo_vendedor=1) and loja exists, use loja_id instead
$seguir_id = $vendedor_id; // default to user id

if (isset($carro['tipo_vendedor']) && (int)$carro['tipo_vendedor'] === 1) {
    // For lojas, we need to get the loja ID
    $loja_query = mysqli_query($conexao, "SELECT id FROM lojas WHERE owner_id = $vendedor_id LIMIT 1");
    if ($loja_query && mysqli_num_rows($loja_query) > 0) {
        $loja_row = mysqli_fetch_assoc($loja_query);
        $seguir_id = (int)$loja_row['id'];
    }
}

if ($user_id && $seguir_id) {
        // create table if needed (no-op if already exists)
        $create = "CREATE TABLE IF NOT EXISTS seguidores (
            id INT NOT NULL AUTO_INCREMENT,
            seguidor_id INT NOT NULL,
            seguido_id INT NOT NULL,
            criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY uniq_seg (seguidor_id, seguido_id),
            KEY idx_seguido (seguido_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        @mysqli_query($conexao, $create);

        $q = @mysqli_query($conexao, "SELECT id FROM seguidores WHERE seguidor_id = " . (int)$user_id . " AND seguido_id = " . (int)$seguir_id . " LIMIT 1");
        if ($q && mysqli_num_rows($q) > 0) {
            $seguindo = 1;
        }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title><?= strtoupper($carro['marca_nome'])  . ' ' . strtoupper($carro['modelo']) . ' ' . strtoupper($carro['versao']) ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .carro-img {
            cursor: pointer;
        }

        #img-row .col-3 img {
            opacity: .5 !important;
        }

        #img-row .col-3.selecionado img {
            opacity: 1 !important;
        }
    </style>
    <script>
        // Register click for this vehicle on page load (not on reload)
        document.addEventListener('DOMContentLoaded', function() {
            const vehicleId = <?= json_encode($id_veiculo) ?>;
            const sessionKey = 'vehicle_' + vehicleId + '_clicked';
            
            // Check if already clicked in this session
            if (!sessionStorage.getItem(sessionKey)) {
                // Mark as clicked in this session
                sessionStorage.setItem(sessionKey, 'true');
                
                // Send click to server
                fetch('controladores/registrar-click.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id_veiculo=' + vehicleId
                }).catch(err => console.log('Click registered'));
            }
        });
    </script>
</head>

<body>
    <?php
    include 'estruturas/top-button/top-button.php' ?>
    <?php $float = true;
    include 'estruturas/navbar/navbar-compras.php' ?>
    <main class="bg-body-tertiary fs-nav">
        <div class="container py-5">
            <div class="row g-5 mb-3">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body px-5">
                            <div class="row">
                                <div class="col-12">
                                    <div id="imagems-carro" class="carousel slide" data-quant="1" data-bs-touch="false">
                                        <div class="carousel-inner">
                                            <?php if (!empty($photos)): ?>
                                                <?php foreach ($photos as $idx => $ph): ?>
                                                    <div id="crl-<?= ($idx + 1) ?>" class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
                                                        <div class="ratio ratio-16x9">
                                                            <img src="img/anuncios/carros/<?= $id_veiculo ?>/<?= htmlspecialchars($ph) ?>" class="d-block img-fluid object-fit-cover" alt="Imagem <?= ($idx + 1) ?>">
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="carousel-item active">
                                                    <div class="ratio ratio-16x9">
                                                        <img src="img/compras/1.png" class="d-block img-fluid object-fit-cover" alt="Imagem 1">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row position-absolute bottom-0 p-2">
                                            <div class="col-auto">
                                                <div class="text-bg-dark bg-opacity-50 rounded-pill py-1" style="font-size: .8rem; padding-left: .75rem; padding-right: .75rem;"><span class="min">1</span>/<span class="max"></span></div>
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#imagems-carro" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#imagems-carro" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="progress-stacked" style="height: 4px;">
                                        <div class="progress" role="progressbar" style="width: 0%">
                                            <div class="progress-bar bg-transparent"></div>
                                        </div>
                                        <div class="progress" role="progressbar" style="width: 25%">
                                            <div class="progress-bar bg-dark-subtle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="img-row" class="row g-0">
                                <?php if (!empty($photos)): ?>
                                    <?php foreach (array_slice($photos, 0, 4) as $idx => $ph): ?>
                                        <div class="col-3 <?= $idx === 0 ? 'selecionado' : '' ?>">
                                            <div class="carro-img ratio ratio-16x9" data-img="<?= ($idx + 1) ?>">
                                                <img src="img/anuncios/carros/<?= $id_veiculo ?>/<?= htmlspecialchars($ph) ?>" alt="" class="img-fluid object-fit-cover">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-3 selecionado">
                                        <div class="carro-img ratio ratio-16x9" data-img="1">
                                            <img src="img/compras/1.png" alt="" class="img-fluid object-fit-cover">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="carro-img ratio ratio-16x9" data-img="2">
                                            <img src="img/compras/2.png" alt="" class="img-fluid object-fit-cover">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="carro-img ratio ratio-16x9" data-img="3">
                                            <img src="img/compras/3.png" alt="" class="img-fluid object-fit-cover">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="carro-img ratio ratio-16x9" data-img="4">
                                            <img src="img/compras/4.png" alt="" class="img-fluid object-fit-cover">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <form class="card-body px-4 d-flex flex-column justify-content-between">
                            <div class="row d-flex justify-content-between">
                                <div class="col-auto">
                                    <p class="fs-1 fw-semibold mb-0">R$ <?= number_format((int)$carro['preco'], 0, ',', '.'); ?></p>
                                </div>
                                <div class="col-auto">
                                    <span class="badge text-white py-2 user-select-none rounded-3" style="background-color: var(--cor-verde-escuro);"><i class="bi bi-shield-check"></i> Confiável</span>
                                </div>
                                <p>Envie uma mensagem para o vendedor</p>
                            </div>
                            <?php if (!isset($_SESSION['id'])): ?>
                                <div class="row">
                                    <div class="mb-3">
                                        <p class="mb-2 text-muted">Para enviar uma mensagem você precisa fazer login.</p>
                                        <a href="sign-in.php" class="btn btn-dark rounded-4 w-100">Entrar</a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="row h-100">
                                    <div class="mb-5">
                                        <div class="d-flex justify-content-between mb-0">
                                            <label for="mensagem-input" class="form-label form-text">
                                                Mensagem<sup class="text-danger">*</sup>
                                            </label>
                                            <small id="max-mensagem" class="form-text" style="font-size: .75rem;">0/500</small>
                                        </div>
                                        <textarea class="form-control h-100 shadow-sm rounded-4" id="mensagem-input" placeholder="Mensagem" rows="5" maxlength="500" minlength="10" required></textarea>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['id'])): ?>
                            <div class="row">
                                <div class="col" <?= $carro['id_vendedor'] == $_SESSION['id'] ? " title=\"Você não pode enviar mensagem para si mesmo\"" : '' ?>>
                                    <button type="submit" class="btn rounded-4 btn-dark w-100 mb-3 py-2 shadow-sm" <?= $carro['id_vendedor'] == $_SESSION['id'] ? 'disabled' : '' ?>>Enviar mensagem</button>
                                </div>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row g-5 mb-3">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body py-4">
                            <div class="row mb-4 d-flex justify-content-between px-4">
                                <div class="col-auto pe-0">
                                    <h2 class="fw-bold mb-0 text-uppercase"><?= $carro['marca_nome'] ?> <span style="color: var(--cor-verde-escuro);"><?= $carro['modelo'] ?></span></h2>
                                    <p class="text-uppercase"><?= $carro['versao'] ?></p>
                                </div>
                                <div class="col">
                                    <p class="text-capitalize text-end"><i class="bi bi-geo-alt"></i> <?= $carro['cidade'] . ' - '  . $carro['estado_local'] ?></p>
                                </div>
                                <?php if (isset($_SESSION['id'])): ?>
                                <div class="col-auto">
                                    <?php
                                    $heart_class = $favoritado ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary';
                                    ?>
                                    <button type="button" class="btn p-0 favoritar favoritar-danger" data-anuncio="<?= $id_veiculo ?>" data-favoritado="<?= $favoritado ?>">
                                        <i class="bi <?= $heart_class ?> fs-5"></i>
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="row px-4 pt-3">
                                <p>Informações</p>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Ano</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold "><?= $carro['ano_fabricacao'] . '/' . $carro['ano_modelo'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">KM</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold "><?= is_numeric($carro['quilometragem']) ? number_format((int)$carro['quilometragem'], 0, ',', '.') . ' km' : $carro['quilometragem'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Cor</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold text-capitalize"><?= $carro['cor_nome'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Câmbio</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold ">Automático</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Blindando</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold "><?= $carro['blindagem'] == 1 ? 'Sim' : 'Não' ?></p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Aceita troca</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold text-capitalize"><?= $carro['aceita_troca'] == 1 ? 'Sim' : 'Não' ?></p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Revisão feita</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold text-capitalize"><?= $carro['revisao'] > 0 ? 'Sim (' . $carro['revisao'] . ')' : 'Não' ?></p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Combustível</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold ">Gasolina</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($carro['descricao'])): ?>
                                <hr class="w-100">
                                <div class="row px-4 pt-3">
                                    <p>Descrição do veículo</p>
                                    <p class="text-secondary"><?= nl2br(htmlspecialchars($carro['descricao'])) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-auto">
                        <div class="card-body py-4">
                            <div class="row d-flex justify-content-between">
                                <div class="col">
                                    <p>Vendedor</p>
                                </div>
                                <div class="col">
                                    <?php if (isset($_SESSION['id']) && $_SESSION['id'] != $carro['id_vendedor']): ?>
                                        <?php
                                        // render initial follow button text based on $seguindo
                                        $seguir_text = $seguindo ? 'Seguindo <i class="bi bi-check"></i>' : 'Seguir';
                                        $seguir_btn_classes = $seguindo ? 'text-secondary' : 'text-primary bg-primary-subtle';
                                        ?>
                                        <button id="seguir-action-btn" type="button" class="btn btn-sm px-2 float-end rounded-3 <?= $seguir_btn_classes ?>" style="padding-top: .125rem; padding-bottom: .125rem;">
                                            <div id="seguir-btn" class="small"><?= $seguir_text ?></div>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <a href="compras.php?vendedor_id=<?= $carro['id_vendedor'] ?>" class="row mb-3 px-2 text-decoration-none text-dark">
                                <div class="rounded-3 border-2">
                                    <div class="row">
                                        <div class="col-2 p-2 d-flex align-items-center justify-content-center">
                                            <div class="ratio ratio-1x1">
                                                <img src="<?= $vendedor_img ?>" alt="" class="img-fluid rounded-circle shadow-sm">
                                            </div></i>
                                        </div>
                                        <div class="col py-2">
                                            <div class="row">
                                                <p class="fw-semibold mb-0"><?= $vendedor ?></p>
                                            </div>
                                            <div class="row">
                                                <small class="fw-semibold mb-0"><i class="bi bi-person-fill me-1"></i><span id="vendedor-seguidores"><?= $vendedor_seg ?></span></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <?php if (isset($carro['tipo_vendedor']) && (int)$carro['tipo_vendedor'] === 1 && !empty($carro['loja_id_db'])): ?>
                                <div class="list-group small">
                                    <?php
                                        $dotClass = $loja_open_now ? 'text-success' : 'text-danger';
                                        $statusText = '';
                                        if ($loja_open_now) {
                                            $closeAt = $loja_today_close ? htmlspecialchars($loja_today_close) : 'horário desconhecido';
                                            $statusText = "Loja aberta - Fecha às $closeAt";
                                        } else {
                                            $openAt = $loja_today_open ? htmlspecialchars($loja_today_open) : 'horário desconhecido';
                                            $statusText = "Loja fechada - Abre às $openAt";
                                        }
                                    ?>
                                    <button class="list-group-item rounded-2 list-group-item-action d-flex text-muted fw-semibold" data-bs-toggle="collapse" data-bs-target="#horariosCollapse" aria-expanded="false" aria-controls="horariosCollapse">
                                        <span class="me-auto"><span class="me-2"><i class="bi bi-circle-fill align-middle <?= $dotClass ?>" style="font-size: .4rem;"></i></span><?= $statusText ?></span>
                                        <span class="toggle-icon"><i class="bi bi-chevron-down"></i></span>
                                    </button>
                                    <div id="horariosCollapse" class="collapse">
                                        <?php
                                            $dias = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
                                            for ($d = 0; $d < 7; $d++):
                                                $line = '<span class="me-auto">' . $dias[$d] . ':</span>';
                                                $display = 'Fechado';
                                                if (is_array($loja_horarios) && isset($loja_horarios[$d]) && !empty($loja_horarios[$d]['aberto'])) {
                                                        $a = $loja_horarios[$d]['abre'] ?? null;
                                                        $f = $loja_horarios[$d]['fecha'] ?? null;
                                                        // format times to HH:MM (strip seconds) for display
                                                        $a_fmt = function_exists('_format_short_time') ? _format_short_time($a) : (preg_match('/^(\d{1,2}):(\d{2})/', $a, $m) ? sprintf('%02d:%02d',(int)$m[1],(int)$m[2]) : $a);
                                                        $f_fmt = function_exists('_format_short_time') ? _format_short_time($f) : (preg_match('/^(\d{1,2}):(\d{2})/', $f, $m) ? sprintf('%02d:%02d',(int)$m[1],(int)$m[2]) : $f);
                                                        if ($a && $f) $display = htmlspecialchars($a_fmt) . ' - ' . htmlspecialchars($f_fmt);
                                                        else $display = ($a || $f) ? htmlspecialchars($a_fmt ?? $f_fmt) : 'Horário não informado';
                                                }
                                        ?>
                                            <div class="list-group-item d-flex text-muted<?= $d === 6 ? ' rounded-bottom-2' : '' ?>">
                                                <?= $line ?>
                                                <span><?= $display ?></span>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'estruturas/footer/footer.php' ?>

</body>
<?php if (isset($conexao)) {
    mysqli_close($conexao);
} ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="script.js"></script>
<script>
    $(function() {
        const currentUser = <?= isset($_SESSION['id']) ? json_encode($_SESSION['id']) : 'null' ?>;

        // Favoritar button handler (same behavior as index/compras)
        $(document).on('click', 'button.favoritar', function() {
            let anuncioID = $(this).data('anuncio');
            if (!currentUser) {
                window.location.href = 'sign-in.php';
                return;
            }

            const $btn = $(this);
            const $icon = $btn.find('i');

            $.post('controladores/veiculos/favoritar-veiculo.php', {
                usuario: currentUser,
                anuncio: anuncioID
            }, function(resposta) {
                // Toggle heart icon and color
                $icon.toggleClass('bi-heart bi-heart-fill');
                $icon.toggleClass('text-secondary text-danger');
            }, 'json');
        });

        $('form').on('submit', function(e) {
            e.preventDefault();
            $.post('controladores/mensagens/enviar.php', {
                de: currentUser || 0,
                para: <?= $carro['id_vendedor'] ?>,
                anuncio: <?= $_GET['id'] ?>,
                texto: $('#mensagem-input').val()
            }, function(resposta) {
                if (resposta == true) {
                    const btn = $("button[type='submit']");
                    btn.html('Mensagem enviada&nbsp;<i class="bi bi-check2"></i>');
                    btn.prop('disabled', true);
                }
            })
        })

        const msgInput = $('#mensagem-input');
        const msgMax = $('#max-mensagem');
        const max = 500;

        msgInput.attr('maxlength', max);

        msgInput.on('input', function() {
            console.log('oi');
            msgMax.text(msgInput.val().length + '/' + max);
        })

        const carousel = $('.carousel');
        let carousel_imgs = [];
        let selecionado = 1;

        $('.carousel-item .ratio img').each(function(i) {
            carousel_imgs[i] = $(this).attr('src');
        });

        $(carousel).data('quant', $(carousel).find('.carousel-inner').children().length);
        const quant = $(carousel).data('quant');
        $(carousel).find('.max').text(quant);

        const seguir_info = $("#seguir-btn");
        const seguir_btn = $('#seguir-action-btn');
        const vendedorId = <?= json_encode($seguir_id) ?>;

        const progress = $('.progress-bar.bg-transparent').parent();

        seguir_btn.on('click', function() {
            if (!currentUser) {
                window.location.href = 'sign-in.php';
                return;
            }

            // disable button briefly to prevent double clicks
            seguir_btn.prop('disabled', true);

            console.log('Enviando seguir para ID:', vendedorId);
            
            $.post('controladores/usuarios/seguir.php', { seguido: vendedorId }, function(res) {
                console.log('Resposta do servidor:', res);
                
                if (!res || !res.success) {
                    // error: re-enable and optionally show message
                    seguir_btn.prop('disabled', false);
                    console.log('Erro:', res ? res.message : 'Sem resposta');
                    return;
                }

                const action = res.action; // 'follow' or 'unfollow'
                const count = typeof res.seguidores !== 'undefined' ? res.seguidores : null;

                console.log('Action:', action, 'Count:', count);

                if (action === 'follow') {
                    seguir_info.html('Seguindo <i class="bi bi-check"></i>');
                    seguir_btn.removeClass('text-primary bg-primary-subtle').addClass('text-secondary');
                } else if (action === 'unfollow') {
                    seguir_info.html('Seguir');
                    seguir_btn.addClass('text-primary bg-primary-subtle').removeClass('text-secondary');
                }

                if (count !== null) {
                    // update followers count in UI (format with dots)
                    const formatted = count.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    $('#vendedor-seguidores').text(formatted);
                }

                // re-enable
                seguir_btn.prop('disabled', false);
            }, 'json').fail(function(xhr, status, error) {
                console.log('Erro na requisição:', status, error);
                seguir_btn.prop('disabled', false);
            });
        });

        $(".carro-img").on('click', function() {
            let img = $(this).data('img');
            selecionado = img
            $(".carousel-item.active").removeClass('active');
            $('#crl-' + img).addClass('active');

            let sele = $(".selecionado");
            sele.removeClass('selecionado');
            $(this).parent().addClass('selecionado');
            progress.width(25 * (img - 1) + '%');
            $(carousel).find('.min').text(selecionado);
        });

        $('.carousel-control-prev').on('click', function() {
            let atual = $('.col-3.selecionado');
            let anterior = atual.prev();

            atual.removeClass('selecionado');

            if (anterior.length) {
                anterior.addClass('selecionado');
            } else if (selecionado === 1) {
                anterior = $('#img-row .col-3').last();
                anterior.addClass('selecionado');

                let data = quant - 3;

                $('.carro-img').each(function() {
                    $(this).data('img', data);
                    $(this).children().attr('src', carousel_imgs[data - 1]);
                    data++;
                });
            } else {
                atual.addClass('selecionado');

                $('.carro-img').each(function() {
                    let data = $(this).data('img');
                    if ((data - 1) < 1) {
                        data = quant
                    } else {
                        data--
                    };
                    $(this).data('img', data);
                    $(this).children().attr('src', carousel_imgs[data - 1]);
                });
            };

            if (selecionado === 1) {
                selecionado = quant;
            } else {
                selecionado--;
            };

            progress.width(25 * (anterior.children().data('img') - 1) + '%');

            $(carousel).find('.min').text(selecionado);
        });

        $('.carousel-control-next').on('click', function() {
            let atual = $('.col-3.selecionado');
            let proximo = atual.next();

            atual.removeClass('selecionado');

            if (proximo.length) {
                proximo.addClass('selecionado');
            } else if (selecionado === quant) {
                proximo = $('#img-row .col-3').first();
                proximo.addClass('selecionado');

                let data = 1;

                $('.carro-img').each(function() {
                    $(this).data('img', data);
                    $(this).children().attr('src', carousel_imgs[data - 1]);
                    data++;
                });
            } else {
                atual.addClass('selecionado');

                $('.carro-img').each(function() {
                    let data = $(this).data('img');
                    if ((data + 1) > quant) {
                        data = 1
                    } else {
                        data++
                    };
                    $(this).data('img', data);
                    $(this).children().attr('src', carousel_imgs[data - 1]);
                });
            };

            if (selecionado === quant) {
                selecionado = 1;
            } else {
                selecionado++;
            };

            progress.width(25 * (proximo.children().data('img') - 1) + '%');
            $(carousel).find('.min').text(selecionado);
        });

        // Horários collapse toggle: update icon from chevron-down to chevron-up when expanded
        const horariosCollapseEl = document.getElementById('horariosCollapse');
        if (horariosCollapseEl) {
            horariosCollapseEl.addEventListener('show.bs.collapse', function () {
                $('.toggle-icon i').removeClass('bi-chevron-down').addClass('bi-chevron-up');
                $('button.list-group-item').addClass('border-bottom-0').removeClass('rounded-2');
            });
            horariosCollapseEl.addEventListener('hide.bs.collapse', function () {
                $('.toggle-icon i').removeClass('bi-chevron-up').addClass('bi-chevron-down');
                $('button.list-group-item').removeClass('border-bottom-0').addClass('rounded-2');
            });
        }
    })
</script>

</html>