<?php

/**
 * partage components.
 *
 * @package    zt2
 * @subpackage main
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class partageComponents extends sfComponents {
  
  public function executeAdd() {
    $this->f = new UploadsForm();
    if ($this->category)
      $this->f->setDefault("cat", $this->category->getId());
  }

  public function executeCategories() {
  	if ($this->category)
  	  $this->f = new CategoriesForm($this->category);
  	else
  	  $this->f = new CategoriesForm();
  }

  public function executeTopUploaders() {
    $this->mbr = Doctrine_Query::create()
      ->select("COUNT(up.id) as cnt, up.author us.username, us.slug, us.avatar")
      ->from('Uploads up')
      ->leftJoin('up.Users us')
      ->groupBy('up.author')
      ->orderBy('cnt DESC')
      ->limit(20)
      ->where('up.author IS NOT NULL')
      ->useQueryCache(true)->setQueryCacheLifeSpan(3600*24)  
      ->useResultCache(true)->setResultCacheLifeSpan(60*60)
      ->execute();
  }
  
}