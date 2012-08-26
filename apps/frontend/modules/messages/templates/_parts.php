<table class="table">
  <?php foreach ($participants as $p):?>
  	<tr>
  	  <td>
  	  	<img src="<?=$p->Users->getAvatar(16)?>" /> 
  	  	<?=$p->Users?>
  	  </td>
  	  <td>
  	  	<?php if ($p->getReaded()):?>
  	  	  <?php if ($p->getDeleted()):?>
  	  	    <img src="/images/icones/16x16/email_delete.png" /> <span class="label label-important"><?=__("Deleted")?></span>
  	  	  <?php else: ?>
	  	    <img src="/images/icones/16x16/email_open.png" /> <span class="label"><?=__("Readed")?></span>
  	  	  <?php endif; ?>
  	  	<?php else: ?>
  	  	  <img src="/images/icones/16x16/email.png" /> <span class="label label-info"><?=__("Unreaded")?></span>
  	  	<?php endif; ?>
  	  </td>
  	  <td>
        <?php if ($sf_user->getAttribute("id") == $p->getMpmid()): ?>
          <a href="?delete" class="btn btn-danger btn-mini">
          	<i class="icon-white icon-trash"></i>
            <?=__('Delete')?>
          </a>
    	<?php endif; ?>
  	  </td>
  	</tr>
  <?php endforeach; ?>
</table>