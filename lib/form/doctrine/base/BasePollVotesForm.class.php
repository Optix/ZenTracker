<?php

/**
 * PollVotes form base class.
 *
 * @method PollVotes getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePollVotesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'choice' => new sfWidgetFormInputHidden(),
      'uid'    => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'choice' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('choice')), 'empty_value' => $this->getObject()->get('choice'), 'required' => false)),
      'uid'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('uid')), 'empty_value' => $this->getObject()->get('uid'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('poll_votes[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PollVotes';
  }

}
