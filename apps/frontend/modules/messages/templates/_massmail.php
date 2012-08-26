<form method="post" action="<?=url_for('messages/massmail')?>" class="form-horizontal" enctype="multipart/form-data">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/email_add.png" width="24" alt="" />
      <?=__("Send a mass mail")?>
    </legend>
    <?=$form?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" />
    </div>
  </fieldset>
</form>