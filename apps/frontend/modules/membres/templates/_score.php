<div class="btn" style="width: 96%">
<?php $max = $mbr->getLevels(); $max = $max[$mbr->getLevel()]; ?>
<?php $s = $mbr->getScore(true);?>
  <div class="progress">
    <div style="width: <?=round($mbr->getScore()/$max*100, 1)?>%;" class="bar">
      <?=round($mbr->getScore()/$max*100, 1)?>%
    </div>
  </div>
  <p style="padding:0px;margin:0;text-align: center">
    <strong><?=__("Level")?> <?=$mbr->getLevel()?></strong> : 
    <?=format_number($mbr->getScore())?> / <?=format_number($max)?>
  </p>
</div>

<table class="table">
  <thead>
  	<tr>
  	  <th>Ratio: <?php include_partial('global/ratio', array('membre' => $mbr)); ?></th>
  	  <th style="text-align: center">Nb</th>
  	  <th style="text-align: center">Multi</th>
  	  <th style="text-align: center">Pts</th>
  	</tr>
  </thead>
  <tbody>
    <tr>
      <td>
      	<img src="/images/icones/16x16/money.png" alt="<?=__("Donations")?>" /> <?=__("Donations")?> 
      </td>
      <td style="text-align: center;">
      	<?=format_currency($score['sql']['do'], 'EUR')?>
      </td>
      <td class="multiplicator" style="text-align: center;">
      	x<?=sfConfig::get('app_pts_donations', 100)?>
      </td>
      <td style="text-align: right">
      	<?=format_number($score['data']['donations'])?>
      </td>
    </tr>
    <tr>
      <td>
      	<img src="/images/icones/16x16/user_comment.png" alt="<?=__("Shoutbox")?>" /> <?=__("Shoutbox")?>
      </td>
      <td style="text-align: center;">
      	<?=format_number($score['sql']['sht'])?>
      </td>
      <td class="multiplicator" style="text-align: center;">
      	x<?=sfConfig::get('app_pts_shoutbox', 1)?>
      </td>
      <td style="text-align: right">
      	<?=format_number($score['data']['sht'])?>
      </td>
    </tr>
	<tr>
      <td>
      	<img src="/images/icones/16x16/comments.png" alt="<?=__("Messages")?>" /> <?=__("Messages")?>
      </td>
      <td style="text-align: center;">
      	<?=format_number($score['sql']['msg'])?>
      </td>
      <td class="multiplicator" style="text-align: center;">
      	x<?=sfConfig::get('app_pts_msg', 2)?>
      </td>
      <td style="text-align: right">
      	<?=format_number($score['data']['frm'])?>
      </td>
    </tr>
	<tr>
      <td>
      	<img src="/images/icones/16x16/add.png" alt="<?=__("Uploads")?>" /> <?=__("Uploads")?>
      </td>
      <td style="text-align: center;">
      	<?=format_number($score['sql']['tor'])?>
      </td>
      <td class="multiplicator" style="text-align: center;">
      	x<?=sfConfig::get('app_pts_uppost', 50)?>
      </td>
      <td style="text-align: right">
      	<?=format_number($score['data']['post'])?>
      </td>
    </tr>
    <tr>
      <td>
      	<img src="/images/icones/16x16/picture_add.png" alt="<?=__("Avatar")?>" /> <?=__("Avatar")?>
      </td>
      <td style="text-align: center;">
      	<?=($mbr->getAvatar("raw")) ? __('Yes') : __("No")?>
      </td>
      <td class="multiplicator" style="text-align: center;">
      	x<?=sfConfig::get('app_pts_avatar', 50)?>
      </td>
      <td style="text-align: right">
      	<?=format_number($score['data']['avt'])?>
      </td>
    </tr>
    <tr>
      <td>
      	<img src="/images/icones/16x16/date.png" alt="<?=__("Seniority")?>" /> <?=__("Seniority")?>
      </td>
      <td style="text-align: center;">
      	<?=format_number($score['data']['age'])?> <?=__('days')?>
      </td>
      <td class="multiplicator" style="text-align: center;">
      	x<?=sfConfig::get('app_pts_age', 1)?>
      </td>
      <td style="text-align: right">
      	<?=format_number($score['data']['age'])?>
      </td>
    </tr>
    <?php $r = $mbr->getRatio("up");?>
    <tr>
      <td>
      	<img src="/images/icones/16x16/arrow_down.png" alt="<?=__("Downloaded")?>" /> <?=__("Downloaded")?>
      </td>
      <td style="text-align: center;" class="size">
      	<?=$mbr->getRatio("down")?>
      </td>
      <td class="multiplicator" style="text-align: center;">
      	x<?=sfConfig::get('app_pts_down', 1)?>
      </td>
      <td style="text-align: right">
      	<?=format_number($score['data']['down'])?>
      </td>
    </tr>
    <tr>
      <td>
      	<img src="/images/icones/16x16/arrow_up.png" alt="<?=__("Uploaded")?>" /> <?=__("Uploaded")?>
      </td>
      <td style="text-align: center;" class="size">
      	<?=$mbr->getRatio("up")?>
      </td>
      <td class="multiplicator" style="text-align: center;">
      	x<?=sfConfig::get('app_pts_up', 5)?>
      </td>
      <td style="text-align: right">
      	<?=format_number($score['data']['up'])?>
      </td>
    </tr>
  </tbody>
</table>

<style type="text/css">
.multiplicator {
  font-size: 120%;
  font-weight: bold;
}
</style>