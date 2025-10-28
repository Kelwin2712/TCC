<aside class="border-end d-flex flex-column position-fixed vh-100" style="width: calc(200px + 5vw);">
    <div class="position-relative">
        <button class="btn bg-white border position-absolute start-100 rounded-start-0 rounded-top-0"><i class="bi bi-layout-sidebar-inset"></i></button>
    </div>
    <a href="../index.php" class="d-flex mx-4 mt-4 mb-3 text-decoration-none text-dark">
        <img src="/sites/TCC/img/logo-fahren.png" class="d-inline-block align-text-center" width="23" height="38" alt="logo" style="filter: invert(1);">
        <span class="fw-semibold fs-3">&nbsp;Fahren</span>
    </a>
    <hr class="mx-3">
    <nav class="sidebar nav d-flex flex-column nav-pills mx-3 flex-shrink-1 small overflow-y-auto" style="min-height: 0;">
        <div class="my-2">
            <a class="nav-link <?php if ($selected == 'config') {
                                    echo 'active';
                                } ?>" aria-current="page" href="configuracoes.php"><i class="bi bi-gear-fill"></i>&nbsp;Configurações</a>
            <a class="nav-link <?php if ($selected == 'compras') {
                                    echo 'active';
                                } ?>" href="compras.php"><i class="bi bi-bag-fill"></i>&nbsp;Compras</a>
            <a class="nav-link <?php if ($selected == 'fav') {
                                    echo 'active';
                                } ?>" href="favoritos.php"><i class="bi bi-heart-fill"></i>&nbsp;Favoritos</a>
            <hr class="mx-3">
            <a class="nav-link <?php if ($selected == 'ad') {
                                    echo 'active';
                                } ?>" href="anuncios.php"><i class="bi bi-megaphone-fill"></i>&nbsp;Meus anúncios</a>
            <a class="nav-link <?php if ($selected == 'mensagens') {
                                    echo 'active';
                                } ?>" href="mensagens.php"><i class="bi bi-chat-left-text-fill"></i>&nbsp;Mensagens</a>
            <hr class="mx-3">
            <button class="nav-link sidebar-drop w-100 text-start <?= isset($loja_id_selected) ? '' : 'collapsed'?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <span>
                    <i class="bi bi-building-fill"></i>&nbsp;Lojas
                </span>
            </button>

            <div class="collapse mx-3 <?= isset($loja_id_selected) ? 'show' : ''?>" id="collapseExample">
                <div class="py-2 d-flex flex-column">
                    <?php
                    include('../conexao_bd.php');

                    // fetch current user's avatar if available (fallback to default)
                                    $user_avatar_src = '../img/usuarios/avatares/user.png';
                    if (isset($_SESSION['id'])) {
                        if (!empty($_SESSION['avatar'])) {
                            $user_avatar_src = '../' . $_SESSION['avatar'];
                        } else {
                            $uid = (int) $_SESSION['id'];
                            $res_av = mysqli_query($conexao, "SELECT avatar FROM usuarios WHERE id = $uid");
                            if ($res_av && mysqli_num_rows($res_av) > 0) {
                                $r_av = mysqli_fetch_assoc($res_av);
                                    if (!empty($r_av['avatar'])) {
                                    $user_avatar_src = '../' . $r_av['avatar'];
                                }
                            }
                        }
                    }

                    $sql = "SELECT nome, id FROM lojas";
                    $resultado = mysqli_query($conexao, $sql);

                    $lojas = [];

                    while ($linha = mysqli_fetch_array($resultado)) {
                        $lojas[] = $linha;
                    }

                    foreach ($lojas as $loja_side):
                    ?>
                    <a href="loja.php?id=<?= $loja_side['id']?>" class="nav-link <?= $loja_id_selected == $loja_side['id'] ? 'active' : ''?> p-2 text-capitalize">
                        <img src="../img/logo-fahren-bg.jpg" alt="Foto de Perfil" width="28" height="28" class="rounded-circle me-2">
                        <?= $loja_side['nome'] ?>
                    </a>
                    <?php endforeach; mysqli_close($conexao);?>
                    <button class="nav-link p-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#loja-modal">
                        <span class="d-inline-flex justify-content-center align-items-center bg-body-secondary rounded-circle me-2"
                            style="width:28px; height:28px;">
                            <i class="bi bi-plus-lg"></i>
                        </span>
                        Criar nova loja
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <hr class="mx-3 mt-auto">
            <div class="footer flex-shrink-0 mx-3 mb-3 d-flex justify-content-between align-content-center">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="position-relative">
                    <img src="<?= htmlspecialchars($user_avatar_src) ?>" alt="Foto de Perfil" width="32" height="32" class="rounded-circle me-2">
                </div>
                <span class="fw-semibold"><?= $_SESSION['nome'] ?></span>
            </a>
            <ul class="dropdown-menu text-small shadow">
                <li><a class="dropdown-item" href="../controladores/logout.php">Sair</a></li>
            </ul>
        </div>
    </div>
</aside>