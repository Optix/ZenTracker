<?php

/**
 * Users form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UsersForm extends BaseUsersForm
{
  public function configure()
  {
  	$this->useFields(array("username", "password", "email", "pid", "role", "description"));
  	$this->widgetSchema['password'] = new sfWidgetFormInputPassword(
      array(),
      array(
        "pattern" => "(.){6,}", 
        "title" => "Your password is too short !",
      )
    );

    if (!$this->isNew()) {
      $this->validatorSchema['password'] = new sfValidatorString(array('required' => false, 'min_length' => 6));
    }

    $this->widgetSchema['email'] = new sfWidgetFormInputText(
      array(
        "type" => "email"
      )
    );
    $this->widgetSchema['pid'] = new sfWidgetFormInputText();
    $this->validatorSchema['pid']=new sfValidatorString();

    $this->widgetSchema['role'] = new sfWidgetFormChoice(array(
      "choices" => Users::getRoles()
    ));

    $this->widgetSchema['description'] = new sfWidgetFormTextarea(array(), array(
      "class" => "tinymce", "style" => "height: 400px"));

    // If we're not admin, remove role change
    if (!sfContext::getInstance()->getUser()->hasCredential("adm"))
      unset($this->widgetSchema['role'], $this->validatorSchema['role']);
  }
}
