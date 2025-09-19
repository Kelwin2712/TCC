<aside class="border-end d-flex flex-column" style="width: 300px;">
            <div class="position-relative">
                <button class="btn bg-white border position-absolute start-100 rounded-start-0 rounded-top-0"><i class="bi bi-layout-sidebar-inset"></i></button>
            </div>
            <a href="index.php" class="d-flex mx-4 mt-4 mb-3 text-decoration-none text-dark">
                <img src="img/logo-fahren.png" class="d-inline-block align-text-center" width="23" height="38" alt="logo" style="filter: invert(1);">
                <span class="fw-semibold fs-3">&nbsp;Fahren</span>
            </a>
            <hr class="mx-3">
            <nav class="sidebar nav d-flex flex-column nav-pills mx-3 gap-2 flex-grow-1">
                <div class="my-1">
                    <a class="nav-link <?php if ($selected == 'perfil') {echo 'active';}?>" aria-current="page" href="perfil.php"><i class="bi bi-person-fill"></i>&nbsp;Perfil</a>
                    <a class="nav-link <?php if ($selected == 'config') {echo 'active';}?>" aria-current="page" href="configuracoes.php"><i class="bi bi-gear-fill"></i>&nbsp;Configurações</a>
                    <a class="nav-link <?php if ($selected == 'compras') {echo 'active';}?>" href="#"><i class="bi bi-bag-fill"></i>&nbsp;Compras</a>
                    <a class="nav-link <?php if ($selected == 'fav') {echo 'active';}?>" href="favoritos.php"><i class="bi bi-heart-fill"></i>&nbsp;Favoritos</a>
                </div>
                <hr class="mx-3">
                <div class="my-1">
                    <a class="nav-link <?php if ($selected == 'vendas') {echo 'active';}?>" href="#"><i class="bi bi-cart-fill"></i>&nbsp;Vendas</a>
                    <a class="nav-link <?php if ($selected == 'ad') {echo 'active';}?>" href="#"><i class="bi bi-megaphone-fill"></i>&nbsp;Meus anúncios</a>
                </div>
                <hr class="mx-3">
                <div class="my-1">
                    <a class="nav-link <?php if ($selected == 'loja') {echo 'active';}?>" href="#"><i class="bi bi-building-fill"></i>&nbsp;Minha loja</a>
                    <a class="nav-link <?php if ($selected == 'equipe') {echo 'active';}?>" href="#"><i class="bi bi-people-fill"></i>&nbsp;Equipe</a>
                    <a class="nav-link <?php if ($selected == 'stats') {echo 'active';}?>" href="#"><i class="bi bi-bar-chart-fill"></i>&nbsp;Estatísticas</a>
                    <a class="nav-link <?php if ($selected == 'config-loja') {echo 'active';}?>" href="#"><i class="bi bi-building-fill-gear"></i>&nbsp;Configurações da loja</a>
                </div>
                <hr class="mx-3 mt-auto">
                <div class="footer mx-3 mb-3 d-flex justify-content-between align-content-center">
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="position-relative">
                                <img src="img/logo-fahren-bg.jpg" alt="Foto de Perfil" width="32" height="32" class="rounded-circle me-2">
                            </div>
                            <span class="fw-semibold"><?= $_SESSION['nome']?></span>
                        </a>
                        <ul class="dropdown-menu text-small shadow">
                            <li><a class="dropdown-item" href="controladores/logout.php">Sair</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </aside>