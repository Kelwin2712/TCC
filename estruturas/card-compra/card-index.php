<?php if (empty($no_carousel_wrapper)): ?>
    <div class="carousel-item">
    <?php endif; ?>
    <div class="card card-compra h-100 shadow-hover position-relative">
        <img src="<?php echo $img; ?>" class="card-img-top" alt="">
        <div class="card-body pb-1 d-flex flex-column justify-content-between">
            <div class="row mb-2">
                <a href="pagina-venda.php?id=<?php echo $id; ?>" class="text-decoration-none text-dark stretched-link">
                    <h5 class="card-title fw-bold mb-0 text-uppercase"><?php echo $nome; ?></h5>
                </a>
                <p class="card-text text-uppercase text-secondary small"><?php echo $info; ?></p>
            </div>
            <div class="row">
                <p class="card-text h5 fw-bold mb-0"><?php echo $preco; ?></p>
                <div class="row">
                    <div class="col-4">
                        <p class="card-text text-nowrap text-secondary small"><?php echo $ano; ?></p>
                    </div>
                    <div class="col-8">
                        <p class="card-text text-end text-secondary small"><?php echo $km; ?> km</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer border-top-0 bg-body">
            <div class="row">
                <div class="col d-flex align-items-center gap-2 text-secondary">
                    <i class="bi bi-geo-alt-fill"></i>
                    <p class="card-text text-nowrap small"><?php echo $loc; ?></p>
                </div>
                <div class="col-auto">
                    <?php if (isset($_SESSION['id']) && $_SESSION['id'] > 0): ?>
                        <button type="button" class="btn p-0 position-relative favoritar" data-anuncio="<?php echo $id; ?>">
                            <i class="bi <?= ($favoritado == 1) ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary'  ?>"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php if (empty($no_carousel_wrapper)): ?>
    </div>
<?php endif; ?>