<?php
class rememberFilter extends sfFilter {
  public function execute($filterChain) {
    // Execute this filter only once
    if ($this->isFirstCall()) {
      // Accessing to HTTP request
      $r = $this->getContext()->getRequest();
      // Accessing user session
      $user = $this->getContext()->getUser();
      // Setting the best language according to browser's language
      $lng = $r->getPreferredCulture(array('en', 'fr'));
      
      $hote = $this->getContext()->getRequest()->getHost();
      
      // Getting cookies
      $uid = $r->getCookie("uid");
      $pwd = $r->getCookie("pwd");
      
      // If cookies are matching with pattern
      if ($uid != null && preg_match('#([0-9a-f]{64})#', $pwd)) {
        // Getting user account from cache
        $q = Doctrine_Query::create()
          ->from('Users u')->where("id = ?", $uid)
          ->leftJoin("u.Ips i")
          ->useQueryCache(true)->setQueryCacheLifeSpan(60*60*24)
          ->useResultCache(true)->setResultCacheLifeSpan(60)->execute();
        if (isset($q[0])) $u = $q[0];
        
        // AUTOLOGIN : If user found, password match and not already auth
        if (count($q) === 1
                &&  $pwd == $u->getCookiesHash()
                && !$user->isAuthenticated()) {
          
          // Access granted, Mr. Wayne.
          $user->setAuthenticated(true);
          
          // Updating last visit
          Doctrine_Query::create()->update('Users')
                  ->set('updated_at', '"'.date("Y-m-d H:i:s").'"')
                  ->where('id = ?', $u->getId())->execute();
          
          // Loading permissions
          $user->addCredential($u->getRole());
          
          // If new client IP, storing it into DB allowing transfers
          $needUpdateIP = true;
          foreach ($u->Ips as $ip) {
            // If IP is found, no update needed
            if ($ip->getIp() == $r->getRemoteAddress())
              $needUpdateIP = false;
          }
          if ($needUpdateIP) {
            // Storing in DB
            $ip = new Ips();
            $ip->setIp($r->getRemoteAddress());
            $ip->setUid($u->getId());
            $ip->replace();
          }
          
          // Loading attributes
          $user->setAttribute('id', $u->getId());
          $user->setAttribute('username', (string) $u);
          $user->setAttribute('avatar', $u->getAvatar());
          $user->setAttribute('ses', $u);
          
          // Creating a new token
          if ($user->getAttribute('token') == null)
            $user->setAttribute('token', sha1(mt_rand(0,9999).time()));
        }
        // END AUTOLOGIN

        // If we're already logged in
        if ($user->isAuthenticated()) {
          // If user doesn't exists anymore
          if (!isset($u))
            $user->setAuthenticated(false);
          else {
            // If we're banned, auto disconnect member
            if ($u->getRole() == "ban")
              return $this->context->getController()->redirect('membres/logout?token='.$user->getAttribute("token"));

            // Storing in online users
            $sfCache = sfContext::getInstance()->getCache();
            $onlineUsers = json_decode($sfCache->get("onlineUsers"), true);
            $onlineUsers[$u->getSlug()] = array(
              "username" => $u->getUsername(),
              "avatar" => $u->getAvatar(16), 
              "slug" => $u->getSlug(),
              "time" => time());
            ksort($onlineUsers);
            $sfCache->set("onlineUsers", json_encode($onlineUsers));
          }
        }
      }
    }
    // Execute next filter
    $filterChain->execute();
  }
}