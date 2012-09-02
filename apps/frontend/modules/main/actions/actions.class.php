<?php

/**
 * main actions.
 *
 * @package    zt2
 * @subpackage main
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mainActions extends sfActions
{
 /**
  * Executes index action
  */
  public function executeIndex(sfWebRequest $r) {
    // If we aren't auth and when it's allowed, display register form
    if (!$this->getUser()->isAuthenticated() && sfConfig::get("app_register")) {
      $this->regform = new RegisterForm();
      // If we posted
      if ($r->isMethod('post')) {
        $params = $r->getParameter($this->regform->getName());
        $this->regform->bind($params);
        if ($this->regform->isValid()) {
          // Blacklisted domains
          $blacklistdomain = array('pourri.fr', 'yopmail.com','yopmail.fr','jetable.org','mail-temporaire.fr','ephemail.com','trashmail.net','kasmail.com','spamgourmet.com','tempomail.com','guerrillamail.com','mytempemail.com','saynotospams.com','tempemail.co.za','mailinator.com','mytrashmail.com','mailexpire.com','maileater.com','spambox.us','guerrillamail.com','10minutemail.com','dontreg.com','filzmail.com','spamfree24.org','brefmail.com','0-mail.com','link2mail.com','dodgeit.com','dontreg.com','e4ward.com','gishpuppy','guerrillamail.com','haltospam.com','kasmail.com','mailexpire.com','maileater.com','mailinator.com','mailnull.com','mytrashmail','nobulk.com','nospamfor.us','pookmail.com','shortmail.net','sneakemail.com','spam.la','spambob.com','spambox.us','spamday.com','spamh0le.com','spaml.com','tempinbox.com','temporaryinbox.com','willhackforfood.biz','willselfdestruct.com','wuzupmail.net','10minutemail.com','klzlk.com','courriel.fr.nf');
          // Checking email domain
          if (in_array(parse_url("http://".$params['email'], PHP_URL_HOST), $blacklistdomain)) {
            $this->getUser()->setFlash("error", $this->getContext()->getI18N()->__("Your e-mail address is invalid."));
            $this->redirect("@homepage");
          }
          
          // It's good : saving datas
          $m = $this->regform->save();

          // We're sending confirmation mail if validation is needed
          if (sfConfig::get("app_validate")) {
            // Launch the mailer
            $message = $this->getMailer()->compose();
            // Setting subject
            $message->setSubject(sfConfig::get('app_name')." : ".$this->getContext()->getI18N()->__("account validation"));
            // The recipient
            $message->setTo($m->getEmail());
            // Our mail address
            $message->setFrom(sfConfig::get('app_mail'));
            // Vars inside the mail
            $mailContext = array(
              'id' => $m->getId(),
              'cle' => $m->getPid(),
              'pseudo' => $this->regform->getValue("username"),
              'hote' => $r->getHost(),
            );
            // Get the HTML version
            $html = $this->getPartial('global/mail_validation_html', $mailContext);
            $message->setBody($html, 'text/html');
            // Text version (HTML with strip_tags)
            $text = $this->getPartial('global/mail_validation_html', $mailContext);
            $message->addPart(strip_tags($text), 'text/plain');
            // Sending...
            $this->getMailer()->send($message);
            // Confirmation and redirect
            $this->getUser()->setFlash("notice", $this->getContext()->getI18N()->__("Your account has been created. A confirmation mail has just been sent in order to activate it."));
          }
          else
            $this->getUser()->setFlash("notice", $this->getContext()->getI18N()->__("Your account has been created. You can now proceed to login."));
          $this->redirect("@homepage");
        }
        $c = (string) $this->regform->getErrorSchema();
        preg_match_all('#(.+) \[(.+)\]#U', $c, $m);
        $m[1] = array_map('trim', $m[1]);
        die(json_encode($m, JSON_FORCE_OBJECT));
      }
    }

    if (!$this->getUser()->isAuthenticated())
      $this->loginForm = new LoginForm();
  }
  
 /**
  * Notifications
  */
  public function executeNotifications(sfWebRequest $r) {
    $n = Doctrine::getTable("Notifications")
      ->getNotifications($this->getUser()->getAttribute("id"));
    return $this->renderText(json_encode($n));
  }
  
  /**
   * Ajax Upload
   */
  public function executeUpload(sfWebRequest $r) {
    if ($this->getUser()->isAuthenticated()) {
      foreach ($r->getFiles() as $fileName) {
        // Check image
        if (!is_array(getimagesize($fileName['tmp_name']))) {
          echo json_encode(array('status' => "Only images are allowed!"));
          exit();
        }
        // Building filename
        $name_array = explode('.', $fileName['name']);
        if (count($name_array) >= 1)
          $ext = ".".$name_array[count($name_array)-1];
        else
          $ext = null;
        $dest = "uploads/images/original/".$this->getUser()->getAttribute("id").'-'.str_replace('.', null, microtime(true)).$ext;

        // Save file
        if (move_uploaded_file($fileName['tmp_name'], $dest))
          $a = array("filelink" => "/".$dest);
      }
      return $this->renderText(json_encode($a));
    }
    return sfView::NONE;
  }
  
 /**
  * ANNOUNCE
  *
  *  info_hash=%c5Q%00%c9%cc%ff%fexC%f7%0fN%00-%1b%ad%01%3a%3a%f2&
  *  peer_id=-UT2210-%d6bM%24%7dou%1ea%ff%3b%a6&
  *  port=54298&uploaded=0&downloaded=0&left=7054990212&corrupt=0&
  *  key=924042E0&
  *  event=started&
  *  numwant=200&
  *  compact=1&
  *  no_peer_id=1&
  *  ipv6=2a01%3ae34%3aee26%3a9530%3aad45%3af4e7%3a1ce3%3a25c2
  */
  public function executeAnnounce(sfWebRequest $r) {
    $return = array();

    // If it's a browser, redirect to homepage
    if (preg_match('#Mozilla#', $r->getHttpHeader('User-Agent')))  $this->redirect("@homepage");
    
    // Injecting correct MIME for BT clients
    $this->getResponse()->setContentType("text/plain");
    
    // ** STAGE 1 : ACCOUNT
    // Building the query with cache
    $mbr = Doctrine_Query::create()
      ->select("u.role, u.pid")->from('Users u')->leftJoin('u.Ips i')
      ->useQueryCache(true)->setQueryCacheLifeSpan(3600*24)  
      ->useResultCache(true)->setResultCacheLifeSpan(900);
    // Is BT client is sending a PID
    if ($r->hasParameter("pid"))
      $mbr->addWhere('u.pid = ?', $r->getParameter("pid"));
    else
      $mbr->addWhere('i.ip = ?', $r->getRemoteAddress());
    // Execute request
    $m = $mbr->execute();

    // If system can't find the right account
    if (count($m) !== 1)
      $this->btSendError("Sorry, I'm unable to authenticate you. Please, check PID or IP.");
    $mbr = $m[0];

    // Can we access to this torrent ?
    if (in_array($mbr->getRole(), array("ban", "val")))
      $this->btSendError("You don't have the sufficient rights.");

    // ** STAGE 2 : HASH
    if (!$r->hasParameter("info_hash"))
      $this->btSendError("I need an info_hash.");

    // Hashing the hash... yeah i'm not very inspired for this comment ^^ 
      // Symfony is corrupting my hash, getting the right one from $_SERVER. Not very clean, but it works.
    preg_match('#info_hash=(.*)&#U', $_SERVER['QUERY_STRING'], $hash);
    $hash = bin2hex(urldecode($hash[1]));
    if (!preg_match('#([0-9a-f]{40})#', $hash)) 
      $this->btSendError("Invalid hash.");

    // Getting the torrent and related peers & co
    $torrent = Doctrine_Query::create()
      ->select('t.minlevel, p.*, o.*, UNHEX(p.peer_id) as peerid')->from("Uploads t")
      ->leftJoin('t.TorrentsPeers p WITH p.updated_at > ?', date('Y-m-d H:i:s', time()-3600)) // Recents peers
      ->leftJoin('t.TorrentsPeersOffset o WITH o.pid = ?', $mbr->getPid()) // Our offset (where did we stopped last time ?)
      ->where('t.hash = ?', $hash)
      ->useQueryCache(true)->setQueryCacheLifeSpan(3600*24) // Just caching raw SQL request, not the result 
      ->execute(array(), Doctrine::HYDRATE_ARRAY);

    // If hash not found
    if (count($torrent) !== 1)
      $this->btSendError("Torrent not found.");
    $this->torrent = $torrent[0];

    // If we don't have the required level
    if ($mbr->getLevel() < $this->torrent['minlevel'])
      $this->btSendError("You don't have the required level");

    // Is client asking for a compact response ?
    $this->isCompact = ($r->getParameter("compact")=="1") ? true : false;
    
    // Can I provide PeerId to client ?
    $this->noPeerId = ($r->getParameter("no_peer_id")=="1" || $this->isCompact) ? true : false;
    
    // Is client giving the right port number ?
    $port = $r->getParameter("port");
    if (!is_numeric($port) && $port > 0 && $port < 65535)
      $this->btSendError("Invalid port number.");
    
    // Number of peers asked by client (50 by default)
    $numPeers = ($r->getParameter("numwant") > 0) ? (int) $r->getParameter("numwant") : 50;
    
    // Peer_id of current client
    $peer_id = bin2hex(urldecode($r->getParameter("peer_id")));
    if (!preg_match('#([0-9a-f]{40})#', $peer_id)) $this->btSendError("Invalid PeerID.");
    
    // Processing offset
    if (count($this->torrent['TorrentsPeersOffset']) === 1) {
      $download = $this->torrent['TorrentsPeersOffset'][0]['download'] + $r->getParameter("downloaded");
      $upload = $this->torrent['TorrentsPeersOffset'][0]['upload'] + $r->getParameter("uploaded");
    }
    else {
      $download = (int) $r->getParameter("downloaded");
      $upload = (int) $r->getParameter("uploaded");
    }

    // If freeleech, don't care about download
    if ($this->torrent['minlevel'] == 0)
      $download = 0;

    // Processing events
    switch($r->getParameter("event")) {
      // Regular update, just sending current peers
      case "":
        $return['peers'] = $this->btGetPeers($numPeers);
      break;
      
      // Client is starting transfer
      case "started":
        // Updating offset
        $o = new TorrentsPeersOffset();
        $o->setHash($hash);
        $o->setPid($mbr->getPid());
        $o->setDownload($download);
        $o->setUpload($upload);
        $o->replace();
        // Send peerlist
        $return['peers'] = $this->btGetPeers($numPeers);
      break;
      
      // Client is stopping
      case "stopped":
        // Saving offset
        $o = new TorrentsPeersOffset();
        $o->setHash($hash);
        $o->setPid($mbr->getPid());
        $o->setDownload($download);
        $o->setUpload($upload);
        $o->replace();
      break;
      
      // Completed
      case "completed":
        // Sending new peerlist to client
        $return['peers'] = $this->btGetPeers($numPeers);
      break;
      
      // If client is sending an unexcepted event
      default:
        $this->btSendError("Invalid event.");
    }

    // Complete the return response
    $return['interval'] = sfConfig::get("app_bt_interval", 900); // Time between updates
    $return['min interval'] = sfConfig::get("app_bt_interval", 900); // Keep theses values over 60sec
    // Include stats
    $stats = $this->btGetPeers($numPeers, true);
    $return['complete'] = $stats[0];
    $return['incomplete'] = $stats[1];
    
    // Sending array to client
    require_once("../lib/bittorrent/Autoload.php");
    $encoder = new PHP_BitTorrent_Encoder();
    echo $encoder->encode($return);
    
    // Are we in the peerlist ? Doing a quick scan
    $inPeerList = false;
    foreach ($this->torrent['TorrentsPeers'] as $p)
      if ($p['uid'] == $mbr->getId())
        $inPeerList = true;

    // If we are, just updating some datas
    if ($inPeerList) {
      Doctrine_Query::create()->update("TorrentsPeers")
        ->set('updated_at', 'NOW()')
        ->set('ip', '"'.$r->getRemoteAddress().'"')
        ->set('port', $port)
        ->set('download', $download)
        ->set('upload', $upload)
        ->set('remain', $r->getParameter("left"))
        ->where("hash = ?", $hash)
        ->andWhere("pid = ?", $mbr->getPid())
        ->execute();
    }
    // Else, inserting new datas
    else {
      $q = new TorrentsPeers();
      $q->setHash($hash);
      $q->setPid($mbr->getPid());
      $q->setPeerId($peer_id);
      $q->setUid($mbr->getId());
      $q->setIp($r->getRemoteAddress());
      $q->setPort($port);
      $q->setDownload($download);
      $q->setUpload($upload);
      $q->setRemain($r->getParameter("left"));
      $q->setUseragent($r->getHttpHeader('User-Agent'));
      $q->replace();
    }
    return sfView::NONE;
  }
  
  public function executeScrape(sfWebRequest $r) {
    // If it's a browser, redirect to homepage
    if (preg_match('#Mozilla#', $r->getHttpHeader('User-Agent'))) $this->redirect("@homepage");
    
    // Injection correct MIME header
    $this->getResponse()->setContentType("text/plain");
    
    // Not very clean, but it works !
    $infohash = array();
    foreach (explode("&", $_SERVER["QUERY_STRING"]) as $item) {
      if (substr($item, 0, 10) == "info_hash=") {
        $hash = substr($item, 10);
        $hash = urldecode($hash);

        if (get_magic_quotes_gpc())
          $info_hash = stripslashes($hash);
        else
          $info_hash = $hash;
        if (strlen($info_hash) == 20)
          $info_hash = bin2hex($info_hash);
        else if (strlen($info_hash) != 40)
          continue;
        $infohash[] = strtolower($info_hash);
      }
    }

    if (!count($infohash)) $this->btEnvoyerErreur("Invalid hash.");
    
    $datetime = date("Y-m-d H:i:s", time()-sfConfig::get("app_bt_interval", 900)*2);

    $q = Doctrine_Query::create()
      ->select("t.hash, t.title")
      ->addSelect('(SELECT COUNT(*) FROM TorrentsPeers tt WHERE remain = 0 AND tt.hash = t.hash AND updated_at > "'.$datetime.'") AS seeders')
      ->addSelect('(SELECT COUNT(*) FROM TorrentsPeers ttt WHERE remain > 0 AND ttt.hash = t.hash AND updated_at > "'.$datetime.'") AS leechers')
      ->addSelect('(SELECT COUNT(*) FROM TorrentsPeers tttt WHERE remain = 0 AND tttt.hash = t.hash) AS completed')
      ->from("Uploads t")
      ->whereIn("t.hash", $infohash)
      ->execute( array(), Doctrine_Core::HYDRATE_ARRAY );

    $result="d5:filesd";
    foreach ($q as $row) {
      $hash = $this->hex2bin($row['hash']);
      $result.="20:".$hash."d";
      $result.="8:completei".$row['seeders']."e";
      $result.="10:downloadedi".$row['completed']."e";
      $result.="10:incompletei".$row['leechers']."e";
      $result.="4:name".strlen($row['title']).":".$row['title']."e";
      $result.="e";
    }
    $result.="ee";
    echo $result;
    exit();
  }
  
 /**
  * Sending an error to BT client and stopping process
  */
  protected function btSendError($msg) {
    require_once("../lib/bittorrent/Encoder.php");
    echo PHP_BitTorrent_Encoder::encode(array("failure reason" => $this->getContext()->getI18N()->__($msg)));    
    exit();
  }
  
 /**
  * Getting peers for BT client
  */
  protected function btGetPeers($num, $stats=null) {
    $seeders = 0; $leechers = 0; $r = array(); $pp = "";
    $q = $this->torrent['TorrentsPeers'];

    // Sending rendom sorted peerlist when it's too long
    if (count($q) > $num) {
      shuffle($q);
      $q = array_slice($q, 0, $num);
    }
    
    // For each peer
    foreach ($q as $p) {
      // If Compact mode is enabled
      if ($this->isCompact)
        $pp .= str_pad(pack("Nn", ip2long($p['ip']), $p['port']), 6);

      // If peer has finished transfer : it's a seeder.
      if ($p['remain'] == 0)
        $seeders++;
      else
        $leechers++;
      // Don't send "left" information to client, it's just for us
      unset($p['remain']);
      
      // If client doesn't care about peerid
      if ($this->noPeerId)
        unset($p['peer_id']);

      // Storing in return array
      $r[] = $p;
    }

    // If we're just aksing stats
    if ($stats != null)
      return array($seeders, $leechers);
    // Else, sending peerlist
    else 
      if ($this->isCompact)
        return $pp;
      else
        return $r;
  }
  
  protected function hex2bin($hexdata) {
    $bindata = "";
    for ($i=0;$i<strlen($hexdata);$i+=2) {
      $bindata.=chr(hexdec(substr($hexdata,$i,2)));
    }
    return $bindata;
  }
}
