<?php

/**
 * messages components.
 *
 * @package    zt2
 * @subpackage messages
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class messagesComponents extends sfComponents {
  
  public function executeNewpm() {
    $this->form = new PmTopicsForm();
  }

  public function executeNew() {
    
  }
  
  public function executeTopPosters() {
  	$this->mbr = Doctrine_Query::create()
      ->select("COUNT(m.id) as cnt, m.author, us.username, us.slug, us.avatar")
      ->from('MsgMessages m')
      ->leftJoin('m.Users us')
      ->groupBy('m.author')
      ->orderBy('cnt DESC')
      ->where('m.author IS NOT NULL')
      ->limit(20)
      ->useQueryCache(true)->setQueryCacheLifeSpan(3600*24)  
      ->useResultCache(true)->setResultCacheLifeSpan(60*60)
      ->execute();
  }
}