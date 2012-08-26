<div id="centre" class="span6">
  <?PHP foreach ($mbrlist as $m): ?>
  <a class="entree btn" href="<?=url_for('@profile?slug='.$m->getSlug()) ?>" style="min-height: 32px;">
    <div class="thumbnail" style="height: 28px; width: 28px; position: relative; top: -3px; ">
      <img class="avatar" style="height: 28px; width: 28px;" src="<?=$m->getAvatar(32)?>">
    </div>
    <span class="badge pull-right" style="margin-top: 5px; "><?=$m->getScore()?></span>
    <h3><?=$m?></h3>
  </a>
  <?PHP endforeach; ?>
</div>

<div id="droite" class="span6">
  <div class="well" style="height: 880px; ">
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
      <a data-toggle="tab" href="#topuploader" style="text-align: center; ">
        <img src="/images/icones/16x16/medal_gold_1.png"> 
        <span class="visible-desktop">
          <?=__("Best Uploaders")?>
        </span>
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#topposter" style="text-align: center; ">
        <img src="/images/icones/16x16/medal_gold_3.png"> 
        <span class="visible-desktop">
          <?=__("Best Posters")?>
        </span>
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#topdonors" style="text-align: center; ">
        <img src="/images/icones/16x16/moneybox.png"> 
        <span class="visible-desktop">
          <?=__("Best Donors")?>
        </span>
      </a>
    </li>
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
    <div class="tab-pane" id="topuploader">
      <?php include_component('partage', 'topUploaders') ?>
    </div>
    <div class="tab-pane" id="topposter">
      <?php include_component('messages', 'topPosters') ?>
    </div>
  </div>
</div>
