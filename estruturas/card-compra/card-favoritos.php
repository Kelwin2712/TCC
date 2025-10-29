<div class="card card-compra shadow-hover border-1 border-secondary border-opacity-25 position-relative h-100">
    <div class="row g-0">
        <div class="col-12 col-lg-4 col-xxl-2">
            <div class="ratio ratio-4x3 h-100">
                <img src="<?= $img1; ?>" class="img-fluid object-fit-cover" alt="Imagem 1">
            </div>
        </div>

        <div class="col">
            <div class="card-body position-relative">
            <div class="position-absolute top-0 end-0 p-2 favoritar-btn">
                <div class="col-auto">
                    <button type="button" class="btn text-dark border-0 position-relative favoritar rounded-circle" data-anuncio='<?= $id ?>'>
                        <i class="bi <?= ($favoritado == 1) ? 'bi-heart' : 'bi-heart-fill'  ?>"></i>
                    </button>
                </div>
            </div>
                <div class="row mb-3">
                    <a href="../pagina-venda.php?id=<?= $id; ?>" class="text-decoration-none text-dark stretched-link" style="padding-right: 2.5rem;">
                        <h5 class="card-title fw-bold mb-0 text-uppercase"><?= $marca ?> <?= $modelo ?></h5>
                    </a>
                    <p class="card-text text-uppercase text-secondary small"><?= $versao; ?></p>
                </div>
                <div class="row mb-3 gx-4">
                    <div class="col-auto">
                        <p class="card-text text-nowrap text-secondary small"><?= $ano; ?></p>
                    </div>
                    <div class="col-auto">
                        <p class="card-text text-end text-secondary small"><?= $km; ?> km</p>
                    </div>
                    <div class="col d-flex align-items-center gap-2 text-secondary">
                        <i class="bi bi-geo-alt-fill"></i>
                        <p class="card-text text-nowrap small text-truncate"><?= $loc; ?></p>
                    </div>
                </div>
                <p class="card-text h5 fw-bold mb-2">R$ <?= number_format($preco, 2, ',', '.'); ?></p>
            </div>
        </div>
    </div>
</div>