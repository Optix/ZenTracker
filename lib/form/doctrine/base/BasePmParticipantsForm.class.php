<?php

/**
 * PmParticipants form base class.
 *
 * @method PmParticipants getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePmParticipantsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'mpid'    => new sfWidgetFormInputHidden(),
      'mpmid'   => new sfWidgetFormInputHidden(),
      'readed'  => new sfWidgetFormInputCheckbox(),
      'deleted' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'mpid'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('mpid')), 'empty_value' => $this->getObject()->get('mpid'), 'required' => false)),
      'mpmid'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('mpmid')), 'empty_value' => $this->getObject()->get('mpmid'), 'required' => false)),
      'readed'  => new sfValidatorBoolean(array('required' => false)),
      'deleted' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('pm_participants[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PmParticipants';
  }

}
