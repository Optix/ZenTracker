<?php

/**
 * news components.
 *
 * @package    zt2
 * @subpackage main
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class newsComponents extends sfComponents {
  
  public function executeLastestnews() {
    $this->news = Doctrine::getTable("News")->getLastestNews(sfConfig::get('app_nws_home', 2));
  }
  
}