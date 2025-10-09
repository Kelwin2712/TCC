<div class="card card-anuncio shadow-hover border-1 border-secondary border-opacity-25 position-relative h-100">
    <div class="row g-0">
        <div class="col-12 col-lg-4 col-xxl-2">
            <div class="ratio ratio-4x3 bg-danger h-100">
                <img src="<?= $img1; ?>" class="img-fluid object-fit-cover" alt="Imagem 1">
            </div>
        </div>

        <div class="col">
            <div class="card-body pb-1">
                <div class="row mb-3">
                    <a href="editar-anuncio.php?id=<?= $id; ?>" class="text-decoration-none text-dark stretched-link">
                        <h5 class="card-title fw-bold mb-0 text-uppercase"><?= $marca ?> <?= $modelo ?></h5>
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
            <div class="card-footer position-relative border-top-0 bg-body">
                <div class="row mb-1">
                    <div class="col">
                        <a href="editar-anuncio.php?id=<?= $id; ?>" class="btn btn-dark rounded-pill fw-bold w-100">Editar&nbsp;&nbsp;<i class="bi bi-pencil"></i></a>
                    </div>
                    <div class="col-auto">
                        <button class="btn border rounded-circle text-uppercase fw-bold w-100" data-bs-toggle="modal" data-id-delete="<?= $id; ?>" data-bs-target="#delete-modal"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>