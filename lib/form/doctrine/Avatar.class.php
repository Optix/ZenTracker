<?php

/**
 * Avatar form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AvatarForm extends BaseUsersForm
{
  public function configure() {
    $this->useFields(array("avatar"));
    $this->setWidgets(array(
        'avatar' => new sfWidgetFormInputFileEditable(array(
        "file_src" => $this->getObject()->getAvatar(),
        "default" => "/images/avatar_default.gif",
        "is_image" => true,
        "with_delete" => false,
        'edit_mode' => !$this->isNew(),
      )),
		));
    $this->widgetSchema->setLabels(array
		(
			'avatar'	=> 'Avatar',
		));
    $this->validatorSchema['avatar'] = new sfValidatorFile(array(
    	'required' => false,
    	'path' => sfConfig::get('sf_upload_dir').'/avatars',
      'mime_types' => 'web_images'
    ));
    $this->widgetSchema->setNameFormat('avatar[%s]');
  }
  
  public function updateObject($values = null) {
    $object = parent::updateObject($values);    
    
    if ($av = $object->getAvatar('raw')) {
      $tmp_name = sfConfig::get('sf_upload_dir').'/avatars/'.$av;
      if (!file_exists($tmp_name)) return $object;
      // Génération miniature 50x50
      $mini = new sfImage($tmp_name);
      $mini->thumbnail(50,50,'inflate');
      $mini->setQuality(80);
      $mini->saveAs(sfConfig::get('sf_upload_dir').'/avatars/50x50/'.$av);
      
      // Génération miniature 32x32
      $mini = new sfImage($tmp_name);
      $mini->thumbnail(32,32,'inflate');
      $mini->setQuality(80);
      $mini->saveAs(sfConfig::get('sf_upload_dir').'/avatars/32x32/'.$av);
      
      // Génération miniature 16x16
      $mini = new sfImage($tmp_name);
      $mini->thumbnail(16,16,'inflate');
      $mini->setQuality(80);
      $mini->saveAs(sfConfig::get('sf_upload_dir').'/avatars/16x16/'.$av);
      
      // On supprime le fichier original
      unlink($tmp_name);
    }
    return $object;
  }

  protected function doSave($con = null) {
    Doctrine::getTable("Shoutbox")->setShout(
      array("uploadedNewAvatar", "picture_edit.png", $this->getObject()->getUsername()), 
      sfContext::getInstance()->getController()->genUrl('@profile?slug='.$this->getObject()->getSlug())
    );

    return parent::doSave($con);
  }
}

