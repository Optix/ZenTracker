<?php

/**
 * Invites form base class.
 *
 * @method Invites getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseInvitesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'uid'      => new sfWidgetFormInputHidden(),
      'code'     => new sfWidgetFormInputHidden(),
      'expire'   => new sfWidgetFormInputText(),
      'multiple' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'uid'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('uid')), 'empty_value' => $this->getObject()->get('uid'), 'required' => false)),
      'code'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('code')), 'empty_value' => $this->getObject()->get('code'), 'required' => false)),
      'expire'   => new sfValidatorPass(),
      'multiple' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('invites[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Invites';
  }

}
