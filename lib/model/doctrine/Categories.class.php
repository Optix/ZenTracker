<?php

/**
 * Categories
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    zt2
 * @subpackage model
 * @author     Optix
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Categories extends BaseCategories
{
  public function __toString() {
    return $this->getName();
  }

  public function getNbPosts() {
    $t = Doctrine_Query::create()
      ->select('COUNT(id)')
      ->from("Uploads t")->leftJoin('t.Categories c')
      ->where("c.root_id = ?", $this->getRootId())
      ->useQueryCache(true)->setQueryCacheLifeSpan(3600*24)
      ->useResultCache(true)->setResultCacheLifeSpan(3600)
      ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    return $t;
  }

  public function getParentId(){
    if (!$this->getNode()->isValidNode() || $this->getNode()->isRoot())     
      return null;
    
    $parent = $this->getNode()->getParent();
    
    return $parent['id'];
  }
  
  public function getIndentedName(){
    return str_repeat('- ',$this['level']).$this['name'];
  }
}
