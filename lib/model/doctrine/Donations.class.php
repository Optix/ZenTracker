<?php

/**
 * Donations
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    zt2
 * @subpackage model
 * @author     Optix
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Donations extends BaseDonations
{
  /*public function save(Doctrine_Connection $con = null) {
    $q = parent::save();

    Doctrine::getTable("Shoutbox")->setShout(
      array("hasDonated", "money_add.png", $this->getTitle()), 
      sfContext::getInstance()->getController()
        ->genUrl("@upload?c=".$this->Categories->getSlug()."&slug=".$this->getSlug())
    );
    return $q;
  }*/
}
