<?php

/**
 * MsgVotes form base class.
 *
 * @method MsgVotes getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseMsgVotesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'uid'        => new sfWidgetFormInputHidden(),
      'mid'        => new sfWidgetFormInputHidden(),
      'vote'       => new sfWidgetFormInputCheckbox(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'uid'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('uid')), 'empty_value' => $this->getObject()->get('uid'), 'required' => false)),
      'mid'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('mid')), 'empty_value' => $this->getObject()->get('mid'), 'required' => false)),
      'vote'       => new sfValidatorBoolean(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('msg_votes[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MsgVotes';
  }

}
