<?php

/**
 * Register form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RegisterForm extends BaseUsersForm
{
  public function configure() {
    $this->useFields(array("username", "password", "email"));

    $this->widgetSchema['username'] = new sfWidgetFormInputText(
      array(),
      array(
        "pattern" => "([a-zA-Z0-9_-]{3,25})",
        "required" => "required",
        "title" => "Keep it simple, no special chars."
      )
    );
    $this->widgetSchema['password'] = $this->widgetSchema['confirm'] = new sfWidgetFormInputPassword(
      array(),
      array(
        "pattern" => "(.){6,}", 
        "title" => "Your password is too short !",
        "required" => "required"
      )
    );
    $this->widgetSchema['email'] = new sfWidgetFormInputText(
      array("type" => "email"),
      array( "required" => "required")
    );

    if (sfConfig::get('app_validate')) {
      $this->widgetSchema['emailconfirm'] = new sfWidgetFormInputText(
        array("type" => "email", "label" => "Confirm email"),
        array( "required" => "required")
      );
      $this->validatorSchema['emailconfirm'] = new sfValidatorEmail(array('required' => true, 'trim' => true,'max_length' => 255));
    
      $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
        new sfValidatorSchemaCompare('password', '==', 'confirm', array(),
              array('invalid' => "Passwords don't match.")),
        new sfValidatorSchemaCompare('email', '==', 'emailconfirm', array(),
              array('invalid' => "Emails don't match.")))));
    }
    else
      $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('password', '==', 'confirm', array(),
        array('invalid' => "Passwords don't match.")));

    // If process requires an invitation
    if (sfConfig::get("app_invitation")) {
      // Creating widget
      $this->widgetSchema['invite'] = new sfWidgetFormInputText(
        array(),
        array(
          "required" => "required",
          "pattern" => "(.){10}",
          "maxlength" => "10",
        )
      );
      // Setting a more relevant label
      $this->widgetSchema->setLabel('invite', "Invitation code");
      // Is invite code valid ?
      $this->validatorSchema['invite'] = new sfValidatorDoctrineChoice(array(
        "model" => "Invites",
        "column" => "code",
        "query" => Doctrine_Query::create()
          ->select('i.code')
          ->from("Invites i")
          ->where('i.expire >= ?', date('Y-m-d H:i:s'))
      ));
    }

    $this->getWidgetSchema()->moveField('confirm', sfWidgetFormSchema::AFTER, 'password');
    
    $this->validatorSchema['username'] = new sfValidatorAnd(array(
        new sfValidatorString(array('min_length' => 3, 'max_length' => 25)),
        new sfValidatorRegex(array('pattern' => '#([a-zA-Z0-9_-]+)#')),
        new sfValidatorDoctrineUnique(array('model' => "Users", "column" => "username"))
      ));
    $this->validatorSchema['password'] = $this->validatorSchema['confirm'] = new sfValidatorString(array("min_length" => 6));
    $this->validatorSchema['email'] = new sfValidatorAnd(array(
      new sfValidatorEmail(array('required' => true, 'trim' => true,'max_length' => 255)),
      new sfValidatorDoctrineUnique(array('model' => "Users", "column" => "email"))
    ));
    
  }
  
  public function updateObject($values = null) {
    $object = parent::updateObject($values);    
    
    if ($this->isNew()) {
      // Setting random integer
      $object->setRandom(mt_rand(10000,99999));
      
      // And a rendom signature for BT
      $object->setPid(sha1(time()));
	  
	  // Setting password expiration
      $object->setPassexpires(date('Y-m-d H:i:s', time()+3600*24*365));

      // If invite, setting parent
      if (sfConfig::get('app_invitation')) {
        // Fetching code
        $c = Doctrine::getTable("Invites")->findOneByCode($this->getValue('invite'));
        // Setting parent
        $object->setParent($c->getUid());
        // Expiring code
        $c->setExpire(date('Y-m-d H:i:s'));
        $c->save();
      }

      // Is validation required ?
      if (sfConfig::get("app_validate"))
        $object->setRole("val");
      else
        $object->setRole("mbr");
    }
    
    return $object;
  }
}
