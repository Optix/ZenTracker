<?php foreach ($ups as $id => $up): ?>
  <a href="<?=url_for('@upload?c='.$up->Categories->getSlug().'&slug='.$up->getSlug())?>" class="thumbnail detectCover" rel="tooltip" title="<?=$up->getTitle()?>">
    <?=strip_tags(html_entity_decode($up->getDescription()), '<img>')?>
  </a>
<?php endforeach; ?>