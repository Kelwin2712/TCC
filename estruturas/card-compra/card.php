<div class="carousel-item">
    <div class="card card-hover">
        <img src="<?php echo $img; ?>" class="card-img-top" alt="">
        <div class="card-body">
            <a href="pagina-venda.php" class="text-decoration-none text-dark stretched-link">
                <h5 class="card-title fw-bold mb-0 text-uppercase"><?php echo $nome; ?></h5>
            </a>
            <p class="card-text text-uppercase text-secondary" style="font-size: .8em;"><?php echo $info; ?></p>
        </div>
        <div class="card-body">
            <p class="card-text h5 fw-bold mb-0"><?php echo $preco; ?></p>
            <div class="row">
                <div class="col-4">
                    <p class="card-text text-nowrap text-secondary" style="font-size: .8em;"><?php echo $ano; ?></p>
                </div>
                <div class="col-8">
                    <p class="card-text text-end text-secondary" style="font-size: .8em;"><?php echo $km; ?> km</p>
                </div>
            </div>
        </div>
        <div class="card-footer border-top-0 bg-body">
            <div class="row">
                <div class="col d-flex align-items-center gap-2 text-secondary">
                    <i class="bi bi-geo-alt-fill"></i>
                    <p class="card-text text-nowrap" style="font-size: .8em;"><?php echo $loc; ?></p>
                </div>
                <div class="col-auto">
                    <button class="btn p-0">
                        <i class="bi bi-heart text-secondary"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>