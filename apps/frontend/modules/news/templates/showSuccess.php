<div id="ajax" class="well">
  <?php
    include_partial("news", array("n" => $news));
  ?>
  
  <?php if ($sf_user->hasCredential("adm")||$sf_user->hasCredential("mod")): ?>
  <h2>
    <img src="/images/icones/32x32/newspaper_go.png" width="24" />
    <?=__("Actions")?>
  </h2>
  <p style="text-align:center; padding: 5px;" class="btn_actions">
    <a href="<?=url_for('news/editer?id='.$news->getId())?>" style="font-weight:bold;display:block;float:left;margin-right:10px;">
      <img src="/images/icones/32x32/pencil.png" alt="<?=__("Edit")?>" />
    </a>
    
    <a href="<?=url_for('news/supprimer?id='.$news->getId())?>" style="font-weight:bold;display:block;float:left">
      <img src="/images/icones/32x32/newspaper_delete.png" alt="<?=__("Delete")?>" />
    </a>
  </p>
  <?php endif; ?>
</div>