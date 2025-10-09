<div class="carousel-item">
    <a href="#" class="card card-hover" style="text-decoration: none;">
        <img src="<?php  echo $img;?>" class="card-img-top" alt="">
        <div class="card-body">
            <h5 class="card-title fw-bold mb-0 text-uppercase"><?php  echo $nome;?></h5>
            <p class="card-text text-uppercase" style="font-size: .8em;"><?php  echo $info;?></p>
        </div>
        <div class="card-body">
            <p class="card-text h5 fw-bold mb-0"><?php  echo $preco;?></p>
            <div class="row">
                <div class="col-4">
                    <p class="card-text text-nowrap" style="font-size: .8em;"><?php  echo $ano;?></p>
                </div>
                <div class="col-8">
                    <p class="card-text text-end" style="font-size: .8em;"><?php  echo $km;?> km</p>
                </div>
            </div>
        </div>
        <div class="card-footer bg-body d-flex align-items-center gap-2">
            <i class="bi bi-geo-alt-fill"></i>
            <p class="card-text text-nowrap" style="font-size: .8em;"><?php  echo $loc;?></p>
        </div>
    </a>
</div>