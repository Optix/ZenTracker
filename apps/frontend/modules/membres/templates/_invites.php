<table class="table table-striped">
  <thead>
    <tr>
      <th style="text-align:center">
        <?=__("Code")?>
      </th>
      <th style="text-align:center">
        <?=__("Expires at")?>
      </th>
    </tr>
  </thead>
  <?php foreach ($codes as $i): ?>
  <tr>
    <td>
      <?=$i->getCode()?>
    </td>
    <td style="text-align:center">
      <?php if (strtotime($i->getExpire()) > time()): ?>
        <img src="/images/icones/16x16/bullet_green.png" /> 
      <?php else: ?>
        <img src="/images/icones/16x16/bullet_red.png" /> 
      <?php endif; ?>
      <?=$i->getExpire()?>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (count($codes)===0): ?>
  <tr>
    <td colspan="2" style="text-align:center">
      <?=__("No code found.")?>
    </td>
  </tr>
  <?php endif;?>
</table>

<form method="post" action="<?=url_for('membres/invite')?>/add" class="form-horizontal">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/cup_add.png" width="24" alt="" />
      <?=__("Create a new invitation code")?>
    </legend>
    <?php if ($sf_user->getAttribute("ses")->getLevel() >= sfConfig::get("app_invite_minlevel")):?>
      <?=$form?>
      <div class="form-actions">
        <input type="submit" class="btn btn-primary" />
      </div>
    <?php else: ?>
      <div class="alert alert-error">
        <img src="/images/icones/16x16/cup_delete.png" /> 
        <?=__("Only users (level %level%) can create and send invitation codes.", array("%level%" => sfConfig::get("app_invite_minlevel")))?>
      </div>
    <?php endif; ?>
  </fieldset>
</form>