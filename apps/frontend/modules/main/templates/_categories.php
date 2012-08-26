  <li class="divider-vertical"></li>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <img src="/images/icones/16x16/folders.png" width="16" alt="" />
      <?=__("Categories")?>
      <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
    <?php foreach($cat as $c): ?>
      <li>
        <a href="<?=url_for("@uploadcats?c=".$c->getSlug())?>">
          <span class="label label-info pull-right" style="margin-top: 1px"><?=format_number($c->getNbPosts())?></span>
          <img src="/images/icones/16x16/<?=$c->getPicture()?>" class="img" width="16" height="16" alt="" />
          <?=$c?> 
        </a>
      </li>
    <?php endforeach; ?>
      <li class="divider"></li>
      <li>
        <a href="<?=url_for("partage/index")?>">
          <img src="/images/icones/16x16/database.png" class="img" width="16" height="16" alt="" />
            <?=__('All uploads')?>
        </a>
      </li>
    </ul>
  </li>
  <li class="divider-vertical"></li> 