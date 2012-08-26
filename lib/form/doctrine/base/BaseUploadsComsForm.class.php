<?php

/**
 * UploadsComs form base class.
 *
 * @method UploadsComs getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUploadsComsForm extends MsgMessagesForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('uploads_coms[%s]');
  }

  public function getModelName()
  {
    return 'UploadsComs';
  }

}
