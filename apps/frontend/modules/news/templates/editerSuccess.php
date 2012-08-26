<div id="droite">
  <h2>
    <img src="/images/icones/32x32/form.png" width="24" />
    <?=__('Form')?>
  </h2>

  <form method="post" action="<?=url_for("news/editer?id=".$id)?>">
    <div class="control-group">
      <?=$form['titre']->renderError()?>
      <?=$form['titre']->render(array("placeholder" => __("Title")))?>
    </div>
    <div class="control-group">
      <?php include_partial("global/editeur", array("f" => $form['contenu'])); ?>
    </div>
    <div class="form-actions">
      <input type="submit" value="<?=__('Send')?>" class="btn btn-primary" />
      <input type="submit" value="<?=__('Cancel')?>" class="btn" />
    </div>
    <?=$form->renderHiddenFields(); ?>
  </form>
</div>