<div id="centre" class="span6">
  <?PHP foreach ($news as $n): ?>
  <a class="entree btn" href="#<?=url_for('@news?slug='.$n['slug']) ?>">  
    <em class="date" data-timestamp="<?=strtotime($n['created_at']);?>"></em>
    
    <div class="pseudo">
      <h3>
        <img src="/images/icones/16x16/newspaper.png" />
        <?=$n['title']?>
      </h3>
    </div>
    
    <div class="thumbnail">
      <img class="avatar" src="<?=$n->Users->getAvatar(50)?>" alt="<?=$n['Users']['username']?>" />
    </div>
    
    <p><?=mb_substr(bbcode_strip($n['description']),0,220, 'UTF-8')?>...</p>
    <hr class="clrfx" />
  </a>
  <?PHP endforeach; ?>
</div>

<div id="droite" class="span6">
  <div class="well">
  <?php if(isset($form)): ?>
    <?php include_partial('news/add', array("form" => $form))?>
  <?php endif; ?>
  </div>
</div>