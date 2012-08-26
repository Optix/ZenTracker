<div class="page-header">
  <h2>
    <img src="/images/icones/32x32/coins_in_hand.png" />
    <?=__("Amount of your donation")?>
  </h2>
</div>
  <?php foreach ($dons as $id => $don):?>
  <div style="margin: auto; margin-bottom: 10px; text-align: center">
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="display: none" name="paypal<?=$id?>">
      <input type="hidden" name="cmd" value="_donations">
      <input type="hidden" name="business" value="<?=sfConfig::get("app_paypal_mail")?>">
      <input type="hidden" name="lc" value="<?=$sf_user->getCulture()?>">
      <input type="hidden" name="item_name" value="<?=sfConfig::get("app_name")?>">
      <input type="hidden" name="no_note" value="1">
      <input type="hidden" name="amount" value="<?=$don['amount']?>">
      <input type="hidden" name="return" value="http://<?=$r->getHost().url_for("membres/dons?hash=".$don['hash'])?>">
      <input type="hidden" name="cn" value="">
      <input type="hidden" name="no_shipping" value="1">
      <input type="hidden" name="currency_code" value="EUR">
      <input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHosted">
    </form>
    <a href="javascript: document.paypal<?=$id?>.submit();" class="btn" style="font-size: 16pt;">
      <img src="/images/icones/32x32/money_add.png" />
      <?=format_currency($don['amount'], 'EUR')?>
      (+ <?=format_number($don['amount']*100)?> pts)
    </a>
  </div>
  <?php endforeach; ?>