<?php
$r = $membre->getRatio(1);
if ($r['ratio'] >= 1)
  $c = "green";
elseif ($r['ratio'] > 0.5)
  $c = "orange";
else
  $c = "red";
?>
<span title="Ratio de <?=$membre->getUsername()?>" style="color: <?=$c?>"><?=number_format($r['ratio'],2, '.', ' ')?></span>