<?php

/**
 * main components.
 *
 * @package    zt2
 * @subpackage main
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mainComponents extends sfComponents
{
  // Liste des catégories
  public function executeCategories() {
    $this->cat = Doctrine::getTable("Categories")->getCats();
  }
  
  // Sidebar
  public function executeSidebar() {
    // On récupère les stats
    $st = Doctrine_Query::create()
      ->select('COUNT(*) as users')
      ->addSelect('(SELECT COUNT(*) FROM FrmTopics) AS forum')
      ->addSelect('(SELECT COUNT(*) FROM Polls) AS polls')
      ->addSelect('(SELECT COUNT(*) FROM News) AS news')
      ->from('Users u')
      ->whereNotIn('u.role', array("ban", "val"))
        ->useQueryCache(true)->setQueryCacheLifeSpan(3600*24)  
        ->useResultCache(true)->setResultCacheLifeSpan(60*60)
      ->execute(array(), Doctrine::HYDRATE_ARRAY);
    $this->stat = $st[0];
    
    // Search form
    $this->search = new UploadsFormFilter();
    
    // If we're connected
    if ($this->getUser()->isAuthenticated()) {
      $this->ses = $this->getUser()->getAttribute("ses");
      
      // Donations amount
      $this->donations = Doctrine::getTable("Donations")->getAmount();

      // Online users
      $this->onlineUsers = json_decode(sfContext::getInstance()->getCache()->get("onlineUsers"), true);
      foreach ($this->onlineUsers as $id => $u) {
        if ($u['time'] <= time()-900)
          unset($this->onlineUsers[$id]);
      }
      sfContext::getInstance()->set("usersOnline", json_encode($this->onlineUsers));
      
      // Notifications
      $this->notifications = Doctrine::getTable("Notifications")->getNotifications()->execute();
    }
  }
}
