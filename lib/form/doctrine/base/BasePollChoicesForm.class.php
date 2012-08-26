<?php

/**
 * PollChoices form base class.
 *
 * @method PollChoices getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePollChoicesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'     => new sfWidgetFormInputHidden(),
      'poll'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Polls'), 'add_empty' => false)),
      'choice' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'poll'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Polls'))),
      'choice' => new sfValidatorString(array('max_length' => 128)),
    ));

    $this->widgetSchema->setNameFormat('poll_choices[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PollChoices';
  }

}
