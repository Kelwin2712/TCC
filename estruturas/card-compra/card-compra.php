<div class="card card-compra shadow-hover border-1 border-secondary border-opacity-25 position-relative">
    <div id="<?= $id ?>" class="carousel slide card-img-top position-relative" data-quant="1">
        <div class="carousel-inner">
            <a class="carro-img" href="pagina-venda.php?marca=<?= $marca?>&modelo=<?= $modelo?>&versao=<?= $versao?>&preco=<?= $preco?>&ano=<?= $ano?>&km=<?= $km?>">
                <div class="carousel-item active">
                    <div class="ratio ratio-4x3">
                        <img src="<?= $img1; ?>" class="d-block img-fluid object-fit-cover" alt="Imagem 1">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="ratio ratio-4x3">
                        <img src="<?= $img2; ?>" class="d-block img-fluid object-fit-cover" alt="Imagem 2">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="ratio ratio-4x3">
                        <img src="<?= $img3; ?>" class="d-block img-fluid object-fit-cover" alt="Imagem 3">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="ratio ratio-4x3">
                        <img src="<?= $img4; ?>" class="d-block img-fluid object-fit-cover" alt="Imagem 4">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="ratio ratio-4x3">
                        <img src="<?= $img5; ?>" class="d-block img-fluid object-fit-cover" alt="Imagem 5">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="ratio ratio-4x3">
                        <img src="<?= $img6; ?>" class="d-block img-fluid object-fit-cover" alt="Imagem 6">
                    </div>
                </div>
            </a>
            <div id="img-quant" class="row position-absolute bottom-0 p-2" style="display: none;">
                <div class="col-auto">
                    <div class="text-bg-dark bg-opacity-50 rounded-pill py-1" style="font-size: .8rem; padding-left: .75rem; padding-right: .75rem;"><span class="min">1</span>/<span class="max"></span></div>
                </div>
            </div>
            <div class="row position-absolute top-0 end-0 p-2 favoritar-btn">
                <div class="col-auto">
                    <button type="button" class="btn text-bg-dark bg-opacity-50 position-relative favoritar rounded-circle">
                        <i class="bi bi-heart"></i>
                    </button>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" style="display: none;" type="button" data-bs-target="#<?= $id ?>" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" style="display: none;" type="button" data-bs-target="#<?= $id ?>" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <div class="card-body pb-1">
        <div class="row mb-3">
            <a href="pagina-venda.php?marca=<?= $marca?>&modelo=<?= $modelo?>&versao=<?= $versao?>&preco=<?= $preco?>&ano=<?= $ano?>&km=<?= $km?>" class="text-decoration-none text-dark stretched-link">
                <h5 class="card-title fw-bold mb-0 text-uppercase"><?= $marca?> <?= $modelo?></h5>
            </a>
            <p class="card-text text-uppercase text-secondary small"><?= $versao; ?></p>
        </div>
        <div class="row mb-3 gx-2">
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
        <p class="card-text h5 fw-bold mb-2">R$ <?= $preco; ?></p>
    </div>
    <div class="card-footer border-top-0 bg-body">
        <div class="row mb-1">
            <div class="col">
                <button class="btn btn-dark rounded-5 text-uppercase fw-bold w-100">Comprar</button>
            </div>
        </div>
    </div>
</div>