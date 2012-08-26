<div id="ajax">

<h2>
  <img src="/images/icones/32x32/user.png" width="24" />
  <?=$mbr?>
</h2>

<table class="table2">
  <tr>
    <td rowspan="3" width="50">
      <img src="<?=$mbr->getAvatar()?>" />
    </td>
    <td style="width:100px">
      <img src="/images/icones/16x16/user_silhouette.png" />
      <?=__("Status")?>
    </td>
    <td>
      <?php if ($mbr->getRole() == "adm"): ?>
        <img src="/images/icones/16x16/user_suit.png" />
        <?=__("Administrator")?>
      <?php elseif ($mbr->getRole() == "mod"): ?>
        <img src="/images/icones/16x16/user_policeman.png" />
        <?=__("Moderator")?>
      <?php elseif ($mbr->getRole() == "mbr"): ?>
        <img src="/images/icones/16x16/user.png" />
        <?=__("Member")?>
      <?php elseif ($mbr->getRole() == "ban"): ?>
        <img src="/images/icones/16x16/user_zorro.png" />
        <?=__("Banned")?>
      <?php elseif ($mbr->getRole() == "val"): ?>
        <img src="/images/icones/16x16/user_gray.png" />
        <?=__("Pending validation")?>
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td>
      <img src="/images/icones/16x16/travel.png" />
      Inscription
    </td>
    <td>
      <?=ucfirst(getRelativeTime($mbr['created_at']))?>
    </td>
  </tr>
  <tr>
    <td>
      <img src="/images/icones/16x16/cup.png" />
      <?=__("Last visit")?>
    </td>
    <td>
      <?php if ($mbr->getLastvisit() == null): ?>
        <em><?=__("Never connected")?></em>
      <?php else: ?>
        <span class="date" data-timestamp="<?=strtotime($mbr['lastvisit'])?>"></span>
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td></td>
    <td>
      <img src="/images/icones/16x16/traffic_usage.png" />
      <?=__("Ratio")?>
    </td>
    <td>
      <?php include_partial('global/ratio', array('membre' => $mbr)); ?>
    </td>
  </tr>
</table>