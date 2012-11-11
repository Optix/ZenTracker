<?php

/**
 * Uploads form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UploadsForm extends BaseUploadsForm
{
  public function configure()
  {
    // We're using only some fields
    $fields = array(
        "title",
        "cat",
        "description", 
        "nfo",
        "url",
        "minlevel"
    );
    $this->useFields($fields);

    // Getting userlevels
    $levels = array();
    foreach (Users::getLevels() as $lvl => $score) {
      if ($lvl > 0)
        $levels[$lvl] = "Level ".$lvl;
    }
    // If freeleech is allowed, adding this choice
    if (sfConfig::get('app_bt_allowfreeleech', true))
      $levels[0] = "Freeleech";
    ksort($levels);
    

    // Indicate that these fields are required (HTML5)
    $this->widgetSchema['title'] = new sfWidgetFormInputText(array(), array(
      "required" => "required",
      "style" => "width: 90%;",
    ));


    // Loading richtext editor
    $this->widgetSchema['description'] = new sfWidgetFormTextarea(array(), array(
      "class" => "tinymce", "style" => "height: 400px;width: 100%"));


    // NFO file is optional.
    $this->widgetSchema['nfo'] = new sfWidgetFormInputFile();
    $this->validatorSchema['nfo'] = new sfValidatorFile(array(
      'path' => sfConfig::get('sf_upload_dir').'/nfo',
      'required' => false
    ));

    // CATEGORIES
    $qcats = Doctrine_Query::create()->from("Categories");
    $this->widgetSchema['cat'] = new sfWidgetFormDoctrineChoice(array(
      'model'     => 'categories',
      "expanded" => false,
      "multiple" => false,
      "query" => $qcats,
      'method' => 'getIndentedName',
      "add_empty" => false,
      'order_by' => array('root_id, lft',''),
    ));
    $this->validatorSchema['cat'] = new sfValidatorDoctrineChoice(array(
      "model" => "Categories",
      "multiple" => false,
      "required" => true,
    ));
    $this->widgetSchema['cat']->setLabel("Category");

    // Overriding some fields, we want a file transfer for torrent
    $this->widgetSchema['hash'] = new sfWidgetFormInputFile();
    $this->validatorSchema['hash'] = new sfValidatorFileTorrent(array(
      'required' => ($this->isNew() && !sfConfig::get("app_bt_allowddl", true)) ? true : false,
      'path' => sfConfig::get('sf_upload_dir').'/torrents',
    ));

    $this->validatorSchema['url'] = new sfValidatorUrljHeberg(array("required" => false));

    // Resetting labels
    $this->widgetSchema['url']->setLabel('URL');
    $this->widgetSchema['hash']->setLabel('Torrent');
    $this->widgetSchema->setHelp("url", "Paste your jHeberg link here, if you want to use DDL.");
    
    // Setting the right announce URL
    if (sfContext::getInstance()->getRequest()->isSecure())
      $announceUrl = "https://";
    else
      $announceUrl = "http://";
    $announceUrl .= sfContext::getInstance()->getRequest()->getHost()."/announce";
    $this->widgetSchema->setHelp("hash", "Announce URL : ".$announceUrl);

    // Setting some help and labels
    $this->widgetSchema['cat']->setLabel('Category');
    $this->widgetSchema['nfo']->setLabel('NFO');

    $this->widgetSchema['minlevel'] = new sfWidgetFormChoice(array("choices" => $levels));
    $this->setDefault('minlevel', sfConfig::get("app_bt_minleveldefault", 1));

    // Delete fields if some settings are disabled
    if (!sfConfig::get("app_bt_allowddl", true) || (!$this->isNew() && $this->getObject()->getHash() != ""))
      unset($this->widgetSchema['url'], $this->validatorSchema['url']);
    if (!sfConfig::get('app_bt_allowminlevelmbr', true) && sfContext::getInstance()->getUser()->hasCredential("mbr"))
      unset($this->widgetSchema['minlevel'], $this->validatorSchema['minlevel']);
    if ($this->getObject()->getUrl() && !$this->getObject()->getHash())
      unset($this->widgetSchema['hash'], $this->validatorSchema['hash']);
  }

  public function updateObject($v = null) {
    $object = parent::updateObject($v);   

    // If it's a torrent file, storing location in URL field
    if ($this->getValue('hash')) {
      $object->setUrl($object->getHash());
      // And just setting the hash in the right field
      $object->setHash(HASH);
    }
    
    // Storing total size of the upload in bytes
    $object->setSize(SIZE);

    // Setting author of the upload
    if ($this->isNew())
      $object->setAuthor(sfContext::getInstance()->getUser()->getAttribute("id"));

    // Setting minlevel if disabled
    if (!sfConfig::get('app_bt_allowminlevelmbr', true) && sfContext::getInstance()->getUser()->hasCredential("mbr"))
      $object->setMinlevel(sfConfig::get("app_bt_minleveldefault", 1));

    return $object;
  }
}
