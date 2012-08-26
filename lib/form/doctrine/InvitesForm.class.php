<?php

/**
 * Invites form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class InvitesForm extends BaseInvitesForm
{
  public function configure()
  {
  	// Can we create a code that can be used more than once ?
  	if (sfConfig::get("app_invite_multiple"))
  	  $this->useFields(array("code", "multiple"));
  	else
  	  $this->useFields(array("code"));

  	$this->widgetSchema['code'] = new sfWidgetFormInputText();
  	$this->validatorSchema['code'] = new sfValidatorString(array(
  		'required' => true,
  		'min_length' => 10,
  		'max_length' => 10
  	));
  	$this->setDefault("code", substr(md5(uniqid()), -10));
  }

  public function updateObject($v = null) {
  	$object = parent::updateObject($v);

  	if ($this->isNew()) {
  	  $object->setUid(sfContext::getInstance()->getUser()->getAttribute("id"));
  	  $object->setExpire(date('Y-m-d H:i:s', time()+3600*24));
  	}
  }
}
