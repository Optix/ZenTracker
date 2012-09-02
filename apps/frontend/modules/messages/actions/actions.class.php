<?php

/**
 * messages actions.
 *
 * @package    zt2
 * @subpackage messages
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class messagesActions extends sfActions
{
 /**
  * Liste des messages
  */
  public function executeIndex(sfWebRequest $r) {
    // Getting private messages
    $this->q = Doctrine::getTable("PmTopics")->getMessages($this->getUser()->getAttribute("id"));

    // If Ajax call
    if ($r->isXmlHttpRequest()) {
      // Storing PMs in a array
      $this->q = (array) $this->q->execute(array(), Doctrine::HYDRATE_ARRAY);

      // For each PM, we're cheating with JS frontend : PM seems like forum topics.
      foreach ($this->q as $id => $t) {
        // Replace PM topics with forum ones
        $this->q[$id]['FrmMessages'] = $this->q[$id]['PmMessages'];
        unset($this->q[$id]['PmMessages']);
        // Let's cheating about the last msg readed
        $this->q[$id]['FrmTopicsUsr'] = array();
        if ($t['PmParticipants'][0]['readed'])
          $this->q[$id]['FrmTopicsUsr'][0] = array("lastmsgid" => $this->q[$id]['FrmMessages'][0]['id']);
        else
          $this->q[$id]['FrmTopicsUsr'][0] = array("lastmsgid" => 0);
        // Generate URL, simply by adding slug behind current URL
        $this->q[$id]['url'] = $this->getContext()->getController()->genUrl('@pm?slug='.$t['slug']);
        // Formatting date
        $this->q[$id]['updated_at'] = strtotime($this->q[$id]['updated_at']);
      }

      // Prepare something to write
      $n = array("new" => $this->getTab("New private message", "add.png", $this->getComponent('messages', 'newpm')));

      // If we're admin, we can mass-mail every member
      if ($this->getUser()->hasCredential('adm'))
        $n['massmail'] = $this->getTab("Mass-mail", "email_to_friend.png", 
          $this->getPartial($this->getModuleName()."/massmail", array("form" => new MailForm()))
        );
      
      // Sending JSON to browser with correct MIME
      $this->getResponse()->setHttpHeader('Content-type','application/json');
      return $this->renderText(json_encode(array(
        "left" => $this->q,
        "right" => $n,
        "module" => "topics",
      )));
    }
    // HTTP call
    else {
      $this->list = new sfDoctrinePager('PmTopics', 20);
      $this->list->setQuery($this->q);
      $this->list->setPage($r->getParameter("page", 1));
      $this->list->init();
      $this->page = $r->getParameter("page", 1);
    }
  }

  public function executeAdd(sfWebRequest $r) {
    if ($r->isMethod('post')) {
      // Loading form
      $f = new PmMessagesForm();
      // Binding fields
      $f->bind($r->getParameter($f->getName()));
      // If form is valid
      if ($f->isValid()) {
        // We can save it.
        $m = $f->save();
        // Update topic with last message
        Doctrine_Query::create()->update("PmTopics")->set("updated_at", '"'.$m->getCreatedAt().'"')
          ->where('id = ?', $m->getPmid())->execute();
        // Set every people in this MP to unreaded
        Doctrine_Query::create()->update('PmParticipants')->set('readed', 0)
          ->where('mpid = ?', $m->getPmid())->andWhere('mpmid != ?', $this->getUser()->getAttribute("id"))->execute();
        // Redirect to topic
        $this->redirect($r->getReferer());
      }
    }
    else
      $this->forward404();
  }

  public function executeMassmail(sfWebRequest $r) {
    if (!$r->isMethod('post'))
      $this->forward404();
    // Load form
    $f = new MailForm();
    // Binding fields
    $f->bind($r->getParameter($f->getName()));
    // If everything is OK
    if ($f->isValid()) {
      // Loading email service
      $message = $this->getMailer()->compose();
      // Setting object
      $message->setSubject(sfConfig::get("app_name", "ZenTracker CMS")." : ".$f->getValue('title'));
      // The sender
      $message->setFrom($this->getUser()->getAttribute('ses')->getEmail());
      // The body
      $message->setBody($f->getValue('content'), 'text/html');
      $message->addPart(strip_tags($f->getValue('content')), 'text/plain');
      // Member emails
      $emails = Doctrine_Query::create()->select('email')->from("Users")->execute(array(), Doctrine::HYDRATE_ARRAY);
      foreach ($emails as $email)
        $message->setBcc($email['email']);
      // Sending !
      $this->getMailer()->send($message);
      // Redirect
      $this->redirect('@homepage');
    }
  }

  public function executeNew(sfWebRequest $r) {
    if (!$r->isMethod('post'))
      $this->forward404();

    // Calling form 
    $f = new PmTopicsForm();
    // Binding values
    $f->bind($r->getParameter($f->getName()));
    // If form is valid
    if ($f->isValid()) {
      // Save message
      $m = $f->save();
      // Save recipients
      $recipients = explode(',', $f->getValue('recipients'));
      foreach ($recipients as $recipient) {
        $rec = new PmParticipants();
        $rec->setMpid($m->getId());
        $rec->setMpmid($recipient);
        $rec->setReaded(0)->setDeleted(0)->replace();
      }
      // Saving ourselves
      $rec = new PmParticipants();
      $rec->setMpid($m->getId())->setMpmid($this->getUser()->getAttribute("id"))->setReaded(1)->setDeleted(0)->replace();
      // Updating PmTopic
      $u = Doctrine_Query::create()
        ->update('MsgMessages')
        ->set('pmid', $m->getId())
        ->where('pmid IS NULL')
        ->andWhere('created_at = ?', $m->getCreatedAt())
        ->execute();
      Doctrine_Query::create()->update("PmTopics")->set('updated_at', '"'.$m->getCreatedAt().'"')->where("id = ?", $m->getId())->execute();
      // Redirect
      $this->redirect('messages/index');
    } 
  }
  
  public function executeVote(sfWebRequest $r) {
    $v = new MsgVotes();
    $v->setUid($this->getUser()->getAttribute("id"));
    $v->setMid($r->getUrlParameter("mid"));
    $v->setVote($r->getUrlParameter("v"));
    $v->replace();
    return $this->renderText("ok");
  }

  public function executeDelete(sfWebRequest $r) {
    $q = Doctrine::getTable("MsgMessages")->find($r->getUrlParameter("mid"));
    if ($this->getUser()->hasCredential("adm")
      ||$this->getUser()->hasCredential("mod")
      ||$this->getUser()->getAttribute("id") == $q->getAuthor()) {
      $q->delete();
      return $this->renderText("ok");
    }
    else {
      $this->getResponse()->setStatusCode(403);
      return $this->renderText("Can't delete");
    }
  }

  public function executeEdit(sfWebRequest $r) {
    $q = Doctrine::getTable("MsgMessages")->find($r->getUrlParameter("mid"));
    if ($this->getUser()->hasCredential("adm")
      ||$this->getUser()->hasCredential("mod")
      ||$this->getUser()->getAttribute("id") == $q->getAuthor()) {
      $q->setContent($r->getPostParameter("msg"));
      $q->save();
      return $this->renderText("ok");
    }
    else {
      $this->getResponse()->setStatusCode(403);
      return $this->renderText("Can't edit");
    } 
  }
  
 /**
  * Affichage de la conversation
  */
  public function executeShow(sfWebRequest $r) {
    // Fetching forum from route
    $this->pm = $this->getRoute()->getObject();
    $this->forward404Unless($this->pm);

    // If we requested delete
    if ($r->hasParameter("delete")) {
      $u = Doctrine_Query::create()->update("PmParticipants")->set("deleted", 1)
        ->where("mpmid = ?", $this->getUser()->getAttribute("id"))
        ->andWhere("mpid = ?", $this->pm->getId())->execute();
      $this->redirect('messages/index');
    }

    // If we aren't in the recipient list
    $r404 = false;
    foreach ($this->pm->PmParticipants as $p)
      if ($p->getMpmid() == $this->getUser()->getAttribute("id"))
        $r404 = true;
    // Send 404
    if (!$r404) $this->forward404();

    // Getting related comments
    $this->coms = Doctrine::getTable("MsgMessages")->getComments("pm", $this->pm->getId());
    $a['coms'] = $this->getTab("Messages", "email.png", $this->coms->toArray());
    
    // Saying to system that we readed it
    $u = Doctrine_Query::create()->update("PmParticipants")->set("readed", 1)
      ->where("mpmid = ?", $this->getUser()->getAttribute("id"))
      ->andWhere("mpid = ?", $this->pm->getId())->execute();

    // Reply form
    $f = new PmMessagesForm();
    $f->setDefault('pmid', $this->pm->getId());
    $a['new'] = $this->getTab("Add a new message", "comment_add.png",
      $this->getComponent('messages', 'new', array(
        "form" => $f,
        "submitUrl" => $this->getContext()->getController()->genUrl("messages/add")
      ))
    );

    // Participants
    $a['parts'] = $this->getTab("Participants", "group.png", 
      $this->getPartial($this->getModuleName().'/parts', array('participants' => $this->pm->PmParticipants))
    );

    return $this->renderText(json_encode(array(
      "right" => $a
    )));
  }

  
  /**
   * Send mail when a new message is posted 
   */
  private function sendMail($m) {
    // Launch the mailer
    $message = $this->getMailer()->compose();
    // Setting subject
    $message->setSubject(sfConfig::get('app_name')." PM : ".$m->MpSujet->getMpSujet());
    // The recipient(s)
    foreach ($m->MpSujet->MpParticipants as $part) {
      if ($part->Membre->getMbrId() != $this->getUser()->getAttribute("ses")->getMbrId())
        $message->addBcc($part->Membre->getEmail());
    }
    // Our mail address
    $message->setFrom(array(sfConfig::get('app_mail') => $this->getUser()->getAttribute("ses")->getUsername()));
    // Message
    $message->setBody($m->getMpTxt(), 'text/plain');
    // Sending...
    $this->getMailer()->send($message);
  }
}
