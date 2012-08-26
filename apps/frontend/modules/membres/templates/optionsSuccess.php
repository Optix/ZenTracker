<div id="centre">

  <div class="entree mini">
    <div class="flechedroite" data-href="<?=url_for("membres/options?page=avatar")?>"></div>
    <a href="#<?=url_for('membres/options?page=avatar');?>" style="font-size:16pt;display:block">
      <img src="/images/icones/32x32/picture_edit.png" />
      <?=__("Modify my avatar")?>
    </a>
  </div>
  
  <div class="entree mini">
    <div class="flechedroite" data-href="<?=url_for("membres/options?page=ip")?>"></div>
    <a href="#<?=url_for('membres/options?page=ip');?>" style="font-size:16pt;display:block">
      <img src="/images/icones/32x32/ip_class.png" />
      <?=__("Manage my IP addresses")?>
    </a>
  </div>
  
  <div class="entree mini">
    <div class="flechedroite" data-href="<?=url_for("membres/options?page=design")?>"></div>
    <a href="#<?=url_for('membres/options?page=design');?>" style="font-size:16pt;display:block" class="js_droite">
      <img src="/images/icones/32x32/palette.png" />
      <?=__("Change my layout")?>
    </a>
  </div>

  <div class="entree mini">
    <div class="flechedroite" data-href="<?=url_for("membres/options?page=password")?>"></div>
    <a href="#<?=url_for('membres/options?page=password');?>" style="font-size:16pt;display:block" class="js_droite">
      <img src="/images/icones/32x32/key.png" />
      <?=__("Change my password")?>
    </a>
  </div>
  
  <div class="entree mini">
    <div class="flechedroite" data-href="<?=url_for("membres/options?page=email")?>"></div>
    <a href="#<?=url_for('membres/options?page=email');?>" style="font-size:16pt;display:block" class="js_droite">
      <img src="/images/icones/32x32/email_edit.png" />
      <?=__("Change my e-mail address")?>
    </a>
  </div>
  
</div>

<div id="droite">
  <p class="survolez"></p>
</div>