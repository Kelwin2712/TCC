<aside class="border-end d-flex flex-column position-fixed vh-100" style="width: calc(200px + 5vw);">
            <div class="position-relative">
                <button class="btn bg-white border position-absolute start-100 rounded-start-0 rounded-top-0"><i class="bi bi-layout-sidebar-inset"></i></button>
            </div>
            <a href="../index.php" class="d-flex mx-4 mt-4 mb-3 text-decoration-none text-dark">
                <img src="/sites/TCC/img/logo-fahren.png" class="d-inline-block align-text-center" width="23" height="38" alt="logo" style="filter: invert(1);">
                <span class="fw-semibold fs-3">&nbsp;Fahren</span>
            </a>
            <hr class="mx-3">
            <nav class="sidebar nav d-flex flex-column nav-pills mx-3 flex-grow-1 small ">
                <div class="my-2">
                    <a class="nav-link <?php if ($selected == 'config') {echo 'active';}?>" aria-current="page" href="configuracoes.php"><i class="bi bi-gear-fill"></i>&nbsp;Configurações</a>
                    <a class="nav-link <?php if ($selected == 'compras') {echo 'active';}?>" href="compras.php"><i class="bi bi-bag-fill"></i>&nbsp;Compras</a>
                    <a class="nav-link <?php if ($selected == 'fav') {echo 'active';}?>" href="favoritos.php"><i class="bi bi-heart-fill"></i>&nbsp;Favoritos</a>
                <hr class="mx-3">
                    <a class="nav-link <?php if ($selected == 'ad') {echo 'active';}?>" href="anuncios.php"><i class="bi bi-megaphone-fill"></i>&nbsp;Meus anúncios</a>
                    <a class="nav-link <?php if ($selected == 'mensagens') {echo 'active';}?>" href="mensagens.php"><i class="bi bi-chat-left-text-fill"></i>&nbsp;Mensagens</a>
                <hr class="mx-3">
                    <a class="nav-link <?php if ($selected == 'loja') {echo 'active';}?>" href="lista-lojas.php"><i class="bi bi-building-fill"></i>&nbsp;Lojas</a>
                    <a class="nav-link <?php if ($selected == 'equipe') {echo 'active';}?>" href="#"><i class="bi bi-people-fill"></i>&nbsp;Equipe</a>
                </div>
                <hr class="mx-3 mt-auto">
                <div class="footer mx-3 mb-3 d-flex justify-content-between align-content-center">
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="position-relative">
                                <img src="../img/logo-fahren-bg.jpg" alt="Foto de Perfil" width="32" height="32" class="rounded-circle me-2">
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