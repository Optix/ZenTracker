<?php

/**
 * Notifications form base class.
 *
 * @method Notifications getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseNotificationsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'owner'      => new sfWidgetFormInputText(),
      'uid'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'readed'     => new sfWidgetFormInputCheckbox(),
      'picture'    => new sfWidgetFormInputText(),
      'message'    => new sfWidgetFormTextarea(),
      'link'       => new sfWidgetFormInputText(),
      'extract'    => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'owner'      => new sfValidatorInteger(),
      'uid'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'required' => false)),
      'readed'     => new sfValidatorBoolean(array('required' => false)),
      'picture'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'message'    => new sfValidatorString(),
      'link'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'extract'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('notifications[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Notifications';
  }

}
