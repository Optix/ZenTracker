
<div id="droite">

  <h2>
    <img src="/images/icones/32x32/moneybox.png" width="24" />
    <?=__("Best donors")?>
  </h2>
  <table style="margin: auto; width: 200px;">
    <thead>
      <tr>
        <th><?=__("Member")?></th>
        <th><?=__("Donations")?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($bestDonors as $bestDonor): ?>
      <tr>
        <td class="btn_actions" style="text-align: center">
          <?php include_partial('global/mbr', array('v' => $bestDonor->Membre)); ?>
        </td>
        <td style="text-align: right;font-weight: bold;">
          <?=format_currency($bestDonor->getValue(), 'EUR')?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>