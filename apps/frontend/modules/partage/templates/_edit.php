<form method="post" action="#" class="form-horizontal" enctype="multipart/form-data">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/pencil.png" width="24" alt="" />
      <?=__("Edit an upload")?>
    </legend>
    <?=$f?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" />
      <a class="btn btn-danger" href="<?=url_for('partage/delete?id='.$f->getObject()->getId().'&token='.$sf_user->getAttribute('token'))?>">
        <i class="icon-white icon-trash"></i>
        <?=__("Delete upload")?>
      </a>
    </div>
  </fieldset>
</form>