<?php if (!$ajax):?>
<div id="centre">
  <?php if($sf_user->isAuthenticated()): ?>
  <div class="entree selectionne">
    <form method="post" class="sht" action="<?=url_for('forums/liste?frmid='.$q->getIdfrmForum())?>">
      <img src="<?=$sf_user->getAttribute('ses')->getAvatar()?>" class="avatar" width="50" height="50" />
      <?=$form['txt']->render()?>
      <?=$form['titre']->render(array('style' => 'width:310px'))?>
      <?=$form->renderHiddenFields(); ?>
      <input type="submit" value="<?=__('Send')?>" />
    </form>
    <?php echo $form['txt']->renderError() ?>
    <?php echo $form['titre']->renderError() ?>
  </div>
  <?php endif; ?>
  
  <div class="cat">
    <img src="/images/icones/32x32/comments.png" />
    <?=$q->getFrmTitre()?>
  </div>
  <?php endif; ?>
  <?php foreach ($f->getResults() as $sjt): ?>
    <div class="entree mini" data-page="<?=$f->getPage()?>">
      <div class="flechedroite" data-href="<?=url_for('forums/sujet?id='.$sjt->getIdfrmSujet())?>"></div>
      <img class="avatar s32" src="<?=@$sjt->FrmMsg[0]->Membre->getAvatar()?>" alt="" />
      <div class="pseudo">
        <?php if($sjt->getVerrouille() == 1): ?>
          <img src="/images/icones/16x16/lock.png" />
        <?php endif; ?>
        <?php if($sjt->getPostit() == 1): ?>
          <img src="/images/icones/16x16/error.png" />
        <?php endif; ?>
        <a href="#<?=url_for('forums/sujet?id='.$sjt->getIdfrmSujet())?>">
          <?=$sjt?>
        </a>
      </div>
      <em class="date" data-timestamp="<?=strtotime($sjt['FrmMsg'][0]->getCreation());?>"></em>
    </div>
  <?php endforeach; ?>
  <?php if (!$ajax): ?>
</div>

<div id="droite">
  <p class="survolez"></p>
</div>
<style>
  #centre .entree .s32 {
    margin-top: -2px;width:32px;height:32px;
  }
  #centre .entree .pseudo, #centre .entree .date {
    position: relative;
    top: 6px;
  }
</style>
<script>
$(window).scroll(function(){
  if  ($(window).scrollTop() == $(document).height() - $(window).height()){
    nextPage();
  }
});
</script>
<?php endif; ?>