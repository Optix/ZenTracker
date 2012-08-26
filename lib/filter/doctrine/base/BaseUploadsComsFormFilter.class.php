<?php

/**
 * UploadsComs filter form base class.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedInheritanceTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseUploadsComsFormFilter extends MsgMessagesFormFilter
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('uploads_coms_filters[%s]');
  }

  public function getModelName()
  {
    return 'UploadsComs';
  }
}
