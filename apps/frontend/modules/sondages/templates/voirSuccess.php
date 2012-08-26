<div id="ajax">
  <?php if ($avote): ?>
  <h2>
    <img src="/images/icones/32x32/chart_up_color.png" width="24 "/>
    <?=$sdg['question']?>
  </h2>
  <div>
    <?php foreach ($sdg['SdgReponses'] as $rep):?>
    <div class="peer">
      <a style="font-size: 12px;font-weight:bold">
        <img src="/images/icones/16x16/chart_bar.png" />
        <?=$rep['reponse']?>
      </a>
      
      <div style="float: right; margin-right: 5px;">
        <?php $cntv = count($rep['SdgVotes']); echo round($cntv/$cnt*100, 2);?>% 
        (<?=$cntv?> vote<?=($cntv>1)?'s':''?>)
      </div>
      <div class="ui-progressbar ui-widget ui-widget-content ui-corner-all" style="margin-left: 5px;margin-right: 5px; margin-top: 2px;">
        <div style="width: <?=round($cntv/$cnt*100, 0)?>%;" class="ui-progressbar-value ui-widget-header ui-corner-left ui-corner-right"></div>
      </div> 
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <h2>
    <img src="/images/icones/32x32/chart_bar_add.png" width="24" />
    <?=__("Reply at this poll")?>
  </h2>
  <form method="post" action="<?=url_for("sondages/voter")?>">
    <table>
      <tr>
        <td colspan="2" style="text-align: center">
          <strong><?=$sdg['question']?></strong>
        </td>
      </tr>
      <?php foreach ($sdg['SdgReponses'] as $rep): ?>
      <tr>
        <td style="width:16px">
          <input style="width:16px" type="radio" id="rep<?=$rep['idsdg_reponses']?>" name="rep" value="<?=$rep['idsdg_reponses']?>">
        </td>
        <td style="text-align: left;">
          <label for="rep<?=$rep['idsdg_reponses']?>">
            <?=$rep['reponse']?>
          </label>
        </td>
      </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="2" style="text-align: center">
          <input type="submit" value="<?=__("Validate my vote")?>" />
        </td>
      </tr>
    </table>
  </form>
  <?php endif; ?>
  
  <h2>
    <img src="/images/icones/32x32/user_go.png" width="24" />
    <?=__("%i% voters", array("%i%" => count($vot)))?>
  </h2>
  <div class="participants">
    <?php foreach ($vot as $v):?>
      <?php include_partial('global/mbr', array('v' => $v)); ?>
    <?php endforeach; ?>
  </div>
  
  <?php if ($sf_user->hasCredential("adm") || $sf_user->hasCredential("mod")): ?>
  <h2>
    <img src="/images/icones/32x32/chart_bar_delete.png" width="24" />
    <?=__("Delete")?>
  </h2>
  <p style="text-align:center; padding: 5px;">
    <a href="<?=url_for('sondages/supprimer?id='.$sdg['idsdg_questions'])?>" style="font-weight:bold;display:block;float:left">
      <img src="/images/icones/32x32/chart_bar_delete.png" /><br />
      <?=__("Delete")?>
    </a>
  </p>
  <?php endif; ?>
</div>