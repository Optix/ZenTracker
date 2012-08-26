<div id="ajax">
<h2>
  <img src="/images/icones/32x32/comments.png" width="24" />
  <?=$mp?>
</h2>

<table class="frmmsg">
<?php foreach ($mp->MpMsg as $m): ?>
  <?php include_partial('global/messages', array(
          'id' => $m->getMpmsgId(),
          'm' => $m,
          'msg' => $m->getMpTxt(),
          'tps' => $m->getMpDate(),
          'mbr' => $m->Membre,
          'verr' => false,
          'edit' => false,
          'suppr' => false
        )); ?>
<?php endforeach; 
  if ($sf_user->isAuthenticated()): ?>
  <tr>
    <td style="text-align: center;font-weight:bold">
      <?=$sf_user->getAttribute("username")?>
    </td>
    <td>
      <?=__("Now")?>
    </td>
    </tr>
    <tr>
      <td style="text-align:center">
        <img src="<?=$sf_user->getAttribute('ses')->getAvatar()?>" class="avatar" />
      </td>
      <td style="text-align:center">
        <form method="post" action="<?=url_for("messages/ajouter?id=".$mp->getMpId())?>" style="border:none">
          <?=$mpform['mp_txt']->render(array("style" => "width:99%;height:75px"))?>
          <input type="submit" value="<?=__("Send")?>" />
          <?=$mpform->renderHiddenFields()?>
        </form>
      </td>
    <tr>
<?php endif; ?>
</table>

<h2>
  <img src="/images/icones/32x32/group.png" width="24" />
  <?=__("Members")?>
</h2>

<div class="participants">
  <?php foreach ($mp->MpParticipants as $p): ?>
    <a href="#<?=url_for('@profil?id='.$p->Membre->getMbrId().'&username='.$p->Membre)?>">
      <?php if (!$p->getReaded()): ?>
        <img src="/images/icones/16x16/email.png" title="Pas encore lu" />
        <span style="font-weight:bold">
      <?php elseif ($p->getDeleted()): ?>
        <img src="/images/icones/16x16/email_delete.png" title="Supprimé" />
        <span style="text-decoration: line-through">
      <?php else: ?>
        <img src="/images/icones/16x16/email_open.png" />
        <span>
      <?php endif; ?>
        <img src="<?=$p->Membre->getAvatar(16)?>" width="16" height="16" />
        <?=$p->Membre?>
      </span>
    </a>
  <?php endforeach; ?>
  <a href="#<?=url_for("messages/invite?id=".$m->getMpId())?>">
    <img src="/images/icones/16x16/email_to_friend.png" />
    <?=__("Add a member")?>
  </a>
  <a href="<?=url_for("messages/retirer?id=".$mp->getMpId())?>">
    <img src="/images/icones/16x16/email_delete.png" title="Supprimer" />
    <?=__("Delete")?>
  </a>
</div>

<style>
.mpmsg {
  min-height: 55px;
  clear: left;
  border-top: 1px dotted #ccc;
  padding: 5px;
}
</style>

</div>