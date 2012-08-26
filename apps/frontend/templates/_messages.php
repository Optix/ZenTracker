<div id="msg<?=$id?>">
  <tr>
    
    <td style="text-align:center;width:100px;font-weight:bold">
      <a style="font-size: 10pt" href="#<?=url_for("@profil?id=".$mbr->getMbrId()."&username=".$mbr)?>">
        <?=$mbr?>
      </a>
    </td>
    
    <td>
      <span class="date" data-timestamp="<?=strtotime($tps)?>"></span>
      
      <?php
      if(!empty($tps_edit)): ?>
        <em>(<?=__('edited')?> 
          <span class="date" style="text-transform: lowercase;" data-timestamp="<?=strtotime($tps_edit)?>"></span>)
        </em>
      <?php endif; ?>
        
        
      <div class="frmmsg_act" style="float: right;font-weight:bold;margin-right: 10px">
        <?php if (isset($parent)): ?>
          <a href="#<?=$parent[0]?>">
            <img src="/images/icones/16x16/door_in.png" /> 
            <?=$parent[1]?>
          </a>
        <?php endif; ?>
        <?php if ($sf_user->isAuthenticated() && $verr == 0): ?>
        <a href="#" class="frm_quote" data-id="<?=$id?>">
          <img src="/images/icones/16x16/comment_add.png" /> <?=__('Quote')?>
        </a>
        <?php endif; if (($sf_user->getAttribute("id") == $mbr->getMbrId()) || ($sf_user->hasCredential("mod") || $sf_user->hasCredential("adm"))): ?>
        <?php if ($edit): ?>
        <a href="#" class="frm_edit" data-id="<?=$id?>">
          <img src="/images/icones/16x16/comment_edit.png" /> <?=__('Edit')?>
        </a><?php endif; if ($suppr): ?>
        <a href="#" class="frm_delete" data-id="<?=$suppr?>">
          <img src="/images/icones/16x16/comment_delete.png" /> <?=__('Delete')?>
        </a>
        <?php endif; endif; ?>
      </div>
    </td>
  </tr>
  
  <tr>
    <td style="text-align:center;vertical-align: top">
      <img src="<?=$mbr->getAvatar()?>" class="avatar" />
      <br />
      <?=__("Ratio")?> : <?php include_partial("global/ratio", array("membre" => $mbr)) ?>
    </td>
    
    <td class="frm_msg" id="frm_msg<?=$id?>">
      <p><?=bbcode_decode($msg, 1)?></p>
      
      <?php if ($edit): ?>
        <textarea data-id="<?=$edit?>" name="txt" style="width:99%;height:100px;display: none"><?=$msg?></textarea>
      <?php endif; ?>
    </td>
  </tr>
</div>