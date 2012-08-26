<div class="page-header">
  <h2>
    <img src="/images/icones/32x32/credit.png" width="24" />
    <?=__("Goal to reach")?>
  </h2>
</div>
<div class="progress">
  <div class="bar"
       style="width: <?=round($donationsPercentage, 0)?>%"></div>
</div>
<p style="text-align:center">
  <?=format_currency($donations, 'EUR')?>
  /
  <?=format_currency(sfConfig::get('app_dons_objectif'), 'EUR')?>
</p>

<div class="page-header">
  <h2>
    <img src="/images/icones/32x32/credit.png" width="24" />
    <?=__("Last donations")?>
  </h2>
</div>
<table class="table table-stripe">
  <thead>
    <th>
      <?=__('Member')?>
    </th>
    <th style="text-align: center">
      <?=__('Amount')?>
    </th>
    <th style="text-align: center">
      <?=__('Date')?>
    </th>
  </thead>
  <tbody>
    <?php foreach ($lastDonations as $d):?>
    <tr>
      <td>
        <img src="<?=$d->Users->getAvatar(16)?>" style="width:16px" />
        <?=$d->Users?>
      </td>
      <td style="text-align: center">
        <?=format_currency($d->getAmount(), 'EUR')?>
      </td>
      <td style="text-align: center" class="date" data-timestamp="<?=strtotime($d->getCreatedAt())?>">
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>