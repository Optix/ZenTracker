<?php

/**
 * partage actions.
 *
 * @package    zt2
 * @subpackage partage
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class partageActions extends sfActions {
 /**
  * Uploads listing
  */
  public function executeIndex(sfWebRequest $r) {    
    $a = array();

    // Loading filters
    $this->filter = new UploadsFormFilter(); 

    // If something is posted for the filter
    if($r->isMethod('post')) {
      
      // Binding filter values to validators
      $this->filter->bind($r->getParameter($this->filter->getName()));      
    }

    // Retrieving values from it
    $values = $this->filter->getValues();

	  // If a category is selected
    if ($r->hasParameter('c')) {

      // Getting record from db
      $this->cat = Doctrine::getTable("Categories")->findOneBySlug($r->getUrlParameter("c"));

      // If the cat doesn't exist, sending 404
      $this->forward404Unless($this->cat);

  	  // Setting current cat in filter
  	  $this->filter->setDefault("cat", $this->cat->getId());

      // Find children of that cat
      $children = array();
      $node = $this->cat->getNode();
      if ($node->hasChildren())
        foreach ($node->getDescendants() as $ch)
          $children[] = $ch->getId();
      else
        $children[] = $this->cat->getId();
    }
    else
      $this->cat = false;

    // Building query with values from filter
    $t = $this->filter->buildQuery($values);
    $t->select('r.*, c.*, u.username, u.avatar, u.slug')
      ->addSelect('(SELECT COUNT(*) FROM MsgMessages m WHERE module = "up" AND upid = r.id) AS cnt_coms')
      ->addSelect('(SELECT COUNT(*) FROM TorrentsPeers p WHERE p.hash = r.hash 
        AND remain = 0 AND updated_at > "'.date('Y-m-d H:i:s', time()-3600).'") AS cnt_seeders')
      ->addSelect('(SELECT COUNT(*) FROM TorrentsPeers pp WHERE pp.hash = r.hash 
        AND remain > 0 AND updated_at > "'.date('Y-m-d H:i:s', time()-3600).'") AS cnt_leechers')
      ->addSelect('(SELECT COUNT(*) FROM TorrentsPeers ppp WHERE ppp.hash = r.hash 
        AND remain = 0) AS cnt_completed')
      ->leftJoin("r.Categories c")->leftJoin("r.Users u")->orderBy("id desc");
    if ($this->cat)
      $t->andWhereIn("r.cat", $children);
    
    // If AJAX, sending list in JSON
    if ($r->isXmlHttpRequest()) {

      // Sending correct MIME
      $this->getResponse()->setHttpHeader('Content-type','application/json');

      // Building tabs
      $a['filter'] = $this->getTab("Filters", "filter.png", 
        $this->getPartial($this->getModuleName()."/filters", array("f" => $this->filter)));

      $a['new'] = $this->getTab("Add an upload", "add.png", 
        $this->getComponent($this->getModuleName(), "add", array("category" => $this->cat)));

      // If we're admin, we can add/edit cat
      if ($this->getUser()->hasCredential("adm"))
        $a['cats'] = $this->getTab("Categories", "folders.png",
          $this->getComponent($this->getModuleName(), "categories", array("category" => $this->cat)));

      $ups = $t->execute(array(), Doctrine::HYDRATE_ARRAY);

	    // Generating URL
      foreach ($ups as $id => $up) {
        $ups[$id]['url'] = $this->getContext()->getController()->genUrl("@upload?slug=".$up['slug']."&c=".$up['Categories']['slug']);
        $ups[$id]['description'] = strip_tags($up['description'], '<img>');
      }
        
      // Sending everything to browser
      return $this->renderText(json_encode(array(
        "module" => $this->getModuleName(),
        "left"  => $ups,
        "right" => $a
      )));
    }
    else {
      $this->list = new sfDoctrinePager('Uploads', 20);
      $this->list->setQuery($t);
      $this->list->setPage($r->getParameter("page", 1));
      $this->list->init();
      $this->page = $r->getParameter("page", 1);
    }
  }
  
 /**
  * Details of an upload
  */
  public function executeFiche(sfWebRequest $r) {
    $a = array();
    // Getting current object
    $this->u = $this->getRoute()->getObject();

    // If not found, send 404 to browser
    //$this->forward404Unless($this->u);

    // Calling edit form
    $formEdit = new UploadsForm($this->u);

    // If editing
    if ($r->isMethod('post')) {
      // Binding fields
      $formEdit->bind($r->getParameter($formEdit->getName()), $r->getFiles($formEdit->getName()));
      // If everything is correct
      if ($formEdit->isValid()) {
        // Save it !
        $formEdit->save();
        // Redirect
        $this->redirect("@uploadcats?c=".$this->u->Categories->getSlug());
      }
    }

    // Getting some related content
    $this->coms = Doctrine::getTable('MsgMessages')->getComments("up", $this->u->getId());
    $this->ups = Doctrine::getTable('Uploads')->getUploadsByUser($this->u->getAuthor());
    
    // If torrent, getting related peers
    if ($this->u->getHash()) {
      $this->peers = Doctrine_Query::create()
        ->select('p.*, u.username, u.avatar')
        ->from('TorrentsPeers p')
        ->leftJoin('p.Users u')
        ->where('p.hash = ?', $this->u->getHash())
        ->andWhere('p.updated_at > ?', date('Y-m-d H:i:s', time()-3600))
        ->orderBy('remain')
        ->execute(array(), Doctrine::HYDRATE_ARRAY);

      foreach ($this->peers as $id => $peer) {
        // Injecting size of upload
        $this->peers[$id]['uploadsize'] = $this->u->getSize();
        // Don't send IP&PID to browser, keep it for us 
        unset($this->peers[$id]['ip'], $this->peers[$id]['pid'], $this->peers[$id]['peer_id']);
      }
    }

    

    $a['description'] = $this->getTab("Description", "book_open.png", $this->u->getDescription());
    $this->files = $this->u->getFiles($this->u->getUrl());

    // Infos
    $a['infos'] = $this->getTab("Download", "document_info.png",
      $this->getPartial($this->getModuleName().'/infos', array(
        'u' => $this->u, 'ups' => $this->ups, 'files' => $this->files
    )));
    // Filelist
    if (is_array($this->files))
      $a['files'] = $this->getTab("Files", "folders.png", $this->files);
    else
      $a['files'] = $this->getTab("Files", "folders.png", array('url' => $this->u->getUrl()));

    // If NFO, getting content
    if (is_file("uploads/nfo/".$this->u->getNfo()))
      $a['nfo'] = $this->getTab("NFO", "script.png",
        "<pre>".htmlentities(file_get_contents("uploads/nfo/".$this->u->getNfo()))."</pre>");

    // Comments
    $f = new UploadsComsForm();
    $f->setDefault('upid', $this->u->getId());
    $a['coms'] = $this->getTab("Commentaires", "comments.png", $this->coms->toArray());
    $a['newcom'] = $this->getTab("Add comment", "comment_add.png",
      $this->getComponent('messages', 'new', array(
        "form" => $f,
        "submitUrl" => $this->getContext()->getController()->genUrl($this->getModuleName()."/comment")
    )));

    if (isset($this->peers))
      $a['peers'] = $this->getTab("Peers", "status_online.png", $this->peers);

    // If owner or mod or adm, editing mode
    if ($this->u->getAuthor() == $this->getUser()->getAttribute("id")
    ||  $this->getUser()->hasCredential("adm")
    ||  $this->getUser()->hasCredential("mod")) {
      $this->formEdit = $formEdit;
      $a['opt'] = $this->getTab("Options", "wrench.png",
        $this->getPartial($this->getModuleName().'/edit', array("f" => $this->formEdit)));
    }
    
    // Returning content
    if ($r->isXmlHttpRequest()) {
      // Sending JSON mime type
      $this->getResponse()->setHttpHeader('Content-type', 'application/json');
      // Sending content
      return $this->renderText(json_encode(array(
        "right" => $a,
      )));
    }
  }
  

  public function executeCategories(sfWebRequest $r) {
    if ($r->isMethod('post')) {
      $c = $r->getPostParameter("categories");
      if ($c['id'] > 0)
        $this->f = new CategoriesForm(
          Doctrine::getTable("Categories")->find($c['id']));
      else
        $this->f = new CategoriesForm();
      $this->f->bind($r->getParameter($this->f->getName()));
      if ($this->f->isValid()) {
        $this->f->save();
        $this->redirect('partage/index');
      }
    }
  }
  
 /**
  * Download
  */
  public function executeDownload(sfWebRequest $r) {
    // Getting upload from route
    $u = $this->getRoute()->getObject();
    $this->forward404Unless($u);

    // If it's a torrent
    if ($u->getHash()) {

      // If torrent file has gone away, send 404
      if (!file_exists('uploads/torrents/'.$u->getUrl()))
        $this->forward404();

      // We're gonna rewrite all HTTP headers
      $this->getResponse()->clearHttpHeaders();

      // We're sending a torrent file
      $this->getResponse()->setContentType('application/x-bittorrent');

      // Forcing download and setting filename
      $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename="'.$u->getTitle().'.torrent"');

      // If user has a PID, we can use it inside the torrent file
      if ($this->getUser()->getAttribute("ses")->getPid()) {

        // Load torrent class
        require_once("../lib/bittorrent/Autoload.php");

        // Open a new torrent instance
        $torrent = new PHP_BitTorrent_Torrent();

        // Loading it
        $torrent->loadFromTorrentFile('uploads/torrents/'.$u->getUrl());

        // Resetting announce
        $torrent->setAnnounce($torrent->getAnnounce().'?pid='.$this->getUser()->getAttribute("ses")->getPid());

        // Prepare body content with torrent's one
        $this->getResponse()->setContent($torrent->save("/dev/null", false));
      }
      // No PID : just send like it is
      else
        $this->getResponse()->setContent(file_get_contents('uploads/torrents/'.$u->getUrl()));
      
      // Sending all stuff to browser
      $this->getResponse()->send();
    }
    // If DDL
    else {
      // Record click
      $c = new UploadsHits();
      $c->setUid($this->getUser()->getAttribute("id"));
      $c->setUpid($u->getId());
      $c->save();

      $this->redirect($u->getUrl());
    }
    
    return sfView::NONE;
  }
  
 /**
  * Add an upload
  */
  public function executeAdd(sfWebRequest $r) {
    // Calling form
    $this->form = new UploadsForm();

    // If we've posted something
    if ($r->isMethod("post")) {

      // Binding fields & files to validators
      $this->form->bind($r->getParameter($this->form->getName()), $r->getFiles($this->form->getName()));

      // If everything is clean
      if ($this->form->isValid()) {

        // Saving into database
        $u = $this->form->save();

        $this->getUser()->setFlash("notice", "Your upload has been saved !");
        $this->redirect("@homepage");
        
        //$this->redirect("partage/index?category=".$u->Categories->getSlug());
      }
      else {
        $c = (string) $this->form->getErrorSchema();
        preg_match_all('#(.+) \[(.+)\]#U', $c, $m);
        $m[1] = array_map('trim', $m[1]);
        die(json_encode($m, JSON_FORCE_OBJECT));
      }
    }
    // This method requires an POST request
    else
      $this->forward404();
  }
  
  
 /**
  * Delete an upload
  */
  public function executeDelete(sfWebRequest $r) {
    if ($this->getUser()->getAttribute("token") !== $r->getUrlParameter("token"))
      $this->forward404();
    
    $u = Doctrine::getTable("Uploads")->find($r->getParameter("id"));
    $this->forward404Unless($u);

    if ($u->getAuthor() == $this->getUser()->getAttribute("id")
    ||  $this->getUser()->hasCredential("adm")
    ||  $this->getUser()->hasCredential("mod")) {
      // Get category
      $url = '@uploadcats?c='.$u->Categories->getSlug();
      // Delete files
      @unlink('uploads/nfo/'.$u->getNfo());
      @unlink('uploads/torrents/'.$u->getUrl());
      // Delete from DB
      $u->delete();
      // Redirect
      $this->getUser()->setFlash("notice", "Upload has been deleted.");
      $this->redirect("@homepage");
    }
    return $this->renderText("");
  }

 /**
  * Delete a category
  */
  public function executeCategorydelete(sfWebRequest $r) {
    if ($this->getUser()->getAttribute("token") !== $r->getUrlParameter("token"))
      $this->forward404();
    
    $u = Doctrine::getTable("Categories")->find($r->getParameter("id"));
    $this->forward404Unless($u);

    // Delete from DB
    $u->delete();

    // Redirect
    $this->getUser()->setFlash("notice", "Category has been deleted.");
    $this->redirect("@homepage");
  }

  public function executeComment(sfWebRequest $r) {
    if (!$r->isMethod('post'))
      $this->forward404();
    // Load form
    $f = new UploadsComsForm();
    // Binding fields
    $f->bind($r->getParameter($f->getName()));
    // If form is valid
    if ($f->isValid()) {
      // Save
      $m = $f->save();
      // Redirect
      $this->redirect($r->getReferer());
    }
  }
 
 /**
  * Demande de reseed
  */
  public function executeReseed(sfWebRequest $r) {
    // On récupère le titre et les complétés du torrent
    $t = Doctrine_Query::create()
      ->select('t.titre, c.mid, c.reste')
      ->from('Torrent t')
      ->leftJoin('t.TorrentsConnectes c')
      ->where('t.hash = ?', $r->getUrlParameter("hash"))
      ->useQueryCache(true)->setQueryCacheLifeSpan(3600*24)
      ->execute(array(), Doctrine::HYDRATE_ARRAY);
    if (count($t) === 0)
      $this->forward404();
    
    // On crée le MP
    $s = new MpSujet();
    $s->setMpSujet($this->getContext()->getI18N()->__("Reseed.")." : ".$t[0]['titre']);
    $s->save();
    
    // On crée le contenu du message
    $c = new MpMsg();
    $c->setMpId($s->getMpId());
    $c->setMpAuteur($this->getUser()->getAttribute('id'));
    $c->setMpDate(date("Y-m-d H:i:s"));
    $c->setMpTxt("Bonjour. Je demande le reseed d'un torrent que vous posséder : ".$t[0]['titre'].". Pourriez-vous, s'il vous plaÃ®t reprendre le partage ? Merci d'avance. ".$this->getUser()->getAttribute('username'));
    $c->save();
    
    // On ajoute les participants ('d'abord, soi-mÃªme')
    $p = new MpParticipants();
    $p->setMpId($s->getMpId());
    $p->setMpMid($this->getUser()->getAttribute("id"));
    $p->setReaded(1);
    $p->setDeleted(0);
    $p->save();
    // Puis chaque connecté
    foreach ($t[0]['TorrentsConnectes'] as $pp) {
      // ... qui a fini le fichier
      if ($pp['reste'] == 0) {
        $p = new MpParticipants();
        $p->setMpId($s->getMpId());
        $p->setMpMid($pp['mid']);
        $p->setReaded(0);
        $p->setDeleted(0);
        $p->save();
      }
    }
    
    // On redirige et basta
    $this->getUser()->setFlash("notice", $this->getContext()->getI18N()->__("Your reseed request has been sent. Keep an eye on your private messages."));
    $this->redirect("messages/index");
  }
}