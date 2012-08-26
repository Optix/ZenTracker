<form method="post" action="<?=url_for('membres/options')?>/avatars" class="form-horizontal" enctype="multipart/form-data">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/picture_edit.png" width="24" />
      <?=__("Change avatar")?>
    </legend>
    <p>
      <?=__("The community identifies easily a member with his avatar, a small picture representing you. %name% enables you to send an image stored on your computer very simply. The picture will be converted with the good size, automatically.", array("%name%" => sfConfig::get("app_name")))?>
    </p>
    <?=$form?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" />
    </div>
  </fieldset>
</form>