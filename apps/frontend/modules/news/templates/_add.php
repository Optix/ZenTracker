<form method="post" action="<?=url_for('news/add')?>/" class="form-horizontal">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/newspaper_add.png" width="24" />
      <?=__('Add a news')?>
    </legend>
    <?=$form?>
    <div class="form-actions">
      <input type="submit" value="<?=__('Send')?>" class="btn btn-primary" />
      <input type="submit" value="<?=__('Cancel')?>" class="btn" />
    </div>
  </fieldset>
</form>