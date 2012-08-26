<?php

/**
 * PollChoices filter form base class.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePollChoicesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'poll'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Polls'), 'add_empty' => true)),
      'choice' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'poll'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Polls'), 'column' => 'id')),
      'choice' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('poll_choices_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PollChoices';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'poll'   => 'ForeignKey',
      'choice' => 'Text',
    );
  }
}
