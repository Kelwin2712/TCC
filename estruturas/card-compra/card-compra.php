<div class="card border-1 border-secondary border-opacity-25 shadow-sm position-relative">
    <div id="imagems-carro" class="carousel slide card-img-top position-relative">
        <div class="carousel-inner">
            <a href="pagina-venda.php">
                <div class="carousel-item active">
                    <div class="ratio ratio-4x3">
                        <img src="<?php echo $img1; ?>" class="d-block w-100 h-100 object-fit-cover" alt="Imagem 1">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="ratio ratio-4x3">
                        <img src="<?php echo $img2; ?>" class="d-block w-100 h-100 object-fit-cover" alt="Imagem 2">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="ratio ratio-4x3">
                        <img src="<?php echo $img3; ?>" class="d-block w-100 h-100 object-fit-cover" alt="Imagem 3">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="ratio ratio-4x3">
                        <img src="<?php echo $img4; ?>" class="d-block w-100 h-100 object-fit-cover" alt="Imagem 4">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="ratio ratio-4x3">
                        <img src="<?php echo $img5; ?>" class="d-block w-100 h-100 object-fit-cover" alt="Imagem 5">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="ratio ratio-4x3">
                        <img src="<?php echo $img6; ?>" class="d-block w-100 h-100 object-fit-cover" alt="Imagem 6">
                    </div>
                </div>
            </a>
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
    <div class="card-body pb-1">
        <div class="row mb-3">
            <a href="pagina-venda.php" class="text-decoration-none text-dark stretched-link">
                <h5 class="card-title fw-bold mb-0 text-uppercase"><?php echo $nome; ?></h5>
            </a>
            <p class="card-text text-uppercase text-secondary small"><?php echo $info; ?></p>
        </div>
        <div class="row mb-3 gx-2">
            <div class="col-auto">
                <p class="card-text text-nowrap text-secondary small"><?php echo $ano; ?></p>
            </div>
            <div class="col-auto">
                <p class="card-text text-end text-secondary small"><?php echo $km; ?> km</p>
            </div>
            <div class="col d-flex align-items-center gap-2 text-secondary">
                <i class="bi bi-geo-alt-fill"></i>
                <p class="card-text text-nowrap small"><?php echo $loc; ?></p>
            </div>
        </div>
        <p class="card-text h5 fw-bold mb-2"><?php echo $preco; ?></p>
    </div>
    <div class="card-footer border-top-0 bg-body">
        <div class="row mb-1">
            <div class="col">
                <button class="btn btn-dark text-uppercase fw-bold w-100">Comprar</button>
            </div>
        </div>
    </div>
</div>