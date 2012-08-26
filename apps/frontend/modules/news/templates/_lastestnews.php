<div id="news" class="tab-pane<?php if ($sf_user->isAuthenticated()):?> active<?php endif; ?>">
<?php
  foreach ($news as $n):
    include_partial("news/news", array("n" => $n));
  endforeach;
?>
</div>