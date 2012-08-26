<p style="text-align: justify">
  <?=__("The website links your IP addresses to your account allowing announce (the part of the tracker that communicates with your BitTorrent client) acknowledging you and authorize the transfer.")?>
  <?=__("For safety, an IP not listed here is not allowed to connect to it. If you have a seedbox in your possession, simply add the IP address below to authorize it. Feel free to do the cleaning if multiple IPs from which you logged are old.")?>
</p>
<table class="table table-striped">
  <thead>
    <tr>
      <th style="text-align:center">
        <?=__("IP address")?>
      </th>
      <th style="text-align:center">
        <?=__("Created at")?>
      </th>
    </tr>
  </thead>
  <?php foreach ($ips as $i): ?>
  <tr>
    <td>
      <a href="<?=url_for("membres/ip?delete=".$i->getIp());?>" class="btn btn-mini btn-danger">
        <i class="icon-white icon-trash"></i>
      </a>
      <?=$i->getIp()?>
    </td>
    <td style="text-align:center">
      <?=$i->getCreatedAt()?>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (count($ips)===0): ?>
  <tr>
    <td colspan="2" style="text-align:center">
      <?=__("No found IP address")?>
    </td>
  </tr>
  <?php endif;?>
</table>

<?php if (isset($form)):?>
<form method="post" action="<?=url_for('membres/ip')?>/add" id="addip" class="form-horizontal">
  <fieldset>
    <legend>
      <img src="/images/icones/32x32/ip.png" width="24" alt="" />
      <?=__("Add a new IP address")?>
    </legend>
    <?=$form?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" />
    </div>
  </fieldset>
</form>
<?php endif; ?>