<?php
session_start();
include('controladores/conexao_bd.php');

if (!isset($_SESSION['id'])) {
  header('Location: sign-in.php');
  exit();
}

$uid = (int) $_SESSION['id'];

// If arrived via POST from vender-preco, save posted fields into session and operate in temp mode
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // save price/contact fields from vender-preco
  $_SESSION['preco'] = isset($_POST['preco']) ? $_POST['preco'] : ($_SESSION['preco'] ?? null);
  $_SESSION['troca'] = isset($_POST['troca']) ? $_POST['troca'] : ($_SESSION['troca'] ?? null);
  $_SESSION['email'] = isset($_POST['email']) ? $_POST['email'] : ($_SESSION['email'] ?? null);
  $_SESSION['telefone'] = isset($_POST['telefone']) ? $_POST['telefone'] : ($_SESSION['telefone'] ?? null);
  // save seller type and selected loja (if any)
  if (isset($_POST['tipo_vendedor'])) {
    $_SESSION['tipo_vendedor'] = $_POST['tipo_vendedor']; // expected 'pf' or 'pj'
  }
  if (isset($_POST['loja_id'])) {
    $_SESSION['loja_id'] = (int) $_POST['loja_id'];
  }
  // save optional location from previous step (estado/cidade)
  if (isset($_POST['estado'])) {
    $_SESSION['estado_local'] = $_POST['estado'];
  }
  if (isset($_POST['cidade'])) {
    $_SESSION['cidade'] = $_POST['cidade'];
  }
  // operate in temp mode (no carro_id yet)
  $carro_id = 0;
  // fetch any temp photos already uploaded for this user
  $photos = [];
  $tmp_dir = __DIR__ . '/img/anuncios/temp/' . $uid . '/';
  if (is_dir($tmp_dir)) {
    $files = glob($tmp_dir . '*');
    foreach ($files as $f) {
      if (is_file($f)) $photos[] = ['caminho_foto' => basename($f), 'id' => null];
    }
  }
} else {
  // GET mode: expect ?id={carro_id} to manage existing anuncio
  $carro_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
  if ($carro_id <= 0) {
    $_SESSION['msg_alert'] = ['danger', 'Anúncio inválido.'];
    header('Location: menu/anuncios.php');
    exit();
  }
  // ensure current user is owner of the anuncio
  $res = mysqli_query($conexao, "SELECT id FROM anuncios_carros WHERE id = $carro_id AND id_vendedor = $uid");
  if (!$res || mysqli_num_rows($res) === 0) {
    $_SESSION['msg_alert'] = ['danger', 'Anúncio não encontrado ou você não tem permissão.'];
    header('Location: menu/anuncios.php');
    exit();
  }
  // fetch existing photos
  $photos = [];
  $qry = mysqli_query($conexao, "SELECT * FROM fotos_carros WHERE carro_id = $carro_id ORDER BY `ordem` ASC, id ASC");
  if ($qry) {
    while ($row = mysqli_fetch_assoc($qry)) $photos[] = $row;
  }
}

mysqli_close($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <title>Adicionar fotos - Fahren</title>
  <style>
    .thumb { width: 140px; height: 100px; object-fit: cover; border-radius: 6px; }
    .thumb-wrap { position: relative; display:inline-block; margin:6px }
    .thumb-actions { position:absolute; top:6px; right:6px; }
  </style>
</head>

<body>
  <div class="vh-100 d-flex flex-column">
    <?php include 'estruturas/alert/alert.php' ?>
    <?php include 'estruturas/navbar/navbar-default.php' ?>
    <main class="bg-body-tertiary fs-nav flex-grow-1 d-flex justify-content-center align-items-start py-5">
      <div class="container">
        <div class="card">
          <div class="card-body p-5">
            <h3 class="mb-5 fw-bold">Fotos do anúncio</h3>
            <div id="dropzone" class="mb-3 w-100 d-flex flex-column justify-content-center align-items-center p-5 border rounded-5 text-muted bg-body-secondary bg-opacity-25" style="cursor:pointer">
              <span class="fs-1"><i class="bi bi-image"></i></span>
              <span><a href="#" id="dropzone-click">Clique aqui</a> para escolher sua imagem ou arraste e solte</span>
              <small>5MB por arquivo</small>
            </div>

            <hr class="my-5">

            <div class="alert alert-info" role="alert">
              <i class="bi bi-info-circle"></i> É necessário adicionar no mínimo <strong>5 fotos</strong> para finalizar o anúncio.
            </div>

            <h6>Imagens atuais</h6>
            <div id="gallery" class="mt-3">
              <?php if (empty($photos)): ?>
                <div class="text-muted">Nenhuma imagem enviada ainda.</div>
              <?php else: ?>
                <?php foreach ($photos as $p): ?>
                  <?php
                    if ($carro_id > 0) {
                      $src = 'img/anuncios/carros/' . $carro_id . '/' . $p['caminho_foto'];
                      $data_attr = 'data-id="' . htmlspecialchars($p['id']) . '"';
                      $btn_attr = 'data-id="' . htmlspecialchars($p['id']) . '"';
                    } else {
                      $src = 'img/anuncios/temp/' . $uid . '/' . $p['caminho_foto'];
                      $data_attr = 'data-filename="' . htmlspecialchars($p['caminho_foto']) . '"';
                      $btn_attr = 'data-filename="' . htmlspecialchars($p['caminho_foto']) . '"';
                    }
                  ?>
                  <div class="thumb-wrap" <?= $data_attr ?> >
                    <img class="thumb" src="<?= htmlspecialchars($src) ?>" alt="foto">
                    <div class="thumb-actions">
                      <button class="btn btn-sm btn-danger btn-delete-photo" <?= $btn_attr ?> title="Remover"><i class="bi bi-trash"></i></button>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            
            <hr class="my-4">

            <div class="mb-3">
              <label for="descricao" class="form-label">Descrição do anúncio <small class="text-muted">(opcional — máx. 1000 caracteres)</small></label>
              <textarea id="descricao" name="descricao" class="form-control" rows="6" maxlength="1000" placeholder="Descreva o veículo: estado, opcionais, histórico, diferenciais..."></textarea>
              <div class="form-text text-end"><span id="desc-count">0</span>/1000</div>
            </div>
            
            <div class="row d-flex justify-content-between align-items-center w-100 mt-5">
              <div class="col-auto">
                <a href="vender-placa.php" class="btn text-muted"><i class="bi bi-caret-left"></i>&nbsp;Voltar</a>
              </div>
              <div class="col-auto">
                <!-- finalize/next button used by JS (#finalizar-btn) -->
                <button id="finalizar-btn" type="button" class="btn btn-dark shadow-sm">Próximo passo&nbsp;<i class="bi bi-caret-right-fill"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <!-- Confirmation modal -->
  <div class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-5">
          <div class="bg-success-subtle rounded-circle d-flex text-success justify-content-center align-items-center mb-3 mx-auto fs-5" style="width: 60px; height: 60px;">
            <i class="bi bi-check-lg"></i>
          </div>
          <div class="text-center">
            <h4>Confirmar publicação</h4>
            <p class="text-muted">Deseja finalizar e publicar o anúncio agora? Verifique se a descrição e as fotos estão corretas.</p>
          </div>
        </div>
        <div class="modal-footer d-flex flex-column border-top-0">
          <button type="button" id="confirm-finalize" class="btn btn-success w-100">Sim, publicar</button>
          <button type="button" class="btn bg-body-secondary w-100" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // showAlert helper (same look as server-side alert)
    function showAlert(type, text, timeout = 4500) {
      $('#alert-mensagem').closest('.position-fixed').remove();
      const icon = (type === 'danger') ? 'bi-exclamation-triangle' : 'bi-check';
      const $wrap = $('<div/>', { 'class': 'position-fixed d-flex justify-content-center start-50 translate-middle-x mb-5 z-3 bottom-0' });
      const $alert = $(
        '<div id="alert-mensagem" class="alert alert-' + type + ' alert-dismissible h-100 rounded-2" role="alert">' +
        '<div><span><i class=" bi ' + icon + '"></i></span> ' + $('<div/>').text(text).html() + '</div>' +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
        '</div>'
      );
      $wrap.append($alert);
      $('body').append($wrap);
      if (timeout > 0) setTimeout(function() { try { $alert.alert('close'); } catch (e) { $wrap.remove(); } }, timeout);
    }

        $(function() {
      function updateFinalButton() {
        const count = $('#gallery .thumb-wrap').length;
        const $final = $('#finalizar-btn');
        if ($final.length === 0) return; // not in temp mode
        
        // If there are thumb-wraps, hide the "Nenhuma imagem" message
        if (count > 0) {
          $('#gallery > .text-muted').hide();
        } else {
          $('#gallery > .text-muted').show();
        }
        
        const descLen = ($('#descricao').val() || '').trim().length;
  // require at least 5 photos, and description is optional; if present must be at most 1000 chars
        if (count >= 5 && descLen <= 1000) {
          $final.prop('disabled', false);
        } else {
          $final.prop('disabled', true);
        }
      }
      // pendingFiles holds File objects selected/dropped but not yet uploaded
      let pendingFiles = [];

      function addLocalPreview(file) {
        const uid = 'p' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
        pendingFiles.push({ id: uid, file: file });
        const reader = new FileReader();
        reader.onload = function(ev) {
          const $wrap = $('<div/>', { 'class': 'thumb-wrap', 'data-pending-id': uid });
          $wrap.append($('<img/>', { 'class': 'thumb', src: ev.target.result }));
          const $actions = $('<div/>', { 'class': 'thumb-actions' });
          const $btn = $('<button/>', { 'class': 'btn btn-sm btn-danger btn-delete-photo-pending', 'data-pending-id': uid, 'html': '<i class="bi bi-trash"></i>' });
          $actions.append($btn);
          $wrap.append($actions);
          $('#gallery').append($wrap);
          updateFinalButton();
        }
        reader.readAsDataURL(file);
      }

      // dropzone events
      const $drop = $('#dropzone');
      $drop.on('click', function(e) {
        e.preventDefault();
        // create a temporary input to open file dialog
        const $input = $('<input type="file" accept="image/*" multiple style="display:none">');
        $input.on('change', function() {
          const files = this.files;
          handleSelectedFiles(files);
          $input.remove();
        });
        $('body').append($input);
        $input.trigger('click');
      });

  $drop.on('dragenter dragover', function(e) { e.preventDefault(); e.stopPropagation(); $drop.addClass('border-primary-subtle bg-primary-subtle'); });
  $drop.on('dragleave dragend drop', function(e) { e.preventDefault(); e.stopPropagation(); $drop.removeClass('border-primary-subtle bg-primary-subtle'); });
      $drop.on('drop', function(e) {
        e.preventDefault();
        const dt = e.originalEvent.dataTransfer;
        if (!dt) return;
        const files = dt.files;
        handleSelectedFiles(files);
      });

      function handleSelectedFiles(files) {
        if (!files || files.length === 0) return;
        // validate each and add preview
        for (let i = 0; i < files.length; i++) {
          const f = files[i];
          if (!f.type.startsWith('image/')) continue;
          if (f.size > 5 * 1024 * 1024) { showAlert('danger', 'Arquivo maior que 5MB: ' + f.name); continue; }
          addLocalPreview(f);
        }
      }

      // Note: upload is performed together with finalization. There is no separate "Enviar imagens" button.

      $(document).on('click', '.btn-delete-photo', function() {
        const $btn = $(this);
        const id = $btn.data('id');
        const filename = $btn.data('filename');
        // No confirm and no toast/alert: silently request deletion and remove thumbnail on success
        if (id) {
          $.post('controladores/veiculos/deletar-foto-carro.php', { id: id }, function(res) {
            if (res && res.success) {
              $btn.closest('.thumb-wrap').remove();
              updateFinalButton();
            } else {
              // keep silent in UI; log error for debugging
              console.error((res && res.message) ? res.message : 'Não foi possível remover a imagem.');
            }
          }, 'json');
        } else if (filename) {
          $.post('controladores/veiculos/deletar-foto-carro.php', { filename: filename }, function(res) {
            if (res && res.success) {
              $btn.closest('.thumb-wrap').remove();
              updateFinalButton();
            } else {
              console.error((res && res.message) ? res.message : 'Não foi possível remover a imagem.');
            }
          }, 'json');
        }
      });

      // remove pending local preview before upload
      $(document).on('click', '.btn-delete-photo-pending', function() {
        const pid = $(this).data('pending-id');
        if (typeof pid === 'undefined') { $(this).closest('.thumb-wrap').remove(); updateFinalButton(); return; }
        // remove from pendingFiles by id
        const idx = pendingFiles.findIndex(function(it) { return it && it.id === pid; });
        if (idx !== -1) pendingFiles.splice(idx, 1);
        $(this).closest('.thumb-wrap').remove();
        updateFinalButton();
      });

      // Description input: update counter and final button enabled state
      $('#descricao').on('input', function() {
        const len = ($(this).val() || '').length;
        $('#desc-count').text(len);
        updateFinalButton();
      });

      // finalize anuncio: show modal first, then send pending files together with descricao when confirmed
      $('#finalizar-btn').on('click', function(e) {
        e.preventDefault();
        const count = $('#gallery .thumb-wrap').length;
        if (count < 5) { showAlert('danger', 'É necessário pelo menos 5 fotos para finalizar.'); return; }
        const desc = ($('#descricao').val() || '').trim();
        // description is optional; if provided, enforce length constraints
  if (desc.length > 1000) { showAlert('danger', 'A descrição deve ter no máximo 1000 caracteres.'); return; }
        // show confirmation modal
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();
      });

      // when user confirms in modal, perform upload
      $('#confirm-finalize').on('click', function() {
        const $btn = $(this);
        $btn.prop('disabled', true).text('Enviando...');
        const fd = new FormData();
        pendingFiles.forEach(function(f) { if (f && f.file) fd.append('fotos[]', f.file); });
        fd.append('descricao', ($('#descricao').val() || '').trim());

        $.ajax({
          url: 'controladores/veiculos/finalizar-anuncio.php',
          type: 'POST',
          data: fd,
          processData: false,
          contentType: false,
          dataType: 'json',
          success: function(res) {
            console.log('finalizar-anuncio response:', res);
            if (res && res.success) {
              showAlert('success', res.message || 'Anúncio publicado.');
              if (res.redirect) setTimeout(function() { window.location.href = res.redirect; }, 900);
            } else {
              // log server message for debugging
              console.error('finalizar-anuncio failed:', res);
              showAlert('danger', (res && res.message) ? res.message : 'Erro ao finalizar anúncio.');
            }
          },
          error: function(xhr, status, err) {
            // log full response to console for debugging
            console.error('finalizar-anuncio AJAX error:', status, err, xhr.responseText);
            showAlert('danger', 'Erro ao finalizar anúncio. Veja console para detalhes.');
          }
        }).always(function() {
          $btn.prop('disabled', false).text('Sim, publicar');
          const modalEl = document.getElementById('confirmModal');
          const modal = bootstrap.Modal.getInstance(modalEl);
          if (modal) modal.hide();
        });
      });

      // initialize state
      updateFinalButton();
    });
  </script>
</body>

</html>
