<div class="card">
    <img src="<?php echo $img; ?>" class="card-img-top" alt="">
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
        <p class="card-text h5 fw-bold mb-0"><?php echo $preco; ?></p>
    </div>
    <div class="card-footer border-top-0 bg-body">
        <div class="row mb-1">
            <div class="col">
                <button class="btn btn-dark text-uppercase fw-bold w-100">Comprar</button>
            </div>
        </div>
    </div>
</div>