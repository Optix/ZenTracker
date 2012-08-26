<form method="post" action="<?=url_for('forums/new')?>" class="form-horizontal" enctype="multipart/form-data">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/add.png" width="24" alt="" />
      <?=__("Add a new topic")?>
    </legend>
    <?=$form?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" />
    </div>
  </fieldset>
</form>