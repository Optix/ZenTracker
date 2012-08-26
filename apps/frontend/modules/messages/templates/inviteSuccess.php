<div id="ajax">
  <h2>
    <img src="/images/icones/32x32/group.png" width="24" />
    <?=__("Members")?>
  </h2>
  <div class="participants">
  <?php foreach ($part as $p): ?>
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
  </div>
  
  <h2>
    <img src="/images/icones/32x32/email_to_friend.png" width="24" />
    <?=__("Add a member")?>
  </h2>
  
  <form method="post" action="<?=url_for("messages/invite?id=".$id)?>">
    <table>
      <tr>
        <td>
          <label for="dest"><?=__("Recipient")?> : </label>
        </td>
        <td>
          <input type="text" name="dest" id="dest" />
          <div id="destres" class="participants"></div>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align:center">
          <input type="submit" value="<?=__("Send")?>" />
        </td>
      </tr>
    </table>
</div>