<div id="forums" class="tab-pane">
<?php foreach ($msg as $m): ?>

  <a class="entree btn topic" 
    style="height: 44px;<?php if (isset($m['FrmTopicsUsr'][0]['lastmsgid']) && $m['FrmTopicsUsr'][0]['lastmsgid'] == $m['FrmMessages'][0]['id']):?>opacity: 0.4;<?php endif; ?>" 
    href="<?=url_for('@topic?c='.$m['FrmForums']['FrmCats']['slug'].'&f='.$m['FrmForums']['slug'].'&slug='.$m['slug'])?>" 
    >
    <div class="thumbnail">
    	<?php if ($m['is_locked']):?>
        <img class="avatar" src="/images/icones/32x32/lock.png">
    	<?php else: ?>
    	  <img class="avatar" src="/images/icones/32x32/comments.png">
    	<?php endif; ?>
    </div>
    <h3><?=$m['title']?></h3>
    <p style="max-height: 19px">
    	<?=__("By")?>
    	<img src="/uploads/avatars/16x16/<?=$m['FrmMessages'][0]['Users']['avatar']?>" style="width:16px">
    	<?=$m['FrmMessages'][0]['Users']['username']?>
    	<span class="date" data-timestamp="<?=strtotime($m['FrmMessages'][0]['updated_at'])?>"></span> : 
      <?=strip_tags(html_entity_decode($m['FrmMessages'][0]['content']))?>
    </p>
  </a>
<?php endforeach; ?>
</div>