<?php

/**
 * Ips form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class IpsForm extends BaseIpsForm
{
  public function configure()
  {
  	$this->useFields(array("ip"));
  	$this->widgetSchema['ip'] = new sfWidgetFormInput(array(), array("required" => "required"));
    $this->validatorSchema['ip'] = new sfValidatorString();
  	$this->widgetSchema->setLabel('ip', 'IP');
  }

  public function updateObject($values = null) {
    $object = parent::updateObject($values);  
    $object->setUid(sfContext::getInstance()->getUser()->getAttribute("id"));
    return $object;
  }
}
