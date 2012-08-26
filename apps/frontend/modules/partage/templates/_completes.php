<tr>
  <td>
    <img src="/images/icones/16x16/flag_finish.png" />
    <?=__("Completed")?>
  </td>
  <td>
    <?=__("%i% times", array("%i%" => count($complete)))?> 
    <?php if (count($complete)>0): ?>
      <?=__("with")?> 
    <?php foreach ($complete as $c): ?>
      <?php include_partial('global/mbr', array('v' => $c->Membre)); ?>
      <?php include_partial('global/ratio', array('membre' => $c->Membre)); ?>, 
    <?php endforeach; endif; ?>
  </td>
</tr>