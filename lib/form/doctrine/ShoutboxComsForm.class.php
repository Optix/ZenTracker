<?php

/**
 * ShoutboxComs form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ShoutboxComsForm extends BaseShoutboxComsForm
{
  /**
   * @see MsgMessagesForm
   */
  public function configure()
  {
    $this->useFields(array('content', 'shtid'));
    $this->widgetSchema['shtid'] = new sfWidgetFormInputHidden();
    parent::configure();
  }
}
