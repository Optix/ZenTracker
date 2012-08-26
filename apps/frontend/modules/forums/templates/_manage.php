<form method="post" action="<?=url_for('forums/add')?>" class="form-horizontal" enctype="multipart/form-data">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/add.png" width="24" alt="" />
      <?=__("Add a categorie")?>
    </legend>
    <?=$cat?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" />
    </div>
  </fieldset>
</form>
<form method="post" action="<?=url_for('forums/addfrm')?>" class="form-horizontal" enctype="multipart/form-data">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/add.png" width="24" alt="" />
      <?=__("Add a forum")?>
    </legend>
    <?=$frm?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" />
    </div>
  </fieldset>
</form>