<form method="post" action="<?=$submitUrl?>" class="form-horizontal">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/comment_add.png" width="24" />
      <?=__('Add comment')?>
    </legend>
    <?=$form?>
    <div class="form-actions">
      <input type="submit" value="<?=__('Send')?>" class="btn btn-primary" />
      <input type="submit" value="<?=__('Cancel')?>" class="btn" />
    </div>
  </fieldset>
</form>