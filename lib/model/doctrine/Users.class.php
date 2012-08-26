<?php

/**
 * Users
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    zt2
 * @subpackage model
 * @author     Optix
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Users extends BaseUsers
{
  public function __toString() {
    if ($this->getUsername())
      return $this->getUsername();
    else
      return "Anonymous";
  }

  /**
   * When setting password, this function will hashing it before insert/update
   * @param string plain password
   */
  public function setPassword($password) {
    if ($password)
      $this->_set('password', hash('sha256', $password));
  }

  /**
   * In which format is stored current password ?
   * Used for compatibility
   * @return string hash method
   */
  public function getHash() {
    switch (strlen($this->_get("password"))) {
      case 32:
        return "md5";
      break;

      case 40:
        return "sha1";
      break;

      case 64:
        return "sha256";
      break;

      default:
        return "sha256";
    }
  }
  
  
  public function getAvatar($n = null) {
    // First step : building path
    if ($n == "raw")
      return $this->_get("avatar");
    if ($n == null)
      $p = "uploads/avatars/50x50/".$this->_get('avatar');
    else
      $p = "uploads/avatars/".$n."x".$n."/".$this->_get('avatar');
    
    // Final step : return correct one if file found
    if (is_file($p))
      return '/'.$p;
    else
      if ($n == 32 || $n == 16)
        return "/images/avatar_default.png";
      else
        return "/images/avatar_default.gif";
  }
  
  /**
   * Composing a SHA256 key for cookie
   * @return hashed cookie string
   */
  public function getCookiesHash() {
    return hash('sha256', md5($this->getRandom()).$this->getPassword());
  }


  public function getLastestMessages($l = 20) {
    return Doctrine_Query::create()
      ->from('MsgMessages m')
      ->where('m.author = ?', $this->getId())
      ->andWhere('m.module != "pm"')
      ->execute();
  }
  
  /**
   * This function will calculate the sharing ratio of an user
   * @param type $a for selecting output format
   * @return real 
   */
  public function getRatio($a = null) {
    $q = Doctrine_Query::create()
        ->select('SUM(c.download) AS download, SUM(c.upload) AS upload')
          ->addSelect("(SELECT SUM(u.size) FROM Uploads u WHERE u.author = ".$this->getId().") as uploadddl")
        ->from('TorrentsPeers c')
        ->where('c.uid = ?', $this->getId())
            ->useQueryCache(true)->setQueryCacheLifeSpan(3600*24)
            ->useResultCache(true)->setResultCacheLifeSpan(60)
        ->execute(array(), Doctrine::HYDRATE_ARRAY);
    $q2 = Doctrine_Query::create()
        ->select('SUM(u.size) AS download')
        ->from('Uploads u')->leftJoin('u.UploadsHits h')
        ->where('h.uid = ?', $this->getId())
            ->useQueryCache(true)->setQueryCacheLifeSpan(3600*24)
            ->useResultCache(true)->setResultCacheLifeSpan(60)
        ->execute(array(), Doctrine::HYDRATE_ARRAY);
    
    // We are preventing errors when dividing by zero
    if ($q[0]['download'] == 0) $d = 1; // If user hasn't downloaded, just setting 1 byte
    else $d = $q[0]['download']; // Else we're setting real sum of what he downloaded
    if ($q2[0]['download'] == 0) $dd = 1;
    else $dd = $q2[0]['download'];
    if ($q[0]['upload'] == 0) $u = 1;
    else $u = $q[0]['upload'];
    if ($q[0]['uploadddl'] == 0) $uu = 1;
    else $uu = $q[0]['uploadddl'];
    
    // Calculating ratio
    $ra = round(($u+$uu)/($d+$dd),2);
    
    // We're limiting maximum ratio to 99
    $max = 99.99;
    if ($ra > $max)
      $ra = $max;
    
    // Building complete array
    $array = array(
        "down" => $q[0]['download']+$q2[0]['download'],
        "up" => $q[0]['upload']+$q[0]['uploadddl'],
        "ratio" => $ra); 
    
    // If no option is specified, just return raw ratio
    if ($a == null)
      return $ra;
    elseif($a == "up")
      return $array['up'];
    elseif($a == "down")
      return $array['down'];
    // When random string, sending complete array
    else
      return $array;
  }
  
  /**
   * Calculating reputation score of user
   * @param boolean $getArray
   * @return int 
   */
  public function getScore($getArray = false) {
    $s = array();
    // Retriving a lot of COUNT ^^ 
    $mid = $this->getId();
    $q = Doctrine_Query::create()
        ->select('COUNT(*) as tor')
          ->addSelect("(SELECT SUM(do.amount) FROM Donations do WHERE do.donor = ".$mid.") as do")
          ->addSelect("(SELECT COUNT(*) FROM Shoutbox s WHERE s.author = ".$mid.") as sht")
          ->addSelect("(SELECT COUNT(*) FROM MsgMessages m WHERE m.author = ".$mid.") as msg")
        ->from('Uploads t')
        ->where('t.author = ?', $mid)
            ->useQueryCache(true)->setQueryCacheLifeSpan(3600*24)
            ->useResultCache(true)->setResultCacheLifeSpan(60)
        ->execute(array(), Doctrine::HYDRATE_ARRAY);
    // Let's do some calc
    $s['sht'] = $q[0]['sht'];
    $s['post'] = $q[0]['tor']*50;
    $s['frm'] = $q[0]['msg']*2;
    $s['donations'] = $q[0]['do']*100;
    // And the download/upload ?
    $a = $this->getRatio("all");
    $s['up'] = round($a['up']/1024/1024/1024*5);
    $s['down'] = round($a['down']/1024/1024/1024);
    // How long this person is here
    if ($this->getUpdatedAt() != null) {
      $c = new DateTime($this->getCreatedAt());
      $l = new DateTime($this->getUpdatedAt());
      $i = $c->diff($l, false);
      $s['age'] = (int) $i->days;
    }
    // If avatar is uploaded
    if ($this->getAvatar("raw"))
      $s['avt'] = 50;
    else
      $s['avt'] = 0;
    
    if ($getArray)
      return array("sql" => $q[0], "data" => $s);
    else {
      $sum = 0;
      foreach ($s as $i)
        $sum += $i;
      return $sum;
    }
  }
  
  /**
   * Getting all level
   * @return array with max score for each level
   */
  static public function getLevels() {
    // First entry is always 0 (level 0 = 0).
    return array(0,500,1000,2000,5000,10000,25000,50000,99999);
  }
  
  /**
   * Get current level
   * @return number of level
   */
  public function getLevel() {
    foreach ($this->getLevels() as $id => $level) {
      if ($this->getScore() <= $level)
        return $id;
    }
  }

  /**
   * Get roles available
   * @return array
   */
  static public function getRoles() {
    return array(
      "adm" => "Administrator",
      "mod" => "Moderator",
      "mbr" => "Member",
      "ban" => "Banned",
    );
  }
}
