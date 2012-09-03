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
  <a class="entree btn topic<?php if ($l->getIsImportant()):?> btn-warning<?php endif; ?>"
    href="<?=url_for('@topic?c='.$l->FrmForums->FrmCats->getSlug().'&f='.$l->FrmForums->getSlug().'&slug='.$l['slug'])?>" 
    style="height: 44px;<?php if ($l->FrmTopicsUsr[0]->getLastmsgid() == $l->FrmMessages[0]->getId()):?>opacity: 0.5;<?php endif; ?>">    
    <div class="thumbnail">
      <?php if ($l->getIsLocked()):?>
        <img class="avatar" src="/images/icones/32x32/lock.png" />
      <?php else: ?>
        <img class="avatar" src="/images/icones/32x32/comments.png" />
      <?php endif; ?>
    </div>

    <h3>
      <?=$l?>
    </h3>

    <p style="max-height: 19px; text-align: left;">
      <?=__('By')?>
      <?php if ($l->FrmMessages[0]->getAuthor()): ?> 
        <img src="<?=$l->FrmMessages[0]->Users->getAvatar(16)?>" alt="" />
        <?=$l->FrmMessages[0]->Users?>
      <?php else: ?>
        <img src="/images/avatar_default.gif" style="width:16px;" />
        <?=__("Anonymous")?>
      <?php endif; ?>
      <span class="date" data-timestamp="<?=$l->FrmMessages[0]->getUpdatedAt()?>"></span> :
      <?=strip_tags(htmlspecialchars_decode($l->FrmMessages[0]->getContent()));?>
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
            <?=__('New topic')?>
          </span>
        </a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="new">
        <?php include_component('forums', "newtopic", array("forum" => $q)); ?>
      </div>
    </div>
  </div>
</div>