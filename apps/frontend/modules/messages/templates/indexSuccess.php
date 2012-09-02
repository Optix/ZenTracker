<div id="centre" class="span6">  
  <div id="leftPaginate" class="pagination pagination-centered">
    <ul>
      <li class="prev">
        <a href="?page=<?=$list->getPreviousPage()?>">Prev</a>
      </li>
      <?php foreach ($list->getLinks() as $page): ?>
        <?php if ($list->getPage() == $page): ?>
          <li class="active"><a><?=$page?></a></li>
        <?php else: ?>
          <li><a href="?page=<?=$page?>"><?=$page?></a></li>
        <?php endif; ?>
      <?php endforeach; ?>
      <li class="next">
        <a href="?page=<?=$list->getNextPage()?>">Next</a>
      </li>
    </ul>
  </div>
  <?php foreach ($list->getResults() as $l):?>
  <a class="entree btn topic" href="<?=url_for("@pm?slug=".$l['slug'])?>" 
  	style="height: 44px;<?php if ($l->PmParticipants[0]->getReaded()):?>opacity: 0.5;<?php endif; ?>">    
    <div class="thumbnail">
      <img class="avatar" src="/images/icones/32x32/comments.png" />
    </div>

    <h3>
      <?=$l?>
    </h3>

    <p style="max-height: 19px; text-align: left;">
      <?=__('By')?> 
      <img src="<?=$l->PmMessages[0]->Users->getAvatar(16)?>" alt="" />
      <?=$l->PmMessages[0]->Users?>
      <span class="date" data-timestamp="<?=$l->PmMessages[0]->getUpdatedAt()?>"></span> :
      <?=strip_tags(htmlspecialchars_decode($l->PmMessages[0]->getContent()));?>
    </p>
  </a>
  <?php endforeach; ?>
</div>

<div id="droite" class="span6">
  <div class="well">
  	<ul class="nav nav-tabs">
  	  <li class="active">
  	    <a data-toggle="tab" href="#new" style="text-align: center; ">
  	      <img src="/images/icones/16x16/add.png"> 
  	      <span class="visible-desktop">
  	      	<?=__('New private message')?>
  	      </span>
  	    </a>
  	  </li>
  	  <?php if ($sf_user->hasCredential("adm")):?>
  	  <li>
  	  	<a data-toggle="tab" href="#massmail" style="text-align: center; ">
  	  	  <img src="/images/icones/16x16/email_to_friend.png">
  	  	  <span class="visible-desktop">
  	  	  	<?=__('Mass-mail')?>
  	  	  </span>
  	  	</a>
  	  </li>
  	  <?php endif; ?>
  	</ul>
  	<div class="tab-content">
  	  <div class="tab-pane active" id="new">
  	  	<?php include_component('messages', "newpm"); ?>
  	  </div>
  	  <?php if ($sf_user->hasCredential("adm")):?>
  	  <div class="tab-pane" id="massmail">
  	  	<?php include_partial("massmail", array('form' => new MailForm())); ?>
  	  </div>
  	  <?php endif; ?>
  	</div>
  </div>
</div>