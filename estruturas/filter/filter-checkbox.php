<div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id=<?= $id;?><?php if ((isset($categoria) and $categoria === $id) or (isset($checked))) {echo ' checked';}?>>
  <label class="form-check-label" for="<?= $id;?>">
    <?= $text;?>
  </label>
  <small class="float-end">
    (34)
  </small>
</div>