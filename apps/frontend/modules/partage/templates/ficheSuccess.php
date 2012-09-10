<div id="centre" class="span6">
</div>

<div id="droite" class="span6">
  <div class="well">
    <ul class="nav nav-tabs">
      <li class="active">
      	<a data-toggle="tab" href="#description" style="text-align: center; ">
      	  <img src="/images/icones/16x16/book_open.png">
	  	  <span class="visible-desktop">
	  	    <?=__('Description')?>
	  	  </span>
      	</a>
      </li>
      <li>
      	<a data-toggle="tab" href="#infos" style="text-align: center; ">
      	  <img src="/images/icones/16x16/document_info.png">
      	  <span class="visible-desktop">
      	  	<?=__('Download')?>
      	  </span>
      	</a>
      </li>
      <li>
      	<a data-toggle="tab" href="#newcom" style="text-align: center; ">
      	  <img src="/images/icones/16x16/comment_add.png">
      	  <span class="visible-desktop">
      	  	<?=__('Add comment')?>
      	  </span>
      	</a>
      </li>
      <?php if (isset($peers)): ?>
      <li>
      	<a data-toggle="tab" href="#peers" style="text-align: center; ">
      	  <img src="/images/icones/16x16/status_online.png">
      	  <span class="visible-desktop">
      	  	<?=__('Peers')?>
      	  </span>
      	</a>
      </li>
      <?php endif; ?>
      <?php if ($u->getAuthor() == $sf_user->getAttribute("id")
        ||  $sf_user->hasCredential("adm")
        ||  $sf_user->hasCredential("mod")): ?>
      <li>
      	<a data-toggle="tab" href="#opt" style="text-align: center; ">
      	  <img src="/images/icones/16x16/wrench.png">
      	  <span class="visible-desktop">
      	  	<?=__('Options')?>
      	  </span>
      	</a>
      </li>
      <?php endif; ?>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="description">
      	<?=html_entity_decode($u->getDescription())?>
      </div>
      <div class="tab-pane" id="infos">
      	<?php include_partial('infos', array(
      	  'u' => $u,
      	  'ups' => $ups,
      	)); ?>
      </div>
      <div class="tab-pane" id="newcom">
      	<?php 
        $f = new UploadsComsForm();
        include_component('messages', 'new', array(
          "form" => $f->setDefault('upid', $u->getId()),
          "submitUrl" => url_for("partage/comment")));
        ?>
	    </div>
	  <div class="tab-pane" id="peers"><div></div></div>
    <?php if ($u->getAuthor() == $sf_user->getAttribute("id")
        ||  $sf_user->hasCredential("adm")
        ||  $sf_user->hasCredential("mod")): ?>
	  <div class="tab-pane" id="opt">
	  	<?php include_partial('edit', array(
	  	  "f" => $formEdit
	  	)); ?>
	  </div>
    <?php endif; ?>
	</div>
  </div>
</div>