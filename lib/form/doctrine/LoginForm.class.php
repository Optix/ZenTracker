<?php

/**
 * Login form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class LoginForm extends BaseUsersForm
{
  public function configure() {
    $this->useFields(array("username", "password"));

    $this->widgetSchema['username'] = new sfWidgetFormInputText(
      array(),
      array(
        "pattern" => "([a-zA-Z0-9_-]{3,25})",
        "required" => "required",
        "title" => "Keep it simple, no special chars."
      )
    );
    $this->widgetSchema['password'] = new sfWidgetFormInputPassword(
      array(),
      array(
        "pattern" => "(.){6,}", 
        "title" => "Your password is too short !",
        "required" => "required"
      )
    );

    $this->validatorSchema['username'] = new sfValidatorAnd(array(
      new sfValidatorString(array('min_length' => 3, 'max_length' => 25)),
      new sfValidatorRegex(array('pattern' => '#([a-zA-Z0-9_-]+)#')),
      new sfValidatorDoctrineChoice(array('model' => "Users", "column" => "username"))
    ));
    $this->validatorSchema['password'] = new sfValidatorString(array("min_length" => 6));
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array(
      'callback' => array($this, 'checkPassword'),
    )));
    $this->widgetSchema->setNameFormat('login[%s]');
  }

  public function checkPassword($validator, $values) {
    $q = Doctrine::getTable("Users")->findOneByUsername($values['username']);
    $pwd = hash($this->getHash($q->getPassword()), $values['password']);
    if ($pwd == $q['password']) {
      if ($q['role'] == "val")
        throw new sfValidatorErrorSchema($validator, array(
          'password' => new sfValidatorError($validator, 'Account pending validation.')));
      elseif ($q['role'] == "ban")
        throw new sfValidatorErrorSchema($validator, array(
          'password' => new sfValidatorError($validator, 'Account banned.')));
      else
        return $values;
    }
    else
      throw new sfValidatorErrorSchema($validator, array(
        'password' => new sfValidatorError($validator, 'Incorrect.'),
      ));
  }

/**
   * In which format is stored current password ?
   * Used for compatibility
   * @return string hash method
   */
  public function getHash($p) {
    switch (strlen($p)) {
      case 32:
        return "md5";
      break;

      case 40:
        return "sha1";
      break;

      case 64:
        return "sha256";
      break;

      default:
        return "sha256";
    }
  }
}