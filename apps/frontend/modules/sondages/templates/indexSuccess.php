<div id="centre">

  <?php if($sf_user->isAuthenticated()): ?>
  <div class="entree mini" style="text-align: center;">
    <div class="flechedroite" data-href="<?=url_for('sondages/nouveau')?>"></div>
    <a href="#<?=url_for('sondages/nouveau');?>" style="font-size:16pt;display:block">
      <img src="/images/icones/32x32/chart_bar_add.png" />
      <?=__("Add a new poll")?>
    </a>
  </div>
  <?php endif; ?>


  <?php foreach ($sdg as $s): ?>
  <div class="entree mini" id="sdg_<?=$s['idsdg_questions']?>">
    <div class="flechedroite" data-href="<?=url_for("sondages/voir?id=".$s['idsdg_questions'])?>"></div>
    <img class="avatar" style="background:url(/images/icones/32x32/chart_column_2.png) center center no-repeat; border: none;margin-top: -2px; border-radius:0px; width: 36px; height:36px" />
    
    <div class="pseudo">
      <img src="/images/icones/16x16/user_comment.png" />
        <a href="#<?=url_for('@profil?id='.$s['sht_mid'].'&username='.$s['Membre']['username']) ?>">
          <?=$s['question']?>
        </a>
    </div>
    
    <em class="date" data-timestamp="<?=(strtotime($s['lastvote'])!=null)?strtotime($s['lastvote']):0;?>"></em>
    
    <p><?=$s['cnt']?> vote<?=($s['cnt']>1)?'s':''?>  &bull; Créé <?=getRelativeTime($s['creation'])?></p>
  </div>
  <?php endforeach; ?>
</div>

<div id="droite">
  <p class="survolez"></p>
</div>

<script>
  $(function() {
    // Add a new choice
    $('#droite').on('click', '#add_choice', function(e){
      e.preventDefault();
      var num = $('.choice').length+1;
      $('.choice:first').clone().appendTo('#droite form table').hide().fadeIn("slow");
      $('.choice:last').attr("data-cid", num);
      $('.choice:last label').html(num);
      $('.choice:last label').attr("for", "choice"+num);
      $('.choice:last input').attr("id", "choice"+num);
      $('.choice:last input').attr("name", "choice"+num);
      if ($('.choice').length == 8)
        $('#add_choice').hide("slow", function() { $(this).remove(); });
    });
    // Merge choices in a single hidden textarea for form processing
    $('#droite').on("submit", "#new", function(e) {
      var ch = "";
      $('.choice input').each(function(idx) {
        ch += $(this).val()+"\r\n";
      });
      var textarea = '<textarea name="txt" style="display:none">'+ch+'</textarea>';
      $(textarea).appendTo("#droite form");
    });
  });
</script>