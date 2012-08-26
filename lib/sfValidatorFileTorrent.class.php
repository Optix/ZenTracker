<?php

class sfValidatorFileTorrent extends sfValidatorFile {
  
  protected function configure($options = array(), $messages = array()) {
    // Default values
    $this->addOption('mime_types' , array('application/x-bittorrent'));
    $this->addOption('private', 1);
    $this->addOption('announce', "http://".$_SERVER['SERVER_NAME']."/announce");
    
    // Error messages
    $this->addMessage('private', "Your torrent file isn't set to private mode.");
    $this->addMessage('announce', "Your torrent file hasn't the correct announce URL (%announce%).");
    $this->addMessage('already', "A torrent file is already posted with the same hash (%hash%).");
    
    parent::configure($options, $messages);
  }
  
  protected function doClean($value) {
    // Getting uploaded file (still in tmp at this stage)
    $validatedFile = parent::doClean($value);
    
    // Loading BitTorrent class
    require("../lib/bittorrent/Autoload.php");
    // Open a new torrent instance
    $torrent = new PHP_BitTorrent_Torrent();
    // Loading it
    $torrent->loadFromTorrentFile($validatedFile->getTempName());
    // Generating hash
    $hash = sha1(PHP_BitTorrent_Encoder::encode($torrent->getInfo()));
    // I want to use this hash outside this class, not clean at all, shame on me ^^ 
    define('HASH', $hash);
    define('SIZE', $torrent->getSize());
    
    // Looking if hash is already posted
    $sh = Doctrine_Query::create()
      ->select('COUNT(*)')->from("Uploads")->where("hash = ?", $hash)
      ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    if ($sh > 0)
      throw new sfValidatorError($this, 'already', array('hash' => $hash));
    
    // Private mode
    $i = $torrent->getInfo();
    if ($this->getOption('private') != $i['private'])
      throw new sfValidatorError($this, 'private', array('private' => $this->getOption('private')));
    
    // Checking if announce URL is correct
    /*if ($this->getOption('announce') != $torrent->getAnnounce())
      throw new sfValidatorError($this, 'announce', array('announce' => $this->getOption('announce')));
    */
    return $validatedFile;
  }
  
}