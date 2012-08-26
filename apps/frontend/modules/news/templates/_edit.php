<form method="post" action="#" class="form-horizontal">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/pencil.png" width="24" />
      <?=__('Edit a news')?>
    </legend>
    <?=$form?>
    <div class="form-actions">
      <input type="submit" value="<?=__('Send')?>" class="btn btn-primary" />
      <a href="?delete" class="btn btn-danger">
        <i class="icon-white icon-trash"></i>
        <?=__("Delete news")?>
      </a>
    </div>
  </fieldset>
</form>