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
  <a class="entree btn upload" href="<?=url_for("@upload?slug=".$l['slug']."&c=".$l['Categories']['slug'])?>">    
    <h3>
      <?=$l?>
    </h3>
    
    <?php 
      $desc = htmlspecialchars_decode($l->getDescription());
      preg_match('#src="(.*)"#U', $desc, $i);
    ?>

    <div class="thumbnail">
      <img class="avatar" src="<?=$i[1]?>" style="width: 50px; height:50px" />
    </div>
      
    <p>
      <?=strip_tags($desc);?>
    </p>

    <p class="stats">
      <?php if (!empty($l['hash'])): ?>
        <span class="badge"><img src="/images/icones/16x16/utorrent.png" /></span>
      <?php else: ?>
        <span class="badge"><img src="/images/icones/16x16/server.png" /></span>
      <?php endif; ?>
      
      <span class="badge">
        <?php if ($l->getAuthor()): ?>
          <img src="<?=$l->Users->getAvatar(16)?>"> <?=$l->Users?></span>
        <?php else: ?>
          <img src="/images/avatar_default.png" style="width: 16px"> <?=__('Anonymous')?></span>
        <?php endif; ?>
      <span class="badge">
        <?php if ($l->Categories->getPicture()): ?>
          <img src="/images/icones/16x16/<?=$l->Categories->getPicture()?>"> 
        <?php endif; ?>
        <?=$l->Categories->getName()?></span>
      <span class="badge"><img src="/images/icones/16x16/compress.png"> <?=makesize($l->getSize())?></span>
      <span class="badge"><img src="/images/icones/16x16/comments.png"> <?=$l['cnt_coms']?></span>
      <span class="badge badge-success"><img src="/images/icones/16x16/status_online.png"> <?=$l['cnt_seeders']?></span>
      <span class="badge" style="background-color: #b94a48"><img src="/images/icones/16x16/comments.png"> <?=$l['cnt_leechers']?></span>
      <span class="badge badge-info"><img src="/images/icones/16x16/flag_finish.png"> <?=$l['cnt_completed']?></span>
    </p>
  </a>
  <?php endforeach; ?>
</div>

<div id="droite" class="span6">
  <div class="well">
    <div>
      <ul class="nav nav-tabs">
        <li class="active">
          <a data-toggle="tab" href="#filter" style="text-align: center; ">
            <img src="/images/icones/16x16/filter.png"> 
            <span class="visible-desktop">
              <?=__("Filters")?>
            </span>
          </a>
        </li>
        <li>
          <a data-toggle="tab" href="#new" style="text-align: center; ">
            <img src="/images/icones/16x16/add.png"> 
            <span class="visible-desktop">
              <?=__('Add an upload')?>
            </span>
          </a>
        </li>
        <?php if ($sf_user->hasCredential("adm")):?>
          <li>
            <a data-toggle="tab" href="#cats" style="text-align: center; ">
              <img src="/images/icones/16x16/folders.png"> 
              <span class="visible-desktop">
                <?=__('Categories')?>
              </span>
            </a>
          </li>
        <?php endif; ?>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="filter">
          <form method="post" action="#" id="filter" class="form-horizontal">
            <fieldset>
              <legend>
                <img src="/images/icones/32x32/filter.png" width="24" alt="">
                <?=__("Filters")?>
              </legend>
              <?=$filter?>
              <div class="form-actions">
                <input type="submit" class="btn btn-primary">
              </div>
            </fieldset>
          </form>
        </div>
        <div class="tab-pane" id="new">
          <?php include_component("partage", "add", array("category" => $cat))?>
        </div>
        <?php if ($sf_user->hasCredential("adm")):?>
          <div class="tab-pane" id="cats">
            <?php include_component("partage", "categories", array("category" => $cat))?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>