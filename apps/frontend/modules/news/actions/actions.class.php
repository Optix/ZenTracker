<?php

/**
 * news actions.
 *
 * @package    zt2
 * @subpackage news
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class newsActions extends sfActions {
 
 /**
  * News listing
  */
  public function executeIndex(sfWebRequest $r) {
    // Retrieving news from database
    $this->news = Doctrine::getTable("news")->getLastestNews(999);
    
    // If we can add a news
    if ($this->getUser()->hasCredential("adm") || $this->getUser()->hasCredential("mod")) {
      // Calling form
      $this->form = new NewsForm();
      $form = array(
        "new" => array(
          "title" => $this->getContext()->getI18N()->__("Add a news"),
          "picture" => "newspaper_add.png",
          "format" => "html",
          "data" => $this->getPartial('news/add', array('form' => $this->form))
        )
      );
    }

    // If AJAX, sending list in JSON
    if ($r->isXmlHttpRequest()) {
      // Sending correct MIME
      $this->getResponse()->setHttpHeader('Content-type','application/json');
      // Playing with listing
      $nws = $this->news->toArray();
      // Generating URL
      foreach ($nws as $id => $n)
        $nws[$id]['url'] = $this->getContext()->getController()->genUrl('@news?slug='.$n['slug']);
      // Sending everything to browser
      return $this->renderText(json_encode(array(
          "module" => $this->getModuleName(),
          "left"  => $nws,
          "right" => array_merge($form)
      )));
    }
    
  }
  
 /**
  * Reading a news
  */
  public function executeShow(sfWebRequest $r) {
    // Fetching current news from route
    $this->news = $this->getRoute()->getObject();
    // If not found, send 404 to browser
    $this->forward404Unless($this->news);

    // If we're staff
    if ($this->getUser()->hasCredential("adm") || $this->getUser()->hasCredential("mod")) {
      // If we're requesting delete
      if ($r->hasParameter("delete") && $this->getUser()->hasCredential("adm")) {
        // Bye !
        $this->news->delete();
        // Reload newslist
        $this->redirect('news/index');
      }
      // Loading form
      $this->form = new NewsForm($this->news);
      // If we've posted something
      if ($r->isMethod('post')) {
        // Binding fields
        $this->form->bind($r->getParameter($this->form->getName()));
        // If everything is fine
        if ($this->form->isValid()) {
          // Saving changes
          $n = $this->form->save();
          // Reload newslist
          $this->redirect('news/index');
        }
      }
    }
    else
      $this->form = "You are not the master of the world. Sorry, maybe next life.";

    // Getting related comments for this news
   /* $this->coms = Doctrine::getTable("MsgMessages")->getComments(
      $this->getModuleName(), 
      $this->news->getId());*/
    // When AJAX
    if ($r->isXmlHttpRequest()) {
      // Building array
      $a = array(
        "right" => array(
          "nws" => $this->getTab("News", "newspaper.png", 
            $this->getPartial('news/news', array('n' => $this->news))),
          "edit" => $this->getTab("Options", "pencil.png", 
            $this->getPartial('news/edit')),
          /*"coms" => $this->getTab("Comments", "comments.png", $this->coms->toArray()),*/
          /*"new" => $this->getTab("Add comment", "comment_add.png",
            $this->getComponent('messages', 'new', array(
              "module" => $this->getModuleName(),
              "key" => $this->news->getId(),
            ))
          )*/
        )
      );
      // Sending correct MIME
      $this->getResponse()->setHttpHeader('Content-type','application/json');
      // Sending content
      return $this->renderText(json_encode($a));
    }
  }
  
 /**
  * Add a news 
  */
  public function executeAdd(sfWebRequest $r) {
    // If we can add a news
    if ($this->getUser()->hasCredential("adm") || $this->getUser()->hasCredential("mod")) {
      // Calling form
      $this->form = new NewsForm();    
      // If a news is posted
      if ($r->isMethod('post')) {
        // Bind fields to validators
        $this->form->bind($r->getParameter('news'));
        // If form is valid
        if ($this->form->isValid()) {
          // Saving
          $n = $this->form->save();
          $this->redirect("news/index");
        }
      }
    }
  }
  
 /**
  * Editer une news
  */
  public function executeEdit(sfWebRequest $r) {
    // On récupère la news
    $n = Doctrine::getTable("News")->find($r->getUrlParameter("id"));
    // On charge le formulaire
    $this->form = $form = new NewsForm($n);
    $this->id = $r->getUrlParameter("id"); 
    
    // Si on a posté
    if ($r->isMethod('post')) {
      $form->bind($r->getParameter($form->getName()));
      // Si le formulaire est valide
      if($form->isValid()){
        // On sauvegarde
        $form->save();
        // Reset cache
        if ($cache = $this->getContext()->getViewCacheManager()) {
          $cache->remove('news/index');
          $cache->remove("@sf_cache_partial?module=news&action=_news&sf_cache_key=*");
        }
        // On confirme
        $this->getUser()->setFlash("notice", $this->getContext()->getI18N()->__("Changes are saved successfully."));
        // On redirige
        $this->redirect("news/index");
      }  
    }
  }
}
