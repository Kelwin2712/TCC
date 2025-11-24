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
            <?php
            // ensure DB connection and compute pending invites before rendering the collapse button
            include_once(__DIR__ . '/../../conexao_bd.php');
            $pending_invites = [];
            $pending_count = 0;
            if (isset($_SESSION['id']) && isset($conexao) && $conexao) {
                $uid_pending = (int) $_SESSION['id'];
                $pq = "SELECT e.loja_id, l.nome as loja_nome, l.logo as loja_logo FROM equipe e JOIN lojas l ON l.id = e.loja_id WHERE e.usuario_id = $uid_pending AND e.status = 'P' ORDER BY e.created_at DESC";
                $pres = mysqli_query($conexao, $pq);
                if ($pres) {
                    while ($r = mysqli_fetch_assoc($pres)) $pending_invites[] = $r;
                }
                $pending_count = count($pending_invites);
            }
            ?>

            <button class="nav-link sidebar-drop w-100 text-start <?= isset($loja_id_selected) ? '' : 'collapsed'?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <span class="d-flex align-items-center justify-content-between w-100">
                    <span class="position-relative" style="display:inline-block;">
                        <i class="bi bi-building-fill"></i>&nbsp;Lojas
                        <?php if ($pending_count > 0): ?>
                            <span id="lojas-pending-dot" aria-hidden="true" style="position:absolute; top:-6px; right:-8px; width:10px; height:10px; background:#dc3545; border-radius:50%; display:inline-block; box-shadow:0 0 0 2px rgba(0,0,0,0.04);"></span>
                        <?php endif; ?>
                    </span>
                </span>
            </button>

            <div class="collapse mx-3 <?= isset($loja_id_selected) ? 'show' : ''?>" id="collapseExample">
                <div id="lojas-list" class="py-2 d-flex flex-column">
                    <?php

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

                    // fetch lojas where current user is an active member
                    $lojas = [];
                    if (isset($_SESSION['id'])) {
                        $uid = (int) $_SESSION['id'];
                        $sql = "SELECT l.id, l.nome, l.logo FROM lojas l JOIN equipe e ON e.loja_id = l.id WHERE e.usuario_id = $uid AND e.status = 'A' ORDER BY l.nome ASC";
                        $resultado = mysqli_query($conexao, $sql);
                        while ($linha = mysqli_fetch_array($resultado)) {
                            $lojas[] = $linha;
                        }
                    }

                    foreach ($lojas as $loja_side):
                        // determine logo path
                        $logoPath = '../img/logo-fahren-bg.jpg';
                        $possible = __DIR__ . '/../../img/lojas/' . $loja_side['id'] . '/';
                        if (!empty($loja_side['logo']) && file_exists($possible . $loja_side['logo'])) {
                            $logoPath = '../img/lojas/' . $loja_side['id'] . '/' . $loja_side['logo'];
                        }
                    ?>
                    <a href="loja.php?id=<?= $loja_side['id']?>" class="nav-link <?= $loja_id_selected == $loja_side['id'] ? 'active' : ''?> p-2 text-capitalize">
                        <img src="<?= $logoPath ?>" alt="Foto de Perfil" width="28" height="28" class="rounded-circle me-2" onerror="this.src='../img/logo-fahren-bg.jpg'">
                        <?= $loja_side['nome'] ?>
                    </a>
                    <?php endforeach; ?>

                    <!-- pending invites rendered inside collapse -->
                    <?php if (!empty($pending_invites)): ?>
                        <div class="mt-3">
                            <?php foreach ($pending_invites as $inv):
                                $logoPathI = '../img/logo-fahren-bg.jpg';
                                $possibleI = __DIR__ . '/../../img/lojas/' . $inv['loja_id'] . '/';
                                if (!empty($inv['loja_logo']) && file_exists($possibleI . $inv['loja_logo'])) {
                                    $logoPathI = '../img/lojas/' . $inv['loja_id'] . '/' . $inv['loja_logo'];
                                }
                            ?>
                            <div class="card mb-2">
                                <div class="card-body p-2 d-flex gap-2 align-items-center">
                                    <img src="<?= $logoPathI ?>" width="48" height="48" class="rounded-circle me-2" alt="logo" onerror="this.src='../img/logo-fahren-bg.jpg'">
                                    <div class="flex-grow-1">
                                        <div class="small text-muted">Você recebeu um convite:</div>
                                        <div class="fw-semibold text-capitalize"><?= htmlspecialchars($inv['loja_nome']) ?></div>
                                    </div>
                                    <div class="d-flex gap-1 ms-2">
                                        <button class="btn btn-sm btn-success accept-invite" data-loja="<?= $inv['loja_id'] ?>" title="Aceitar"><i class="bi bi-check-lg"></i></button>
                                        <button class="btn btn-sm btn-danger decline-invite" data-loja="<?= $inv['loja_id'] ?>" title="Recusar"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

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

<script>
document.addEventListener('click', function(e){
                        if(e.target && e.target.closest && e.target.closest('.accept-invite')){
        const btn = e.target.closest('.accept-invite');
        const lojaId = btn.dataset.loja;
        if(!confirm('Aceitar convite para esta loja?')) return;
        const p = new URLSearchParams(); p.append('loja_id', lojaId); p.append('usuario_id', <?= isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0 ?>); p.append('action','accept');
        fetch('../controladores/loja/responder-convite.php', { method:'POST', body: p }).then(r=>r.json()).then(res=>{
            if(!res.success) { alert(res.message||'Erro'); return; }
            // remove the invite card
            const card = btn.closest('.card'); if(card) card.remove();
            // if backend returned loja info, insert into lojas list if not already present
            if (res.loja && res.loja.id) {
                const lojaIdNum = res.loja.id;
                const lojasList = document.getElementById('lojas-list');
                if (lojasList && !lojasList.querySelector('[data-loja-id="'+lojaIdNum+'"]')) {
                    const a = document.createElement('a');
                    a.href = 'loja.php?id=' + lojaIdNum;
                    a.className = 'nav-link p-2 text-capitalize';
                    a.setAttribute('data-loja-id', lojaIdNum);
                    const img = document.createElement('img');
                    img.src = res.loja.logo_url || '../img/logo-fahren-bg.jpg';
                    img.width = 28; img.height = 28; img.className = 'rounded-circle me-2'; img.alt = 'Logo'; img.setAttribute('onerror', "this.src='../img/logo-fahren-bg.jpg'");
                    a.appendChild(img);
                    a.insertAdjacentHTML('beforeend', res.loja.nome);
                    // insert before the 'Criar nova loja' button (last element)
                    // find the create button
                    const createBtn = lojasList.querySelector('[data-bs-target="#loja-modal"]').closest('.nav-link');
                    if (createBtn) lojasList.insertBefore(a, createBtn);
                    else lojasList.appendChild(a);
                }
            }
            // hide red dot if no pending invites remain
            const remainingInvites = document.querySelectorAll('#collapseExample .card');
            const dot = document.getElementById('lojas-pending-dot');
            if (dot && remainingInvites.length === 0) dot.remove();
        }).catch(err=>{ console.error(err); alert('Erro de rede'); });
    }
                        if(e.target && e.target.closest && e.target.closest('.decline-invite')){
        const btn = e.target.closest('.decline-invite');
        const lojaId = btn.dataset.loja;
        if(!confirm('Recusar convite?')) return;
        const p = new URLSearchParams(); p.append('loja_id', lojaId); p.append('usuario_id', <?= isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0 ?>); p.append('action','decline');
        fetch('../controladores/loja/responder-convite.php', { method:'POST', body: p }).then(r=>r.json()).then(res=>{
            if(!res.success) { alert(res.message||'Erro'); return; }
            const card = btn.closest('.card'); if(card) card.remove();
            const remainingInvites = document.querySelectorAll('#collapseExample .card');
            const dot = document.getElementById('lojas-pending-dot');
            if (dot && remainingInvites.length === 0) dot.remove();
        }).catch(err=>{ console.error(err); alert('Erro de rede'); });
    }
});
</script>