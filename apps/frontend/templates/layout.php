<!DOCTYPE html>
<html lang="<?=substr($sf_user->getCulture(), 0, 2)?>">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <script>
      var scriptname = "<?=url_for("@homepage")?>";
      var isAuthenticated = <?=($sf_user->isAuthenticated())?'true':'false'?>;
      var level = <?=($sf_user->isAuthenticated()) ? $sf_user->getAttribute("ses")->getLevel() : "0"?>;
      var uid = <?=$sf_user->getAttribute("id", 0)?>;
      var role = <?=($sf_user->isAuthenticated()) ? '"'.$sf_user->getAttribute("ses")->getRole().'"' : "guest"?>;
      var dateClient = new Date();
      var offsetTime = dateClient.getTime() - <?=time()?>*1000;
      var array = new Object;
      var itemPerPage = 17; var leftPage = 1;
    </script>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" href="/js/redactor/redactor.css" />
    <?php include_javascripts() ?>
    <?php include_stylesheets() ?>
    <script type="text/javascript">
      $(function() {
        $('#newshtcom').live("keypress", function(e) {
          if (e.which == '13') {
            e.preventDefault();
            $(this).parent().submit();
          }
        });      
      });
      if (wsState == false) var timerShout = setInterval('actualiserShout()', 10000);
    </script>
  </head>
  <body>
    <noscript class="general"><?=__('Javascript must be enabled !')?></noscript>
    
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container" style="width: 98%">
          <a href="<?=url_for("@homepage")?>" class="brand hidden-phone" title="<?=__("Back to homepage")?>">
            <?=sfConfig::get("app_name", "ZenTracker CMS")?>
          </a>
          <div class="nav-collapse">
          <ul class="nav">
            <?php include_component("main", "categories")?>
            <?php include_component("main", "sidebar")?>
          </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid" style="margin-top: 40px">
      <div class="row-fluid">
        <?php if ($sf_user->hasFlash('notice')): ?>
          <div class="alert alert-success span6"><i class="icon-ok-sign"></i> <?php echo $sf_user->getFlash('notice') ?></div>
        <?php endif ?>
        <?php if ($sf_user->hasFlash('error')): ?>
          <div class="alert alert-error span6"><i class="icon-exclamation-sign"></i> <?php echo $sf_user->getFlash('error') ?></div>
        <?php endif ?>
        <?php echo $sf_content ?>
      </div>
    </div>
    <audio style="display: none" id="sdn-pop">
      <source src="/js/pop.mp3"></source>
      <source src="/js/pop.wav"></source>
    </audio>
    <!--<div class="modal" id="myModal">
      <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3></h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row-fluid">

            <div class="span4">

            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-warning">
          <i class="icon-white icon-pencil"></i>
          Éditer
        </a>
        <a href="#" class="btn btn-danger">
          <i class="icon-white icon-trash"></i>
          Supprimer
        </a>
        <a href="#" class="btn btn-primary">
          <i class="icon-white icon-download-alt"></i>
          Télécharger
        </a>
      </div>
    </div>-->
  </body>
</html>