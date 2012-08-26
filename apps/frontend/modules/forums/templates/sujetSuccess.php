<div id="ajax">
  <table class="frmmsg">
    <?php foreach ($p as $m): ?>
      <?php $isDeleted = $m->getSuppression();
      if (empty($isDeleted))
        include_partial('global/messages', array(
          'id' => $m->getIdfrmMsg(),
          'm' => $m,
          'msg' => $m->getMessage(),
          'tps' => $m->getCreation(),
          'tps_edit' => $m->getEdition(),
          'mbr' => $m->Membre,
          'verr' => $s->getVerrouille(),
          'edit' => url_for('forums/editerreponse?msgid='.$m->getIdfrmMsg()),
          'suppr'=> url_for('forums/supprimerreponse?msgid='.$m->getIdfrmMsg()), 
        )); ?>
    <?php endforeach;    
    if ($sf_user->isAuthenticated()):
      if ($s->getVerrouille() == 0):
      ?>
      <tr>
        <td style="text-align: center;font-weight:bold">
          <?=$sf_user->getAttribute("username")?>
        </td>
        <td>
          <?=__('Now')?>
        </td>
        </tr>
        <tr>
          <td style="text-align:center">
            <img src="<?=$sf_user->getAttribute('ses')->getAvatar()?>" class="avatar" />
          </td>
          <td style="text-align:center">
            <form method="post" style="border:none" action="<?=url_for("forums/sujet?id=".$s->getIdfrm_sujet())?>">
              <?=$rep['message']->render(array("style" => "width:99%;height:75px", "id" => "frm_reply"))?>
              <input type="submit" value="<?=__('Send my reply')?>" />
              <?=$rep->renderHiddenFields()?>
            </form>
          </td>
        <tr>
        <tr>
          <td></td>
          <td></td>
        </tr>
      <?php else: ?>
        <tr>
          <td colspan="2" style="text-align:center;font-size: 14pt;color:#999">
            <img src="/images/icones/32x32/lock.png" width="24" /> <?=__('Locked topic')?>
          </td>
        </tr>
      <?php endif; ?>
        <tr>
          <td colspan="2" style="text-align:center;font-weight:bold">
             <a href="#<?=url_for('forums/suivre?sjtid='.$s->getIdfrmSujet())?>">
              <img src="/images/icones/16x16/bell.png" /> <?=__('Follow')?>
            </a>
            <?php if ($sf_user->hasCredential('adm')||$sf_user->hasCredential('mod')):?>
            | 
            <a href="<?=url_for('forums/important?sjtid='.$s->getIdfrmSujet())?>">
              <?php if ($s->getPostit() == 0): ?>
                <img src="/images/icones/16x16/error_add.png" /> <?=__('Important')?>
              <?php else: ?>
                <img src="/images/icones/16x16/error_delete.png" /> <?=__('Normal')?>
              <?php endif;?>
            </a> | 
            <a href="<?=url_for('forums/verrouiller?sjtid='.$s->getIdfrmSujet())?>">
              <?php if ($s->getVerrouille() == 0): ?>
                <img src="/images/icones/16x16/lock.png" /> <?=__('Lock')?>
              <?php else: ?>
                <img src="/images/icones/16x16/lock_open.png" /> <?=__('Unlock')?>
              <?php endif;?>
            </a> | 
            <a href="<?=url_for('forums/supprimer?sjtid='.$s->getIdfrmSujet())?>">
              <img src="/images/icones/16x16/delete.png" /> <?=__('Delete')?>
            </a>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="text-align:center;font-weight:bold">
            <form method="post" action="<?=url_for("forums/renommer?id=".$s->getIdfrmSujet())?>">
                <img src="/images/icones/16x16/pencil.png" alt="" />
                <?=__('Rename this topic')?> : <input type="text" name="titre_topic" value="<?=$s->getTitre()?>" /> <input type="submit" value="<?=__('Send')?>" />
            </form>
          </td>
        </tr>
      <?php endif; ?>
    <?php endif; ?>
  </table>
</div>