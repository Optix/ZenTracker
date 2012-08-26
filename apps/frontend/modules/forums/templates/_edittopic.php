<form method="post" action="<?=url_for('forums/edittopic')?>" class="form-horizontal">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/add.png" width="24" alt="" />
      <?=__("Edit this topic")?>
    </legend>
    <?=$form?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" />
      <a class="btn btn-danger" href="<?=url_for('forums/delete?id='.$obj->getId().'&token='.$sf_user->getAttribute('token'))?>">
        <i class="icon-white icon-trash"></i>
        <?=__("Delete topic")?>
      </a>
    </div>
  </fieldset>
</form>