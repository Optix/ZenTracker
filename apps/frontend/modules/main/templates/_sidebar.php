  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <img src="/images/icones/16x16/bricks.png" width="16" alt="" />
      <?=__("Modules")?>
      <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
      <?php if (sfConfig::get('app_dons') !== false && $sf_user->isAuthenticated()): ?>
      <li>
        <a href="<?=url_for("membres/dons")?>">
          <?php if($donations > sfConfig::get('app_dons_objectif')): ?>
            <span class="label label-success pull-right" style="margin-top: 1px" title="<?=__('Objective reached !')?>">
          <?php else: ?>
            <span class="label label-important pull-right" style="margin-top: 1px" title="<?=__("We've to reach %c%â‚¬", array("%c%" => sfConfig::get('app_dons_objectif')))?>">
          <?php endif; ?>
            <?=format_currency($donations, 'EUR')?>
          </span> 
          <img src="/images/icones/16x16/money_add.png" class="img" width="16" height="16" alt="" />
          <?=__('Donations')?>
        </a>
      </li>
     <?php endif; ?>
      <li>
        <a href="<?=url_for("forums/index")?>">
          <span title="<?=__('Number of topics')?>" class="label label-info pull-right" style="margin-top: 1px">
            <?=format_number($stat['forum'])?>
          </span>
          <img src="/images/icones/16x16/comments.png" class="img" width="16" height="16" alt="" />
          <?=__('Forums')?>
        </a>
      </li>
      <li>
        <a href="<?=url_for("membres/index")?>">
          <span title="<?=__('Number of members')?>" class="label label-info pull-right" style="margin-top: 1px">
            <?=format_number($stat['users'])?>
          </span>
          <img src="/images/icones/16x16/group.png" class="img" width="16" height="16" alt="" />
          <?=__('Members')?>
        </a>
      </li>
      <li>
        <a href="<?=url_for("news/index")?>">
          <span title="<?=__('Number of news')?>" class="label label-info pull-right" style="margin-top: 1px">
            <?=format_number($stat['news'])?>
          </span>
          <img src="/images/icones/16x16/newspaper.png" class="img" width="16" height="16" alt="" />
          <?=__('News')?>
        </a>
      </li>
      <!--<li>
        <a href="<?=url_for("sondages/index")?>">
          <span title="<?=__('Number of polls')?>" class="label label-info pull-right" style="margin-top: 1px">
            <?=format_number($stat['polls'])?>
          </span>
          <img src="/images/icones/16x16/chart_bar.png" class="img" width="16" height="16" alt="" />
          <?=__('Polls')?>
        </a>
      </li>-->
    </ul>
  </li>
  <li id="mobile-switch" style="width: 32px;display: none;" data-close="0">
    <span>
      <img src="/images/icones/32x32/hand_point.png" alt="" width="24" />
    </span>
  </li>
  <li class="divider-vertical"></li>
</ul>
<form method="post" action="<?=url_for("partage/index")?>" name="formrecherche" 
  class="navbar-search pull-left visible-desktop">
  <?=$search['title']->render(array("placeholder" => __("Search"), "class" => "search-query"));?>
  <?=$search->renderHiddenFields();?>
</form>

<?php if ($sf_user->isAuthenticated()):?>
<ul class="nav pull-right">
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="<?=$ses->getAvatar(16)?>" width="16" />
        <?=$ses?>
        <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
      <li>
        <a href="<?=url_for('membres/options')?>/" class="options">
          <img src="/images/icones/16x16/setting_tools.png" alt="" />
          <?=__('Settings')?>
        </a> 
      </li>
      <li>
        <a href="<?=url_for('messages/index')?>">
          <img src="/images/icones/16x16/mail_box.png" width="16" height="16" alt="" />
          <?=__('Messages')?>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <div style="height:25px">
          <i class="icon-volume-off"></i>
          <input type="range" id="sndVolume" style="width: 75px" step="0.1" min="0" max="1" />
          <i class="icon-volume-up"></i>
        </div>
      </li>
      <li class="divider"></li>
      <li>
        <a href="<?=url_for('membres/logout?token='.$sf_user->getAttribute('token'))?>" class="logout">
          <i class="icon-off"></i>
          <?=__('Logout')?>
        </a>
      </li>
    </ul>
  </li>
  <li class="dropdown" id="notifications">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <img src="/images/icones/16x16/events.png" class="img" width="16" height="16" alt="" />
      <?php if (count($notifications)>0):?>
        <span class="badge badge-error"><?=count($notifications)?></span>
      <?php else: ?>
        <span class="badge">0</span>
      <?php endif; ?>
      <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
      <?php foreach ($notifications as $notification):?>
      <li>
        <a href="<?=$notification->getLink()?>">
          <div class="thumbnail" style="width: 32px">
            <img src="<?=$notification->Users->getAvatar(32)?>" />
          </div>
          <?php if ($notification->getReaded() == 0):?>
            <span class="pull-right label label-info">New</span> 
          <?php endif; ?>
          <strong><?=$notification->Users?></strong> <?=$notification->getMessage()?>
          
          <blockquote style="margin-left: 50px;width: 300px;white-space:normal;text-align: justify;margin-bottom:0">
            <?=$notification->getExtract()?>
            <small class="date" data-timestamp="<?=strtotime($notification->getCreatedAt())?>">
              <?=$notification->getCreatedAt()?>
            </small>
           </blockquote>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </li>
  <li class="dropdown" id="users-connected">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">  
      <span class="badge">
        <img src="/images/icones/16x16/status_online.png" width="16" alt="" />
        <span><?=count($onlineUsers)?></span>
      </span>
      <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
      <?php foreach ($onlineUsers as $u): ?>
        <li>
          <a href="<?=url_for('@profile?slug='.$u['slug'])?>">
            <img src="<?=$u['avatar']?>" style="width: 16px" /> <?=$u['username']?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </li>
</ul>
<?php endif;?>