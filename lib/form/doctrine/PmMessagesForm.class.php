<?php

/**
 * PmMessages form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PmMessagesForm extends BasePmMessagesForm
{
  /**
   * @see MsgMessagesForm
   */
  public function configure() {
	  $this->useFields(array('content', 'pmid'));

	  
    $this->widgetSchema['pmid'] = new sfWidgetFormInputHidden();

    

    parent::configure();
  }
}
