<?php

/**
 * FrmMessages form base class.
 *
 * @method FrmMessages getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseFrmMessagesForm extends MsgMessagesForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('frm_messages[%s]');
  }

  public function getModelName()
  {
    return 'FrmMessages';
  }

}
