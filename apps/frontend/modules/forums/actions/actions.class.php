<?php

/**
 * forums actions.
 *
 * @package    zt2
 * @subpackage forums
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class forumsActions extends sfActions {
 /**
  * Forums index
  */
  public function executeIndex(sfWebRequest $r) {
    // Getting forumlist
    $this->frm = Doctrine::getTable("FrmForums")->getForums();

  	// If Ajax
  	if ($r->isXmlHttpRequest()) {

      // Formatting for JSON
      $a = $b = array();
      // Foreach category
    	foreach ($this->frm as $cat) {

        // If the cat has no forum, don't display it.
        if (count($cat['FrmForums']) === 0)
          continue;

    		$a[] = array("title" => $cat['name']);

        // Foreach forum
    		foreach ($cat['FrmForums'] as $frm) {
          
          // If we can't see this forum, skip it
          if (!$this->canViewForum($frm))
            continue;

    			$a[] = array(
    				"title" => $frm['name'],
    				"description" => $frm['description'],
    				"url" => $this->getContext()->getController()->genUrl("@forum?c=".$cat['slug']."&slug=".$frm['slug']),
    			);
    		}
    	}

      // If we are admin, we can manage cats & forums
      if ($this->getUser()->hasCredential("adm"))
        $b['mng'] = $this->getTab("Manage", "add.png", $this->getComponent('forums', 'manage', array()));

  	  // Sending it to browser
      return $this->renderText(json_encode(array(
        "left" => $a,
        "right" => $b,
        "module" => $this->getModuleName()
      )));
    }
  }

  /**
  * Adding new categorie
  */
  public function executeAdd(sfWebRequest $r) {
    $f = new FrmCatsForm();
    if ($r->isMethod('post')) {
      $f->bind($r->getParameter($f->getName()));
      if ($f->isValid()) {
        $f->save();
        $this->redirect('forums/index');
      }
    }
  }

  /**
  * Adding new forum
  */
  public function executeAddfrm(sfWebRequest $r) {
    $f = new FrmForumsForm();
    if ($r->isMethod('post')) {
      $f->bind($r->getParameter($f->getName()));
      if ($f->isValid()) {
        $f->save();
        $this->redirect('forums/index');
      }
    }
  }

  /**
  * Adding new topic
  */
  public function executeNew(sfWebRequest $r) {
    $this->form = new FrmTopicsForm();
    if ($r->isMethod('post')) {
      $this->form->bind($r->getParameter($this->form->getName()));
      if ($this->form->isValid()) {
        $t = $this->form->save();
        $m = Doctrine_Query::create()
          ->update('MsgMessages')
          ->set('tid', $t->getId())
          ->where('tid IS NULL')
          ->andWhere('created_at = ?', $t->getCreatedAt())
          ->execute();
        Doctrine_Query::create()->update("FrmTopics")->set('updated_at', '"'.$t->getCreatedAt().'"')->where("id = ?", $t->getId())->execute();
        $f = Doctrine::getTable("FrmForums")->find($t->getForum());
        $this->redirect("@forum?c=".$f->FrmCats->getSlug()."&slug=".$f->getSlug());
      }
      else {
        $c = (string) $this->form->getErrorSchema();
        preg_match_all('#(.+) \[(.+)\]#U', $c, $m);
        $m[1] = array_map('trim', $m[1]);
        die(json_encode($m, JSON_FORCE_OBJECT));
      }
    }
    else
      $this->forward404();
  }

 /**
  * Thread list
  */
  public function executeList(sfWebRequest $r) {
    // Fetching forum from route
    $this->q = $this->getRoute()->getObject();
    $this->forward404Unless($this->q);

    // Can we viex inside this forum ? If no, redirect to forum homepage
    if (!$this->canViewForum($this->q))
      $this->redirect($this->getModuleName().'/index');
	
  	// Getting all threads in this forum
  	$this->topics = Doctrine::getTable("FrmTopics")->getTopics($this->q->getId())->toArray();
    // For each topic
    foreach ($this->topics as $id => $t) {
      // Generate URL, simply by adding slug behind current URL
      $this->topics[$id]['url'] = $r->getUri()."/".$t['slug'];
    }
  	
  	return $this->renderText(json_encode(array(
  		"left" => $this->topics,
  		"right" => array(
  			"new" => $this->getTab("New topic", "add.png", $this->getComponent('forums', 'newtopic',
          array('forum' => $this->q)))
  		),
  		"module" => "topics",
  	)));
  }
  
 /**
  * Voir le contenu d'un sujet
  */
  public function executeTopic(sfWebRequest $r) {
    $a = array();
    $this->topic = $this->getRoute()->getObject();
    // If no topic found
    $this->forward404Unless($this->topic);
    // Getting related comments
    $this->coms = Doctrine::getTable("MsgMessages")->getComments($this->getModuleName(), $this->topic->getId());
    // Updating
    $u = new FrmTopicsUsr();
    $u->setTopic($this->topic->getId());
    $u->setUid($this->getUser()->getAttribute("id"));
    $u->setLastmsgid($this->coms[(count($this->coms)-1)]->getId());
    $u->replace();

    // Injecting comments
    $a['coms'] = $this->getTab("Messages", "comments.png", $this->coms->toArray());

    // If we can post (topic unlocked)
    if (!$this->topic->getIsLocked() && $this->canWriteForum($this->topic->FrmForums)) {
      $f = new FrmMessagesForm();
      $f->setDefault('tid', $this->topic->getId());
      $a['new'] = $this->getTab("Add a new message", "comment_add.png",
        $this->getComponent('messages', 'new', array(
          "form" => $f,
          "submitUrl" => $this->getContext()->getController()->genUrl("forums/newmsg")
        ))
      );
    }

    // Topic options (only for adm/mod)
    if ($this->getUser()->hasCredential("adm") || $this->getUser()->hasCredential("mod"))
      $a['opt'] = $this->getTab("Options", "wrench.png", 
        $this->getComponent('forums', 'edittopic', array(
          'obj' => $this->topic))
      );

    // Sending correct MIME
    $this->getResponse()->setHttpHeader('Content-type','application/json');
    
    // Sending content
    return $this->renderText(json_encode(array("right" => $a)));
  }

  
 /**
  * Modification d'un topic
  */
  public function executeEdittopic(sfWebRequest $r) {
    // Getting topic id
    $id = $r->getPostParameter("frm_topics[id]");

    // Fetching it from DB
    $t = Doctrine::getTable("FrmTopics")->find($id);
    $this->forward404Unless($t);

    // Loading form
    $f = $this->form = new FrmTopicsForm($t);

    // Binding fields
    $f->bind($r->getParameter($f->getName()));

    // Is everything is valid
    if ($f->isValid()) {

      // Just keeping in mind the current update time
      $updatedAt = $t->getUpdatedAt();

      // Save it (updated time lost)
      $f->save();

      // Recover update time
      Doctrine_Query::create()->update("FrmTopics")->set("updated_at", '"'.date('Y-m-d H:i:s', $updatedAt).'"')
        ->where('id = ?', $t->getId())->execute();

      // Success : redirect to thread list
      $this->redirect("@forum?c=".$t->FrmForums->FrmCats->getSlug()."&slug=".$t->FrmForums->getSlug());
    }
  }


  public function executeDelete(sfWebRequest $r) {
    $t = Doctrine::getTable("FrmTopics")->find($r->getParameter("id"));
    $this->forward404Unless($t);
    if ($r->getParameter("token") == $this->getUser()->getAttribute("token")) {
      //$url = '@forum?c='.$t->FrmForums->FrmCats->getSlug().'&slug='.$t->FrmForums->getSlug();
      $t->delete();
      $this->redirect('forums/index');
    }
    else {
      $this->getReponse()->setStatusCode(403);
      return $this->renderText("");
    }
  }


  public function executeNewmsg(sfWebRequest $r) {
    if ($r->isMethod('post')) {
      // Loading form
      $f = new FrmMessagesForm();
      // Binding fields
      $f->bind($r->getParameter($f->getName()));
      // If form is valid
      if ($f->isValid()) {
        // We can save it.
        $m = $f->save();
        // Update topic with last message
        Doctrine_Query::create()->update("FrmTopics")->set("updated_at", '"'.$m->getCreatedAt().'"')
          ->where('id = ?', $m->getTid())->execute();
        // Redirect to topic
        $this->redirect($r->getReferer());
      }
    }
    else
      $this->forward404();
  }
  
  /** 
   * Reset homepage cache
   */
  public function resetCache() {
    if ($cache = $this->getContext()->getViewCacheManager()) {
      $cache->remove("@sf_cache_partial?module=forums&action=_lastreplies&sf_cache_key=*");
    }
  }


  protected function canViewForum($f) {
    // Default policy
    $can = true;

    // If user hasn't the required level
    if ($this->getUser()->getAttribute("ses")->getLevel() < $f['minlevelread'])
      $can = false;

    // If user hasn't the required role
    if (!empty($f['minroleread'])) {
      // If forum requires login (mbr) and user isn't logged
      if ($f['minroleread'] == "mbr" && !$this->getUser()->isAuthenticated())
        $can = false;
      // If moderator required
      if ($f['minroleread'] == "mod" && !$this->getUser()->isAuthenticated() && $this->getUser()->hasCredential("mbr"))
        $can = false;
      // If only for admins
      if ($f['minroleread'] == "adm" && !$this->getUser()->hasCredential("adm"))
        $can = false;
    }

    return $can;
  }
  protected function canWriteForum($f) {
    // Default policy
    $can = true;

    // If user hasn't the required level
    if ($this->getUser()->getAttribute("ses")->getLevel() < $f['minlevelwrite'])
      $can = false;

    // If user hasn't the required role
    if (!empty($f['minrolewrite'])) {
      // If forum requires login (mbr) and user isn't logged
      if ($f['minrolewrite'] == "mbr" && !$this->getUser()->isAuthenticated())
        $can = false;
      // If moderator required
      if ($f['minroleread'] == "mod" && !$this->getUser()->isAuthenticated() && $this->getUser()->hasCredential("mbr"))
        $can = false;
      // If only for admins
      if ($f['minrolewrite'] == "adm" && !$this->getUser()->hasCredential("adm"))
        $can = false;
    }

    return $can;
  }
}
