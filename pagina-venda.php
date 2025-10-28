<?php
session_start();
include('controladores/conexao_bd.php');

$id_veiculo = $_GET['id'];

if (!$id_veiculo) {
    header('Location: index.php');
    exit;
}

$sql = "SELECT carros.*, marcas.nome as marca_nome, usuarios.nome as vendedor_nome, usuarios.sobrenome as vendedor_sobrenome, usuarios.avatar as vendedor_avatar FROM anuncios_carros carros INNER JOIN marcas ON carros.marca = marcas.id INNER JOIN usuarios ON carros.id_vendedor = usuarios.id WHERE carros.id = $id_veiculo";
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

$vendedor = $carro['vendedor_nome'] . ' ' . $carro['vendedor_sobrenome'];
$vendedor_img = !empty($carro['vendedor_avatar']) ? $carro['vendedor_avatar'] : 'img/usuarios/avatares/user.png';
$vendedor_est = '4.63';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title><?= $carro['marca'] . ' ' . $carro['modelo'] . ' ' . $carro['versao'] ?></title>
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
                                                    <div id="crl-<?= ($idx+1) ?>" class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
                                                        <div class="ratio ratio-16x9">
                                                            <img src="img/anuncios/carros/<?= $id_veiculo ?>/<?= htmlspecialchars($ph) ?>" class="d-block img-fluid object-fit-cover" alt="Imagem <?= ($idx+1) ?>">
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
                                                            <div class="carro-img ratio ratio-16x9" data-img="<?= ($idx+1) ?>">
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
                                    <p class="fs-1 fw-semibold mb-0">R$ <?= number_format($carro['preco'], 2, ',', '.'); ?></p>
                                </div>
                                <div class="col-auto">
                                    <span class="badge text-bg-primary py-2 user-select-none rounded-3"><i class="bi bi-shield-check"></i> Confiável</span>
                                </div>
                                <p>Envie uma mensagem para o vendedor</p>
                            </div>
                            <?php if (!isset($_SESSION['id'])): ?>
                                <div class="row">
                                    <div class="mb-2">
                                        <label for="nome-input" class="form-label form-text mb-0">Nome<sup class="text-danger">*</sup></label>
                                        <input type="text" class="form-control shadow-sm rounded-4" id="nome-input" placeholder="Nome" required>
                                    </div>
                                    <div class="mb-2">
                                        <label for="email-input" class="form-label form-text mb-0">Email<sup class="text-danger">*</sup></label>
                                        <input type="email" class="form-control shadow-sm rounded-4" id="email-input" placeholder="Email" required>
                                    </div>
                                    <div class="mb-2">
                                        <label for="telefone-input" class="form-label form-text mb-0">Telefone<sup class="text-danger">*</sup></label>
                                        <input type="email" class="form-control shadow-sm rounded-4" id="telefone-input" placeholder="Telefone" required>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-0">
                                            <label for="mensagem-input" class="form-label form-text">
                                                Mensagem<sup class="text-danger">*</sup>
                                            </label>
                                            <small id="max-mensagem" class="form-text" style="font-size: .75rem;">0/500</small>
                                        </div>
                                        <textarea class="form-control shadow-sm rounded-4" id="mensagem-input" placeholder="Mensagem" maxlength="500" minlength="10" required></textarea>
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
                            <div class="row">
                                <div class="col"<?= $carro['id_vendedor'] == $_SESSION['id'] ? " title=\"Você não pode enviar mensagem para si mesmo\"" : '' ?>>
                                    <button type="submit" class="btn rounded-4 btn-dark w-100 mb-3 py-2 shadow-sm" <?= $carro['id_vendedor'] == $_SESSION['id'] ? 'disabled' : '' ?>>Enviar mensagem</button>
                                </div>
                            </div>
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
                                    <h2 class="fw-bold mb-0 text-uppercase"><?= $carro['marca_nome'] ?> <?= $carro['modelo'] ?></h2>
                                    <p class="text-uppercase"><?= $carro['versao'] ?></p>
                                </div>
                                <div class="col">
                                    <p class="text-capitalize text-end"><i class="bi bi-geo-alt"></i> São José dos Campos - <?= $carro['estado_local'] ?></p>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn p-0 favoritar favoritar-danger">
                                        <i class="bi bi-heart text-secondary fs-5"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row px-4 pt-3">
                                <p>Informações</p>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="row">
                                            <p class="mb-0">Ano</p>
                                        </div>
                                        <div class="row">
                                            <p class="fw-semibold "><?= $carro['ano_fabricacao'].'/'.$carro['ano_modelo'] ?></p>
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
                                            <p class="fw-semibold text-capitalize"><?= $carro['cor'] ?></p>
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
                            <hr class="w-100">
                            <div class="row px-4 pt-3">
                                <p>Descrição do veículo</p>
                                <p class="text-secondary">Blindado DVB (Divena) com vidros AGP B33; Downpipe, Stage 2 by Soldera, filtro K&N; Cor Preta GT com PPF Full transparente/fosco; Faróis LED PDLS Plus; Rodas RS Spyder em cinza acetinado; Interno Cinza Ardósia; Bancos elétricos com memória; Pacote Sport Chrono; Sistema de áudio BOSE; Sport Exhaust; PASM; Câmbio PDK; Teto Solar Panorâmico blindado; Painel e Multimídia TFT; Apple Car Play e Android Auto; Drive Mode no volante; Ar-condicionado Dual-Zone; Revisado Outros Opcionais: Comando de áudio no volante, Controle de estabilidade, Direção Elétrica, Distribuição eletrônica de frenagem, Kit Multimídia, Pára-choques na cor do veículo, Porta-copos.
                                </p>
                            </div>
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
                                    <button type="button" class="btn btn-sm bg-primary-subtle text-primary px-2 float-end rounded-3" style="padding-top: .125rem; padding-bottom: .125rem;">
                                        <div id="seguir-btn" class="small">Seguir</div>
                                    </button>
                                </div>
                            </div>
                            <a href="compras.php?vendedor=<?= $vendedor ?>&vendedor_img=<?= $vendedor_img ?>&vendedor_est=<?= $vendedor_est ?>" class="row px-2 text-decoration-none text-dark">
                                <div class="rounded-3 border-2">
                                    <div class="row">
                                        <div class="col p-2 d-flex align-items-center justify-content-center">
                                            <div class="ratio ratio-1x1">
                                                <img src="<?= $vendedor_img ?>" alt="" class="img-fluid rounded-3 shadow-sm">
                                            </div></i>
                                        </div>
                                        <div class="col-7 py-2">
                                            <div class="row">
                                                <p class="fw-semibold mb-0"><?= $vendedor ?></p>
                                            </div>
                                            <div class="row">
                                                <small class="fw-semibold mb-0"><?= $vendedor_est ?> <i class="bi bi-star-fill"></i></small>
                                            </div>
                                        </div>
                                        <div class="col-3 d-inline-flex align-items-center text-nowrap">
                                            <small>Aberto <i class="bi bi-circle-fill text-success" style="font-size: 0.5rem !important; vertical-align: middle;"></i></small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'estruturas/footer/footer.php' ?>

</body>
<?php if (isset($conexao)) { mysqli_close($conexao); } ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="script.js"></script>
<script>
    $(function() {
        $('form').on('submit', function(e) {
            e.preventDefault();
            $.post('controladores/mensagens/enviar.php', {
                de: <?= $_SESSION['id']?>,
                para: <?= $carro['id_vendedor']?>,
                anuncio: <?= $_GET['id']?>,
                texto: $('#mensagem-input').val()
            }, function(resposta) {
                if (resposta == true) {
                    const btn = $("button[type='submit']");
                    btn.html('Mensagem enviada&nbsp;<i class="bi bi-check2"></i>');
                    btn.prop('disabled', true);
                }
            })
        })
        
        const msgInput =  $('#mensagem-input');
        const msgMax =  $('#max-mensagem');
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
        const seguir_btn = seguir_info.parent();

        const progress = $('.progress-bar.bg-transparent').parent();

        seguir_btn.on('click', function() {
            if (seguir_info.html() == 'Seguir') {
                seguir_info.html('Seguindo <i class="bi bi-check"></i>');
                seguir_btn.removeClass('text-primary');
                seguir_btn.removeClass('bg-primary-subtle');
                seguir_btn.addClass('text-secondary');
            } else {
                seguir_info.html('Seguir');
                seguir_btn.addClass('text-primary');
                seguir_btn.addClass('bg-primary-subtle');
                seguir_btn.removeClass('text-secondary');
            };
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
    })
</script>

</html>