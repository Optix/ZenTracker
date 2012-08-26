<?php

/**
 * Users form base class.
 *
 * @method Users getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUsersForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'parent'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parents'), 'add_empty' => true)),
      'username'    => new sfWidgetFormInputText(),
      'password'    => new sfWidgetFormInputText(),
      'passexpires' => new sfWidgetFormInputText(),
      'random'      => new sfWidgetFormInputText(),
      'email'       => new sfWidgetFormInputText(),
      'avatar'      => new sfWidgetFormInputText(),
      'lastvisit'   => new sfWidgetFormInputText(),
      'pid'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TorrentsPeersOffset'), 'add_empty' => false)),
      'role'        => new sfWidgetFormInputText(),
      'active'      => new sfWidgetFormInputCheckbox(),
      'reason'      => new sfWidgetFormInputText(),
      'ban_expire'  => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormTextarea(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
      'slug'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'parent'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Parents'), 'required' => false)),
      'username'    => new sfValidatorString(array('max_length' => 25)),
      'password'    => new sfValidatorString(array('max_length' => 64)),
      'passexpires' => new sfValidatorPass(),
      'random'      => new sfValidatorString(array('max_length' => 5)),
      'email'       => new sfValidatorString(array('max_length' => 255)),
      'avatar'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'lastvisit'   => new sfValidatorPass(array('required' => false)),
      'pid'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TorrentsPeersOffset'))),
      'role'        => new sfValidatorString(array('max_length' => 3, 'required' => false)),
      'active'      => new sfValidatorBoolean(array('required' => false)),
      'reason'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'ban_expire'  => new sfValidatorPass(array('required' => false)),
      'description' => new sfValidatorString(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
      'slug'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Users', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('users[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Users';
  }

}
