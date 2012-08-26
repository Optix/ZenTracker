<h2>
  <img src="/images/icones/32x32/newspaper.png" width="24" />
  <?=$n->getTitle()?>
</h2>
<div>
    <!--<?php include_partial('global/mbr', array('v' => $n->Users)); ?>-->
    <span class="date" style="text-transform: lowercase;" data-timestamp="<?=strtotime($n->getUpdatedAt())?>"></span>
  <br /><br /><p>
    <?=html_entity_decode($n->getDescription())?>
  </p>
</div>