<?php

/**
 * NewsTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class NewsTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object NewsTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('News');
    }
    
  public function getLastestNews($limit = 2) {
    $n = Doctrine_Query::create()
      ->select('n.*, m.username, m.avatar')
      ->from("News n")
      ->leftJoin("n.Users m")
      ->orderBy("id DESC")
      ->where('deleted_at IS NULL')
      ->limit($limit)
      ->execute();
    return $n;
  }
}