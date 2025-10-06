<?php if (isset($_SESSION['msg_alert'])): ?>
    <div class="position-fixed d-flex justify-content-center start-50 translate-middle-x mb-5 z-3 bottom-0">
      <div id="alert-mensagem" class="alert alert-<?= $_SESSION['msg_alert'][0] ?> alert-dismissible h-100 rounded-2" role="alert"">
                <div><span><i class=" bi <?php if ($_SESSION['msg_alert'][0] == 'danger') {
                                            echo 'bi-exclamation-triangle';
                                          } else {
                                            echo 'bi-check';
                                          } ?>"></i></span> <?= $_SESSION['msg_alert'][1] ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    </div>
    <?php unset($_SESSION['msg_alert']); ?>
<?php endif; ?>