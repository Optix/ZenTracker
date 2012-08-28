<div id="centre" class="span6">
  <?php foreach ($frm as $f): ?>
    <a class="entree btn btn-inverse">
      <h3>
        <?=$f['name']?>
      </h3>
    </a>
    <?php foreach ($f['FrmForums'] as $ff): ?>
      <a class="entree btn" href="<?=url_for('@forum?c='.$f['slug']."&slug=".$ff['slug'])?>" style="height: 44px;overflow: hidden">
        <div class="thumbnail">
          <img class="avatar" src="/images/icones/32x32/comments.png" alt="" />
        </div>
        <h3>
          <?=$ff['name']?>
        </h3>
        <p>
          <?=$ff['description']?>
        </p>
      </a>
    <?php endforeach; ?>
  <?php endforeach; ?>
</div>

<div id="droite" class="span6">
  <div class="well">
    <ul class="nav nav-tabs">
      <?php if($sf_user->hasCredential("adm")):?>
      <li>
        <a data-toggle="tab" href="#mng" style="text-align: center;">
          <img src="/images/icones/16x16/add.png">
          <span class="visible-desktop"><?=__("Manage")?></span>
        </a>
      </li>
      <?php endif; ?>
    </ul>
    <div class="tab-content">
      <?php if($sf_user->hasCredential("adm")):?>
      <div class="tab-pane" id="mng">
        <?php include_component('forums', 'manage', array()); ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>