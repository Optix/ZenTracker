<form method="post" action="<?=url_for('messages/new')?>" class="form-horizontal" enctype="multipart/form-data">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/email_add.png" width="24" alt="" />
      <?=__("Write a new private message")?>
    </legend>
    <?=$form?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" />
    </div>
  </fieldset>
</form>