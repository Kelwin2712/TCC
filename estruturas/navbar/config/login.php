<div class="nav-item dropdown">
    <button class="btn border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle"></i>&nbsp;<?= $_SESSION['nome'] ?></button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="menu/configuracoes.php"><i class="bi bi-person-fill me-2"></i>Perfil</a></li>
        <hr class="dropdown-divider">
        <li><a class="dropdown-item" href="controladores/logout.php"><i class="bi bi-door-open me-2"></i>Sair</a></li>
    </ul>
</div>
<div class="col-auto ms-1 gap-5">
    <a href="menu/favoritos.php" class="btn px-2 border-0"><i class="bi bi-heart"></i></a>
    <a href="menu/mensagens.php" class="btn px-2 border-0">
        <i class="bi bi-chat-left-text position-relative">
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="padding: 0;">
                <span class="visually-hidden">New alerts</span>
            </span>
        </i>
    </a>
</div>