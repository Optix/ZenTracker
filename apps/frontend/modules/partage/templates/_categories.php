<form method="post" action="<?=url_for('partage/categories')?>/" class="form-horizontal">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/folders.png" width="24" alt="" />
      <?=__("Manage categories")?>
    </legend>
    <?=$f?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" />
    </div>
  </fieldset>
</form>