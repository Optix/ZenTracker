<?php

/**
 * shoutbox actions.
 *
 * @package    zt2
 * @subpackage shoutbox
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class shoutboxActions extends sfActions
{
 /**
  * Executes index action
  */
  public function executeIndex(sfWebRequest $r) {
    // Don't answer to non-AJAX requests
    if (!$r->isXmlHttpRequest())
      $this->forward404();
    // Retrieving shout from db
    if (sfConfig::get("app_sht_ispublic", true) || 
      (!sfConfig::get("app_sht_ispublic", true) && $this->getUser()->isAuthenticated()))
      $s = Doctrine::getTable("Shoutbox")->getShouts($r->getUrlParameter("id"));
    else
      $s = array();
    // Setting right MIME header
    $this->getResponse()->setHttpHeader('Content-type','application/json');
    // Send data in JSON
    return $this->renderText(json_encode($s));
  }
  
 /**
  * Add a new shout
  */
  public function executeAdd(sfWebRequest $r) {
  	// We're excepting a POST request. If GET => 404
    if (!$r->isMethod('post'))
      $this->forward404();
    // Trim the string
    $txt = trim($r->getPostParameter("sht_txt"));
  	// If it's long enough, save it !
    if (strlen($txt) >= 3) {
      $s = Doctrine::getTable("Shoutbox")->setShout($txt);
      return $this->renderText("ok");
    }
    else
      return $this->renderText("error");
  }
  
 /**
  * Delete a shout
  */
  public function executeDelete(sfWebRequest $r) {
    // Retriving the shout
    $s = Doctrine::getTable("Shoutbox")->find($r->getUrlParameter("id"));
    // If we can delete it... (owner, admin or mod)
    if ($s->getAuthor() == $this->getUser()->getAttribute("id")
    || $this->getUser()->hasCredential("adm")
    || $this->getUser()->hasCredential("mod"))
      $s->delete();
    // If it's an Ajax call, just send "OK"
    if ($r->isXmlHttpRequest())
      return $this->renderText("ok");
    else {
      $this->getUser()->setFlash("notice", 
             $this->getContext()->getI18N()->__("Message deleted."));
      $this->redirect("@homepage");
    }
  }
  
 /**
  * I Like ! <3
  */
  public function executeJaime(sfWebRequest $r) {    
    $sid = $r->getUrlParameter("id");
    // Get Shout
    $sht = Doctrine::getTable("Shoutbox")->find($sid);
    // If Shout isn't found, return 404
    $this->forward404Unless($sht);
    
    // Record in database
    $s = new ShtLikes();
    $s->setShtId($sid);
    $s->setMbrId($this->getUser()->getAttribute("id"));
    $s->setWarn(0);
    $s->save();
    
    // Notify
    $msg = '<a href="#'.$this->getController()->genUrl('@profil?id='.$this->getUser()->getAttribute("id").'&username='.$this->getUser()->getAttribute("username")).'"><img src="/uploads/avatars/16x16/'.$this->getUser()->getAttribute("avatar").'" /> '.$this->getUser()->getAttribute("username").'</a> aime votre <a href="#'.$this->getController()->genUrl('shoutbox/voir?id='.$sid).'">shout</a>.';
    notifie($sht->getShtMid(), $msg, "thumb_up");
    
    if ($r->isXmlHttpRequest()) {
      die("ok");
    }
    else {
      $this->getUser()->setFlash("notice", "Vous aimez.");
      $this->redirect("@homepage");
      return sfView::NONE;
    }
  }
  
 /**
  * Showing a shout (eg comments...)
  */
  public function executeShow(sfWebRequest $r) {
    $a = array();
    // Getting selected shout
    $s = Doctrine::getTable("Shoutbox")->find($r->getUrlParameter("id"));
    // If not found, 404
    $this->forward404Unless($s);

    // Loading form
    $f = new ShoutboxComsForm();
    $f->setDefault('shtid', $s->getId());

    // If comment is posted
    if ($r->isMethod('post')) {
      $f->bind($r->getParameter($f->getName()));
      if ($f->isValid()) {
        $c = $f->save();
      }
    }

    // If there is a link in the text (user specified, not system)
    if (preg_match("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", $s->getDescription(), $match)) {
      $a['frame'] = $this->getTab("Link", "world.png", 
         '<div style="text-align: center;margin-bottom: 5px">
            <a href="'.$match[0].'" class="btn btn-primary" target="_blank">
              <i class="icon-white icon-share"></i>
              '.sfContext::getInstance()->getI18n()->__('Open in a new tab').'
            </a>
          </div>
          <iframe style="width: 100%;height: 700px;border: none" src="'.$match[0].'"></iframe>');
    }

    // Comments
    $this->coms = Doctrine::getTable("MsgMessages")->getComments("sht", $s->getId());
    $a['coms'] = $this->getTab("Comments", "comments.png", $this->coms->toArray());
    $a["new"] = $this->getTab("Add comment", "comment_add.png",
      $this->getComponent('messages', 'new', array(
        "form" => $f,
        "submitUrl" => "#"
    )));
    
    return $this->renderText(json_encode(array(
      "right" => $a
    )));
  }
}
