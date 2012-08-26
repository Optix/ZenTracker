<div id="ajax" class="well">

<h2>
  <img src="/images/icones/32x32/user_comment.png" width="24" />
  <?=__('The shout')?>
</h2>
<div>
  <img class="avatar" src="<?=$sht->Membre->getAvatar()?>" alt="" style="float:left; margin-right: 5px" />

  <div class="pseudo">
    <?php if(!$sht->getShtSystem()):?>
      <img src="/images/icones/16x16/user_comment.png" width="16" height="16" />
    <?php else:?>
      <img src="/images/icones/16x16/<?=$sht->getSys('img')?>.png" width="16" height="16" />
    <?php endif; ?>
    <a href="#<?=url_for('@profil?id='.$sht->Membre->getMbrId().'&username='.$sht->Membre) ?>">
      <?=$sht->Membre ?>
    </a>
    <span style="font-weight:normal"><?=@$sht->getSys('msg')?></span>
  </div>
    
  <em class="date" data-timestamp="<?=strtotime($sht->getShtDate())?>"></em>
    
    <p>
      <?php if(!$sht->getShtSystem()):?>
        <?=smileys_decode(bbcode_decode($sht->getShtTxt())) ?>
      <?php else: ?>
        <img src="/images/icones/16x16/<?=$sht->getSys('img')?>.png" width="16" height="16" style="padding: 2px; border:1px solid #999;background:white; margin:5px;margin-left:10px" />
        <a href="#<?=$sht->getSys('url')?>" style="font-weight:bold"><?=$sht->getSys('titre')?></a>
      <?php endif; ?>
    </p>
</div>

<h2>
  <img src="/images/icones/32x32/thumb_up.png" width="24" />
  <?=__('They liked')?>
</h2>
<?php if (count($sht->ShtLikes) > 0): ?>
<div class="participants">
  <?php foreach ($sht->ShtLikes as $l): ?>
    <?php include_partial('global/mbr', array('v' => $l->Membre)); ?>
  <?php endforeach; ?>
</div>
<?php else: ?>
<i style="text-align:center;display:block;padding: 3px;"><?=__('No one liked this shout. Patience.')?></i>
<?php endif; ?>

<h2>
  <img src="/images/icones/32x32/comments.png" width="24" />
  <?=__('Comments')?> (<?=count($com)?>)
</h2>
<div class="coms">
<table class="frmmsg">
  <?php if ($sf_user->isAuthenticated()): ?>
  <tr>
    <td style="text-align: center;font-weight:bold;width:100px">
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
      <form style="border:none" method="post" action="<?=$sf_request->getUri()?>">
        <?=$form['shtcom_txt']->render(
                array(
                    "id" => "newshtcom", 
                    "style" => "width:99%;height:50px;", 
                    "data-shtid" => $sht->getShtId()
                    )
                )
        ?>
        <?=$form->renderHiddenFields()?>
      </form>
    </td>
  </tr>
<?php endif; foreach ($com as $c): ?>
  <?php 
    include_partial('global/messages', array(
      'id' => $c->getIdshtComs(),
      'm' => $c,
      'mbr' => $c->Membre,
      'msg' => $c->getShtcomTxt(),
      'tps' => $c->getShtcomDate(),
      'verr' => 1,
      'edit' => false,
      'suppr'=> url_for('shoutbox/supprimercommentaire?id='.$c->getIdshtComs()),
    ));
  ?>
<?php endforeach; ?>
</table>

</div>