<?php

/**
 * UploadsComs form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UploadsComsForm extends BaseUploadsComsForm
{
  /**
   * @see MsgMessagesForm
   */
  public function configure()
  {
    $this->useFields(array('content', 'upid'));
    $this->widgetSchema['upid'] = new sfWidgetFormInputHidden();
    parent::configure();
  }
}
