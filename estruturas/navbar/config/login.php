<div class="nav-item dropdown">
    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-fill me-1"></i><?= $_SESSION['nome'] ?></button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="configuracoes.php"><i class="bi bi-gear-fill me-2"></i>Configurações</a></li>
        <li class="border-bottom pb-1 mb-1"><a class="dropdown-item" href="configuracoes.php"><i class="bi bi-person-fill me-2"></i>Perfil</a></li>
        <li><a class="dropdown-item" href="controladores/logout.php"><i class="bi bi-door-open me-2"></i>Sair</a></li>
    </ul>
</div>
<div class="col-auto ms-1 gap-5">
    <a href="favoritos.php" class="btn px-2"><i class="bi bi-heart"></i></a>
    <a href="" class="btn px-2"><i class="bi bi-chat-left-text"></i></a>
</div>