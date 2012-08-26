<div class="btn-group">
  <a class="btn" href="#<?=url_for('@profile?slug='.$v->getSlug())?>">
    <img src="<?=$v->getAvatar(16)?>" width="14" />
    <?=$v?>
  </a>
  <a class="btn dropdown-toggle" data-toggle="dropdown">
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu">
    <li><a href="">Profil</a></li>
  </ul>
</div>