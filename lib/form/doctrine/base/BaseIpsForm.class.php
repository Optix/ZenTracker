<?php

/**
 * Ips form base class.
 *
 * @method Ips getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseIpsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ip'         => new sfWidgetFormInputHidden(),
      'uid'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => false)),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'ip'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ip')), 'empty_value' => $this->getObject()->get('ip'), 'required' => false)),
      'uid'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'))),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ips[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Ips';
  }

}
