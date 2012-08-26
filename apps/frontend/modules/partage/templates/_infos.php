<?php if ($u->getMinlevel() == 0):?>
<div class="alert alert-info">
  <i class="icon-download"></i>
  <strong><?=__("Freeleech download")?></strong> : 
  <?=__("Download won't be taken into account. Only upload will be.")?>
</div>
<?php endif; ?>

<?php if ($sf_user->getAttribute("ses")->getLevel() < $u->getMinlevel()):?>
<div class="alert alert-error">
  <i class="icon-minus-sign"></i>
  <strong><?=__("Restricted download")?></strong> : 
  <?=__("Uploader allows only users who have at least the level")?> <?=$u->getMinlevel()?>
</div>
<?php elseif (!$files): ?>

<div class="alert alert-error">
  <i class="icon-minus-sign"></i>
  <strong><?=__("Download not available")?></strong> : 
  <?=__("Sorry, but I can't find a torrent file or a URL link for this upload.")?>
</div>
<?php else:  ?>
<div style="text-align: center">
  <a href="<?=url_for('@download?slug='.$u->getSlug())?>" class="btn btn-primary btn-large">
    <i class="icon-white icon-download-alt"></i>
    <?=__('Download')?>
  </a>
</div>
<?php endif; ?>

<div class="page-header" style="padding-bottom: 0px">
  <h2>
    <img src="/images/icones/32x32/information.png" />
    <?=__("General information")?>
  </h2>
</div>
<table class="table">
  <tr>
    <td class="span2">
      <img src="/images/icones/16x16/date.png" /> 
      <?=__("Date")?>
    </td>
    <td class="date" data-timestamp="<?=strtotime($u->getCreatedAt())?>"></td>
  </tr>
  <tr>
    <td>
      <img src="/images/icones/16x16/folders.png" /> 
      <?=__("Category")?>
    </td>
    <td>
      <img src="/images/icones/16x16/<?=$u->Categories->getPicture()?>" />
      <?=$u->Categories->getName()?>
    </td>
  </tr>
  <tr>
    <td>
      <img src="/images/icones/16x16/compress.png" /> 
      <?=__("Size")?>
    </td>
    <td class="size" data-size="<?=$u->getSize()?>">
      <?=$u->getSize()?>
    </td>
  </tr>
</table>

<?php if ($u->getAuthor()):?>
<div class="page-header" style="padding-bottom: 0px;">
  <h2>
  	<img src="<?=$u->Users->getAvatar(32)?>" />
  	<?=$u->Users?>
    <small><?=__("Discover other uploads from him")?></small>
  </h2>
</div>
<div>
  <?php foreach ($ups as $id => $up): if ($id >= 10) break; ?>
  	<a href="<?=url_for('@upload?c='.$up->Categories->getSlug().'&slug='.$up->getSlug())?>" class="thumbnail detectCover" rel="tooltip" title="<?=$up->getTitle()?>">
  	  <?=strip_tags(html_entity_decode($up->getDescription()), '<img>')?>
  	</a>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="page-header" style="padding-bottom: 0px;clear: left">
  <h2>
    <img src="/images/icones/32x32/flag_finish.png" />
    <?=__("Completed")?>
    <small><?=__("Who has downloaded ?")?></small>
  </h2>
</div>
<div>
  <?php foreach ($u->getCompleted() as $id => $m): ?>
    <?php if (!isset($m->Users)) continue; ?>
    <a href="<?=url_for('@profile?slug='.$m->Users->getSlug())?>" class="thumbnail detectCover" rel="tooltip" title="<?=$m->Users?>">
      <img src="<?=$m->Users->getAvatar()?>" />
    </a>
  <?php endforeach; ?>
  <?php if (count($u->getCompleted()) === 0): ?>
    <p style="text-align: center">
      <i class="icon-user"></i>
      <?=__("No one has finished downloading yet.")?>
    </p>
  <?php endif; ?>
</div>