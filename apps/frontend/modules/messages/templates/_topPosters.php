<table class="table table-striped table-bordered">
  <?php foreach ($mbr as $id => $u):?>
  	<tr style="font-size: 200%;">
      <td style="vertical-align: middle;text-align: right;width: 30px"><?=$id+1?>.</td>
  	  <td>
  	  	<a href="<?=url_for('@profile?slug='.$u->Users->getSlug())?>">
  	  	  <img src="<?=$u->Users->getAvatar(32)?>" style="width:32px" /> 
  	  	  <span>
  	  	  	<?=$u->Users?>
  	  	  </span>
  	  	</a>
  	  	<span class="pull-right" style="position: relative; top: 5px">
          <?php if ($id === 0):?>
            <img src="/images/icones/32x32/medal_gold_3.png" /> 
          <?php elseif ($id === 1):?>
            <img src="/images/icones/32x32/medal_silver_3.png" />
          <?php elseif ($id === 2):?>
            <img src="/images/icones/32x32/medal_bronze_3.png" />  
          <?php endif; ?>
  	  	  <?=$u['cnt']?>
  	  	</span>
  	  </td>
  	</tr>
  <?php endforeach; ?>
</table>