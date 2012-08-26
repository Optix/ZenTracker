<form method="post" action="<?=url_for('membres/edit?uid='.$f->getObject()->getId())?>" class="form-horizontal">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/user_edit.png" width="24" alt="" />
      <?=__("Edit user's profile")?>
    </legend>
    <?=$f?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" />
    </div>
  </fieldset>
</form>