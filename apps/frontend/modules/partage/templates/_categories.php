<form method="post" action="<?=url_for('partage/categories')?>/" class="form-horizontal">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/folders.png" width="24" alt="" />
      <?=__("Manage categories")?>
    </legend>
    <?=$f?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" />
    </div>
  </fieldset>
</form>


<div class="page-header" style="padding-bottom: 0px">
  <h3>
    <img src="/images/icones/32x32/folders.png" />
    <?=__("Select another category")?>
  </h3>
</div>
<ul style="list-style-type:none">
  <?php foreach ($tree as $c): ?>
    <li>
      <a href="<?=url_for("@uploadcats?c=".$c->getSlug())?>" class="btn" style="margin: 2px">
        <img src="/images/icones/16x16/<?=$c->getPicture()?>" class="img" width="16" height="16" alt="" />
        <?=$c->getIndentedName()?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>