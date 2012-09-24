<?php

/**
 * membres actions.
 *
 * @package    zt2
 * @subpackage membres
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class membresActions extends sfActions
{
 /**
  * Memberlist
  */
  public function executeIndex(sfWebRequest $r) {
    // Loading filter
    $this->filter = new UsersFormFilter();

    // If we posted the filter
    if ($r->isMethod('post')) {
      $this->filter->bind($r->getParameter($this->filter->getName()));
    }

    // Values posted by filter
    $values = $this->filter->getValues();

    // Creating query
    $this->mbrlist = $this->filter->buildQuery($values)
      ->orderBy('created_at')//->orderBy('updated_at DESC')
      ->limit(200)
      ->execute();

    // If Ajax call
  	if ($r->isXmlHttpRequest()) {
      $b = $this->mbrlist->toArray();
    	$a = array();
    	foreach ($this->mbrlist as $id => $m) {
    		$a[$id] = $b[$id];
    		$a[$id]['score'] = $m->getScore();
    		$a[$id]['url'] = $this->getContext()->getController()->genUrl('@profile?slug='.$m['slug']);
    	}

      // Sending correct MIME
      $this->getResponse()->setHttpHeader('Content-type','application/json');

      // Sending body
      return $this->renderText(json_encode(array(
    		"left" => $a,
    		"right"=> array(
    			"filter" => $this->getTab("Filters", "filter.png",
    				$this->getPartial($this->getModuleName()."/filters", array("f" => $this->filter))
    			),
          "topuploaders" => $this->getTab("Best Uploaders", "medal_gold_1.png",
            $this->getComponent("partage", "topUploaders", array())
          ),
          "topposters" => $this->getTab("Best Posters", "medal_gold_3.png",
            $this->getComponent("messages", "topPosters", array())
          ),
          "topdonors" => $this->getTab("Best Donors", "moneybox.png",
            $this->getPartial($this->getModuleName()."/filters", array("f" => $this->filter))
          ),
    		),
    		"module" => $this->getModuleName()
    	)));
    }
    
  }
  
 /**
  * Member's profile
  */
  public function executeShow(sfWebRequest $r) {
    $a = array();
    // Get member from the route
    $this->mbr = $this->getRoute()->getObject();
    // If the member is not found, 404
    $this->forward404Unless($this->mbr);

    // Getting uploads from this user
    $this->ups = Doctrine::getTable("Uploads")->getUploadsByUser($this->mbr->getId());

    // Getting score
    $this->score = $this->mbr->getScore(true);

    if ($r->isXmlHttpRequest()) {
      // Profile
      if ($this->mbr->getDescription())
        $a['desc'] = $this->getTab("Profile", "book_open.png", html_entity_decode($this->mbr->getDescription()));

      // Score tab
      $a['score'] = $this->getTab("Score", "rosette.png", 
        $this->getPartial($this->getModuleName().'/score', array(
          'score' => $this->score,
          'mbr' => $this->mbr
        ))
      );

      // If user has uploaded something, showing tab
      if (count($this->ups) > 0)
        $a['ups'] = $this->getTab("Uploads", "add.png",
          $this->getPartial($this->getModuleName()."/profile_ups", array("ups" => $this->ups)));

      // If we're admin, we can edit this user and show IP
      if ($this->getUser()->hasCredential("adm")) {
        // Calling form
        $usr = new UsersForm($this->mbr);
        // Render tab
        $a['opt'] = $this->getTab("Options", "gear_in.png", 
          $this->getPartial($this->getModuleName()."/edit_profile", array("f" => $usr)));
        // Loading IPs
        $ips = Doctrine::getTable("Ips")->findByUid($this->mbr->getId());
        $a['ips'] = $this->getTab("IP address", "ip.png", 
          $this->getPartial($this->getModuleName()."/ip", array("ips" => $ips)));
      }

      // Sending correct MIME
      $this->getResponse()->setHttpHeader('Content-type','application/json');

      // Sending body
      return $this->renderText(json_encode(array(
        "right" => $a
      )));
    }
  }

  public function executeEdit(sfWebRequest $r) {
    // Check method
    if (!$r->isMethod('post'))
      $this->forward404;

    // Check security
    if (!$this->getUser()->hasCredential('adm') 
      &&!$this->getUser()->hasCredential('mod')
      &&!$this->getUser()->getAttribute("id") == $r->getParameter("uid"))
      $this->forward404();

    // Load form
    $f = new UsersForm(Doctrine::getTable("Users")->find($r->getParameter("uid")));

    // Binding
    $f->bind($r->getParameter($f->getName()));

    // If form is valid
    if ($f->isValid()) {
      // Saving
      $f->save();
      // Redirect
      $this->redirect('membres/index');
    }
  }
  
 /**
  * Menu des options
  */
  public function executeOptions(sfWebRequest $r) {
    $a = array();

    // Loading forms with our session
    $avt = new AvatarForm($this->getUser()->getAttribute("ses"));
    $usr = new UsersForm($this->getUser()->getAttribute("ses"));
    $invites = new InvitesForm();
    $ipForm = new IpsForm();
    $ipForm->setDefault('ip', $r->getRemoteAddress());

    // Tab for avatar changing
    $a['avatar'] = $this->getTab("Avatar", "picture_edit.png", 
      $this->getPartial($this->getModuleName()."/avatar", array("form" => $avt)));

    // Loading IPs
    $ips = Doctrine::getTable("Ips")->findByUid($this->getUser()->getAttribute("id"));
    $a['ips'] = $this->getTab("IP address", "ip.png", 
      $this->getPartial($this->getModuleName()."/ip", array("form" => $ipForm, "ips" => $ips)));

    // Loading invitation codes if enabled
    if (sfConfig::get("app_invitation")) {
      
      // Getting codes
      $codes = Doctrine::getTable("Invites")->findByUid($this->getUser()->getAttribute("id"));

      // Building tab
      $a['invites'] = $this->getTab("Invitations", "cup.png", 
        $this->getPartial($this->getModuleName()."/invites", array("form" => $invites, "codes" => $codes)));
    }

    $a["options"] = $this->getTab("Options", "gear_in.png", 
      $this->getPartial($this->getModuleName()."/edit_profile", array("f" => $usr)));

    // If we've posted someting
    if ($r->isMethod('post')) {

      // If we've uploaded a new avatar
      if ($r->hasParameter("avatars")) {
        
        // Binding fields
        $avt->bind($r->getParameter($avt->getName()), $r->getFiles($avt->getName()));
        
        // If everything is OK
        if ($avt->isValid()) {
          
          // Record in DB
          $avt->save();

          // Tell it to frontend
          $this->getUser()->setFlash("notice", "Your avatar has been saved !");
          $this->redirect('@homepage');
        }
      }
      elseif ($r->hasParameter("profile")) {
        // Binding fields
        $usr->bind($r->getParameter($usr->getName()));

        // If form is valid
        if ($usr->isValid()) {
          // Save
          $usr->save();
          return $this->renderText("ok");
        }
      }
    }
    else {
      return $this->renderText(json_encode(array(
        "right" => $a
      )));
    }
  }
  
 /**
  * IP management
  */
  public function executeIp(sfWebRequest $r) {
    // If we add a new IP
    if ($r->isMethod("post")) {
      // Loading form
      $f = new IpsForm();
      // Binding fields
      $f->bind($r->getParameter($f->getName()));
      // If everything is OK
      if ($f->isValid()) {
        // Save into db
        $f->save();
        // Tell it to frontend
        return $this->renderText("ok");
      }
    }
    // If we delete an IP
    elseif ($r->hasParameter("delete")) {
      // Getting IP
      $q = Doctrine::getTable("Ips")->find($r->getParameter("delete"));
      // If we are the right owner of this address
      if ($q->getUid() == $this->getUser()->getAttribute("id")) {
        // So, we can delete it
        $q->delete();
        // Same, tell it to frontend
        return $this->renderText("ok");
      }
    }
  }
    
  
 /**
  * Member search
  */
  public function executeSearch(sfWebRequest $r) {
    $q = Doctrine_Query::create()
      ->select("id, username, avatar, slug")
      ->from("Users")
      ->where('username LIKE ?', $r->getParameter("q")."%")
      ->orderBy("username")
      ->useQueryCache(true)->setResultCacheLifeSpan(3600*24)
      ->useResultCache(true)->setResultCacheLifeSpan(3600)
      ->execute()->toArray();
    
    $this->getResponse()->setContentType('application/json');
    return $this->renderText(json_encode($q));
  }

 /**
  * Invitations
  */
  public function executeInvite(sfWebRequest $r) {
    // Loading form
    $f = new InvitesForm();
    // If we've posted an invitation code
    if ($r->isMethod('post')) {
      // Binding values
      $f->bind($r->getParameter($f->getName()));
      // If code is correct
      if ($f->isValid()) {
        // Saving
        $f->save();
        $this->redirect('membres/options');
      }
    }
  }
 
 /**
  * Donations 
  */
  public function executeDons(sfWebRequest $r) {
    $this->dons = array(5,10,15,20,30,50);

    // TODO : needs a little more control if the payment has really passed.
    if ($r->hasParameter("hash")) {
      $sesDons = $this->getUser()->getAttribute("donations");
      foreach ($sesDons as $id => $don) {
        if ($r->getUrlParameter("hash") == $don['hash']) {
          // Save donation
          $d = new Donations();
          $d->setAmount($sesDons[$id]['amount']);
          $d->setDonor($this->getUser()->getAttribute("id"));
          $d->save();

          // Shout
          /*sfProjectConfiguration::getActive()->loadHelpers('Number');
          $sh = Doctrine::getTable("Shoutbox")->ecrireShout(array(
            "img" => "money_add",
            "msg" => $this->getContext()->getI18N()->__("has just made a donation of %amount% !", 
                    array("%amount%" => format_currency($don['amount'], 'EUR'))),
            "url" => $this->getContext()->getController()->genUrl('membres/dons'),
            "titre" => $this->getContext()->getI18N()->__("Go to donations page !"),
          ));*/
          // On confirme
          $this->getUser()->setFlash("notice", $this->getContext()->getI18N()->__("Thanks for your donation !"));
          // On redirige
          $this->redirect("@homepage");
        }
      }
    }
    else {
      // Generating hash for each donation
      foreach ($this->dons as $id => $don) {
        $amount = $don;
        $hash = md5(sfConfig::get("sf_csrf_secret").$don);
        $this->dons[$id] = array(
            "hash" => $hash,
            "amount" => $amount
        );
      }
      $this->getUser()->setAttribute("donations", $this->dons);
    }
    $this->donations = Doctrine::getTable("Donations")->getAmount();
    $this->donationsPercentage = $this->donations / sfConfig::get('app_dons_objectif') * 100;
    if ($this->donationsPercentage > 100)
      $this->donationsPercentage = 100;
    $this->bestDonors = Doctrine::getTable("Donations")->getBestDonors(10);
    $this->lastDonations = Doctrine::getTable("Donations")->getLastDonations(10);
    $this->r = $r;
    return $this->renderText(json_encode(array(
      "right" => array(
        "donations" => $this->getTab("Donations", "money_add.png", 
          $this->getPartial($this->getModuleName().'/donate_choices')),
        "goal" => $this->getTab("Objective", "credit.png",
          $this->getPartial($this->getModuleName().'/donate_goal')),
      )
    )));
  }
  
 /**
  * Deconnexion
  */
  public function executeLogout(sfWebRequest $r) {
    // If token is matching (to prevent CRSF attacks)
    if ($r->getUrlParameter("token") === $this->getUser()->getAttribute("token")) {
      // Erasing cookies
      $this->getResponse()->setCookie("uid", null);
      $this->getResponse()->setCookie("pwd", null);

      // User is not auth
      $this->getUser()->setAuthenticated(false);
      // Destroying session's data
      $this->getUser()->getAttributeHolder()->clear();
      // Destroying session's permissions
      $this->getUser()->clearCredentials();
      // Inform user
      $this->getUser()->setFlash("notice", $this->getContext()->getI18N()->__("You are now disconnected. See you soon !"));

      if ($r->isXmlHttpRequest()) {
        $this->getResponse()->setStatusCode(202);
        return $this->renderText("");
      }
    }
    // Redirect to homepage
    $this->redirect("@homepage");
  }
   
 /**
  * Validation process
  */
  public function executeValidation(sfWebRequest $r) {
    // If key is sha1
    if (preg_match('#([0-9a-f]{40})#', $r->getUrlParameter("key"))) {
      // Fetching data from user account
      $k = Doctrine::getTable("Users")->find($r->getUrlParameter("id"));
      // If user doesn't exists
      $this->forward404Unless($k);
      // Already activated
      if($k->getRole() != "val")
        $this->getUser()->setFlash("error", 
          $this->getContext()->getI18N()->__("Hey ! Your account is already activated, you don't remember ?"));
      // Key isn't correct
      elseif($k->getPid() != $r->getUrlParameter("key"))
        $this->getUser()->setFlash("error", 
          $this->getContext()->getI18N()->__("I'm sorry, but your validation key is not correct."));
      // Key correct !
      else {
        // Setting user to member
        $u = Doctrine_Query::create()->update("Users")->set("role", '"mbr"')->where("id = ?", $r->getUrlParameter("id"))->execute();
        $this->getUser()->setFlash("notice", 
          $this->getContext()->getI18N()->__("Account validated ! Log you in and let the show begin !"));
      }
    }
    // If key isn't sha1
    else
      $this->forward404();
    // Redirect to homepage
    $this->redirect("@homepage");
  }
 
 /**
  * Login process
  */
  public function executeLogin(sfWebRequest $r) {
    // If we've posted the form
    if ($r->isMethod('post')) {
      // Launching form
      $login = new LoginForm();

      // Binding fields to validators
      $login->bind($r->getParameter($login->getName()));

      // Doing a little sleep to prevent automatic bruteforce
      sleep(1);

      // If form is valid
      if ($login->isValid()) {

        // Fetching account for this user
        $q = Doctrine::getTable("Users")->findOneByUsername($login->getValue("username"));

        // Setting cookies for auto-login
        $this->getResponse()->setCookie("uid", $q->getId(), time()+365*3600*24);
        $this->getResponse()->setCookie("pwd", $q->getCookiesHash(), time()+365*3600*24);
        
        // Informing user
        $this->getUser()->setFlash("notice",  
          $this->getContext()->getI18N()->__("Happy to see you %s% !", array("%s%" => $q->getUsername())));

        if ($r->isXmlHttpRequest()) {
          $this->getResponse()->setStatusCode(202);
          return $this->renderText("");
        }
      }
      else {
        $c = (string) $login->getErrorSchema();
        preg_match_all('#(.+) \[(.+)\]#U', $c, $m);
        $m[1] = array_map('trim', $m[1]);
        die(json_encode($m, JSON_FORCE_OBJECT));
      }
      
    }
    // Redirect to homepage
    $this->redirect("@homepage");
  }
}
