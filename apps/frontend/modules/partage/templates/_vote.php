    <tr>
      <td>
        <img src="/images/icones/16x16/draw_star.png" />
        <?=__("Votes")?> 
      </td>
      <td>
        <?php if (count($note) > 0): ?>
          <?php $avg = 0; foreach ($note as $n) $avg += $n->getNote(); $avg = $avg / count($note); ?>
          <?php for($i = 0; $i < ceil($avg);$i++): ?>
            <img src="/images/icones/16x16/star.png" />
          <?php endfor; ?> (<?=round($avg, 1)?>)
        <?php else: ?>
          <em><?=__("No vote yet")?></em>
        <?php endif; ?>
      </td>
    </tr>
    <?php if ($formvote): ?>
    <tr>
      <td colspan="2">
        <form method="post" style="text-align: center;width:300px" action="<?=url_for("partage/vote?id=".$t->getId())?>" class="formvote">
          <?=$formvote['note']->render()?> 
          <input type="submit" value="<?=__("Send")?>" /> 
          <?=$formvote->renderHiddenFields(); ?>
        </form>
      </td>
    </tr>
    <?php endif; ?>