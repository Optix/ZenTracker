<div id="centre" class="span6">
  <a class="entree btn btn-info" style="height: 60px">
    <?php if ($sf_user->isAuthenticated()): ?>
      <form method="post" class="sht" action="<?=url_for('shoutbox/add')?>">
        <div class="thumbnail">
          <img src="<?=$sf_user->getAttribute('ses')->getAvatar()?>" class="avatar" width="50" height="50" />
        </div>
        <textarea name="sht_txt" placeholder="<?=__("I'm going to write a new shout !")?>" style="height:19px;margin-bottom:3px"></textarea>
        <div class="btn_actions">
          <!--<input type="submit" class="shtsub btn btn-info" value="<?=__('Send')?>" style="font-weight: bold;float:right" />-->
          <div id="smilies" class="btn" onclick="javascript: listSmilies();">
            <img src="/images/icones/16x16/emotion_grin.png" width="14" />
          </div>
        </div>
      </form>
    <?php else: ?>
    <div class="thumbnail">
      <img src="/images/avatar_default.gif" class="avatar" />
    </div>
    <span style="font-size:18pt; position: relative; top: 4px;left: -2px"><?=__('Welcome !')?></span><br />
    <span style="position: relative;top: 3px"><?=__('In order to use fully the %name%, you have to login first.', array("%name%" => sfConfig::get("app_name", "ZenTracker CMS")))?></span>
    <hr class="clrfx" />
    <?php endif; ?>
  </a>
  <div id="liste_shouts">
    
  </div>
</div>

<div id="droite" class="span6">
  <div class="well">
    <ul class="nav nav-tabs">
      <?php if (!$sf_user->isAuthenticated()):?>
      <li class="active">
        <a href="#login" data-toggle="tab">
          <img src="/images/icones/16x16/key_go.png" width="16" height="16" alt="" />
          <?=__('Login')?>
        </a>
      </li>
      <li>
        <a href="#register" data-toggle="tab">
          <img src="/images/icones/16x16/key_add.png" width="16" height="16" alt="" />
          <?=__('Registration')?>
        </a>
      </li>
      <?php else: ?>
      <li class="active">
        <a href="#news" data-toggle="tab">
          <img src="/images/icones/16x16/newspaper.png" width="16" height="16" alt="" />
          <?=__('News')?>
        </a>
      </li>
      <li>
        <a href="#forums" data-toggle="tab">
          <img src="/images/icones/16x16/comments.png" width="16" height="16" alt="" />
          <?=__('Forums')?>
        </a>
      </li>
      <?php endif; ?>
    </ul>
    <div class="tab-content">
      <?php if (!$sf_user->isAuthenticated()):?>
      <div id="login" class="tab-pane active">
        <form method="post" class="form-horizontal" action="<?=url_for("membres/login")?>">
          <fieldset>
            <legend>
              <img src="/images/icones/32x32/key.png" width="24" />
              <?=__('Login')?>
            </legend>
            <?=$loginForm?>
            <div class="form-actions">
              <input type="submit" class="btn btn-primary" value="<?=__('Log me in')?>" />
              <input type="reset" class="btn" value="<?=__('Cancel')?>" />
            </div>
          </fieldset>
        </form>
      </div>
      <div class="tab-pane" id="register">
        <?php if (isset($regform)): ?>
        <form method="post" class="form-horizontal" action="<?=url_for("@homepage")?>">
          <?=$regform->renderHiddenFields()?>
          <fieldset>
            <legend>
              <img src="/images/icones/32x32/vcard_add.png" width="24" />
              <?=__('Registration')?>
            </legend>
            <?=$regform?>
            <div class="form-actions">
              <input type="submit" class="btn btn-primary" value="<?=__('Register me')?>" />
              <input type="reset" class="btn" value="<?=__('Cancel')?>" />
            </div>
          </fieldset>
        </form>
        <?php endif; ?>
      </div>
      <?php else: ?>
        <?php include_component('forums', 'lastreplies'); ?>
        <?php include_component('news', 'lastestnews'); ?>
      <?php endif; ?>
    </div>
  </div>
</div>