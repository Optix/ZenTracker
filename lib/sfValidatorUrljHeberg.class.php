<?php

class sfValidatorUrljHeberg extends sfValidatorUrl {
  
  protected function configure($options = array(), $messages = array()) {
    // Default values
    $this->addOption('key', sfConfig::get("app_jheberg_key", ""));
    $this->addOption('antileech', sfConfig::get("app_jheberg_antileech", false));
    
    // Error messages
    $this->addMessage('antileech', "I can't determine if you're the uploader of thie file.");
    $this->addMessage('calling', "I can't call jHeberg service.");
    
    parent::configure($options, $messages);
  }
  
  protected function doClean($value) {
    // Getting URL
    $url = parent::doClean($value);
    
    // Getting key
    $urlExp = explode('/', $url);
    $id = $urlExp[count($urlExp)-1];

    // Building URL
    $jHeberg = "http://www.jheberg.net/api/check-link";
    $jHeberg.= "?id=".$id;
    if ($this->getOption('key') != null)
      $jHeberg .= "&key=".$this->getOption('key');

    // Calling URL (getting JSON string)
    $json = file_get_contents($jHeberg);

    // If calling fails again
    if ($json === false)
      throw new sfValidatorError($this, 'calling');

    // Decoding JSON in array
    $json = json_decode($json, true);

    // Declaring size
    define('SIZE', $json['fileSize']);
    
    // AntiLeech mode
    if ($this->getOption('antileech')) {
      $q = Doctrine_Query::create()
        ->select('COUNT(*)')
        ->from('Ips')
        ->where('ip = ?', $json['ownerIp'])
        ->andWhere('uid = ?', sfContext::getInstance()->getUser()->getAttribute("id"))
        ->execute(array(), Doctrine::HYDRATE_SINGLE_SCALAR);
      // If owner IP doesn't match
      if ($q == 0) {
        throw new sfValidatorError($this, 'antileech');
      }
    }

    return $url;
  }
  
}